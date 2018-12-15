<?php
function pati_initialize_capabilities(){
    /**
     * Add some of the Additional Capabilities to the System
     */
    $admin      = get_role( 'administrator' );
    $subscriber = get_role( 'subscriber' );
    $editor     = get_role( 'editor' );    

   /**
    * General Capability for the Ticket CRUD Operations
    */
   $admin->add_cap('edit_ticket');
   $admin->add_cap('edit_tickets');
   $admin->add_cap('read_ticket');
   $admin->add_cap('delete_ticket');
   $admin->add_cap('edit_others_tickets');
   $admin->add_cap('publish_tickets');
   $admin->add_cap('read_private_tickets');
   $admin->add_cap('delete_private_tickets');
   $admin->add_cap('delete_published_tickets');
   $admin->add_cap('delete_others_tickets');
   $admin->add_cap('edit_private_tickets');
   $admin->add_cap('edit_published_tickets');
   $admin->add_cap('assign_ticket');

   /**
    * Capabilities for the Editor User
    */
   $editor->add_cap('edit_ticket');
   $editor->add_cap('edit_tickets');
   $editor->add_cap('read_ticket');
   $editor->add_cap('delete_ticket');
   $editor->add_cap('edit_others_tickets');
   $editor->add_cap('publish_tickets');
   $editor->add_cap('read_private_tickets');
   $editor->add_cap('delete_private_tickets');
   $editor->add_cap('delete_published_tickets');
   $editor->add_cap('delete_others_tickets');
   $editor->add_cap('edit_private_tickets');
   $editor->add_cap('edit_published_tickets');
   $editor->add_cap('assign_ticket');

   /**
    * Additional Capabilities for Subscriber
    */
   $subscriber->add_cap('edit_ticket');
   $subscriber->add_cap('read_ticket');
   $subscriber->add_cap('edit_tickets');
}

function pati_setup_default_content(){
    /**
     * Create some of the Existing Data
     */
        
    $ticket_type = get_terms('ticket_type',array('hide_empty'=>FALSE));
    
    if(empty($ticket_type)){
        wp_insert_term(__('Question'        ,'pressapps-ticket')    ,'ticket_type');
        wp_insert_term(__('Incident'        ,'pressapps-ticket')    ,'ticket_type');
        wp_insert_term(__('Problem'         ,'pressapps-ticket')    ,'ticket_type');
        wp_insert_term(__('Task'            ,'pressapps-ticket')    ,'ticket_type');
    }

    $ticket_status = get_terms('ticket_status',array('hide_empty'=>FALSE));

    if(empty($ticket_status)){
        $term = wp_insert_term(__('Open'            ,'pressapps-ticket')    ,'ticket_status' , array('description'   => '1'));
        update_option( "ticket_status_{$term['term_id']}", array('catBG'  => '#D65555'));
        
        $term = wp_insert_term(__('Pending'         ,'pressapps-ticket')    ,'ticket_status' , array('description'   => '2'));
        update_option( "ticket_status_{$term['term_id']}", array('catBG'  => '#5E99CE'));

        $term = wp_insert_term(__('Closed'        ,'pressapps-ticket')    ,'ticket_status' , array('description'   => '3'));
        update_option( "ticket_status_{$term['term_id']}", array('catBG'  => '#8EC060'));
    }

    $ticket_priority = get_terms('ticket_priority',array('hide_empty'=>FALSE));

    if(empty($ticket_priority)){
        wp_insert_term(__('Normal'          ,'pressapps-ticket')    ,'ticket_priority' , array('description'   => '1'));
        wp_insert_term(__('Low'             ,'pressapps-ticket')    ,'ticket_priority' , array('description'   => '2'));        
        wp_insert_term(__('High'            ,'pressapps-ticket')    ,'ticket_priority' , array('description'   => '3'));
        wp_insert_term(__('Urgent'          ,'pressapps-ticket')    ,'ticket_priority' , array('description'   => '4'));
    }
      
}