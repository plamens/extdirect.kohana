<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana 3 Ext.Direct Examples
 * Supplied Ext.Direct examples ported over to work within Kohana 3 with the extdirect module
 * Plus some additional examples
 * 
 * Examples Index: http://your_kohana_site/extdirect/examples
 * 
 * @author     Fady Khalife
 */
class Controller_Examples extends Controller_Template
{
    public $template = 'examples/template';
    
    public function before()
    {
        parent::before();
        
        // Get the current action
        if (version_compare(Kohana::VERSION, '3.1', '<'))
        {
            // Kohaha 3.0.x request API
            $media_action = $this->request->action === 'media';
        }
        else
        {
            // Kohaha 3.1.x request API
            $media_action = $this->request->action() === 'media';
        }   
        
        if ($media_action)
		{
			// Do not template media files
			$this->auto_render = FALSE;
		}
		else
		{
            $this->template->title = '';
            $this->template->content = '';
            $this->template->styles = array();
            $this->template->scripts = array();	
        }
    }
    
    public function action_index()
    {
        $this->template->title = 'Kohana 3 Ext.Direct Examples';
        $this->template->content = View::factory('examples/list');
    }
    
    public function action_direct()
    {
        $media_route = Route::get('extdirect/media');
        $this->template->title = 'Ext.Direct Generic Remoting';
        $this->template->content = View::factory('examples/direct')
            ->bind('media_route', $media_route);
        $this->template->scripts[] = $media_route->uri(array('file' => 'js/direct.js'));
    }
    
    public function action_direct_tree()
    {
        $media_route = Route::get('extdirect/media');
        $this->template->title = 'Ext.Direct Remoting With Tree';
        $this->template->content = View::factory('examples/direct-tree')
            ->bind('media_route', $media_route);
        $this->template->scripts[] = $media_route->uri(array('file' => 'js/direct-tree.js'));
    }
    
    public function action_direct_form()
    {
        $media_route = Route::get('extdirect/media');
        $this->template->title = 'Ext.Direct Form Integration';
        $this->template->content = View::factory('examples/direct-form')
            ->bind('media_route', $media_route);
        $this->template->scripts[] = $media_route->uri(array('file' => 'js/direct-form.js'));
    }
    
    public function action_direct_grid_orm()
    {
        $media_route = Route::get('extdirect/media');
        $this->template->title = 'Ext.Direct DataGrid, DirectStore, RowEditor with Kohana ORM';
        $this->template->content = View::factory('examples/direct-grid-orm')
            ->bind('media_route', $media_route);

        $this->template->scripts = array(
            $media_route->uri(array('file' => 'js/ux/RowEditor.js')),
            $media_route->uri(array('file' => 'js/direct-grid-orm.js')),
            'extdirect/api',
        );
        
        $this->template->styles = array(
            $media_route->uri(array('file' => 'css/icons/silk.css')) => 'screen',
            $media_route->uri(array('file' => 'css/ux/css/RowEditor.css')) => 'screen'
        );
    }
    
    public function action_media()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);
  
		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media', $file, $ext))
		{
			// Send the file content as the response
            if (version_compare(Kohana::VERSION, '3.1', '<'))
            {
                // Kohaha 3.0.x request API
                $this->request->response = file_get_contents($file);
            }
            else
            {
                // Kohaha 3.1.x request API
                $this->response->body(file_get_contents($file));
            }            
			
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the proper headers to allow caching
		$this->request->headers['Content-Type']   = Kohana_File::mime_by_ext($ext);
		$this->request->headers['Content-Length'] = filesize($file);
		$this->request->headers['Last-Modified']  = date('r', filemtime($file));
	}
    
    public function after()
    {
        if ($this->auto_render)
		{
            $media_route = Route::get('extdirect/media');
            
            $styles = array(
                $media_route->uri(array('file' => 'css/ext-all.css')) => 'screen',
    			$media_route->uri(array('file' => 'css/examples.css')) => 'screen',
    		);
        
            $scripts = array(
                $media_route->uri(array('file' => 'js/ext-base.js')),
                $media_route->uri(array('file' => 'js/ext-all.js')),
                'extdirect/api',
            );
        
            $this->template->styles = array_merge($styles, $this->template->styles);
            $this->template->scripts = array_merge($scripts, $this->template->scripts); 
        }
      
        parent::after();
    }

}