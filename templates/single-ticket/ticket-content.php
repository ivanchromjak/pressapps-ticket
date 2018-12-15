<div id="pati-<?php pati_the_ID(); ?>" <?php pati_the_class(); ?>>
    <div class="pati-avatar"><?php pati_the_avatar(); ?><p class="pati-author-name"><?php pati_the_author(); ?></p></div>
    <div class="pati-comment">
        <div class="pati-comment-header">
            <div class="pati-update-time pati-pull-left"><?php pati_the_created_time(); ?></div>    
            <div class="pati-pull-right"></div>
        </div>
        <div class="pati-comment-text"><?php pati_the_content(); ?></div>
        <div class="pati-attachments"><?php pati_the_ticket_files(); ?></div>    
    </div>
</div>

