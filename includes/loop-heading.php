<?php


/**
 * 
 * @todo This Needs to Be Improved and Added so the Classification of the Tickets
 * Can Be easily Linked So this Section Still needs some updation over this part
 * That's why though not included in template not removed from the Files
 * 
 * @return type
 */                  
function pati_the_statistics(){
    
    $states = pati_get_ticket_statistics();
    
    if(!is_array($states) || empty($states))
        return ;
    
    foreach($states as $state){
        $output[] = "<a href=\"" . get_term_link((int)$state->term_id,'ticket_status') . "\">{$state->name} ({$state->total})</a>";
    }
    
    echo implode("&nbsp;|&nbsp;", $output);
}

/**
 * In case we wanted to change the Element of the Header Column we can Apply
 * the "pati_col_head_element" and returnf the Element there
 * 
 * add_filter('pati_col_head_element',function(){return 'DIV';});
 * 
 * @param string $column
 * @param array $args
 */
function pati_the_col_head($column){
    
    $default_args = array(
        'label'     => '',
    );
    
    $html       = '';
    $sort_var   = NULL;
    $orderby    = strtolower(get_query_var('orderby'));
    $order      = strtoupper(get_query_var('order'));
    $alt_order  = ($order == 'ASC')?'DESC':'ASC';
    $args       = $default_args;
        
    switch($column){
        case 'updated':
            $sort_var = array(
                'orderby'   => 'modified',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'modified')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Updated'      ,'pressapps-ticket'):$args['label'];
            break;
        case 'author':
            $sort_var = array(
                'orderby'   => 'author',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'author')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Author'      ,'pressapps-ticket'):$args['label'];
            break;
        case 'ticket_title':
            
            $sort_var = array(
                'orderby'   => 'post_title',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'post_title')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Title'      ,'pressapps-ticket'):$args['label'];
            
            
            break;
        case 'ticket_id';
            $sort_var = array(
                'orderby'   => 'ID',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'id')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('ID'      ,'pressapps-ticket'):$args['label'];
            break;
        case 'ticket_status':
            $sort_var = array(
                'orderby'   => 'ticket_status',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'ticket_status')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Status'      ,'pressapps-ticket'):$args['label'];
            break;
        case 'ticket_category':
            $sort_var = array(
                'orderby'   => 'ticket_category',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'ticket_category')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Category'      ,'pressapps-ticket'):$args['label'];
            break;
        case 'ticket_priority':
            $sort_var = array(
                'orderby'   => 'ticket_priority',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'ticket_priority')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Priority'      ,'pressapps-ticket'):$args['label'];
            break;
        case 'ticket_type':
            $sort_var = array(
                'orderby'   => 'ticket_type',
                'order'     => 'ASC',    
            );
            
            if(($orderby == 'ticket_type')){
                $sort_var['order']  = $alt_order;
            }
            
            $args['label'] = (empty($args['label']))?__('Type'      ,'pressapps-ticket'):$args['label'];
            break;
        default:
            $args['label'] = $column;
            break;
    }
    
    /**
     * Generate the HTML Based on the Finalized Value
     */
    if ( isset($_GET['status']) && $_GET['status'] == 'closed' ) {
        $html .= "<a href='" . get_post_type_archive_link( 'ticket' ) . "?status=closed&orderby={$sort_var['orderby']}&order={$sort_var['order']}' ";
    } else {
        $html .= "<a href='" . get_post_type_archive_link( 'ticket' ) . "?orderby={$sort_var['orderby']}&order={$sort_var['order']}' ";
    }
    $html .= ">";
    $html .= $args['label'];
    $html .= "</a>";
    
    return $html;
}