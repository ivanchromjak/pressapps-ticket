<?php
$comments = pati_get_ticket_updates();
if(count($comments)>0){
    ?>
    <ul class="pati-events">
    <?php
        foreach($comments as $comment){
            pati_setup_comment($comment);
            
            pati_get_template_part('single-ticket/ticket','updaterow');
        }
    ?>
    </ul>
    <?php
}else{
    // pati_get_template_part('single-ticket/ticket','noupdate');
}
?>
