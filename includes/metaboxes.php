<?php

/**
 * 
 * @global type $post
 */
function pati_notes_meta_box(){
    
    global $post;
    
    $notes = get_post_meta($post->ID,'ticket_notes',TRUE);
    ?>
    <textarea name="ticket_notes" id="ticket_notes"
              cols="90" rows="5"
              ><?php echo  trim($notes); ?></textarea>
    <?php
}


