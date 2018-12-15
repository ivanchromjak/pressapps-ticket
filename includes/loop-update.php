<?php

/**
 * 
 * @package PressApps::Ticket
 * @subpackage Loop
 */

function pati_get_ticket_updates($ticket_id = NULL , $args = array()){
    global $patis;
    
    if(is_null($ticket_id)){
        $ticket_id = pati_get_the_ID();
    }
    
    if ($patis->get('commnents_reverse')) {
        $order = 'DESC';
    } else {
        $order = 'ASC';
    }

    $comments       = get_comments(array(
        'post_id'   => $ticket_id,
        'order' => $order,
    ));
    
    return $comments;
    
}
function pati_ticket_have_updates(){
    
}


function pati_get_the_update_ID(){
    global $pati_comment;
    
    return (!empty($pati_comment))?$pati_comment->comment_ID:'0';
}

function pati_the_update_ID(){
    echo pati_get_the_update_ID();
}

function pati_setup_comment($comment){
    
    global $pati_comment;
   
    $pati_comment = $comment;
   
}

function pati_the_update_content(){
    
    echo pati_get_the_update_content();
}

function pati_get_the_update_content(){
    
    global $pati_comment;

    if(isset($pati_comment->comment_content) && $pati_comment->comment_content != '') {
        return $pati_comment->comment_content;
    } else {
        return '<p>' . __('Ticket updated:','pressapps-ticket') . '</p>';
    }
                 
}

function pati_the_update_avatar($size = NULL){
    $size  = ((is_null($size))?64:$size);
    echo pati_get_the_update_avatar($size);
}

function pati_get_the_update_avatar($size = 64){
    global $pati_comment;
    
    if(empty($pati_comment))
        return '';
    
    return get_avatar($pati_comment->user_id,$size);
}

function pati_the_update_author(){
    echo pati_get_the_update_author();
}

function pati_get_the_update_author($args = array()){
    global $pati_comment;
    
    $default    = array(
        'output'    => 'display_name'
    );
    
    $args       = array_merge($default,$args);
    $author     = get_user_by('id', $pati_comment->user_id);
    
    switch (strtolower($args['output'])){
        case 'object':
            return apply_filters('pati_the_update_author',$author,$args);
            break;
        case 'id':
            return apply_filters('pati_the_update_author',$author->ID,$args);
            break;
        case 'display_name':
        default:
            return apply_filters('pati_the_update_author',$author->display_name,$args);
            break;
        case 'name':
            return apply_filters('pati_the_update_author',$author->user_login,$args);
            break;
    }
}

function pati_the_update_time(){
    echo pati_get_the_update_time();
}

function pati_get_the_update_time(){
    global $pati_comment;
    
    return ((isset($pati_comment->comment_date))?date(PATI_DATEFORMAT,  strtotime($pati_comment->comment_date)):'');
}

function pati_the_update_files(){
    echo pati_get_the_update_files();
}

function pati_get_the_update_files(){
    
    $attachments    = pati_get_ticket_attachment_structure(pati_get_the_ID());
    $files          = pati_get_post_attachments(pati_get_the_ID());
    $comment_id     = pati_get_the_update_ID();
    
    if(!isset($attachments['comment'][$comment_id])){
       // return __('No Attachment Has been Uploaded with this Update','pressapps-ticket');
       return;
    }
    
    $temp = "<ul>";
    
    foreach($attachments['comment'][$comment_id] as $attachment_id){
        
        $temp .= "<li>";
        $temp .= "<a href=\"{$files[$attachment_id]->attachment_url}\" target=\"_blank\">{$files[$attachment_id]->post_title}</a>";
        $temp .= "</li>";
        
    }
    
    $temp   .= '</ul>';
    
    return $temp;
}

function pati_has_update_summary(){
    $updates = get_comment_meta(pati_get_the_update_ID(),'ticket_update_summary',TRUE);
    
    return (empty($updates))?FALSE:$updates;
}

function pati_the_update_summary(){
    $updates = pati_has_update_summary();
    
    if(!$updates)
        return ;
    
    echo '<p class="pati-update-details">';

    foreach($updates as $key => $value){
        switch($key){
            case 'user':
                $group_label[-1] = __('Not Assigned'    ,'pressapps-ticket');
                $user_label[-1]  = __('Not Assigned'    ,'pressapps-ticket');
                
                if($value['old_user_group'] != -1){
                    $temp = get_term_by('slug', $value['old_user_group'], PATI()->ticket_user_group->name);
                    $group_label[$value['old_user_group']] = $temp->name;
                }
                
                if($value['new_user_group'] != -1){
                    $temp = get_term_by('slug', $value['new_user_group'], PATI()->ticket_user_group->name);
                    $group_label[$value['new_user_group']] = $temp->name;
                }
                
                if($value['old_user_id'] != -1){
                    $temp = get_user_by('id', $value['old_user_id']);
                    $user_label[$value['old_user_id']] = $temp->display_name;
                }
                
                if($value['new_user_id'] != -1){
                    $temp = get_user_by('id', $value['new_user_id']);
                    $user_label[$value['new_user_id']] = $temp->display_name;
                }
                
                echo "<span>";
                echo __('Assigned','pressapps-ticket');
                echo ": {$user_label[$value['new_user_id']]}";
                //echo ": " . PATI()->ticket_user_group->labels->name;
                echo " - {$group_label[$value['new_user_group']]}";
                echo "</span>";
                break;
            /*    
            case 'tags':
                echo "<label>";
                echo __("Tags have been updated",'pressapps-ticket');
                echo "</label>&nbsp;";
                echo "<br/>";
                break;
            */
            case 'status':
                $status = get_terms(PATI()->ticket_status->name, array(
                    'include'       => $value,
                    'hide_empty'    => FALSE,
                ));
                
                foreach($status as $s){
                    $final_status[$s->term_id] = $s;
                }
                echo "<span>";
                echo PATI()->ticket_status->labels->name;
                echo ": {$final_status[$value['new']]->name}";
                echo "</span>";
                
                break;
            case 'priority':
                
                $priority = get_terms(PATI()->ticket_priority->name, array(
                    'include'       => array_values($value),
                    'hide_empty'    => FALSE,
                ));
                
                foreach($priority as $p){
                    $final_priority[$p->term_id] = $p;
                }
                
                echo "<span>";
                echo PATI()->ticket_priority->labels->name;
                echo ": {$final_priority[$value['new']]->name}";
                echo "</span>";
                
                break;
            case 'type':
                $type = get_terms(PATI()->ticket_type->name, array(
                    'include'       => $value,
                    'hide_empty'    => FALSE,
                ));
                foreach($type as $t){
                    $final_type[$t->term_id] = $t;
                }
                
                echo "<span>";
                echo PATI()->ticket_type->labels->name;
                echo ": {$final_type[$value['new']]->name}";
                echo "</span>";
                
                break;
            
        }
    }
    echo '</p>';
}

function pati_the_update_class( $class = '') {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . implode( ' ', pati_get_update_class( $class, pati_get_the_update_ID() ) ) . '"';
}

function pati_get_update_class($class = '', $ticket_id){
    
    $classes = array();
    
    $classes[] = 'pati-update-body';
    
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
    
    return apply_filters('pati_get_update_class', $classes, $class, $ticket_id);
}