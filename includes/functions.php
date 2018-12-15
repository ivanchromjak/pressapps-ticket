<?php

function pati_get_usergrouplist(){
    
    global $wpdb;

    $groups = get_terms('user_group',array('hide_empty'=>FALSE));
    
    $qry['A']  = " SELECT * FROM {$wpdb->usermeta} WHERE ";
    $qry['A'] .= " meta_key='pati_user_group' ";
    
    $result['A'] = $wpdb->get_results($qry['A'],ARRAY_A);
    
    
    
    if(count($result['A'])>0 && count($groups)>0){
        
        foreach($result['A'] as $row){
            $user_ids[]                         = $row['user_id'];
            $group_user[$row['meta_value']][]   = $row['user_id'];
        }
        
        $qry['B']  = " SELECT ID,user_login,display_name FROM {$wpdb->users} ";
        $qry['B'] .= " WHERE ID IN (" . implode(",", $user_ids) . ") ";
        
        $result['B'] = $wpdb->get_results($qry['B'],OBJECT_K);
        foreach($groups as $group){
            $group = get_object_vars( $group );
            if(!isset($group_user[$group['term_id']])){
                $group['users'] = array();
                $result['C'][$group['term_id']] = $group;
            }else{
                foreach($group_user[$group['term_id']] as $user_id){
                    $group['users'][] = $result['B'][$user_id] ;
                }
                $result['C'][$group['term_id']] = $group;
            }
        }
        
        return $result['C'];
        
    }
    
    return NULL;
}

function pressapps_sort_terms_by_description($terms){
    
    usort($terms,'pati_cmp_terms_order');
    
    return $terms;
    
}

function pati_cmp_terms_order($a, $b){
    
    
    if ($a->description == $b->description) {
        return 0;
    }
    
    return ($a->description < $b->description) ? -1 : 1;
}








function pati_get_dummy_post_data($args){
    
    return array_merge(array(
        'ID'                    => 0,
        'post_status'           => 'publish',
        'post_author'           => 0,
        'post_parent'           => 0,
        'post_type'             => 'page',
        'post_date'             => 0,
        'post_date_gmt'         => 0,
        'post_modified'         => 0,
        'post_modified_gmt'     => 0,
        'post_content'          => '',
        'post_title'            => '',
        'post_excerpt'          => '',
        'post_content_filtered' => '',
        'post_mime_type'        => '',
        'post_password'         => '',
        'post_name'             => '',
        'guid'                  => '',
        'menu_order'            => 0,
        'pinged'                => '',
        'to_ping'               => '',
        'ping_status'           => '',
        'comment_status'        => 'closed',
        'comment_count'         => 0,
        'filter'                => 'raw',

        'is_404'                => false,
        'is_page'               => true,
        'is_single'             => false,
        'is_archive'            => false,
        'is_tax'                => false,

    ),$args);
}


function pati_get_status_code($taxonomy){
    $term_id = (is_object($taxonomy))?$term_id = $taxonomy->term_id:$taxonomy;
    
    $data = get_option("ticket_status_{$term_id}");
    
    return (is_array($data))?$data['catBG']:'';
}



function pati_get_currentuserinfo(){
    global $current_user;
    get_currentuserinfo();
    
    return $current_user;
}

function pati_get_currentuserid(){
    global $current_user;
    get_currentuserinfo();
    
    return $current_user->ID;
}

function  pati_check_user_role( $role, $user_id = null ) {
 
    if ( is_numeric( $user_id ) )
	$user = get_userdata( $user_id );
    else
        $user = wp_get_current_user();
 
    if ( empty( $user ) )
	return false;
 
    return in_array( $role, (array) $user->roles );
}

/**
 * Get all the Attached Files to the Post
 * 
 * @param int $post_id
 * @return Array|NULL
 */
function pati_get_post_attachments($post_id){
    
    $files = get_children(array(
        'post_parent' => $post_id,
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

function pati_current_user_can($capability,$args = array()){
    current_user_can($capability);
}

function pati_get_ticket_statistics($args = array()){
    global $wpdb;
    
    $default = array(
        'case'  => 'ticket_status'
    );
    
    
    $args = array_merge($default,$args);
    
    $qry  = " SELECT A.slug,A.term_id,A.name,count(D.ID) as total ";
    $qry .= " FROM wp_terms A,wp_term_taxonomy B,wp_term_relationships C,wp_posts D ";
    $qry .= " WHERE B.term_id = A.term_id AND B.taxonomy = '{$args['case']}' "; 
    $qry .= " AND D.ID = C.object_id AND C.term_taxonomy_id = B.term_taxonomy_id ";
    
    if(isset($args['user_id'])){
        $qry .= " AND D.post_author = {$args['user_id']} ";
    }
    
    $qry .= " GROUP BY A.name ";
    
    $result = $wpdb->get_results($qry,OBJECT_K);
    
    return $result;
    
}


function pati_get_screen_ids(){
    
    $screens = array(
        'edit-ticket',
        'ticket',
        'edit-ticket_category',
        'edit-ticket_priority',        
        'edit-ticket_status',
        'edit-ticket_type',
        'edit-ticket_tags',
        'edit-user_group',
        'settings_page_pati_settings',
    );
    
    return apply_filters('pati_screen_ids',$screens);
}

function pati_is_template_redirect(){
    return ((PATI_Template_Loader()->current_rendering_hook=='template_redirect')?TRUE:FALSE);
}

function pati_unauth_redirect_url(){
    global $patis;
    $case = $patis->get('nonlogein_redirect');
    $url  = NULL;
    switch($case){
        default:
        case 'home_page':
            $url = esc_url(home_url());
            break;
        case 'login_page':
            $url = wp_login_url();
            break;
        case 'custom_url':
            $url = $patis->get('nonlogein_redirect_url');
            break;
    }
    
    return $url;
}

function pati_update_profile() {
    if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user'){
        global $data;
        if(is_user_logged_in()){

            $current_user = wp_get_current_user();
            $user_id      = $current_user->ID;
            if($_POST['user_id']==$user_id){
                $update_first   = update_user_meta($user_id, 'first_name', $_POST['user_firstname'] );
                $update_last    = update_user_meta($user_id, 'last_name',  $_POST['user_lastname']  );
                if ( !empty( $_POST['nickname'] ) )
                    $update_nickname = update_user_meta( $user_id, 'nickname', esc_attr( $_POST['nickname'] ) );

                $update_displayname     = wp_update_user( array( 'ID' => $user_id, 'display_name' => esc_attr( $_POST['display_name'] ) ) );
                global $current_user;
                unset($current_user); // unset current user data for updated data
                $current_user  = wp_get_current_user();

                if ( !empty( $_POST['user_email'] ) )
                    $update_email   = wp_update_user( array ('ID' => $user_id,'user_email'=>$_POST['user_email'] ) );
                //$update_url     = wp_update_user( array ('ID' => $user_id,'user_url'=>$_POST['user_url'] ) );
                $update_desc    = wp_update_user( array ('ID' => $user_id,'description'=>$_POST['user_description'] ) );

                /* Update user password. */
                if ( !empty($_POST['new_password'] ) && !empty( $_POST['confirm_password'] ) ) {
                    if ( $_POST['new_password'] == $_POST['confirm_password'] )
                        $update_password= empty($_POST['new_password'])?true:wp_update_user( array ('ID' => $user_id,'user_pass'=>$_POST['new_password'] ) );
                    else
                        $_error = __('The passwords you entered do not match.  Your password was not updated.', 'pressapps-ticket' );
                }

                $update_notification  = wp_update_user( array ('ID' => $user_id,'notification'=> esc_attr(@$_POST['notification'] ) ) );
                update_user_meta( $user_id, 'notification', @$_POST['notification'] );

                if($update_first||$update_last||@$update_email||$update_url||$update_aim||$update_yim||$update_jabber||$update_desc||$update_fb_url||$update_tw_url||$update_password){
                    //  _e('Profile updated', 'pressapps-ticket' );
                }else{
                    _e('Profile not updated', 'pressapps-ticket' );
                }
            }else{
                _e('Not your profile. Refresh this page.', 'pressapps-ticket' );
            }
        }else{
            _e('Not loged in', 'pressapps-ticket' );
        }
        //   die;
    }
}
//add_action('init', 'pati_update_profile');




