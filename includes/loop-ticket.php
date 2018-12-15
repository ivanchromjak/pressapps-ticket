<?php

/**
 * 
 * @package PressApps::Ticket
 * @subpackage Loop
 */

function pati_the_title(){
    echo pati_get_the_title();
}

function pati_get_the_title(){
    
    $post = get_post(pati_get_the_ID());
    
    return apply_filters('pati_the_title',$post->post_title);
    
}

/**
 * Ticket Content related functions
 */
function pati_the_content(){
    echo pati_get_the_content();
}

function pati_get_the_content(){
    
    $post = get_post(pati_get_the_ID());
    
    return apply_filters('pati_the_content',$post->post_content);
    
}
/**
 * Ticket Author related functions
 */
function pati_the_author($args = array()){
    echo pati_get_the_author($args);
}

function pati_get_the_author($args = array()){
    
    $default    = array(
        'output'    => 'display_name'
    );
    
    $args = array_merge($default,$args);
    
    $post       = get_post(pati_get_the_ID());
    
    if(empty($post))
        return '';
    
    $author = get_user_by('id', $post->post_author);
    
    if(empty($author))
        return NULL;
    
    switch (strtolower($args['output'])){
        case 'object':
            return apply_filters('pati_the_author',$author,$args);
            break;
        case 'id':
            return apply_filters('pati_the_author',$author->ID,$args);
            break;
        case 'display_name':
        default:
            return apply_filters('pati_the_author',$author->display_name,$args);
            break;
        case 'name':
            return apply_filters('pati_the_author',$author->user_login,$args);
            break;
        case 'user_email':
            return apply_filters('pati_the_author',$author->user_email,$args);
            break;
    }
}

/**
 * Ticket Category related functions
 */
function pati_the_category(){
    global $patis;
    $category = pati_get_the_category(pati_get_the_ID(),array('output'=>    'object'));

    if(!empty($category)){
        echo "<label class=\"pati-category-label pati-category-{$category->slug}\">";
        echo $category->name;
        echo '</label>';
    }
}

function pati_get_the_category($ticket_id = 0,$args = array()){
    
    $default = array(
        'output'    => ''
    );
    
    $args = array_merge($default,$args);
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    
    $category = wp_get_post_terms($ticket_id,'ticket_category');
    
    if($args['output'] == 'object'){
        return (empty($category))?NULL:$category[0];
    }elseif($args['output'] == 'string'){
        return (empty($category))?NULL:$category[0]->name;
    }else{
        return (empty($category))?NULL:$category[0]->term_id;
    }
    
}

/**
 * Ticket Status related functions
 */
function pati_the_status(){
    global $patis;
    $status = pati_get_the_status(pati_get_the_ID(),array('output'=>'object'));
    if (!empty($status)) {
        if($patis->get('status_titles')){
            echo '<label>' . $status->name . '</label>';
        } else {
            echo '<label></label>';
        }
    }
}

function pati_the_status_class(){
    global $patis;
    $status = pati_get_the_status(pati_get_the_ID(),array('output'=>'object'));

    if (!empty($status)) {
        if($patis->get('status_titles')){
            echo ' pati-status-label pati-status-' . $status->slug;
        } else {
            echo ' pati-status-icon pati-status-' . $status->slug;
        }
    }
}

function pati_get_the_status($ticket_id = 0,$args = array()){
    
    $default = array(
        'output'    => ''
    );
    
    $args = array_merge($default,$args);
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    
    $status = wp_get_post_terms($ticket_id,'ticket_status');
    
    if($args['output'] == 'object'){
        return (empty($status))?NULL:$status[0];
    }elseif($args['output'] == 'string'){
        return (empty($status))?NULL:$status[0]->name;
    }else{
        return (empty($status))?NULL:$status[0]->term_id;
    }
    
}

/**
 * Ticket Priority related functions
 */
function pati_the_priority(){
    $priority = pati_get_the_priority(pati_get_the_ID(),array('output'=>'object'));
    if (!empty($priority)) {
        echo "<label class=\"pati-priority-label pati-priority-{$priority->slug}\">";
        echo $priority->name;
        echo "</label>" ;
    }
}

function pati_get_the_priority($ticket_id = 0,$args = array()){
    $default = array(
        'output'    => ''
    );
    
    $args = array_merge($default,$args);
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    
    $priority = wp_get_post_terms($ticket_id,'ticket_priority');
    
    if($args['output'] == 'object'){
        return (empty($priority))?NULL:$priority[0];
    }elseif($args['output'] == 'string'){
        return (empty($priority))?NULL:$priority[0]->name;
    }else{
        return (empty($priority))?NULL:$priority[0]->term_id;
    }

}

function pati_get_the_user_group(){
    
}

/**
 * Ticket type related functions
 */
function pati_the_type(){
    $type = pati_get_the_type(pati_get_the_ID(),array('output'=>'object'));
    if(!empty($type)){
        echo "<label class=\"pati-type-label pati-type-{$type->slug}\">";
        echo $type->name;
        echo "</label>" ;
    }
}

function pati_get_the_type($ticket_id = 0,$args = array()){
    $default = array(
        'output'    => ''
    );
    
    $args = array_merge($default,$args);
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    
    $type = wp_get_post_terms($ticket_id,'ticket_type');
    
    if($args['output'] == 'object'){
        return (empty($type))?NULL:$type[0];
    }elseif($args['output'] == 'string'){
        return (empty($type))?NULL:$type[0]->name;
    }else{
        return (empty($type))?NULL:$type[0]->term_id;
    }
    
}
/**
 * Ticket tags related functions
 */
function pati_the_tags(){
    echo pati_get_the_tags();
}

function pati_get_the_tags($ticket_id=0 , $args = array()){

    $default = array(
        'output'    => ''
    );
    
    $args = array_merge($default,$args);
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    
    $tags = wp_get_post_terms($ticket_id,'ticket_tags');
    
    switch($args['output']){
        case 'name_array':
            $final_tag = array();
            foreach($tags as $tag){
                $final_tag[]    = $tag->name;
            }
            return $final_tag;
            break;
        case 'object':
            return (is_array($tags))?$tags:array();
            break;
        default:
        case 'name':
            $temp = array();
            foreach($tags as $tag){
                $temp[] = $tag->name;
            }
            return implode(",", $temp);
            break;
    }
    
    
    
}

/**
 * Ticket timing Related functions
 */

function pati_the_created_time(){
    echo pati_get_the_created_time();
}

function pati_get_the_created_time(){
    $post = get_post(pati_get_the_ID());
    
    return apply_filters('pati_the_created_time',date(PATI_DATEFORMAT,  strtotime($post->post_date_gmt)));
}

function pati_the_modified_time(){
    echo pati_get_the_modified_time();
}

function pati_get_the_modified_time(){
    $post = get_post(pati_get_the_ID());
    
    return apply_filters('pati_the_created_time',date(PATI_DATEFORMAT,  strtotime($post->post_modified_gmt)));
}

function pati_the_avatar($size = NULL){
    $size  = ((is_null($size))?64:$size);
    echo pati_get_the_avatar($size);
}

function pati_get_the_avatar($size = 64){
    
    $avtar = get_avatar(pati_get_the_author(array('output'=>'id')),$size);

    return (empty($avtar)?NULL:$avtar);
}
/**
 * File Related Function
 */

/**
 * Get the Attachment Structure for the Batterment of the Use case of those 
 * Attachment which links it with main post and comments as well.
 * 
 * @param int $ticket_id
 * @return empty|Array
 */
function pati_get_ticket_attachment_structure($ticket_id){
    return get_post_meta($ticket_id,'pati_attachments',TRUE);
}

/**
 * 
 */
function pati_the_ticket_files(){
    echo pati_get_the_ticket_files();
}

function pati_get_the_ticket_files(){
    
    $attachments    = pati_get_ticket_attachment_structure(pati_get_the_ID());
    $files          = pati_get_post_attachments(pati_get_the_ID());
    

    if(count($attachments['post']) == 0 || count($files)==0){
        //return __('No Attachment Has been Uploaded with this Ticket','pressapps-ticket');
    }
    
    $temp = "<ul>";
    
    foreach($attachments['post'] as $attachment_id){
        if(!isset($files[$attachment_id])){
            continue;
        }
        $temp .= "<li>";
        $temp .= "<a href=\"{$files[$attachment_id]->attachment_url}\" target=\"_blank\">{$files[$attachment_id]->post_title}</a>";
        $temp .= "</li>";
        
    }
    
    $temp   .= '</ul>';
    
    return $temp;
}

function pati_get_ticket_files($ticket_id){
    
    $files = get_children(array(
        'post_parent' => $ticket_id,
        'post_type'   => 'attachment', 
        'numberposts' => -1,
        'post_status' => 'inherit'
    ));
    
    
    if(count($files) == 0){
        return $files;
    }
    
    foreach($files as $file){
        $files[$file->ID]->attachment_url = wp_get_attachment_url($file->ID);
    }
    
    return $files;
}

/**
 *  assignee Related functions
 */

/**
 * 
 */
function pati_the_assigned(){
    $assigned = pati_get_the_assigned();
    
    if(is_null($assigned['user'])){
        echo __('No one','pati');
    }else{
        echo $assigned['user_group'] . ' :: ' . $assigned['user']->display_name ;
    }
}

function pati_get_the_assigned($ticket_id   = 0,$args = array()){
    
    $default_args = array(
        'output'    => 'array',
    );
    
    $args   = array_merge($default_args,$args);
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    
    $meta_assigned              = get_post_meta($ticket_id,'pati_assigned_user',TRUE);
    
    switch($args['output']){
        case 'key':
            return $meta_assigned;
            break;
        case 'array':
            if(empty($meta_assigned)){
                $assigned['user_group'] = -1;        
                $assigned['user_id']    = -1;
                $assigned['user']       = NULL;
            }else{
                $temp                   = explode(':',$meta_assigned);
                $assigned['user_group'] = $temp[0];        
                $assigned['user_id']    = $temp[1];
                $assigned['user']       = get_user_by('id',$assigned['user_id']);
            }

            return $assigned;
            break;
    }
}

function pati_the_ticket_visibility(){
    
}

function pati_get_the_ticket_visibility(){
    
}

function pati_the_class( $class = '') {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . implode( ' ', pati_get_class( $class, pati_get_the_ID() ) ) . '"';
}

function pati_get_class($class = '', $ticket_id){
    
    $classes = array();
    
    $classes[] = 'pati-body';
    
    /**
     * @todo Add the Classes based on Ticket Update Status and further Static
     * classes required for the Styling of the Element
     */
    
    if(!empty($class)){
        if(is_array($class)){
            $classes    = array_merge($classes,$class);
        }else{
            $classes[]  = $class;
        }
    }
    
    return apply_filters('pati_get_class', $classes, $class, $ticket_id);
}