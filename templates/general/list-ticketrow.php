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
<tr id="pati-<?php pati_the_ID(); ?>" class="pati-list-row<?php pati_the_status_class(); ?>">
		<?php 
		if ($layout): foreach ($layout as $key=>$value) {
		 
		    switch($key) {
		 
		        case 'status':
		        	echo '<td class="pati-status">';
		        	pati_the_status();
		        	echo '</td>';
			        break;
		 
		        case 'id':
			        echo '<td><a href="';
			        pati_the_permalink();
			        echo '">#';
			        pati_the_ID();
			        echo '</a></td>';
			        break;
		 
		        case 'title':
			        echo '<td><a class="pati-title" href="';
			        pati_the_permalink();
			        echo '">';
			        pati_the_title();
			        echo '</a></td>';
			        break;
		 
		        case 'category':
		        	echo '<td>';
		        	pati_the_category();
		        	echo '</td>';
			        break;  
		 
		        case 'author':
		        	echo '<td>';
		        	pati_the_author();
		        	echo '</td>';
			        break;  
		 
		        case 'updated':
		        	echo '<td>';
		        	pati_the_modified_time();
		        	echo '</td>';
			        break;  
		 
		        case 'priority':
		        	echo '<td class="pati-priority">';
		        	pati_the_priority();
		        	echo '</td>';
			        break;  
		 
		    }
		 
		}
		endif;
		?>

</tr>
