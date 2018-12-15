<?php

add_filter('map_meta_cap','pati_map_meta_cap',25,4);

function pati_map_meta_cap($caps, $cap, $user_id, $args){
    switch($cap){
        case 'pati_add_ticket':
            
            break;
        case 'pati_edit_ticket':
            
            $ticket = get_post(pati_get_the_ID());
            
            if($ticket->post_author != $user_id)
                $caps   = map_meta_cap('pati_edit_others_ticket',$user_id);
            
            break;
    }
    return $caps;
}

//add_filter('get_user_metadata', 'pati_get_user_metadata',425,4);

add_filter('user_update_ticket_notification','pati_user_update_ticket_notification',25,3);

function pati_user_update_ticket_notification($value, $user_id, $context){
    
    if(is_null($value))
        $value = 'OK';
}

/**
 * For some of the non initialized Property of the User this will provide the 
 * Default value for such attributes by "get_user_metadata" filter
 * 
 * @param string|array $meta_value
 * @param int $object_id
 * @param string $meta_key
 * @param bool $single
 * @return string|array
 */
function pati_get_user_metadata($meta_value,$object_id, $meta_key, $single){
    var_dump($meta_value);
    if(!is_null($meta_value)){
        return $meta_value;
    }
    
    switch($meta_key){
        case 'update_ticket_notification':
            if(user_can($object_id, 'assign_ticket')){
                $meta_value = 'own';
            }else{
                $meta_value = 'TRUE';
            }
            break;
    }
    
    return $meta_value;
    
}