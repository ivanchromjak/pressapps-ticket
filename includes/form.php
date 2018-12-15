<?php
/**
 * @package PressApps::Ticket
 * @subpackage Forms
 */

function pati_input_form_start($case = 'add-ticket'){
    
    $temp = '<form action="" name="pati-form" method="post" enctype="multipart/form-data">';
    switch($case){
        case 'add-ticket':
            $temp .= '<input type="hidden" name="pati_front_do_action" value="add-ticket" />';
            break;
        case 'update-ticket':
        default:
            $temp .= '<input type="hidden" name="pati_front_do_action" value="update-ticket" />';
            $temp .= '<input type="hidden" name="ticket_id" value="' . pati_get_the_ID() . '" />';
            break;
    }
    
    echo $temp;
}

function pati_input_form_end(){
    do_action('pati_list_form_end');
    echo '</form>';
}

function pati_list_form_start(){
    
    $temp  = '<form action="" method="post">';
    $temp .= '<input type="hidden" name="order"     value="" />';
    $temp .= '<input type="hidden" name="orderby"   value="" />';

    echo $temp;
    do_action('pati_list_form_start');
}

function pati_list_form_end(){
    echo '</form>';
}

/**
 * 
 * @param type $args
 */

function pati_input_title($args = array()){
    
    $title = isset($_REQUEST['title'])?$_REQUEST['title']:'';
    
    echo '<input type="text" id="title" name="title" value="' . $title . '" />';
}

function pati_input_content($args = array()){
    
    $content = isset($_REQUEST['content'])?$_REQUEST['content']:'';
    
    echo '<textarea name="content" id="content" rows="7" cols="75">' . trim($content) . '</textarea>';
}

function pati_input_update($args = array()){
    
    $update = isset($_REQUEST['update'])?$_REQUEST['update']:'';
    
    echo '<textarea name="update" id="update" rows="7" cols="75">' . trim($update) . '</textarea>';
}

function pati_input_tag($args  = array()){
    
    $default = array(
        'name'          => 'tags',
        'id'            => 'tags',
        'classname'     => 'ticket_tags',
        'value'         => -1,
    );
    
    $args = array_merge($default,$args);
    
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = pati_get_the_tags();   
        }else{
            $args['value'] = isset($_REQUEST['tags'])?$_REQUEST['tags']:''; 
        }
    }
    
    
    echo '<input type="text" name="' . $args['name'] . '" class="' . $args['classname'] . '" id="tags" value="' . $args['value'] . '" />';
}

function pati_input_type($args = array()){
    global $pati_data;
    
    $default = array(
        'name'          => 'type',
        'id'            => 'type',
        'value'         => -1,        
        'placeholder'   => __('Select type','pressapps-ticket'),
    );
    
    $args = array_merge($default,$args);
    
    if(!isset($pati_data['type']))
        $pati_data['type'] = pressapps_sort_terms_by_description((array)get_terms('ticket_type',array('hide_empty'=>FALSE)));
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = pati_get_the_type();   
        }else{
            $args['value'] = isset($_REQUEST['type'])?$_REQUEST['type']:'';   
        }
    }
    ?>
    <select name="<?php echo $args['name']; ?>" id="<?php echo $args['id']; ?>">    
        <?php
        if(is_array($pati_data['type'])){
            foreach($pati_data['type'] as $obj){
                if($args['value'] == $obj->term_id)
                    echo '<option selected="selected" value="' . $obj->term_id . '">' . $obj->name  . '</option>';
                else
                    echo '<option value="' . $obj->term_id . '">' . $obj->name  . '</option>';
            }
        }
        ?>
    </select>
    <?php
}

function pati_input_category($args = array()){
    global $pati_data;
    
    $default = array(
        'name'          => 'category',
        'id'            => 'category',
        'value'         => -1,
        'placeholder'   => __('Select category','pressapps-ticket'),
    );
    
    $args = array_merge($default,$args);
    
    if(!isset($pati_data['category']))
        $pati_data['category'] =  pressapps_sort_terms_by_description((array)get_terms('ticket_category'     ,array('hide_empty'=>FALSE)));  
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = pati_get_the_category();   
        }else{
            $args['value'] = isset($_REQUEST['category'])?$_REQUEST['category']:''; 
        }
    }
    
    ?>
    <select name="<?php echo $args['name'] ?>" id="<?php echo $args['id'] ?>">
        <option value=""><?php echo $args['placeholder']; ?></option>
        <?php
        if(is_array($pati_data['category'])){
            foreach($pati_data['category'] as $obj){
                if($args['value'] == $obj->term_id)
                    echo '<option selected="selected" value="' . $obj->term_id . '">' . $obj->name  . '</option>';
                else
                    echo '<option value="' . $obj->term_id . '">' . $obj->name  . '</option>';
            }
        }
        ?>
    </select>
    <?php
}

function pati_input_status($args = array()){
    global $pati_data;
    
    $default = array(
        'name'          => 'status',
        'id'            => 'status',
        'value'         => -1,        
    );
    
    $args = array_merge($default,$args);
    
    if(!isset($pati_data['status']))
        $pati_data['status'] =  pressapps_sort_terms_by_description((array)get_terms('ticket_status'     ,array('hide_empty'=>FALSE)));  
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = pati_get_the_status();   
        }else{
            $args['value'] = isset($_REQUEST['status'])?$_REQUEST['status']:''; 
        }
    }
    
    ?>
    <select name="<?php echo $args['name'] ?>" id="<?php echo $args['id'] ?>">        
        <?php
        if(is_array($pati_data['status'])){
            foreach($pati_data['status'] as $obj){
                if($args['value'] == $obj->term_id)
                    echo '<option selected="selected" value="' . $obj->term_id . '">' . $obj->name  . '</option>';
                else
                    echo '<option value="' . $obj->term_id . '">' . $obj->name  . '</option>';
            }
        }
        ?>
    </select>
    <?php
}

function pati_input_priority($args = array()){
    global $pati_data;
    
    $default = array(
        'name'          => 'priority',
        'id'            => 'priority',
        'value'         => -1,
    );
    
    $args = array_merge($default,$args);
    
    if(!isset($pati_data['priority']))
        $pati_data['priority']  =  pressapps_sort_terms_by_description((array)get_terms('ticket_priority'   ,array('hide_empty'=>FALSE)));  
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = pati_get_the_priority();   
        }else{
            $args['value'] = isset($_REQUEST['priority'])?$_REQUEST['priority']:''; 
        }
    }
    
    ?>
    <select name="<?php echo $args['name']; ?>" id="<?php echo $args['id']; ?>">        
        <?php
        if(is_array($pati_data['priority'])){
            foreach($pati_data['priority'] as $obj){
                if($args['value'] == $obj->term_id)
                    echo '<option selected="selected" value="' . $obj->term_id . '">' . $obj->name  . '</option>';
                else
                    echo '<option value="' . $obj->term_id . '">' . $obj->name  . '</option>';
            }
        }
        ?>
    </select>
    <?php
}

function pati_input_user_group($args = array()){
    global $pati_data;
    
    $default = array(
        'name'          => 'assigned_user',
        'id'            => 'assigned_user',
        'value'         => -1,
        'placeholder'   => __('Select User','pressapps-ticket'),
    );
    
    $args = array_merge($default,$args);
    
    if(!isset($pati_data['user_group']))
        $pati_data['user_group'] = pati_get_usergrouplist();
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = pati_get_the_assigned(0,array('output'=>'key'));   
        }else{
            $args['value'] = isset($_REQUEST[$args['name']])?$_REQUEST[$args['name']]:''; 
        }
    }
    ?>
    <select name="<?php echo $args['name']; ?>" id="<?php echo $args['id']; ?>">
        <option value="-1"><?php echo $args['placeholder']; ?></option>
        <?php
        if(!is_null($pati_data['user_group'])) {

            //print_r( $pati_data['user_group'] );

            foreach($pati_data['user_group'] as $group){
                //print_r( $group['users'] );
                if(count($group['users'])==0)
                    continue;
                ?>
                <optgroup label="<?php echo $group['name']; ?>">
                    <?php 
                    foreach($group['users'] as $user){
                        $val = $group['slug'] . ':' . $user->ID;
                        ?><option <?php echo (($val==$args['value'])?'selected="selected"':''); ?> value="<?php echo $val; ?>"><?php echo $user->display_name; ?></option>
                        <?php 
                    }
                    ?>
                </optgroup>
                <?php
            }
        }
        ?>
    </select>
    <?php
    
}

/**
 * @todo this is currently a temp. fix which might be completed in the 
 * future release so for a while no user define Visibility of the ticket 
 * is allowed and all the ticket will be private for the version 1.0
 * @return boolean
 */
function pati_is_visibility_custom(){
    return FALSE;
    //return (get_option('pati_ticket_visibility') == 'userdefine')?TRUE:FALSE;
}

function pati_input_visibility($args = array()){
    
    $default = array(
        'name'          => 'ticket_visibility',
        'id'            => 'ticket_visibility',
        'value'         => -1,
        'placeholder'   => __('Select Status','pressapps-ticket'),
    );
    
    $args = array_merge($default,$args);
    
    if($args['value'] == -1 ){
        if(pati_is_single()){
            $args['value'] = get_post_meta(get_the_ID(),'pati_ticket_visibility',TRUE);   
        }else{
            $args['value'] = isset($_REQUEST[$args['name']])?$_REQUEST[$args['name']]:get_option('pati_ticket_visibility','private'); 
        }
    }
    
    $ticket_visibility  = PATI()->ticket_visibility;
    unset($ticket_visibility['userdefine']);
    foreach($ticket_visibility as $key=>$visibility) {
        ?>
        <input type="radio" name="<?php echo $args['name']; ?>" value="<?php echo $key; ?>" 
               <?php echo ($key == $args['value'])?'checked="checked"':''; ?>
               id="ticket-<?php echo $key?>" />
        <label for="ticket-<?php echo $key?>"><?php echo $visibility; ?></label>    
        <?php
    }
}

function pati_attach_files(){
   ?>
    <div class="files_ct">
        <div id="file_ct">
            <div id="file_container" class="dz-message"></div>
        </div>
    </div>
    <script type="text/javascript">
        var test,filesobjects; 
        filesobjects = Array();
        test = new Dropzone("div#file_ct", { 
            url                 : '<?php echo admin_url() . 'admin-ajax.php?action=pati_upload_file'; ?>', 
            addRemoveLinks      : true,
            clickable           : true,
            previewsContainer   : 'div#file_container',
            success             : function(file, response){
                
                file.serverId   = response;
                input           = '<input type="hidden" name="attachments[]" value="' + response + '" />';
                jQuery(file.previewElement).append(input);
            }
        });
        test.on("removedfile",function(file){
            jQuery.ajax({
                url         : '<?php echo admin_url() . 'admin-ajax.php'; ?>',
                data        : {
                    action  : 'pati_delete_file',
                    file_id : file.serverId
                },
                success     : function(data){
                    
                },
                complete    : function(){
                    
                }
            })
        });
        
    </script>    
   <?php
}

function pati_success_message($form_type){
    global $ticket_id;
    
    switch($form_type){
        case 'add_ticket':
            if($ticket_id !=-1 && !is_wp_error($ticket_id)){
            ?>
            <div class="success">
                <?php 
                echo sprintf(__('You ticket has been Submitted Successfully click <a href="%s">here</a> to view the ticket','pressapps-ticket'),  get_permalink($ticket_id)); 
                ?>
            </div>
            <?php
            }
            break;
        case '':
            break;
    }
}

function pati_validation_message($form_type){
    global $error_message;
    switch($form_type){
        case 'add_ticket':
            if(count($error_message)==0)
                return;
            ?>
            <ul class="error">
            <?php
            foreach($error_message as $message){
                echo '<li>' . $message['message'] . '</li>';
            }
            ?>
            </ul>
            <?php
            
            break;
        case '':
            break;
    }
}
