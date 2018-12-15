<?php

/**
 * @package name
 * @version string
 * @author 
 */

global $patis;

if ( current_user_can('assign_ticket') ) { 
    $form_fields = $patis->get('form_fields_agent');
} else {
    $form_fields = $patis->get('form_fields_user');
}
?>
<div class="pati-form pati-form-update">
    <?php
    pati_input_form_start('update-ticket');
    ?>
    <div class="pati-row-meta">
        <p class="pati-ticket-meta">
            <a href="<?php echo get_post_type_archive_link( 'ticket' ); ?>"><?php _e('All Tickets'  ,'pressapps-ticket'); ?></a> 
        </p>   
        <p class="pati-ticket-meta">
            <?php _e('Ticket','pressapps-ticket'); ?> #<?php pati_the_ID(); ?>
        </p>
        <p class="pati-ticket-meta">
            <?php pati_the_created_time(); ?>
        </p>
        <p class="pati-ticket-meta">
            <?php pati_the_author(); ?> <<a href="mailto:<?php pati_the_author(array('output' => 'user_email')); ?>" target="_blank"><?php pati_the_author(array('output' => 'user_email')); ?></a>>
        </p>
    </div>

    <?php if ( !empty($form_fields) && in_array( 'category', $form_fields ) ) { ?>
        <div class="pati-col-2">
            <label><?php _e('Category','pressapps-ticket'); ?></label>
            <?php pati_input_category(); ?>
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

    <?php if ( !empty($form_fields) && in_array( 'type', $form_fields ) ) { ?>
        <div class="pati-col-2">
            <label><?php _e('Type','pressapps-ticket'); ?></label>
            <?php pati_input_type();  ?>
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
        <div class="pati-comment">
            <label><?php _e('Comments','pressapps-ticket'); ?></label>
            <?php pati_input_update(); ?>
        </div>
    </div>

    <?php if ( !empty($form_fields) && in_array( 'upload', $form_fields ) ) { ?>
        <div class="pati-col-1">
            <label><?php _e('Attachments','pressapps-ticket'); ?></label>
            <?php pati_attach_files(); ?>
        </div>
    <?php } ?>

    <div class="pati-col-1">
        <input class="pati-btn" type="submit" value="<?php _e('Update ticket','pressapps-ticket'); ?>" name="update_ticket" />
    </div>

<?php
    pati_input_form_end();
?>
</div>
