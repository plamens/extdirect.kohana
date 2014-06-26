<?php defined('SYSPATH') or die('No direct script access.');
$config_defaults = Kohana::$config->load('extdirect.defaults');
if($config_defaults['enable_examples'])
{
	// For our examples media files
    Route::set('extdirect/media', 'extdirect/media(/<file>)', array('file' => '.+'))
    	->defaults(array(
    		'controller' => 'examples',
    		'action'     => 'media',
    		'file'       => NULL,
    	));
    
    // Examples routes
    Route::set('extdirect/examples', 'extdirect/examples(/<action>)')
    	->defaults(array(
            'controller' => 'examples',
            'action'     => 'index',
    	));
}

Route::set('extdirectAPI', '(extdirect(/<action>))')
	->defaults(array(
        'controller' => 'extdirect',
        'action'     => 'api',
	));