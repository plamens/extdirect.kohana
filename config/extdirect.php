<?php defined('SYSPATH') or die('No direct script access.');
    return array(
        'class_prefix' => 'Extdirect_',
        'remotable_prefix' => 'direct_',
        'idProperty' =>  'id',
        'root' =>  'data',
        'totalProperty' =>  'total',
        'successProperty' =>  'success',
        'messageProperty' =>  'message',
        'examples_enabled' => Kohana::$environment != Kohana::PRODUCTION,
        'force_cache' => NULL //null means enabled if Kohana::PRODUCTION, otherwise forced to disabled
);