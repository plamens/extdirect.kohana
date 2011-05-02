<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Kohana 3 Ext.Direct PHP
 * To quickly integrate Ext.Direct API calls within a Kohana 3 application
 * 
 * Ported from Ext.Direct PHP - Maintained by tommymaintz (Tommy Maintz)
 * http://extjs.com/forum/showthread.php?t=68186
 * 
 * @author     Fady Khalife
 */
return array(
    
    'defaults' => array(
        'enable_examples' => TRUE,    // You may want to disable examples for a production site
        'auto_include'    => TRUE,    // if you want to use this you have to make sure that your classes (without the prefix)
                                      // are named consistent with the filename and that only one class exists in each file.
        'cache_enabled'   => TRUE,    // expects api_cache.txt to be created and in the module cache folder
                                      // sometimes its helpful to disable during testing
        'base_path'       => MODPATH.'extdirect'.DIRECTORY_SEPARATOR.'classes', // APPPATH.'classes'...
        'namespace'       => '',      // Default Ext.app
        'descriptor'      => '',      // Default Ext.app.REMOTING_API
    ),
    'api' => array(
        'TestAction',                 // Will not be included if "enableExamples" is FALSE
        'Profile',                    // Will not be included if "enableExamples" is FALSE
        'UserAction',                 // Will not be included if "enableExamples" is FALSE
    ),

);

/* example of what you can do with the "api" configuration array

    'api' => array( // an array with all the classnames.
        'LoginAction' => array('prefix' => 'Test_'), // you can set settings for individual classes 
        'SubscriptionAction', 
        'TeamAction' => array('subPath' => 'Subfolder'), // you can specify classes in a subfolder
        'TemplateAction', 
        'TicketAction', 
        'UserAction' => array('autoInclude' => false)
    ), array( // settings for this batch of classes
        'prefix' => '' // you could use this if your classes have a prefix, defaults to empty string
    )
    
*/