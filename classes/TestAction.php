<?php
/**
 * Methods for ExtJS direct examples
 * These are the default methods that ship with ExtJS examples
 */
class TestAction
{
    /**
     * @remotable
     */
    function doEcho($data){
        return $data;
    }
    
    /**
     * @remotable
     */
    function multiply($num){
        if(!is_numeric($num)){
            throw new Exception('Call to multiply with a value that is not a number');
        }
        return $num*8;
    }
    
    /**
     * @remotable
     */
    function getTree($id){
        $out = array();
        if($id == "root"){
        	for($i = 1; $i <= 5; ++$i){
        	    array_push($out, array(
        	    	'id'=>'n' . $i,
        	    	'text'=>'Node ' . $i,
        	    	'leaf'=>false
        	    ));
        	}
        }else if(strlen($id) == 2){
        	$num = substr($id, 1);
        	for($i = 1; $i <= 5; ++$i){
        	    array_push($out, array(
        	    	'id'=>$id . $i,
        	    	'text'=>'Node ' . $num . '.' . $i,
        	    	'leaf'=>true
        	    ));
        	}
        }
        return $out;
    }
}
