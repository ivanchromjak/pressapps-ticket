<?php

class PATI_Ticket_Filtering {
    
    function __construct() {
        add_filter('init',array($this,'init'));
    }
    
    function init(){
        add_filter('pre_get_posts',array($this,'pre_get_posts'));
    }
    
    function pre_get_posts($query){
        global $current_user, $patis;
        get_currentuserinfo();
        
        if( (is_post_type_archive('ticket') || is_tax(array('ticket_priority','ticket_type','ticket_status','ticket_category'))) && $query->is_main_query() && !is_admin() ) {
            
            $order_by = get_query_var('orderby');
            $order    = get_query_var('order');
            $limit    = $patis->get('tickets_per_page');
            $page_no  = get_query_var('paged');
            $page_no  = (empty($page_no)?1:get_query_var('paged'));  
            
            $query->set('posts_per_page',$limit);
            
            /**
             * display only Current User's Own ticket in the List don't List
             * Other tickets to the User 
             */
            if(current_user_can('edit_ticket') && !current_user_can('edit_others_tickets')){
                $query->set('author',$current_user->ID);
            }

            // Exlude Closed tickets
            if ( $patis->get('hide_closed_tickets') && $patis->get('status_closed') ) {

                if ( isset($_GET['status']) && $_GET['status'] == 'closed' ) {

                    $query->set( 'tax_query', array(
                        array(
                            'taxonomy' => 'ticket_status',
                            'field' => 'term_id',
                            'terms' => $patis->get('status_closed'),
                            'operator' => 'IN'
                        )
                    ) );

                } else {

                    $query->set( 'tax_query', array(
                        array(
                            'taxonomy' => 'ticket_status',
                            'field' => 'term_id',
                            'terms' => $patis->get('status_closed'),
                            'operator' => 'NOT IN'
                        )
                    ) );

                }

            }
            
            /**
             * Custom Shorting Logic for the Taxonomies
             */
            if(in_array($order_by, array('ticket_status','ticket_priority','ticket_type','ticket_category'))){
                
                add_filter( 'posts_clauses', array($this,'posts_clauses'), 10, 2);
                
            }
            
        }
        return $query;
    }
    
    function posts_clauses($clauses, $wp_query){
        global $wpdb,$wp_query;                    
        $clauses['join']    .= "
            LEFT OUTER JOIN {$wpdb->term_relationships} AS rel2 ON {$wpdb->posts}.ID = rel2.object_id
            LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tax2 ON rel2.term_taxonomy_id = tax2.term_taxonomy_id
            LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
        ";
        $clauses['where']   .= " AND (taxonomy = '{$wp_query->query['orderby']}' OR taxonomy IS NULL)";
        $clauses['orderby']  = " " . $wpdb->terms . ".name  {$wp_query->query['order']}";

        return $clauses;
    }
}


new PATI_Ticket_Filtering();

