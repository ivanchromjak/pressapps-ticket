<?php

/**
 * @author PressApps
 * @version 1.0
 * @package PATI/templates
 */

global $patis;


$tickets_columns = $patis->get('tickets_columns');
$layout = $tickets_columns['enabled'];

?>
<thead>
    <tr class="pati-list-row pati-list-row-heading">
		<?php 
		if ($layout): foreach ($layout as $key=>$value) {
		 
		    switch($key) {
		 
		        case 'status':
		        	if ( $patis->get('status_titles') ) {
			        	echo '<th>' . pati_the_col_head('ticket_status') . '</th>';
		        	} else {
		        		echo '<th></th>';
		        	}
			        break;
		 
		        case 'id':
			        echo '<th>' . pati_the_col_head('ticket_id') . '</th>';
			        break;
		 
		        case 'title':
			        echo '<th>' . pati_the_col_head('ticket_title') . '</th>';
			        break;
		 
		        case 'category':
			        echo '<th>' . pati_the_col_head('ticket_category') . '</th>';
			        break;  
		 
		        case 'author':
			        echo '<th>' . pati_the_col_head('author') . '</th>';
			        break;  
		 
		        case 'updated':
			        echo '<th>' . pati_the_col_head('updated') . '</th>';
			        break;  
		 
		        case 'priority':
			        echo '<th>' . pati_the_col_head('ticket_priority') . '</th>';
			        break;  
		 
		    }
		 
		}
		endif;
		?>

    </tr>
</thead>
