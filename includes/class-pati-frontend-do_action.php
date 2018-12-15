<?php

class PATI_Frontend_Do_Action{
    
    function __construct() {
        if(isset($_REQUEST['submit_ticket']) && $_REQUEST['pati_front_do_action']=='add-ticket') 
            $this->add_ticket();
        
        if(isset($_REQUEST['update_ticket']) && $_REQUEST['pati_front_do_action']=='update-ticket') 
            $this->update_ticket();
    }
    
    function update_ticket(){
        
        global $current_user,$error_flag,$error_message,$ticket_id;
        get_currentuserinfo();
        
        if(!is_user_logged_in()){
            wp_redirect(esc_url(home_url()));
            die('');
        }
        
        $ticket_id = $_REQUEST['ticket_id'];
        
        /**
         * @patitodo Authorization testing as per the Request
         */
        
        /**
         * Validation for the Updatation of the Ticket
         */
        $error_flag         = FALSE;
        $error_message      = array();
        $assigned_userdata  = NULL;
                 
          /*     
        if(!pati_is_valid_category($_REQUEST['category'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_category',
                'message'   => __('Please Select Appropriate Category for the Ticket','pressapps-ticket'),
            );
        }
        if(!pati_is_valid_status($_REQUEST['status'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_status',
                'message'   => __('Please Select Appropriate Status for the Ticket','pressapps-ticket'),
            );
        }
        
        if(!pati_is_valid_priority($_REQUEST['priority'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_priority',
                'message'   => __('Please Select Appropriate Priority for the Ticket','pressapps-ticket'),
            );
        }
       
        if(!pati_is_valid_type($_REQUEST['type'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_type',
                'message'   => __('Please Select Appropriate Type for the Ticket','pressapps-ticket'),
            );
        }
        
        
        if(pati_is_visibility_custom()) {
            if(!isset($_REQUEST['ticket_visibility'])){
                $error_flag         = TRUE;
                $error_message[]    = array(
                    'key'       => 'ticket_visibility',
                    'message'   => __('Please Select Ticket Visibility','pressapps-ticket'),
                );
            }elseif(!pati_is_valid_visibility($_REQUEST['ticket_visibility'])){
                $error_flag         = TRUE;
                $error_message[]    = array(
                    'key'       => 'ticket_visibility',
                    'message'   => __('Please Select Proper Ticket Visibility','pressapps-ticket'),
                );
            }
        }
  */ 
        if(isset($_REQUEST['assigned_user']) && $_REQUEST['assigned_user']!=-1){
            $assigned_userdata = explode(":",$_REQUEST['assigned_user']);
            if(count($assigned_userdata)!=2){
                $error_flag         = TRUE;
                $error_message[]    = array(
                    'key'       => 'assigned_user',
                    'message'   => __('Please Select Appropriate User Assigne for the Ticket','pressapps-ticket'),
                );
            }elseif (!current_user_can('assign_ticket')) {
                $error_flag         = TRUE;
                $error_message[]    = array(
                    'key'       => 'assigned_user',
                    'message'   => __("You don't Have the Rights To assign The Ticket",'pressapps-ticket'),
                );
            }
            
        }
 
      
        if($error_flag == TRUE){
            /**
             * @todo Add some code here To display the Validation Errors Properly
             */
            echo "<pre>";
            print_r($error_message);
            echo "</pre>";
            die('Error ');
            return ;
        }
        
        
        $updtes = array();

        $category               = pati_get_the_category($ticket_id);
        $status                 = pati_get_the_status($ticket_id);
        $priority               = pati_get_the_priority($ticket_id);
        $type                   = pati_get_the_type($ticket_id);
        $current_tags           = pati_get_the_tags($ticket_id,array('output'=>'name_array'));
        $current_assigne_detail = pati_get_the_assigned($ticket_id);

        if(isset($_REQUEST['category']) && (int)$_REQUEST['category']!=$category){
            $updtes['category'] = array('old'=>$category,'new'=>$_REQUEST['category']);
        }

        if(isset($_REQUEST['status']) && (int)$_REQUEST['status']!=$status){
            $updtes['status'] = array('old'=>$status,'new'=>$_REQUEST['status']);
        }
        
        if(isset($_REQUEST['priority']) && (int)$_REQUEST['priority']!=$priority){
            $updtes['priority'] = array('old'=>$priority,'new'=>$_REQUEST['priority']);
        }
        
        if(isset($_REQUEST['type']) && (int)$_REQUEST['type']!=$type){
            $updtes['type'] = array('old'=>$type,'new'=>$_REQUEST['type']);
        }
        
        if(count($assigned_userdata)==2){
            if(
                    (int)$current_assigne_detail['user_id'] != (int)$assigned_userdata[1]
                                    ||
                    (int)$current_assigne_detail['user_group'] != (int)$assigned_userdata[0]
            ){
                $updtes['user'] = array(
                    'old_user_id'       => $current_assigne_detail['user_id'] ,
                    'new_user_id'       => $assigned_userdata[1],
                    'old_user_group'    => $current_assigne_detail['user_group'] ,
                    'new_user_group'    => $assigned_userdata[0]
                );
            }
        }
        
        if(isset($_REQUEST['tags']) ) {
            $tags = explode(',',$_REQUEST['tags']);

            if(count(array_diff($tags,$current_tags))>0){
                $updtes['tags'] = array(
                    'old'   => $current_tags,
                    'new'   => $tags,
                );            
            }
            wp_set_object_terms($ticket_id  ,$tags                            ,'ticket_tags');
        }
        
        /**
         * Attach the updated Relationship with the ticket
         */
        
        if(isset($_REQUEST['category'])){
            wp_set_object_terms($ticket_id  ,(int)$_REQUEST['category']       ,'ticket_category');
        }
        if(isset($_REQUEST['status'])){
            wp_set_object_terms($ticket_id  ,(int)$_REQUEST['status']         ,'ticket_status');
        }
        if(isset($_REQUEST['priority'])){
            wp_set_object_terms($ticket_id  ,(int)$_REQUEST['priority']       ,'ticket_priority');
        }
        if(isset($_REQUEST['type'])){
            wp_set_object_terms($ticket_id  ,(int)$_REQUEST['type']           ,'ticket_type');
        }

        /**
         * Save the visibility of ticket
         */
        if(pati_is_visibility_custom())
            update_post_meta($ticket_id ,'pati_ticket_visibility' ,$_REQUEST['ticket_visibility']);
        else
            update_post_meta($ticket_id ,'pati_ticket_visibility' ,get_option('pati_ticket_visibility','private'));
        
        if(count($assigned_userdata)==2){
            wp_set_object_terms($ticket_id,$assigned_userdata[0],'user_group',FALSE);

            update_post_meta($ticket_id, 'pati_assigned_user'     ,$_REQUEST['assigned_user']);
            update_post_meta($ticket_id, 'pati_assigned_user_id'  ,$assigned_userdata[1]);
        }
       
        
        if(empty($_REQUEST['update']) && count($updtes)>0){
             $temp                  = '';
             $_REQUEST['update']    = $temp;
        }
        
        $data = array(
            'comment_post_ID'       => $ticket_id,
            'comment_author'        => $current_user->user_login,
            'comment_content'       => wpautop($_REQUEST['update']),
            'user_id'               => $current_user->ID,
            'comment_approved'      => 1,
        );

        $comment_id = wp_insert_comment($data);
        
        if(count($updtes)>0)
            update_comment_meta ($comment_id, 'ticket_update_summary', $updtes);
        
        /**
         * Handle File Upload of Update Ticket
         */
        $attachments = get_post_meta($ticket_id,'pati_attachments',TRUE);
        if(!empty($_REQUEST['attachments'])){
            foreach($_REQUEST['attachments'] as $attachment_id){
                wp_update_post(array(
                    'ID'            => $attachment_id,
                    'post_parent'   => $ticket_id,
                ));
                $attachments['comment'][$comment_id][] = $attachment_id;
            }
        }
        update_post_meta($ticket_id, 'pati_attachments', $attachments);
        
        do_action('pati_ticket_updated',array(
            'ticket_id'     => $ticket_id,
            'comment_id'    => $comment_id,
        ));
        $_REQUEST = array();
        
        wp_redirect(get_post_type_archive_link(PATI()->ticket->name));
        die('');
        
    }
    
    function add_ticket(){
        
        global $current_user,$error_flag,$error_message,$ticket_id;
        get_currentuserinfo();
        
        if(!is_user_logged_in()){
            wp_redirect(esc_url(home_url()));
            die('');
        }
       
        
        /**
         * Validation Checking for the Ticket Fields
         */
        $error_flag     = FALSE;
        $error_message  = array();
        $ticket_id      = -1;
        
        if(empty($_REQUEST['title'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_title',
                'message'   => __('Ticket Title can not be Left Blank','pressapps-ticket'),
            );
        }
        
/*
        if(empty($_REQUEST['content'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_content',
                'message'   => __('Ticket Content can not be Left Blank','pressapps-ticket'),
            );
        }
        if(!pati_is_valid_category($_REQUEST['category'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_category',
                'message'   => __('Please Select Appropriate Category for the Ticket','pressapps-ticket'),
            );
        }
        if(!pati_is_valid_status($_REQUEST['status'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_status',
                'message'   => __('Please Select Appropriate Status for the Ticket','pressapps-ticket'),
            );
        }
        
        if(!pati_is_valid_priority($_REQUEST['priority'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_priority',
                'message'   => __('Please Select Appropriate Priority for the Ticket','pressapps-ticket'),
            );
        }
       
        if(!pati_is_valid_type($_REQUEST['type'])){
            $error_flag         = TRUE;
            $error_message[]    = array(
                'key'       => 'ticket_type',
                'message'   => __('Please Select Appropriate Type for the Ticket','pressapps-ticket'),
            );
        }
        
        if(pati_is_visibility_custom()) {
            if(!isset($_REQUEST['ticket_visibility'])){
                $error_flag         = TRUE;
                $error_message[]    = array(
                    'key'       => 'ticket_visibility',
                    'message'   => __('Please Select Ticket Visibility','pressapps-ticket'),
                );
            }elseif(!pati_is_valid_visibility($_REQUEST['ticket_visibility'])){
                $error_flag         = TRUE;
                $error_message[]    = array(
                    'key'       => 'ticket_visibility',
                    'message'   => __('Please Select Proper Ticket Visibility','pressapps-ticket'),
                );
            }
        }
*/        
        
        if($error_flag == TRUE){
            return;
        }

        $ticket_id = wp_insert_post(array(
            'post_title'    => $_REQUEST['title'],
            'post_content'  => wpautop($_REQUEST['content']),
            'post_status'   => 'publish',
            'post_type'     => 'ticket',
            'post_author'   => $current_user->ID,
        ));
        
        /**
         * Store the Categories and Tags Details
         */
        if(isset($_REQUEST['category'])){
            wp_set_object_terms($ticket_id, array((int)$_REQUEST['category'])   ,'ticket_category');
        }
        if(isset($_REQUEST['status'])){
            wp_set_object_terms($ticket_id, array((int)$_REQUEST['status'])     ,'ticket_status');
        }
        if(isset($_REQUEST['priority'])){
            wp_set_object_terms($ticket_id, array((int)$_REQUEST['priority'])   ,'ticket_priority');
        }
        if(isset($_REQUEST['type'])){
            wp_set_object_terms($ticket_id, array((int)$_REQUEST['type'])       ,'ticket_type');
        }
        if(isset($_REQUEST['tags'])){
            $tags = explode(',',$_REQUEST['tags']);
            wp_set_object_terms($ticket_id, $tags                               ,'ticket_tags');
        }
        
        /**
         * Store the Assigned User Details
         */
        if(isset($_REQUEST['assigned_user']) && current_user_can('assign_ticket')){
            $data = explode(":",$_REQUEST['assigned_user']);

            if(count($data)==2){
                wp_set_object_terms($ticket_id,$data[0],'user_group',FALSE);

                update_post_meta($ticket_id, 'pati_assigned_user'     ,$_REQUEST['assigned_user']);
                update_post_meta($ticket_id, 'pati_assigned_user_id'  ,$data[1]);
            }
        }

        /**
         * Save the visibility of ticket
         */
        if(pati_is_visibility_custom())
            update_post_meta($ticket_id ,'pati_ticket_visibility' ,$_REQUEST['ticket_visibility']);
        else
            update_post_meta($ticket_id ,'pati_ticket_visibility' ,get_option('pati_ticket_visibility','private'));
        
        /**
         * Handle File Upload of Add ticket
         */
        $attachments = array('post'=>array(),'comment'=>array());
        if(!empty($_REQUEST['attachments'])){
            foreach($_REQUEST['attachments'] as $attachment_id){
                wp_update_post(array(
                    'ID'            => $attachment_id,
                    'post_parent'   => $ticket_id,
                ));
                $attachments['post'][] = $attachment_id;
            }
        }
        update_post_meta($ticket_id, 'pati_attachments' ,$attachments);
                
        do_action('pati_ticket_added',$ticket_id);
        $_REQUEST = array();
        
        wp_redirect(get_post_type_archive_link(PATI()->ticket->name));
        die('');
        
    }
}

new PATI_Frontend_Do_Action();
