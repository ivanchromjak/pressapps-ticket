<?php

class PATI_Frontend_Scripts {
    
    function __construct() {
        add_action('wp'             ,array($this,'wp'));
        add_action('wp_enqueue_scripts'        ,array($this,'dynamic_styles'));
   }
    
    function wp(){
        
        wp_register_style('pressapps-ticket'        ,PATI()->plugin_url() . '/assets/css/pressapps-ticket-public.css');
//        wp_register_style('dropzone'                    ,PATI()->plugin_url() . '/assets/css/dropzone.css');
                
        wp_register_script('ticket_tag'                 ,PATI()->plugin_url() . '/assets/js/jquery.tagsinput.min.js' ,'jquery');
        wp_register_script('dropzone'                   ,PATI()->plugin_url() . '/assets/js/dropzone.min.js'         ,'jquery');
        wp_register_script('ticket_validate'            ,PATI()->plugin_url() . '/assets/js/jquery.validate.js'      ,'jquery');
        wp_register_script('pressapps-ticket'        ,PATI()->plugin_url() . '/assets/js/pressapps-ticket-public.js'             ,'jquery');
        
        //wp_enqueue_scripts();
    }

    function dynamic_styles(){
        global $patis;
        $status         = get_terms('ticket_status',array('hide_empty'=>FALSE));
        $statusColours  = array();
        $css            = $patis->get( 'custom_css' );
        if(count($status)>0){
            for($i=0;$i<count($status);$i++){
                $color  = pati_get_status_code($status[$i]->term_id);
                if(empty($color))
                    continue;
                $statusColours[$status[$i]->slug] = $color;
            }
            if(is_array($statusColours)){
                foreach($statusColours as $status => $statusColour){
                    $css .= '.pati-status-' . $status . ' .pati-status label { background-color: ' . $statusColour . '; }';
                }
            }
        }

        // Closed status
        if ($patis->get('status_closed') && $patis->get('status_closed_strikethrough')) {
            $closed_status = get_term_by('id', $patis->get('status_closed'), 'ticket_category');
            $css .= '.pati-status-' . $closed_status->slug . ' .pati-title { text-decoration: line-through; }';
        }

        $css .= '.pati-form textarea, .pati-form input[type="text"], .pati-form input[type="search"], .pati-form select, div.tagsinput, #file_ct, .pati-single .pati-form, .pati-comment-text, .pati-events .pati-comment:before, .pati-body .pati-comment:before { border-color:' . $patis->get('border_color') . '; }';
        $css .= '.pati-form textarea:focus, .pati-form input[type="text"]:focus, .pati-form input[type="search"]:focus, .pati-form select:focus { border-color:' . $patis->get('border_color_focus') . ' }';
        $css .= 'input[type="submit"].pati-btn { background-color:' . $patis->get('btn_bg') . '; border-color:' . $patis->get('btn_bg') . ' }';
        $css .= 'input[type="submit"].pati-btn:hover { background-color:' . $patis->get('btn_bg_hover') . '; border-color:' . $patis->get('btn_bg_hover') . ' }';
        $css .= '.pati-form textarea, .pati-form input[type="text"], .pati-form input[type="search"], .pati-form select, input[type="submit"].pati-btn, div.tagsinput, #file_ct, .pati-comment-text, .pati-avatar .avatar { border-radius:' . $patis->get('border_radius') . 'px; -webkit-border-radius:' . $patis->get('border_radius') . 'px; }';

        wp_add_inline_style( 'pressapps-ticket', wp_kses( $css, array( '\"', '\"' ) ) );
    }
}

new PATI_Frontend_Scripts();