<?php

/**
 * @author PressApps
 * @version 1.0
 * @package PATI/templates
 */

global $patis;

if ( current_user_can('assign_ticket') ) { 
    $form_fields = $patis->get('form_fields_agent');
} else {
    $form_fields = $patis->get('form_fields_user');
}

do_action( 'pati_before_main_content' );

?>
<div class="pati-form pati-form-add">
    <?php 

    pati_validation_message('add_ticket'); 
    //pati_success_message('add_ticket'); 
    ?>
    <?php
        pati_input_form_start('add-ticket');
    ?>
        <div class="pati-col-1">
            <label><?php _e('Title','pressapps-ticket'); ?></label>
            <?php pati_input_title(); ?>
        </div>

        <?php if ( !empty($form_fields) && in_array( 'category', $form_fields ) ) { ?>
            <div class="pati-col-2">
                <label><?php _e('Category','pressapps-ticket'); ?></label>
                <?php pati_input_category(); ?>
            </div>
        <?php } ?>

        <?php if ( !empty($form_fields) && in_array( 'type', $form_fields ) ) { ?>
            <div class="pati-col-2">
                <label><?php _e('Type','pressapps-ticket'); ?></label>
                <?php pati_input_type();  ?>
            </div>
        <?php } ?>

        <?php if ( !empty($form_fields) && in_array( 'status', $form_fields ) ) { ?>
            <div class="pati-col-2">
                <label><?php _e('Status','pressapps-ticket'); ?></label>
                <?php pati_input_status(); ?>
            </div>
        <?php } elseif ( !empty($patis->get('status_open') ) ) { ?>
            <div class="pati-col-2">
                <input type="hidden" name="status" value="<?php echo $patis->get('status_open'); ?>">
            </div>
        <?php } ?>

        <?php if ( !empty($form_fields) && in_array( 'priority', $form_fields ) ) { ?>
            <div class="pati-col-2">
                <label><?php _e('Priority','pressapps-ticket'); ?></label>
                <?php pati_input_priority(); ?>
            </div>
        <?php } ?>

        <?php if ( !empty($form_fields) && in_array( 'assigned', $form_fields ) ) { ?>
            <?php if(current_user_can('assign_ticket')){
                ?>
                <div class="pati-col-2">
                    <label><?php _e('Assigned','pressapps-ticket'); ?></label>
                    <?php pati_input_user_group(); ?>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ( !empty($form_fields) && in_array( 'tags', $form_fields ) ) { ?>
            <div class="pati-col-2">
                <label><?php _e('Tags','pressapps-ticket'); ?></label>
                <?php pati_input_tag(); ?>
            </div>
        <?php } ?>

        <div class="pati-col-1 pati-commnent-field">
            <label><?php _e('Comments','pressapps-ticket'); ?></label>
            <?php pati_input_content(); ?>
        </div>

        <?php if ( !empty($form_fields) && in_array( 'upload', $form_fields ) ) { ?>
            <div class="pati-col-1">
                <label><?php _e('Attachments','pressapps-ticket'); ?></label>
                <?php pati_attach_files(); ?>
            </div>
        <?php } ?>

        <div class="pati-col-1">
            <input class="pati-btn" type="submit" value="<?php _e('Submit ticket','pressapps-ticket'); ?>" name="submit_ticket" />
        </div>
    <?php
        pati_input_form_end();
    ?>
</div>

<?php 
    do_action( 'pati_after_main_content' ); 


    do_action( 'pati_sidebar' ); 