<?php

/**
 * 
 */

class PATI_Post_Type {

	
    public function __construct() {        
        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
        /**
         * Set up the Post Type Upon Plugin Activation
         */
        add_action('pati_before_setup_plugin' , array(__CLASS__,'register_post_types'));
        add_action('pati_before_setup_plugin' , array(__CLASS__,'register_taxonomies'));
    }
    
    public static function register_post_types(){
        global $tickets_slug;
        
        register_post_type( 'ticket',array(
            'description'           => __('Tickets','pressapps-ticket'),
            'labels'                => array(
                'name'                  => __('Tickets'                     ,'pressapps-ticket'),
                'singular_name'         => __('Ticket'                      ,'pressapps-ticket'),
                'add_new'               => __('Add Ticket'                  ,'pressapps-ticket'),  
                'add_new_item'          => __('Add New Ticket'              ,'pressapps-ticket'),  
                'edit_item'             => __('Edit Ticket'                 ,'pressapps-ticket'),  
                'new_item'              => __('New Ticket'                  ,'pressapps-ticket'),  
                'view_item'             => __('View Tickets'                ,'pressapps-ticket'),  
                'search_items'          => __('Search Tickets'              ,'pressapps-ticket'),  
                'not_found'             => __('No Tickets found'            ,'pressapps-ticket'),  
                'not_found_in_trash'    => __('No Tickets found in Trash'   ,'pressapps-ticket'),
                'all_items'             => __('All Tickets'                 ,'pressapps-ticket'),
            ),
            'public'                => true,
            'menu_position'         => 5,
            'rewrite'               => array(
                'slug'       => !empty($tickets_slug) ? $tickets_slug : 'tickets',
                'with_front' => false,
            ),
            'supports'              => array('title','editor','author','comments'),
            'public'                => true,
            'show_ui'               => true,
            'publicly_queryable'    => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            //'capability_type'       => 'ticket',
        ));
        
    }

    public static function register_taxonomies(){

        register_taxonomy( 'ticket_category',array( 'ticket' ),array( 
            'hierarchical'  => false,
            'labels'        => array(
                'name'              => __( 'Categories'             ,'pressapps-ticket'),
                'singular_name'     => __( 'Category'               ,'pressapps-ticket'),
                'search_items'      => __( 'Search Categories'      ,'pressapps-ticket'),
                'all_items'         => __( 'All Categories'         ,'pressapps-ticket'),
                'parent_item'       => __( 'Parent Category'        ,'pressapps-ticket'),
                'parent_item_colon' => __( 'Parent Category:'       ,'pressapps-ticket'),
                'edit_item'         => __( 'Edit Category'          ,'pressapps-ticket'),
                'update_item'       => __( 'Update Category'        ,'pressapps-ticket'),
                'add_new_item'      => __( 'Add New Category'       ,'pressapps-ticket'),
                'new_item_name'     => __( 'New Category Name'      ,'pressapps-ticket'),
                'popular_items'     => NULL,
                'menu_name'         => __( 'Categories'             ,'pressapps-ticket') 
            ),
            'show_ui'       => true,
            'public'        => true,
            'query_var'     => true,
            'hierarchical'  => false,
            'rewrite'       => array( 'slug' => 'ticket_category' )
        ));

        register_taxonomy( 'ticket_status',array( 'ticket' ),array( 
            'hierarchical'  => false,
            'labels'        => array(
                'name'              => __( 'Status'               ,'pressapps-ticket'),
                'singular_name'     => __( 'Status'               ,'pressapps-ticket'),
                'search_items'      => __( 'Search Status'        ,'pressapps-ticket'),
                'all_items'         => __( 'All Status'           ,'pressapps-ticket'),
                'parent_item'       => __( 'Parent Status'        ,'pressapps-ticket'),
                'parent_item_colon' => __( 'Parent Status:'       ,'pressapps-ticket'),
                'edit_item'         => __( 'Edit Status'          ,'pressapps-ticket'),
                'update_item'       => __( 'Update Status'        ,'pressapps-ticket'),
                'add_new_item'      => __( 'Add New Status'       ,'pressapps-ticket'),
                'new_item_name'     => __( 'New Status Name'      ,'pressapps-ticket'),
                'popular_items'     => NULL,
                'menu_name'         => __( 'Status'               ,'pressapps-ticket') 
            ),
            'show_ui'       => true,
            'public'        => true,
            'query_var'     => true,
            'hierarchical'  => false,
            'rewrite'       => array( 'slug' => 'ticket_status' )
        ));
        
        register_taxonomy( 'ticket_priority',array( 'ticket' ),array( 
            'hierarchical'  => false,
            'labels'        => array(
                'name'              => __( 'Priority'               ,'pressapps-ticket'),
                'singular_name'     => __( 'Priority'               ,'pressapps-ticket'),
                'search_items'      => __( 'Search Priority'        ,'pressapps-ticket'),
                'all_items'         => __( 'All Priority'           ,'pressapps-ticket'),
                'parent_item'       => __( 'Parent Priority'        ,'pressapps-ticket'),
                'parent_item_colon' => __( 'Parent Priority:'       ,'pressapps-ticket'),
                'edit_item'         => __( 'Edit Priority'          ,'pressapps-ticket'),
                'update_item'       => __( 'Update Priority'        ,'pressapps-ticket'),
                'add_new_item'      => __( 'Add New Priority'       ,'pressapps-ticket'),
                'new_item_name'     => __( 'New Priority Name'      ,'pressapps-ticket'),
                'popular_items'     => NULL,
                'menu_name'         => __( 'Priority'               ,'pressapps-ticket') 
            ),
            'show_ui'       => true,
            'public'        => true,
            'query_var'     => true,
            'hierarchical'  => false,
            'rewrite'       => array( 'slug' => 'ticket_priority' )
        ));
        
        register_taxonomy( 'ticket_type',array( 'ticket' ),array( 
            'hierarchical'  => false,
            'labels'        => array(
                'name'              => __( 'Type'               ,'pressapps-ticket'),
                'singular_name'     => __( 'Type'               ,'pressapps-ticket'),
                'search_items'      => __( 'Search Type'        ,'pressapps-ticket'),
                'all_items'         => __( 'All Type'           ,'pressapps-ticket'),
                'parent_item'       => __( 'Parent Type'        ,'pressapps-ticket'),
                'parent_item_colon' => __( 'Parent Type:'       ,'pressapps-ticket'),
                'edit_item'         => __( 'Edit Type'          ,'pressapps-ticket'),
                'update_item'       => __( 'Update Type'        ,'pressapps-ticket'),
                'add_new_item'      => __( 'Add New Type'       ,'pressapps-ticket'),
                'new_item_name'     => __( 'New Type Name'      ,'pressapps-ticket'),
                'popular_items'     => NULL,
                'menu_name'         => __( 'Type'               ,'pressapps-ticket') 
            ),
            'show_ui'       => true,
            'public'        => true,
            'query_var'     => true,
            'hierarchical'  => false,
            'rewrite'       => array( 'slug' => 'ticket_type' )
        ));
        
        register_taxonomy( 'ticket_tags',array( 'ticket' ),array( 
            'hierarchical'  => false,
            'labels'        => array(
                'name'              => __( 'Tags'              ,'pressapps-ticket'),
                'singular_name'     => __( 'Tag'               ,'pressapps-ticket'),
                'search_items'      => __( 'Search Tags'       ,'pressapps-ticket'),
                'all_items'         => __( 'All Tags'          ,'pressapps-ticket'),
                'parent_item'       => __( 'Parent Tag'        ,'pressapps-ticket'),
                'parent_item_colon' => __( 'Parent Tag:'       ,'pressapps-ticket'),
                'edit_item'         => __( 'Edit Tag'          ,'pressapps-ticket'),
                'update_item'       => __( 'Update Tag'        ,'pressapps-ticket'),
                'add_new_item'      => __( 'Add New Tag'       ,'pressapps-ticket'),
                'new_item_name'     => __( 'New Tag Name'      ,'pressapps-ticket'),
                'popular_items'     => NULL,
                'menu_name'         => __( 'Tags'              ,'pressapps-ticket') 
            ),
            'show_ui'       => true,
            'public'        => true,
            'query_var'     => true,
            'hierarchical'  => false,
            'rewrite'       => array( 'slug' => 'ticket_tags' )
        ));
        
        register_taxonomy( 'user_group',array( 'ticket' ),array( 
            'hierarchical'  => false,
            'labels'        => array(
                'name'              => __( 'Groups'              ,'pressapps-ticket'),
                'singular_name'     => __( 'Group'               ,'pressapps-ticket'),
                'search_items'      => __( 'Search Groups'       ,'pressapps-ticket'),
                'all_items'         => __( 'All Groups'          ,'pressapps-ticket'),
                'parent_item'       => __( 'Parent Group'        ,'pressapps-ticket'),
                'parent_item_colon' => __( 'Parent Group:'       ,'pressapps-ticket'),
                'edit_item'         => __( 'Edit Group'          ,'pressapps-ticket'),
                'update_item'       => __( 'Update Group'        ,'pressapps-ticket'),
                'add_new_item'      => __( 'Add New Group'       ,'pressapps-ticket'),
                'new_item_name'     => __( 'New Group Name'      ,'pressapps-ticket'),
                'popular_items'     => NULL,
                'menu_name'         => __( 'Groups'              ,'pressapps-ticket') 
            ),
            'show_ui'       => true,
            'public'        => true,
            'query_var'     => true,
            'hierarchical'  => false,
            'rewrite'       => array( 'slug' => 'ticket_tags' )
        ));
    }
    
}

new PATI_Post_Type();