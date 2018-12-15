<?php

class PATI_Admin {
    
    function __construct() {
        
        add_action('admin_init'                         ,array( $this, 'admin_init'));
        add_action('admin_head'                         ,array( $this, 'admin_head'));
        add_action('admin_menu'                         ,array( $this, 'admin_menu'));
        add_action('save_post'                          ,array( $this, 'save_post'));

        add_action('admin_enqueue_scripts'              ,array( $this, 'wp_enqueue_pakb_color_picker' ));
        add_action('ticket_status_add_form_fields'      ,array( $this, 'edit_status_color') );
        add_action('ticket_status_edit_form_fields'     ,array( $this, 'edit_status_color') );
        add_action('edited_ticket_status'               ,array( $this, 'save_status_color') );
        add_action('create_ticket_status'               ,array( $this, 'save_status_color') );

        add_filter('wp_dropdown_users'                  ,array( $this, 'ticket_author' ) );

    }

    function admin_menu(){

        remove_meta_box('tagsdiv-ticket_category'    ,'ticket'   , 'side');
        remove_meta_box('tagsdiv-ticket_status'      ,'ticket'   , 'side');
        remove_meta_box('tagsdiv-ticket_priority'    ,'ticket'   , 'side');
        remove_meta_box('tagsdiv-ticket_type'        ,'ticket'   , 'side');
        remove_meta_box('tagsdiv-user_group'         ,'ticket'   , 'side');
        
    }
    
    function admin_head(){
        
        if(isset($_GET['post'])){
            $post_type = get_post($_GET['post']);
        }elseif(isset($_GET['post_type'])){
            $post_type = $_GET['post_type'];
        }else{
            $post_type = '';
        }
        
        wp_enqueue_style('pati_adminstyle');
        
        switch($post_type){
            case 'ticket':
                
                break;
        }
        
    }
    
    function admin_init(){
        
        wp_register_style('pati_adminstyle'            ,PATI()->plugin_url() . '/assets/css/adminstyle.css');
        
        add_filter('manage_edit-ticket_columns'        ,array($this,'custom_column'));
        add_action('manage_ticket_posts_custom_column' ,array($this,'custom_column_values'));

    }


    function ticket_author() {
        global $post, $typenow;

        if( 'ticket' != $typenow )
            return;

        $users = get_users();

        echo'<select id="post_author_override" name="post_author_override" class="">';

        echo'<option value="1">Admin</option>';

        foreach($users as $user)
        {
            echo '<option value="'.$user->ID.'"';

            if ($post->post_author == $user->ID){ echo 'selected="selected"'; }

            echo'>';
            echo $user->display_name.'</option>';     
        }
        echo'</select>';

    }


    function custom_column_values($column){
        global $post;
        switch($column){
            case 'visibility':
                /**
                 * @todo this options is left incomplete because this feature is 
                 * shifted to be implemted under the next version so.
                 */
                break;
            case 'customer':
                $user = get_user_by('id', $post->post_author);
                echo '<a class="row-title" href="' . admin_url('users.php?s=' . $user->user_login )  . '">' . $user->user_login .  '</a>';
                
                break;
            case 'ticket_id':
                echo "#<strong>" . $post->ID . '</strong>' ;
                break;
            case 'timestamp':
                
                break;
            case 'priority':
                $terms  = wp_get_object_terms($post->ID,'ticket_priority');
                
                if(!empty($terms)){
                    foreach($terms as $term){
                        echo '<a target="_blank" class="row-title" ';
                        echo ' href="' . admin_url('edit-tags.php?action=edit&taxonomy=ticket_priority&tag_ID=' . $term->term_id . '&post_type=ticket') . '">' .  $term->name . '</a>';
                    }
                }
                break;
            case 'type':
                $terms  = wp_get_object_terms($post->ID,'ticket_type');
                
                if(!empty($terms)){
                    foreach($terms as $term){
                        echo '<a target="_blank" class="row-title" ';
                        echo ' href="' . admin_url('edit-tags.php?action=edit&taxonomy=ticket_type&tag_ID=' . $term->term_id . '&post_type=ticket') . '">' .  $term->name . '</a>';
                    }
                }
                break;
            case 'ticket_tags':
                $terms  = wp_get_object_terms($post->ID,'ticket_tags');
                
                if(!empty($terms)){
                    foreach($terms as $term){
                        echo '<a target="_blank" class="row-title" ';
                        echo ' href="' . admin_url('edit-tags.php?action=edit&taxonomy=ticket_tags&tag_ID=' . $term->term_id . '&post_type=ticket') . '">' .  $term->name . '</a><br/>';
                    }
                }
                break;
            case 'updated':

                $time       = strtotime($post->post_modified );
                $time_diff  = current_time('timestamp') - $time;

                if( $time_diff > 0 ){
                    if( $time_diff < ( 24*60*60 ) ){
                        $hour   = floor($time_diff/(60*60));
                        $minute = floor(($time_diff - ($hour*60*60))/60);
                        $sec = floor( $time_diff - ($hour*60*60*60)/60 );
                        if ( $hour < 1 && $minute < 1 ) {
                            echo sprintf( _n( '%d sec ago', '%d secs ago', $sec, 'pressapps-ticket' ), $sec );
                        } elseif ( $hour < 1 && $minute >= 1 ) {
                            echo sprintf( _n( '%d min ago', '%d mins ago', $minute, 'pressapps-ticket' ), $minute );
                        } else {
                            echo $hour . ' H : ' . sprintf( _n( '%d min ago', '%d mins ago', $minute, 'pressapps-ticket' ), $minute );
                        }

                    }else{
                        echo floor(($time_diff/(24*60*60))) . ' day(s) ago';
                    }
                }
                
                break;
            case 'status':
                
                $terms  = wp_get_object_terms($post->ID,'ticket_status');
                
                if(!empty($terms)){
                    foreach($terms as $term){
                        echo '<a target="_blank" class="row-title" ';
                        echo ' href="' . admin_url('edit-tags.php?action=edit&taxonomy=ticket_status&tag_ID=' . $term->term_id . '&post_type=ticket') . '">' .  $term->name . '</a>';
                    }
                }
                
                break;
            case 'category':
                
                $terms  = wp_get_object_terms($post->ID,'ticket_category');
                
                if(!empty($terms)){
                    foreach($terms as $term){
                        echo '<a target="_blank" class="row-title" ';
                        echo ' href="' . admin_url('edit-tags.php?action=edit&taxonomy=ticket_category&tag_ID=' . $term->term_id . '&post_type=ticket') . '">' .  $term->name . '</a>';
                    }
                }
                
                break;
        }
    }
    
    function custom_column($columns){
        
        $new_columns['cb']              = $columns['cb'];
        $new_columns['ticket_id']       = __('Ticket Id'    ,'pressapps-ticket');
        $new_columns['title']           = $columns['title'];
        $new_columns['customer']        = __('customer'     ,'pressapps-ticket');
        $new_columns['category']          = __('Category'       ,'pressapps-ticket');
        $new_columns['status']          = __('Status'       ,'pressapps-ticket');
        //$new_columns['visibility']      = __('Visibility'   ,'pressapps-ticket');
        $new_columns['updated']         = __('Last Updated' ,'pressapps-ticket');
        $new_columns['priority']        = __('Priority'     ,'pressapps-ticket');
        $new_columns['type']            = __('Type'         ,'pressapps-ticket');
        $new_columns['ticket_tags']     = __('Tags'         ,'pressapps-ticket');
        
        return $new_columns;
    }
      
    /**
     * Color picker form For the Ticket Status Taxonomy
     * 
     * @param string|Object $term
     */
    public function edit_status_color( $term = NULL ) {
        $status_color = (!is_string($term))?get_option( "ticket_status_{$term->term_id}" ):array();
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="meta-color"><?php _e('Status Color', 'pressapps-ticket'); ?></label></th>
        <td>
            <div id="pati_colorpicker">
                <input type="text" name="cat_meta[catBG]" class="colorpicker" maxlength="7" style="display: inline-block;" 
                       value="<?php echo (isset($status_color['catBG'])) ? $status_color['catBG'] : '#ffffff'; ?>" />
            </div>
    </tr>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('.colorpicker').wpColorPicker();
        });
    </script>
    <?php
    }

    /** Save Status Color **/
    public function save_status_color( $term_id ) {

        if ( isset( $_POST['cat_meta'] ) ) {
            $term_id = $term_id;
            $status_color = get_option( "ticket_status_$term_id");
            $cat_keys = array_keys($_POST['cat_meta']);
                foreach ($cat_keys as $key){
                if (isset($_POST['cat_meta'][$key])){
                    $status_color[$key] = $_POST['cat_meta'][$key];
                }
            }
            //save the option array
            update_option( "ticket_status_$term_id", $status_color );
        }
    }
    
    function wp_enqueue_pakb_color_picker( ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker-script', PATI()->plugin_url() . '/assets/js/admin.js', array( 'wp-color-picker' ), false, true );
    }
    
    function save_post($post_id){
        $post = get_post($post_id);
        switch($post->post_type){
            case 'ticket':
                if ( isset( $_REQUEST['pati_category'] ) ) {
                    wp_set_object_terms( $post_id, (int) $_REQUEST['pati_category'], 'ticket_category' );
                }
                if ( isset( $_REQUEST['pati_status'] ) ) {
                    wp_set_object_terms( $post_id, (int) $_REQUEST['pati_status'], 'ticket_status' );
                }
                if ( isset( $_REQUEST['pati_priority'] ) ) {
                    wp_set_object_terms( $post_id, (int) $_REQUEST['pati_priority'], 'ticket_priority' );
                }
                if ( isset( $_REQUEST['pati_type'] ) ) {
                    wp_set_object_terms( $post_id, (int) $_REQUEST['pati_type'], 'ticket_type' );
                }

                if ( isset( $_REQUEST['ticket_notes'] ) && ! empty( $_REQUEST['ticket_notes'] ) ) {
                    update_post_meta( $post_id, 'ticket_notes', strip_tags( $_REQUEST['ticket_notes'] ) );
                }

                if(isset($_REQUEST['assigned_user']) && current_user_can('assign_ticket')){
                    $data = explode(":",$_REQUEST['assigned_user']);
                    
                    if(count($data)==2){
                        wp_set_object_terms($post_id,$data[0],'user_group',FALSE);

                        update_post_meta($post_id, 'pati_assigned_user'     ,$_REQUEST['assigned_user']);
                        update_post_meta($post_id, 'pati_assigned_user_id'  ,$data[1]);
                    }
                }
                
                break;
        }
    }
}

new PATI_Admin();


                
