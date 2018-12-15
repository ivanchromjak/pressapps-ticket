<?php

/**
 * @author PressApps
 * @version 1.0
 * @package PATI/templates
 */

do_action( 'pati_before_main_content' );



  global $palm_success, $palm_error;
  if (  $palm_error ) { echo '<p class="alert alert-danger">' .  $palm_error. '</p>'; }
  elseif (  $palm_success ) { echo '<p class="alert alert-success">' .  $palm_success. '</p>'; }


  global $current_user;
  $current_user  = wp_get_current_user();

  $user_id    =$current_user->ID;    
  $user_login    =$current_user->user_login;
  $user_firstname=$current_user->user_firstname;
  $user_lastname =$current_user->user_lastname;
  $user_title    =get_the_author_meta('user_title',$current_user->ID);
  $user_nickname =get_the_author_meta('nickname',$current_user->ID);
  $user_email    =get_the_author_meta('user_email',$current_user->ID);
  //$user_url      =get_the_author_meta('user_url',$current_user->ID);
  $user_description=get_the_author_meta('description',$current_user->ID); 




?>
<div class="pati-form pati-form-profile">

    <?php //pati_validation_message('add_ticket'); ?>

    <form method="post" action="<?php the_permalink(); ?>" id="frontend_user_form" class="form-horizontal">
        <!--
        <div class="pati-row">
            <?php //echo get_avatar( get_current_user_id(), 64 ); ?>
        </div>
        -->
        <div class="pati-col-2">
            <label for="user_firstname"><?php _e('First Name', 'lms-press' ); ?></label>
            <input type="text" id="user_firstname" name="user_firstname" value="<?php echo esc_attr($user_firstname);  ?>">
        </div>
        <div class="pati-col-2">
            <label for="user_lastname"><?php _e('Last Name', 'lms-press' ); ?></label>
            <input type="text" id="user_lastname" name="user_lastname" value="<?php echo esc_attr($user_lastname);  ?>">
        </div>
        <div class="pati-col-2">
            <label for="user_title"><?php _e('Title', 'lms-press' ); ?></label>
            <input type="text" id="user_title" name="user_title" value="<?php echo esc_attr($user_title);  ?>">
        </div>
        <div class="pati-col-2">
            <label for="nickname"><?php _e('Nickname', 'lms-press' ); ?></label>
            <input type="text" id="nickname" name="nickname" value="<?php echo esc_attr($user_nickname); ?>">
        </div>
        <div class="pati-col-2">
            <label for="display_name"><?php _e('Display Name', 'lms-press' ); ?></label>
            <select id="display_name" name="display_name">
            <?php
                $public_display = array();
                $public_display['display_nickname']  = $current_user->nickname;
                $public_display['display_username']  = $current_user->user_login;
                if ( !empty($current_user->first_name) )
                    $public_display['display_firstname'] = $current_user->first_name;
                if ( !empty($current_user->last_name) )
                    $public_display['display_lastname'] = $current_user->last_name;
                if ( !empty($current_user->first_name) && !empty($current_user->last_name) ) {
                    $public_display['display_firstlast'] = $current_user->first_name . ' ' . $current_user->last_name;
                    $public_display['display_lastfirst'] = $current_user->last_name . ' ' . $current_user->first_name;
                }
                if ( !in_array( $current_user->display_name, $public_display ) )// Only add this if it isn't duplicated elsewhere
                    $public_display = array( 'display_displayname' => $current_user->display_name ) + $public_display;
                $public_display = array_map( 'trim', $public_display );
                foreach ( $public_display as $id => $item ) {
            ?>
                <option id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($item); ?>"<?php selected( $current_user->display_name, $item ); ?>><?php echo esc_attr($item); ?></option>
            <?php
                }
            ?>
            </select>               
        </div>
        <div class="pati-col-2">
            <label for="user_email"><?php _e('Email', 'lms-press' ); ?></label>
            <input type="email" id="user_email" name="user_email" value="<?php echo esc_attr($user_email);  ?>">
        </div>
        <!--
        <div class="pati-col-2">
            <label for="user_url"><?php _e('Website', 'lms-press' ); ?></label>
            <input type="url" id="user_url" name="user_url" value="<?php echo esc_url($user_url);  ?>">
        </div>
        -->
        <div class="pati-col-2">
            <label for="new_password"><?php _e('Password', 'lms-press' ); ?></label>
            <input type="password" id="new_password" name="new_password" value="" autocomplete="off">
        </div>
        <div class="pati-col-2">
            <label for="confirm_password"><?php _e('Confirm Password', 'lms-press' ); ?></label>
            <input type="password" id="confirm_password" name="confirm_password" value="" autocomplete="off">
        </div>
        <div class="pati-col-1">
            <label for="user_description"><?php _e('Biographical Info', 'lms-press' ); ?></label>
            <textarea rows="5" id="user_description" name="user_description"><?php echo esc_textarea($user_description);?></textarea>
        </div>
        <div class="pati-col-1">
            <?php do_action('edit_user_profile',$current_user); ?>
            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
        </div>
        <div class="pati-col-1">
            <input class="pati-btn" type="submit" value="<?php _e('Update','pressapps-ticket'); ?>" />
            <?php wp_nonce_field( 'update-user' ) ?>
            <input name="action" type="hidden" id="action" value="update-user" />
        </div>
    </form>
</div>

<?php 
do_action( 'pati_after_main_content' ); 

do_action( 'pati_sidebar' ); 