<?php

add_action('personal_options'  ,'pressapps_personal_options');


function pressapps_personal_options($user){
    $groups         = get_terms('user_group',array('hide_empty'=>FALSE));
    $user_groups    = get_user_meta($user->ID,'pati_user_group');
    
    if(!is_array($groups) || !user_can($user,'assign_ticket')){
        return ;
    }
    
    ?>
    <tr>
        <th scope="row"><?php _e('User Group','pressapps-ticket'); ?></th>
        <td>
            <?php 
            foreach($groups as $group){
                ?>
                <input type="checkbox" name="user_group[]" 
                       <?php echo (in_array($group->term_id,$user_groups))?'checked="checked"':''; ?>
                       value="<?php echo $group->term_id; ?>" id="user_group_<?php echo $group->term_id; ?>" />
                <label for="user_group_<?php echo $group->term_id; ?>"><?php echo $group->name; ?></label>
                &nbsp;&nbsp;
                <?php
            }
            ?>
        </td>
    </tr>
    <?php
}

add_action( 'profile_update', 'pati_profile_update', 10); 

function pati_profile_update($user_id){
    
    if(user_can($user_id,'assign_ticket')){
        /**
         * Save the User Group Information
         */
        delete_user_meta($user_id, 'pati_user_group');
        if(isset($_REQUEST['user_group']) && is_array($_REQUEST['user_group'])){
            foreach($_REQUEST['user_group'] as $id){
                add_user_meta($user_id, 'pati_user_group', $id);
            } 
        }
        /**
         * Notification List for the New Ticket Notification User
         */
        $notification_list['add']      = get_option('pati_new_ticket_notification_users',array());
        
        if(isset($_REQUEST['new_ticket_notification'])){
            $notification_list['add'][]    = $user_id;
            update_user_meta($user_id  ,'new_ticket_notification'       ,TRUE);
        }else{
            $key = array_search($user_id,$notification_list['add']);
            if( $key !== FALSE )
                unset ($notification_list['add'][$key]);
            delete_user_meta($user_id  ,'new_ticket_notification');
        }
        update_option('pati_new_ticket_notification_users', array_values(array_unique($notification_list['add'])));
        
        /**
         * Notification List for the Ticket Update
         */
        $notification_list['update']   = get_option('pati_update_ticket_notification',array());
        
        if(isset($_REQUEST['update_ticket_notification'])){
            update_user_meta($user_id         ,'update_ticket_notification'       ,$_REQUEST['update_ticket_notification']);
            if($_REQUEST['update_ticket_notification'] == 'all'){
                if(!in_array($user_id,$notification_list['update']))
                    $notification_list['update'][]    = $user_id;
            }elseif(in_array($user_id,$notification_list['update'])) {
                unset($notification_list['update'][array_search($user_id,$notification_list['update'])]);
            }
        }
        
        update_option('pati_update_ticket_notification', array_values(array_unique($notification_list['update'])));
        
    }elseif(user_can($user_id,'edit_ticket')){
        if(isset($_REQUEST['update_ticket_notification']))
            update_user_meta($user_id   ,'update_ticket_notification'   ,'TRUE');
        else
            update_user_meta($user_id   ,'update_ticket_notification'   ,'FALSE');
    }
        
    
    
}

function pati_restrict_manage_posts(){
    global $typenow;
    if($typenow != 'ticket'){
        return;
    }
    
    ?>
    <select name="ticket_category">
        <option value="0"><?php _e('Select Category','pressapps-ticket'); ?></option>
    <?php
    $status = get_terms('ticket_category',array('hide_empty'=>FALSE));
    if(count($status)>0){
        foreach(pressapps_sort_terms_by_description($status) as $cat){
            if(
                    ((isset($_GET['ticket_category']))?$_GET['ticket_category']==$cat->slug:FALSE)
               ){
                echo "<option value={$cat->slug} selected=\"selected\">{$cat->name}</option>";
            }else{
                echo "<option value={$cat->slug} >{$cat->name}</option>";
            }
        }
    }
    ?>
    </select>
    <select name="ticket_status">
        <option value="0"><?php _e('Select Status','pressapps-ticket'); ?></option>
    <?php
    $status = get_terms('ticket_status',array('hide_empty'=>FALSE));
    if(count($status)>0){
        foreach(pressapps_sort_terms_by_description($status) as $cat){
            if(
                    ((isset($_GET['ticket_status']))?$_GET['ticket_status']==$cat->slug:FALSE)
               ){
                echo "<option value={$cat->slug} selected=\"selected\">{$cat->name}</option>";
            }else{
                echo "<option value={$cat->slug} >{$cat->name}</option>";
            }
        }
    }
    ?>
    </select>
    <select name="ticket_priority">
        <option value="0"><?php _e('Select Priority','pressapps-ticket'); ?></option>
    <?php
    $ticket_priority = get_terms('ticket_priority',array('hide_empty'=>FALSE));
    if(count($ticket_priority)>0){
        foreach(pressapps_sort_terms_by_description($ticket_priority) as $cat){
            if(
                ((isset($_GET['ticket_priority']))?$_GET['ticket_priority']==$cat->slug:FALSE)
              ){
                echo "<option value={$cat->slug} selected=\"selected\">{$cat->name}</option>";
            }else{
                echo "<option value={$cat->slug} >{$cat->name}</option>";
            }
        }
    }
    ?>
    </select>
    <?php
    
}

add_action('restrict_manage_posts','pati_restrict_manage_posts');

function pati_post_submitbox_misc_actions(){
    global $post,$typenow;
    
    if($typenow != 'ticket'){
        return;
    }
    
    $user_group = pati_get_usergrouplist();
    $post_category          = wp_get_object_terms($post->ID ,'ticket_category');
    $post_status            = wp_get_object_terms($post->ID ,'ticket_status');
    $post_priority          = wp_get_object_terms($post->ID ,'ticket_priority');
    $post_type              = wp_get_object_terms($post->ID ,'ticket_type');
    $post_assigned_user     = get_post_meta($post->ID, 'pati_assigned_user', TRUE);

    ?>
    <div class="misc-pub-section">
        <label for="" class="ticket_label"><?php _e('Category' ,'pressapps-ticket') ?></label>
        <?php
            $category = (empty($post_category))?'0':$post_category[0]->term_id;
            pati_input_category(array(
                'name'      => 'pati_category',
                'value'     => $category,
            ));
        ?>
    </div>
    <div class="misc-pub-section">
        <label for="" class="ticket_label"><?php _e('Status' ,'pressapps-ticket') ?></label>
        <?php
            $status = (empty($post_status))?'0':$post_status[0]->term_id;
            pati_input_status(array(
                'name'      => 'pati_status',
                'value'     => $status,
            ));
        ?>
    </div>
    <div class="misc-pub-section">
        <label for="" class="ticket_label"><?php _e('Priority' ,'pressapps-ticket') ?></label>
        <?php
            $priority = (empty($post_priority))?'0':$post_priority[0]->term_id;
            pati_input_priority(array(
                'name'      => 'pati_priority',
                'value'     => $priority,
            ));
        ?>
    </div>
    <div class="misc-pub-section">
        <label for="" class="ticket_label"><?php _e('Type' ,'pressapps-ticket') ?></label>
        <?php
            $type = (empty($post_type))?'0':$post_type[0]->term_id;
            pati_input_type(array(
                'name'      => 'pati_type',
                'value'     => $type,
            ));
        ?>
    </div>
    <div class="misc-pub-section">
        <label for="assigned_user" class="ticket_label"><?php _e('Assigned' ,'pressapps-ticket') ?></label>
        <select name="assigned_user" id="assigned_user">
            <option value="-1"><?php _e('Select User','pressapps-ticket'); ?></option>
            <?php
            if(!is_null($user_group)) {

                foreach($user_group as $group){
                    ?>
                    <optgroup label="<?php echo $group['name']; ?>">
                        <?php 
                        foreach($group['users'] as $user){
                            $val = $group['slug'] . ':' . $user->ID;
                            ?>
                            <option 
                                <?php echo ($val==$post_assigned_user)?'selected="selected"':''; ?>
                                value="<?php echo $val; ?>">
                                <?php echo $user->display_name; ?>
                            </option>
                            <?php 
                        }
                        ?>
                    </optgroup>
                    <?php
                }
            }
            ?>
        </select>
    </div>
    <?php
}

add_action('post_submitbox_misc_actions','pati_post_submitbox_misc_actions');

add_action('show_user_profile'  ,'pati_user_email_notification');
add_action('edit_user_profile'  ,'pati_user_email_notification');

function pati_user_email_notification($user){
    $notification_type = array(
        'all'   => __('All tickets' ,'pressapps-ticket'),
        'own'   => __('Assigned tickets' ,'pressapps-ticket'),
        'none'  => __('None' ,'pressapps-ticket'),
    );
    ?>
    <h3><?php _E('Ticket Email Notification'); ?></h3>
    <?php 
    if(user_can($user->ID,'assign_ticket')){
        ?>
        <label for="new_ticket_notification">
            <?php _e('Send me email on new ticket submission: ','pressapps-ticket')?>&nbsp;
            <input name="new_ticket_notification" type="checkbox" id="new_ticket_notification" value="yes" <?php echo ((get_user_meta($user->ID,'new_ticket_notification',TRUE)==TRUE)?'checked="checked"':''); ?> />
        </label>
        <br/><br/>
        <?php _e('Send me email on ticket update: '   ,'pressapps-ticket')?>&nbsp;
        <?php
        foreach($notification_type as $key=>$value) {
            ?>
            <label for="update_ticket_notification_<?php echo $key; ?>">
                <input type="radio" name="update_ticket_notification" 
                       <?php echo (($key == $user->update_ticket_notification)?'checked="checked"':''); ?>
                       id="update_ticket_notification_<?php echo $key; ?>" 
                       value="<?php echo $key; ?>" />
                <?php echo $value; ?>
            </label>&nbsp;&nbsp;
            <?php
        }
        ?>
        <?php 
    }elseif(user_can($user->ID,'edit_ticket')){
        ?>
        <label for="update_ticket_notification">
            <?php _e('Send me email on ticket update ','pressapps-ticket')?>&nbsp;
            <input name="update_ticket_notification" type="checkbox" id="update_ticket_notification" value="yes" <?php echo (($user->update_ticket_notification == 'TRUE')?'checked="checked"':''); ?>   />
        </label>
        <?php
    }
    
}
