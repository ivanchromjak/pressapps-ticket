<?php

class PATI_AJAX {
    function __construct() {
        
        $ajax_events = array(
            'upload_file'   => FALSE,
            'delete_file'   => FALSE,
        );
        
        foreach ( $ajax_events as $ajax_event => $nopriv ) {
            add_action( 'wp_ajax_pati_' . $ajax_event, array( $this, $ajax_event ) );

            if ( $nopriv )
                    add_action( 'wp_ajax_nopriv_pati_' . $ajax_event, array( $this, $ajax_event ) );
        }
    }
    
    /**
     * File upload related function will be written here
     */
    function upload_file() {
        
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');  
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');  
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');  
        
        $uploads                    = wp_upload_dir();
        $_FILES["file"]["name"]     = wp_unique_filename( $uploads['path'] , $_FILES["file"]["name"]);
        $ds                         = DIRECTORY_SEPARATOR;
        
        move_uploaded_file($_FILES['file']['tmp_name'], $uploads['path'] . $ds . $_FILES["file"]["name"]);
        $fileurl            = $uploads['url'] . '/' . basename( $_FILES["file"]["name"] );
        $filelocation       = $uploads['path'] . '/' . basename( $_FILES["file"]["name"] );
         
        $attachment = array(
            'guid'              => $fileurl, 
            'post_mime_type'    => $_FILES['file']['type'],
            'post_title'        => basename( $_FILES["file"]["name"] ),
            'post_content'      => '',
            'post_status'       => 'inherit'
         );
        
        $attachment_id  = wp_insert_attachment($attachment, $fileurl);
        $attach_data    = wp_generate_attachment_metadata( $attachment_id, $filelocation );
        
        wp_update_attachment_metadata( $attachment_id, $attach_data );
        echo $attachment_id;
        die('');
    }
    
    /**
     * Delete the Uploaded Attachment 
     * 
     * Wp-admin Ajax Request
     */
    function delete_file(){
        
        /**
         * add the authorization check weather the user having rights to delete
         * the attacment or not 
         */
        $file_id    = $_REQUEST['file_id'];
        
        $result     = wp_delete_attachment($file_id,TRUE);
        
        die();
    }
}

new PATI_AJAX();