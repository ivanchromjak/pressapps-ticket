<?php
/**
 * E-mail Setting Baisc Overview
 * For 2 short of User 
 * (1) Admin , Editor  
 * 2 settings are available @ admin end (1) New Ticket and (2) Update ticket
 * (2) Subscriber Level 
 * There is only 1 Setting Available Weather they wanted to Have the Notification
 * update or not
 */
add_action('pati_ticket_added','pati_ticket_added_email');

function pati_ticket_added_email($ticket_id){
    global $patis;
    
    $ticket             = get_post($ticket_id);
    $headers[]          = 'Content-type: text/html';
    $assigned_user_id   = get_post_meta($ticket_id  ,'pati_assigned_user_id' ,TRUE);
    $user               = get_user_by('id', $ticket->post_author);
    $user_ids           = array();
    
    $from_name  = $patis->get('new_ticket_name');
    $from_email = $patis->get('new_ticket_email');
    if(!empty($from_email) && !empty($from_name))
        $headers[] = 'From: ' . $from_name . ' <' . trim($from_email) . '>';
    
    $replacement = array(
        "#\%ticket_id\%#"       => $ticket->ID,
        "#\%ticket_title\%#"    => $ticket->post_title,
        "#\%ticket_content\%#"  => $ticket->post_content,
        "#\%ticket_url\%#"      => get_permalink($ticket->ID),
        "#\%ticket_type\%#"     => pati_get_the_type($ticket->ID,array('output'=>'string')),
        "#\%ticket_priority\%#" => pati_get_the_priority($ticket->ID,array('output'=>'string')),
        "#\%ticket_status\%#"   => pati_get_the_status($ticket->ID,array('output'=>'string')),
        "#\%ticket_category\%#" => pati_get_the_category($ticket->ID,array('output'=>'string')),
    );
    
    
    $subject    = preg_replace(array_keys($replacement),array_values($replacement),$patis->get('new_ticket_subject'));
    $body       = preg_replace(array_keys($replacement),array_values($replacement),wpautop($patis->get('new_ticket_body')));
    
    /**
     * Send the notification to the ticket owner
     * the Acceptaded Value none and TRUE are for basically 2 short of Users
     * (1) with the User Who can Assign Ticket - Default Admin , Editor Role
     * (2) Who Can only Update ticket - Default Subscriber Role
     */
    if(!in_array($user->update_ticket_notification,array('none','FALSE')))
        $user_ids[] = $user->ID;
    /**
     * Send the notificaion to assigned person or persone who can allocate 
     * the ticket to the respected agent.
     */
    $user_ids = array_merge($user_ids,get_option('pati_new_ticket_notification_users',array()));
    
    if(!empty($assigned_user_id)){
        $user = get_user_by('id',$assigned_user_id);
        if(!in_array($user->update_ticket_notification,array('none','TRUE')))
            $user_ids[] = $user->ID;        
    }
    
    if(count($user_ids)==0)    
        return ;
    /**
     * Send notification over the New Ticket Submission
     */
    $user_ids = array_values(array_unique($user_ids)); // Make Sure that deliver Of The Email Being Done Only Once
    for($i=0;$i<count($user_ids);$i++){
        $user = get_user_by('id',$user_ids[$i]);        
        if(empty($user))
            continue;
        wp_mail( $user->user_email, $subject, $body, $headers );
    }
    
   
}

add_action('pati_ticket_updated','pati_ticket_updated_email');

function pati_ticket_updated_email($args){
    global $patis;

    $ticket_id          = $args['ticket_id'];
    $comment_id         = $args['comment_id'];
    $ticket             = get_post($ticket_id);
    $comment            = get_comment($comment_id);
    
    if( empty($comment->comment_content) || $comment->comment_content == '' ) {
        return;
    }

    $assigned_user_id   = get_post_meta($ticket_id  ,'pati_assigned_user_id' ,TRUE);
    $ticket_owner       = $ticket->post_author;
    $update_author      = get_user_by('id', $comment->user_id);
    $user_ids           = array();
    
    $headers[]          = 'Content-type: text/html';
    
    $from_name  = $patis->get('update_ticket_name');
    $from_email = $patis->get('update_ticket_email');
    if(!empty($from_email) && !empty($from_name))
        $headers[] = 'From: ' . $from_name . ' <' . trim($from_email) . '>';

    $replacement = array(
        "#\%ticket_id\%#"       => $ticket->ID,
        "#\%ticket_title\%#"    => $ticket->post_title,
        "#\%ticket_content\%#"  => $comment->comment_content,
        "#\%ticket_url\%#"      => get_permalink($ticket->ID),
        "#\%ticket_type\%#"     => pati_get_the_type($ticket->ID,array('output'=>'string')),
        "#\%ticket_priority\%#" => pati_get_the_priority($ticket->ID,array('output'=>'string')),
        "#\%ticket_status\%#"   => pati_get_the_status($ticket->ID,array('output'=>'string')),
        "#\%ticket_category\%#"   => pati_get_the_category($ticket->ID,array('output'=>'string')),
        "#\%update_author\%#"   => $update_author->user_login,
    );
    
    
    $subject    = preg_replace(array_keys($replacement),array_values($replacement),$patis->get('update_ticket_subject'));
    $body       = preg_replace(array_keys($replacement),array_values($replacement),wpautop($patis->get('update_ticket_body')));
    
    $user_ids   = get_option('pati_update_ticket_notification',array());
    
    /**
     * Send Notification to the Assigned User
     */
    if(!empty($assigned_user_id) && $comment->user_id != $assigned_user_id){
        $user = get_user_by('id',$assigned_user_id);
        if($user->update_ticket_notification != 'none'){
            $user_ids[] = $user->ID;
        }
    }  
    
    if($comment->user_id != $ticket_owner){
        $user = get_user_by('id',$ticket_owner);
        if(!in_array($user->update_ticket_notification,array('none','FALSE')))
            $user_ids[] = $user->ID;        
    }
    
    if(in_array($comment->user_id, $user_ids)){
        unset($user_ids[array_search($comment->user_id, $user_ids)]);
    }
    
    /**
     * Send notification to All those user who have subscribed for the 
     * All the ticket updates
     */
    $user_ids = array_values(array_unique($user_ids)); // Make Sure that deliver Of The Email Being Done Only Once
    foreach($user_ids as $user_id){
        $user = get_user_by('id',$user_id);        
        if(empty($user))
            continue;
        wp_mail( $user->user_email, $subject, $body, $headers );
    }
    
}
