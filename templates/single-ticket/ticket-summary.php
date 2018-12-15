<div class="pati-summary">
<table>
    <tr>
        <th><?php _e('Ticket Id','pressapps-ticket'); ?></th>
        <td>#<?php pati_the_ID(); ?></td>
        <th><?php _e('Status'   ,'pressapps-ticket'); ?></th>
        <td><?php pati_the_status();?></td>
    </tr>
    <tr>
        <th><?php _e('Priority','pressapps-ticket'); ?></th>
        <td><?php pati_the_priority(); ?></td>
        <th><?php _e('Type'   ,'pressapps-ticket'); ?></th>
        <td><?php pati_the_type();?></td>
    </tr>
    <tr>
        <th><?php _e('Assigned','pressapps-ticket'); ?></th>
        <td><?php pati_the_assigned() ?></td>
        <th><?php _e('Customer'   ,'pressapps-ticket'); ?></th>
        <td><?php pati_the_author(); ?></td>
    </tr>
    <tr>
        <th><?php _e('Created'          ,'pressapps-ticket'); ?></th>
        <td><?php pati_the_created_time(); ?></td>
        <th><?php _e('Last Modified'   ,'pressapps-ticket'); ?></th>
        <td><?php pati_the_modified_time();?></td>
    </tr>
    <tr>
        <th><?php _e('Tags'             ,'pressapps-ticket'); ?></th>
        <td colspan="3"><?php pati_the_tags(); ?></td>
        
    </tr>
</table>
</div>