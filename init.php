<?php defined('SYSPATH') or die('No direct script access.');
if(Kohana::config('extdirect.examples_enabled')){
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

?>