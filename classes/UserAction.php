<?php
/**
 * Methods for additional ExtJS direct examples
 * 
 * @author     Fady Khalife
 */
//sleep(1); // Fake some loading
class UserAction
{
    /**
    * Loads users from our model
    * 
    * @param  Int     $start  Database limit start
    * @param  Int     $limit  Database limit
    * @param  String  $sort   Column to sort by
    * @param  String  $dir    Direction to sort by
    * @return Array           Response packet
    * @remotable
    */
    function get($start=0, $limit=10, $sort='first', $dir='ASC')
    {
        $users = ORM::factory('user')
            ->order_by($sort, $dir)
            ->limit($limit)
            ->offset($start)
            ->find_all();
        $total = ORM::factory('user')->count_all();
        
        $result = array();
        $result['total'] = $total;
        $result['message'] = 'Loaded '.$total.' records';
        $result['data'] = array();
        foreach($users as $user)
        {
            $result['data'][] = array(
                'id'    => $user->id, 
                'email' => $user->email, 
                'first' => $user->first, 
                'last'  => $user->last
            );    
        }
        
        return $result;
    }

    /**
    * Add a user to our database
    *     
    * @param   Array  $data  Posted params, $data['data'] for record    
    * @return  Array         Response packet
    * @remotable
    */
    function add($data)
    {
        $user_data = $data->data;
        $user = ORM::factory('user');
        
        // Bind data to user model object
        foreach($user_data as $key=>$val)
        {
            $user->$key = $user_data->$key;    
        }    
        $user->save();
        
        $result = array();
        $result['success'] = true; // Should check $user->saved()
        $result['message'] = 'Added User '.$user->first.' '.$user->last;
        $result['data'][] = array(
            'id'    => $user->id, 
            'email' => $user->email, 
            'first' => $user->first, 
            'last'  => $user->last
        );
                        
        return $result;
    }
    
    /**
    * Remove a user from our database
    *     
    * @param   Array  $data  Posted params, $data['data'] for record    
    * @return  Array         Response packet
    * @remotable
    */
    function update($data)
    {
        $user_data = $data->data;
        $user = ORM::factory('user', $user_data->id);
        
        // Bind data to user model object
        foreach($user_data as $key=>$val)
        {
            if($key != 'id')
            {
                $user->$key = $user_data->$key;
            }
        }                             
        $user->save();
        
        $result = array();
        $result['success'] = true; // Should check $user->saved()
        $result['message'] = 'Updated User '.$user->first.' '.$user->last;
        $result['data'][] = array(
            'id'    => $user->id, 
            'email' => $user->email, 
            'first' => $user->first, 
            'last'  => $user->last
        );
        
        return $result;
    }
    
    /**
    * Remove a user from our database
    * 
    * @param   Array  $data  Posted params, $data['data'] for record    
    * @return  Array         Response packet
    * @remotable
    */
    function delete($data)
    {
        $result = array();
        
        $user_id = $data->data;
        $user = ORM::factory('user', $user_id);
        
        // Store for our message before delete
        $first = $user->first;
        $last = $user->last;
        
        $user->delete();
        
        $result = array();
        $result['success'] = true; // Should check $user->deleted()
        $result['message'] = 'Removed User '.$first.' '.$last;
        $result['data'][] = array(
            'id' => $user->id
        );
  
        return $result;
    }

}