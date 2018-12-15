<?php

class PATI_Template_Loader {
    
    public $current_rendering_hook;


    protected static $_instance;

    public static function instance() {
        
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    function __construct() {
        global $patis;
        
        if($patis->get('template_hook') == 'template_include'){
            
            $this->current_rendering_hook = 'template_include';
            
            
            if($patis->get('template_rendering') != 'wrapper'){
                add_action( 'pati_before_main_content'  , 'pati_get_header'             , 10 );
                add_action( 'pati_before_main_content'  , 'pati_before_main_content'    , 20 );

                add_action( 'pati_after_main_content'   , 'pati_after_main_content'     , 10 );
                add_action( 'pati_after_main_content'   , 'pati_get_footer'             , 20 );
            }
            
            add_filter('template_include'           ,array($this,'template_include'));
        }else{
            $this->current_rendering_hook = 'template_redirect';
            
            add_filter('template_redirect'          ,array($this,'template_redirect'));
        }
    }
    
    function enqueue_scripts(){
        global $skelet_path;
        
        wp_enqueue_style('pressapps-ticket');
       // wp_enqueue_style('dropzone');
        wp_enqueue_style( 'sk-icons', $skelet_path["uri"] . '/assets/css/sk-icons.css', array(), '1.0.0', 'all' );

        wp_enqueue_script('jquery');
        wp_enqueue_script('ticket_tag');
        wp_enqueue_script('dropzone');
        wp_enqueue_script('ticket_validate');
        wp_enqueue_script('pressapps-ticket');
    }
    
    function template_include($template){
        global $wp_query,$pati_query,$post,$current_user,$patis;
        get_currentuserinfo();
        /**
         * @todo The way all the $pati_query variable are initialized are stil
         * the older and Ugly Way so this also needs updation as Per the 
         * WooCommerce Query So that Some of the Indirect function call 
         * can be removed so this also Requires some Updation
         */        
        $pati_query = $wp_query;

        if($patis->get( 'add_ticket_page' ) && is_page($patis->get( 'add_ticket_page' ))){
            
            remove_all_actions( 'pati_before_main_content');           
            remove_all_actions( 'pati_after_main_content');            
            
            $this->enqueue_scripts(); 
            $this->add_ticket_page_rendering();
            if(!is_user_logged_in() || !current_user_can('read_ticket')){
                header("location:" . pati_unauth_redirect_url());
                exit();
            }
            /*
        }elseif($patis->get( 'profile_page' ) && is_page($patis->get( 'profile_page' ))){
            
            remove_all_actions( 'pati_before_main_content');           
            remove_all_actions( 'pati_after_main_content');            
            
            $this->enqueue_scripts(); 
            $this->profile_page_rendering();
            if(!is_user_logged_in() || !current_user_can('read_ticket')){
                header("location:" . pati_unauth_redirect_url());
                exit();
            }
          */              
        }elseif(is_post_type_archive('ticket') || is_singular('ticket') || is_tax(array('ticket_priority','ticket_type','ticket_status','ticket_category'))){
            
            $this->enqueue_scripts();
            
            if(!is_user_logged_in() || !current_user_can('read_ticket')){
                header("location:" . pati_unauth_redirect_url());
                exit();
            }elseif(is_singular('ticket')){
                //$ticket_visibility = get_post_meta($wp_query->post->ID,'pati_ticket_visibility',TRUE);
                 if(!current_user_can('edit_others_tickets') /* && ($ticket_visibility == 'private') */ && ($current_user->ID != $wp_query->post->post_author)){
                    header("location:" . esc_url(home_url($patis->get('slug'))));
                    exit();
                }
            }
            
            //remove_filter( 'the_content', 'wpautop' );
            
            if(is_post_type_archive('ticket')){
                
                $file   = 'archive-ticket.php';
            }elseif(is_tax('ticket_type')){
                $ticket_type        = get_term_by('slug',get_query_var('ticket_type'),'ticket_type');
                $title              = sprintf(__('%s Tickets','pressapps-ticket'),$ticket_type->name);
                
                $file   = 'taxonomy-ticket_type.php';
            }elseif(is_tax('ticket_priority')){
                
                $ticket_priority    = get_term_by('slug',get_query_var('ticket_priority'),'ticket_priority');
                $title              = sprintf(__('All %s priority Tickets','pressapps-ticket'),$ticket_priority->name);
                
                $file   = 'taxonomy-ticket_priority.php';
            }elseif(is_tax('ticket_status')){
                
                $ticket_status      = get_term_by('slug',get_query_var('ticket_status'),'ticket_status');
                $title              = sprintf(__('All %s Tickets','pressapps-ticket'),$ticket_status->name);
                
                $file   = 'taxonomy-ticket_status.php';
            }elseif(is_tax('ticket_category')){
                
                $ticket_category      = get_term_by('slug',get_query_var('ticket_category'),'ticket_category');
                $title              = sprintf(__('All %s Tickets','pressapps-ticket'),$ticket_category->name);
                
                $file   = 'taxonomy-ticket_category.php';
            }elseif(is_singular('ticket')){
                
                $file   = 'single-ticket.php';
            }
            
            return pati_locate_template($file);
        }
        
        return $template;
    }
    
    function template_redirect(){
        
        global $wp_query,$pati_query,$post,$current_user,$patis;
        get_currentuserinfo();
        
        $pati_query = $wp_query;
        
        if(is_post_type_archive('ticket') || is_singular('ticket') || is_page($patis->get( 'add_ticket_page' ) /*|| is_page($patis->get( 'profile_page' ))*/)){
            
            $this->enqueue_scripts();
            
            if(!is_user_logged_in() || !current_user_can('read_ticket')){
                header("location:" . pati_unauth_redirect_url());
                exit();
            }elseif(is_singular('ticket')){
                //$ticket_visibility = get_post_meta($wp_query->post->ID,'pati_ticket_visibility',TRUE);
                 if(!current_user_can('edit_others_tickets')/* && ($ticket_visibility == 'private') */ && ($current_user->ID != $wp_query->post->post_author)){
                    header("location:" . esc_url(home_url($patis->get('slug'))));
                    exit();
                }
            }
            
            remove_filter( 'the_content', 'wpautop' );
            
            if(is_post_type_archive('ticket')){
                
                $file_content = pati_load_file(pati_locate_template('archive-ticket.php'));
                
                $post   = new WP_Post((object)pati_get_dummy_post_data(array(
                    'post_content'          => $file_content,
                    'post_excerpt'          => $file_content,
                    'post_title'            => __('Tickets'     ,'pressapps-ticket'),
                )));
                
                $wp_query->posts                = array($post);
                $wp_query->post                 = $post;
                $wp_query->post_count           = 1;
                $wp_query->queried_object       = $post;
                $wp_query->queried_object_id    = $post->ID;
                
                $this->override_is_var();
                
                add_filter('page_template',array($this,'page_template'));
                

            }elseif(is_singular('ticket')){
                
                $post   = new WP_Post((object)pati_get_dummy_post_data(array(
                    'post_content'          => pati_load_file(pati_locate_template('single-ticket.php')),
                    'post_title'            => $wp_query->post->post_title,
                )));
                
                $wp_query->posts        = array($post);
                $wp_query->post         = $post;
                $wp_query->post_count   = 1;
                
                $this->override_is_var();
                
            }else{

                if($patis->get( 'add_ticket_page' ) && is_page($patis->get( 'add_ticket_page' ))){
                    $this->add_ticket_page_rendering();
                }
                /*
                elseif($patis->get( 'profile_page' ) && is_page($patis->get( 'profile_page' ))){
                    $this->profile_page_rendering();
                }
                */
            }
        }
        
    }
    
    function override_is_var(){
        global $wp_query;

        $wp_query->is_tax                   = FALSE;
        $wp_query->is_archive               = FALSE;
        $wp_query->is_search                = FALSE;
        $wp_query->is_single                = FALSE;
        $wp_query->is_post_type_archive     = FALSE;

        $wp_query->is_404                   = FALSE;

        $wp_query->is_singular              = TRUE;
        $wp_query->is_page                  = TRUE;
    }
    
    function page_template($template){
        global $patis;

        $page_id        = $patis->get( 'add_ticket_page' );
        $template_file  = get_post_meta($page_id,'_wp_page_template',TRUE);
        
        if(empty($template_file))
            return $template;
        
        $new_template = get_template_directory() . "/{$template_file}";
        
        return (file_exists($new_template)?$new_template:$template);
    }
    
    private function add_ticket_page_rendering(){
        add_filter('the_content',array($this,"add_ticket_page_the_content"));
        
    }
    
    private function profile_page_rendering(){
        add_filter('the_content',array($this,"profile_page_the_content"));
        
    }

    function add_ticket_page_the_content($content){
        $content .= pati_load_file(pati_locate_template('add-ticket.php'));
        return $content;
    }
    
    function profile_page_the_content($content){
        $content .= pati_load_file(pati_locate_template('profile.php'));
        return $content;
    }

}

if(!function_exists('PATI_Template_Loader')):

function PATI_Template_Loader(){
    return PATI_Template_Loader::instance();
}

$GLOBALS['pati_template_loader'] = PATI_Template_Loader();

endif;

