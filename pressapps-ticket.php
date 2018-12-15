<?php
/**
 * Plugin Name: PressApps Ticket
 * Plugin URI: http://pressapps.co/
 * Description: Customer Support Ticketing Solution by PressApps
 * Author: PressApps Team
 * Version: 2.1.0
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Skelet Config
 */
$skelet_paths[] = array(
    // Set unique plugin prefix
    'prefix'      => 'patis',
    'dir'         => wp_normalize_path(  plugin_dir_path( __FILE__ ).'/includes/' ),
    'uri'         => plugin_dir_url( __FILE__ ).'/includes/skelet',
);

/**
 * Load Skelet Framework
 */
if( ! class_exists( 'Skelet_LoadConfig' ) ){
    include_once dirname( __FILE__ ) .'/includes/skelet/skelet.php';
}

/**
 * Global Variables
 */
if ( class_exists( 'Skelet' ) && ! isset( $patis ) ) {
    $patis = new Skelet( 'patis' );
    $tickets_slug = $patis->get('slug');
}

if ( ! class_exists( 'PRESSAPPS_TICKET' ) ) :

final class PRESSAPPS_TICKET{
    
    /**
     *
     * @var type 
     */
    public $version = '1.0.0';


    public $ticket_visibility;
    public $ticket;
    public $ticket_status;
    public $ticket_priority;
    public $ticket_type;
    public $ticket_user_group;

    /**
     *
     * @var type 
     */
    protected static $_instance;

    public static function instance() {
        
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

   
    public function template_path() {
        return apply_filters( 'PATI_TEMPLATE_PATH', 'pati/' );
    }
    
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }
    
    private function define_constants() {
        
        define( 'PATI_PLUGIN_FILE'            , __FILE__ );
        define( 'PATI_VERSION'                , $this->version );
        
        if ( ! defined( 'PATI_TEMPLATE_PATH' ) ) {
            define( 'PATI_TEMPLATE_PATH', $this->template_path() );
        }
        
        if ( ! defined( 'PATI_DATEFORMAT' ) ) {
            define('PATI_DATEFORMAT'    ,'j M y G:i');
        }
    }
    
    private function includes(){
        
        include_once 'includes/functions.php';
        include_once 'includes/class-pati-post-type.php';
        include_once 'includes/actions.php';
        include_once 'includes/filters.php';
        include_once 'includes/loop-general.php';
        include_once 'includes/loop-ticket.php';
        include_once 'includes/loop-update.php';
        include_once 'includes/loop-heading.php';
        include_once 'includes/template.php';
        include_once 'includes/form.php';
        include_once 'includes/form-validation.php';
        include_once 'includes/email.php';
        include_once 'includes/deprecated.php';
        
        if ( defined( 'DOING_AJAX' ) ) {
            include_once 'includes/class-pati-ajax.php';
        }
        
        if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
            
            include_once 'includes/class-pati-ticket-filtering.php';
            include_once 'includes/class-pati-frontend-scripts.php';
            include_once 'includes/class-pati-template-loader.php';
            
        }
        
        if(is_admin()){
            include_once 'includes/metaboxes.php';
            include_once 'includes/admin/class-pati-admin.php';
        }
        
    }


    /**
     * Setup the Environment for the Plugin
     */
    function __construct() {
        
        register_activation_hook(__FILE__, array($this,'activation_hook'));
        
        $this->define_constants();
        
        $this->includes();
        
        $this->ticket_visibility = array(
            'public'        => __('Public'      ,'pressapps-ticket'),
            'private'       => __('Private'     ,'pressapps-ticket'),
            'userdefine'    => __('User Define' ,'pressapps-ticket'),
        );
       
        
        load_plugin_textdomain('pressapps-ticket', false, basename(dirname(__FILE__)).'/language' );
        
        add_action('init'                       ,array($this,'init'));
        
    }

    
    function init(){
        
        add_filter( 'widget_text'       ,'do_shortcode' );
        
        $this->ticket               = get_post_type_object('ticket');
        $this->ticket_status        = get_taxonomy('ticket_status');
        $this->ticket_priority      = get_taxonomy('ticket_priority');
        $this->ticket_type          = get_taxonomy('ticket_type');
        $this->ticket_user_group    = get_taxonomy('user_group');
        
        
        
       
        if(isset($_REQUEST['pati_front_do_action'])){
            include_once 'includes/class-pati-frontend-do_action.php';
        }
        //flush_rewrite_rules();
        //add_rewrite_tag('%ticket_query%','([^&]+)');
        //add_rewrite_rule('^ticket/display/q=([^/]*)/?','index.php?post_type=ticket&ticket_query=$matches[1]','top');
        //add_rewrite_rule('^ticket/([0-9]+)/?','index.php?p=$matches[1]','top');
    }
    
    
    
    /**
     * 
     */
    function activation_hook(){
        
        include_once 'includes/install.php';
        
        $this->init();
        
        do_action('pati_before_setup_plugin');
        
        pati_initialize_capabilities();              
        pati_setup_default_content();
        
        flush_rewrite_rules();
        
        do_action('pati_after_setup_plugin');
        
    }
    
}

endif;

if(!function_exists('PATI')):

function PATI(){
    return PRESSAPPS_TICKET::instance();
}

endif;

$GLOBALS['pati'] = PATI();