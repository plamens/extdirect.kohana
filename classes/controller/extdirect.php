<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana 3 Ext.Direct PHP
 * To quickly integrate Ext.Direct API calls within a Kohana 3 application
 * 
 * Ported from Ext.Direct PHP - Maintained by tommymaintz (Tommy Maintz)
 * http://extjs.com/forum/showthread.php?t=68186
 * 
 * @author     Fady Khalife
 */
class Controller_ExtDirect extends Controller
{
    public $session; 
    
    public function before()
    {
        parent::before();
        $this->auto_render = FALSE;
        $this->session = Session::instance();
        require Kohana::find_file('vendor', 'ExtDirect/API');
        require Kohana::find_file('vendor', 'ExtDirect/CacheProvider');
    }
    
    public function action_api($output = TRUE)
    {
        $config_defaults = Kohana::config('extdirect.defaults');
        $config_api = Kohana::config('extdirect.api');
        $cache_file = Kohana::find_file('cache', 'api_cache', 'txt');
        
        if($config_defaults['cache_enabled'])
        {
            $cache = new ExtDirect_CacheProvider($cache_file);
        }
        else
        {
            $cache = NULL;
            // empty the file
            if(0 != filesize($cache_file))
            {
                file_put_contents($cache_file, '');
            }
            
        }
        
        // Remove "TestAction", "Profile" and "UserAction" from the API if examples_enabled is FALSE
        if( ! $config_defaults['enable_examples'])
        {
            unset($config_api[0], $config_api[1], $config_api[2]);
        }

        $api = new ExtDirect_API();
        
        $api->setRouterUrl(URL::base().'extdirect/router');
        $api->setCacheProvider($cache);
        $api->setNamespace($config_defaults['namespace']);
        $api->setDescriptor($config_defaults['descriptor']);
        $api->setDefaults(array(
            'autoInclude' => $config_defaults['auto_include'],
            'basePath' => $config_defaults['base_path']
        ));
        
        $api->add($config_api);
        
        if($output)
        {
            $this->request->headers['Content-Type'] = 'text/javascript';
            $api->output();
        }
        
        $this->session->set('ext-direct-state', $api->getState());
    }
    
    public function action_router()
    {
        $ext_state = $this->session->get('ext-direct-state');
        if( ! isset($ext_state))
        {
            $this->action_api(FALSE);
        }
        
        $api = new ExtDirect_API();
        $api->setState($ext_state);
        
        require Kohana::find_file('vendor', 'ExtDirect/Router');
        $router = new ExtDirect_Router($api);
        $router->dispatch();
        $router->getResponse(TRUE);
    }
    
    // For the "Ext.Direct Generic Remoting" example so we can call a polling url within the module
    public function action_poll()
    {
        $response = json_encode(array(
           'type'=>'event',
           'name'=>'message',
           'data'=>'Successfully polled at: '. date('g:i:s a')
        ));
        
        if (version_compare(Kohana::VERSION, '3.1', '<'))
        {
            // Kohaha 3.0.x request API
            $this->request->response = $response;
        }
        else
        {
            // Kohaha 3.1.x request API
            $this->response->body($response);
        }
    }

}