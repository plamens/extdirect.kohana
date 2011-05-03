<?php
/**
 * Methods for ExtJS direct examples
 * These are the default methods that ship with ExtJS examples
 */
class ExtDirect_Profile extends Controller_ExtDirect
{

    /**
    * Handler for client side form sumbit
    * @param Array $formPacket Collection of form items along with direct data
    * @return Array response packet
    * @formHandler // Dont forget the handler
    */
    function direct_updateBasicInfo($formPacket){
        $response = array();
        $email = $formPacket['email'];
        if ($email == 'aaron@extjs.com') {
            $success = false;
            $response['errors'] = array(
                'email'=>'already taken'
            );
        } else {
            $success = true;
        }
        $response['success'] = $success;
        // return form packet for demonstration/testing purposes
        $response['debug_formPacket'] = $formPacket;
        return $response;
    }

    /**
    * put your comment there...
    * This method configured with len=2, so 2 arguments will be sent
    * in the order according to the client side specified paramOrder
    * @param Number $userId
    * @param String $foo
    * @return Array response packet
    */
    function direct_getBasicInfo($userId, $foo){
        return array(
            'success'=>true,
            'data'=>array(
                'foo'=>$foo,
                'name'=>'Aaron Conran',
                'company'=>'Ext JS, LLC',
                'email'=>'aaron@extjs.com'
             )
        );
    }

    function direct_getPhoneInfo($userId) {
        return array(
            'success'=>true,
            'data'=>array(
                'cell'=>'443-555-1234',
                'office'=>'1-800-CALLEXT',
                'home'=>''
            )
        );
    }

    function direct_getLocationInfo($userId) {
        return array(
            'success'=>true,
            'data'=>array(
                'street'=>'1234 Red Dog Rd.',
                'city'=>'Seminole',
                'state'=>'FL',
                'zip'=>33776
            )
        );
    }
}
//we may not return CamelCasedName here, cause no uppercase letters except first (which gets uppercased anyway)