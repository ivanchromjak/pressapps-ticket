<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<li>
    <div id="pati-update-<?php pati_the_update_ID(); ?>" <?php pati_the_update_class(); ?>>
        <div class="pati-avatar"><?php pati_the_update_avatar(); ?><p class="pati-author-name"><?php pati_the_update_author(); ?></p></div>
        <div class="pati-comment">
            <div class="pati-comment-header">
                <div class="pati-update-time pati-pull-left"><?php pati_the_update_time(); ?></div>    
                <div class="pati-pull-right"></div>
            </div>
            <div class="pati-comment-text">
                <?php 
                pati_the_update_content();
                pati_the_update_summary();
                ?>
            </div>
            <div class="pati-attachments"><?php pati_the_update_files(); ?></div>
        </div>
    </div>
</li>
