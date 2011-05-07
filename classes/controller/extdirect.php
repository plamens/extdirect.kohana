<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_ExtDirect extends Controller {
	public function retrieve_api($cached=true)
	{
		if(!($api = Cache::instance()->get('extdirect_cached_api')) || !$cached){
			$search_dirs = array_merge(array(APPPATH),array_values(Kohana::modules()));
			$direct_classes = Kohana::list_files('classes/extdirect',$search_dirs);
			$reflected_actions = array();

			foreach($direct_classes as $rel_path => $direct_class)
			{
				$declaredName = require_once($direct_class); //okay included it
				if(is_string($declaredName))
				{
					$class_subname = $declaredName;
				}else{
					$class_subname = ucfirst(pathinfo($rel_path,PATHINFO_FILENAME));
				}

				try
				{
					$ref_class = new ReflectionClass($this->config['class_prefix'].$class_subname); //now get available actions
				}
				catch(Exception $ref_class){ continue; } // there's no such class

				foreach($ref_class->getMethods() as $method)
				{
					$direct_method_prefix = $this->config['remotable_prefix'];
					if(substr($method->getName(),0,strlen($direct_method_prefix)) == $direct_method_prefix) //startsWith direct_
					{
						$actual_name = substr($method->getName(),strlen($direct_method_prefix));
						$reflected_actions[$class_subname][] = array(
							'name'=>$actual_name,
							'len'=>$method->getNumberOfRequiredParameters()
						);
					}
				}

			}

			$api = array(
				'url' => Url::site(Route::get('default')->uri(array('controller'=>'extdirect', 'action'=>'router'))),
				'type' => 'remoting',
				'actions' => $reflected_actions
			);

			Cache::instance()->set('extdirect_cached_api',$api);
		}
		return $api;
	}


    public function action_api()
	{
		$need_cached = Kohana::config('extdirect.force_cache')==null ?
		  	(Kohana::$environment==Kohana::PRODUCTION) :
		  	 Kohana::config('extdirect.force_cache');
		
		$this->response->headers('Content-Type','text/javascript');
		$this->response->body(
			'Ext.ns(\'Ext.app\'); '.
			'Ext.app.REMOTING_API = '.json_encode($this->retrieve_api($need_cached)).';'
		);
    }

	public function action_router()
	{
		if(isset($GLOBALS['HTTP_RAW_POST_DATA'])){
			$data = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
			if(is_array($data)){
				foreach($data as $dataItem){
					$response[] = $this->rpc($dataItem);
				}
			}else{
				$response = $this->rpc($data);
			}

			$this->response->headers('Content-Type','text/javascript'); //ya i know not very Form-friendly...
			//will need to introduce it's support
			$this->response->body(json_encode($response));
		}
		else
		{
			die('Invalid request'); //no post
		}
	}
	protected function rpc($data){
		if(!isset($data->tid) || !isset($data->action) ||!isset($data->method)) //all the params are here
		{
			die('Invalid request');
		}

		$c = &$this->config;
		try{
			$class_name = preg_replace('/[^a-zA-Z0-9_]/','',$this->config['class_prefix'].$data->action);
			$class = new $class_name(); //autoload will require it for us

			$method_name = preg_replace('/[^a-zA-Z0-9_]/','',$this->config['remotable_prefix'].$data->method);

			$params = isset($data->data) && is_array($data->data) ? $data->data : array();
			$response = call_user_func_array(array($class, $method_name), $params);

			$response = array_merge(array(
				$c['successProperty'] => true,
				$c['messageProperty'] => 'ok',
				$c['root'] => array()
			),$response);
			
			return array(
				'type' => 'rpc',
				'tid' => $data->tid,
				'action' => $data->action,
				'method' => $data->method,
				'result' => $response
			);
		}
		catch(Exception $e)
		{
			return array(
				'type'=> 'exception',
				'tid' => $data->tid,
				$c['successProperty'] => false,
				$c['messageProperty'] => $e->getMessage()
			);
		}
	}

	// For the "Ext.Direct Generic Remoting" example so we can call a polling url within the module
    public function action_poll()
    {
        $response = json_encode(array(
           'type'=>'event',
           'name'=>'message',
           'data'=>'Successfully polled at: '. date('g:i:s a')
        ));
		$this->response->body($response);
    }
	
    protected $config;

    public function __construct($request=null,$response=null)
	{
		if($request && $response){
			parent::__construct($request,$response); //otherwise we're just ExtDirect_*
		}

        $this->config = Kohana::config('extdirect');
    }

    public function __call($name,$params)
	{
		$c = &$this->config;
		
		$actionPrefix = $this->config['remotable_prefix'];
		if(substr($name,0,strlen($actionPrefix))!==$actionPrefix) //really first call
		{
			$retvar = call_user_func(array($this,$name),$params); //main data acquisition
			return array(
				$c['successProperty'] => true,
				$c['messageProperty'] => 'ok',
				$c['root'] => $retvar,
			);
		}
		throw new Exception('No such method');

    }
}
