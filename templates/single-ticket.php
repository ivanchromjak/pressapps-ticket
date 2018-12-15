<?php

/**
 * @author PressApps
 * @version 1.0
 * @package PATI/templates
 */

global $patis;

do_action( 'pati_before_main_content' );

?>
<div class="pati-single">
    
    <?php
    while(pati_have_posts()) : pati_the_post();
        if(!pati_is_template_redirect()){
            ?>
            <h1><?php pati_the_title(); ?></h1>
            <?php    
        }        
        if(current_user_can('edit_ticket')){
            pati_get_template_part('single-ticket/ticket-update', 'form');
        }else{
             pati_get_template_part('single-ticket/ticket','summary'); 
        }
        
        if ($patis->get('commnents_reverse')) {
            pati_get_template_part('single-ticket/ticket','comments');
            pati_get_template_part('single-ticket/ticket','content');
        } else {
            pati_get_template_part('single-ticket/ticket','content');
            pati_get_template_part('single-ticket/ticket','comments');
        }
                         
    endwhile;
    ?>
</div>    

<?php do_action( 'pati_after_main_content' ); ?>

<?php do_action( 'pati_sidebar' ); ?>





