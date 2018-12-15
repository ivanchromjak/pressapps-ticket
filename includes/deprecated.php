<?php

function pati_the_ticket_updates($args = array()){
    
    $default_args   = array(
        'post_id'   => pati_get_the_ID(),
        'element'   => 'ol',
    );
    
    $args       = array_merge($default_args,$args);
    $classes    = array('pati-events');
    
    if(isset($args['element_class'])){
        $classes = array_merge($classes,$args['element_class']);
    }
    
    $comments       = get_comments(array(
        'post_id'   => $args['post_id'],
    ));
    
    if(count($comments)>0){
        echo "<{$args['element']} class=\"" . implode(",", $classes) . "\">";
        foreach($comments as $comment){
            pati_setup_comment($comment);
            pati_the_update();
        }
        echo "</{$args['element']}>";
    }else{
        echo pati_load_file(pati_get_template_files('noupdate'));
    }
    
}

function pati_the_update(){
    echo pati_load_file(pati_get_template_files('update-list'));
}

/**
 * 
 * get the proper File based on the $case 
 * 
 * @param string $case
 * @return string correct file path
 */
function pati_get_template_files($case = 'single'){
    
    switch($case){
        case 'noupdate':
            $filename       = 'ticket-noupdate.php';
            break;
        case 'summary':
            $filename       = 'ticket-summary.php';
            break;
        case 'update-list':
            $filename       = 'ticket-update-list.php';
            break;
        case 'update_ticket_form':
            $filename       = 'ticket-update.php';
            break;
        case 'add_ticket':
            $filename       = 'ticket-add.php';
            break;
        case 'archive':
            $filename       = 'ticket-list.php';
            break;
        case 'single':
        default :
            $filename       = 'ticket-single.php';
            break;
            
    }
    
    return pati_locate_template($filename);
}

/**
 * Execute the File and return files output as a string
 * 
 * 
 * @param string $filename
 * @return string
 */
function pati_load_file($filename){
    ob_start();
    include $filename;
    return ob_get_clean();
}

function pati_the_summary(){
    echo pati_load_file(pati_get_template_files('summary'));
}

function pati_ticket_update_form($ticket_id = 0){
    
    if($ticket_id == 0)
        $ticket_id = pati_get_the_ID ();
    /**
     * 
     * @todo Setting option for the Dyanmic <i>Close<i> Staatus option
     */
    if(pati_get_the_status() =='close')
        return;
    
    echo pati_load_file(pati_get_template_files('update_ticket_form'));
    
}

function pati_the_col_heading($col,$args = array()){
    
    $default = array(
        'tag'   => 'th',
        'class' => array(),
    );
    
    $args = array_merge($default,$args);
    
    
    switch($col){
        case 'status':
            $args['id']         = 'status';
            $args['html']       = apply_filters('pati_heading',__('Status','pressapps-ticket'),'status');
            $args['class'][]    = 'sortable';
            break;
        case 'subject':
            $args['id']         = 'subject';
            $args['html']       = apply_filters('pati_heading',__('Subject','pressapps-ticket'),'subject');
            $args['class'][]    = 'sortable';
            break;
        case 'ticket_id':
            $args['id']     = 'ticket_id';
            $args['html']   = apply_filters('pati_heading',__('Ticket Id','pressapps-ticket'),'ticket_id');
            break;
        case 'priority':
            $args['id']     = 'priority';
            $args['html']   = apply_filters('pati_heading',__('Priority','pressapps-ticket'),'priority');
            break;
        case 'priority':
            $args['id']     = 'priority';
            $args['html']   = apply_filters('pati_heading',__('Priority','pressapps-ticket'),'priority');
            break;
    }
    
    echo "<{$args['tag']} id=\"{$args['id']}\" class=\"\" >";
    echo $args['html'];
    echo "</{$args['tag']}>\n\r";
}