<?php

/**
 * @author PressApps
 * @version 1.0
 * @package PATI/templates
 */

global $patis;


    do_action( 'pati_before_main_content' );
?>
        <div>
            <?php if ( $patis->get('hide_closed_tickets') ) { ?>
                <div class="pati-status-nav pati-pull-left">
                    <a href="<?php echo get_post_type_archive_link( 'ticket' ); ?>"><i class="sk-icon si-menu7"></i> <?php _e('Open'  ,'pressapps-ticket'); ?></a> 
                    <a href="<?php echo esc_url( add_query_arg( 'status', 'closed' ) ); ?>"><i class="sk-icon si-checkmark3"></i> <?php _e('Closed'  ,'pressapps-ticket'); ?></a>
                </div>
            <?php } ?>

            <div class="pati-open-nav pati-pull-right">
                <a href="<?php echo get_permalink($patis->get( 'add_ticket_page' )); ?>"><i class="sk-icon si-plus3"></i> <?php _e('New'  ,'pressapps-ticket'); ?></a>
            </div>

            <table class="pati-list-table">
                <?php pati_get_template_part('general/list','head'); ?>
                <?php
                if(have_posts()){
                    while(have_posts()){
                        the_post();
                        pati_get_template_part('general/list','ticketrow');
                    }
                }else{
                    pati_get_template_part('general/list','noticket');
                }
                ?>
            </table>
            <?php
                pati_get_template_part('general/list','pagination');
            ?>
        </div>
<?php 
    do_action( 'pati_after_main_content' ); 
?>

    <?php do_action( 'pati_sidebar' ); ?>
