<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana 3 Ext.Direct PHP
 * To quickly intergrate Ext.Direct API calls within a Kohana 3 application
 * 
 * Ported from Ext.Direct PHP - Maintained by tommymaintz (Tommy Maintz)
 * http://extjs.com/forum/showthread.php?t=68186
 * 
 * @author     Fady Khalife
 */

// Check if config is allowing examples
$config_defaults = Kohana::config('extdirect.defaults');
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

Route::set('default', '(<controller>(/<action>))')
	->defaults(array(
        'controller' => 'extdirect',
        'action'     => 'api',
	));