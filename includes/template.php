<?php
/**
 * 
 * @package PressApps::Ticket
 * @subpackage Template
 */


/**
 * 
 * @param type $template_name
 * @param type $template_path
 * @param string $default_path
 * @return type
 */
function pati_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    if ( ! $template_path ) {
        $template_path = PATI()->template_path();
    }

    if ( ! $default_path ) {
        $default_path = PATI()->plugin_path() . '/templates/';
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template(array(
        trailingslashit( $template_path ) . $template_name,
        $template_name
    ));

    // Get default template
    if ( ! $template ) {
            $template = $default_path . $template_name;
    }

    // Return what we found
    return apply_filters('pati_locate_template', $template, $template_name, $template_path);
}


function pati_get_template_part( $slug, $name = '' ) {
    $template = '';

    if ( $name ) {
        $template = locate_template( array( "{$slug}-{$name}.php", PATI()->template_path() . "{$slug}-{$name}.php" ) );
    }

    // Get default slug-name.php
    if ( ! $template && $name && file_exists( PATI()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
        $template = PATI()->plugin_path() . "/templates/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/pati/slug.php
    if ( ! $template ) {
        $template = locate_template( array( "{$slug}.php", PATI()->template_path() . "{$slug}.php" ) );
    }
    
    if ( ! $template && file_exists( PATI()->plugin_path() . "/templates/{$slug}.php" ) ) {
        $template = PATI()->plugin_path() . "/templates/{$slug}.php";
    }

    // Allow 3rd party plugin filter template file from their plugin
    $template = apply_filters( 'pati_get_template_part', $template, $slug, $name );

    if ( $template ) {
        load_template( $template, false );
    }
}


function pati_before_main_content(){
    pati_get_template_part('global/wrapper','start'); 
}

function pati_after_main_content(){
    pati_get_template_part('global/wrapper','end');
}

function pati_get_header(){
    pati_get_template_part('global/header'); 
}

function pati_get_footer(){
    pati_get_template_part('global/footer');
}


