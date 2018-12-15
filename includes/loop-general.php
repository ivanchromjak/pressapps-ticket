<?php

/**
 * 
 * @package PressApps::Ticket
 * @subpackage Loop
 */

function pati_the_srno(){
    echo pati_get_the_srno();
}

function pati_get_the_srno(){
    static $i = 1;
    
    return $i++;
}

/**
 * 
 * @global WP_Query $pati_query
 * @return bool
 */
function pati_have_posts() {
    
    global $pati_query;

    return $pati_query->have_posts();
}

function pati_in_the_loop() {
	global $pati_query;

	return $pati_query->in_the_loop;
}

function pati_the_post() {
	global $pati_query;

	$pati_query->the_post();
}


function pati_the_ID(){
    echo pati_get_the_ID();
}

function pati_get_the_ID( $post_id = 0 ) {
        global $wp_query,$pati_query;


        if ( !empty( $post_id ) && is_numeric( $post_id ) ) {
                $ticket_id = $post_id;
        } elseif ( !empty( $pati_query->in_the_loop ) && isset( $pati_query->post->ID ) ) {
                $ticket_id = $pati_query->post->ID;
        } 

        return $ticket_id;
        
}

function pati_the_permalink(){
    echo pati_get_the_permalink();
}

function pati_get_the_permalink($post_id = 0){
    
    if($post_id == 0)
        $post_id = get_post(pati_get_the_ID());
    
    return apply_filters('pati_the_permalink',  get_permalink($post_id));
}

function pati_is_archive(){
    
    if(is_admin())
        return FALSE;
    
    global $pati_query;
    
    return $pati_query->is_archive();
}

function pati_is_single(){
    
    if(is_admin())
        return FALSE;
    
    global $pati_query;
    
    return $pati_query->is_single();
}

function pati_get_paging_nav($args = array()) {
    global $wp_query,$wp_rewrite;

    $links          = NULL;
    $default_args   = array(
        'prev_text'  => __( '&larr; Previous'   , 'pressapps-ticket' ),
        'next_text'  => __( 'Next &rarr;'       , 'pressapps-ticket' ),
    );

    $args = array_merge($default_args,$args);
   
    if ( $wp_query->max_num_pages < 2 ) {
            return ;
    }

    $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args   = array();
    $url_parts    = explode( '?', $pagenum_link );

    if ( isset( $url_parts[1] ) ) {
            wp_parse_str( $url_parts[1], $query_args );
    }

    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

    $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links( array(
        'base'      => $pagenum_link,
        'format'    => $format,
        'total'     => $GLOBALS['wp_query']->max_num_pages,
        'current'   => $paged,
        'mid_size'  => 1,
        'add_args'  => array_map( 'urlencode', $query_args ),
        'prev_text' => $args['prev_text'],
        'next_text' => $args['next_text'],
    ) );

    return $links;
}

function pati_the_paging_nav($args = array()){
    
    $links = pati_get_paging_nav($args);
    
    if ( $links ) :
    ?>
    <nav class="navigation paging-navigation" role="navigation">
        <div class="pagination loop-pagination">
            <?php echo $links; ?>
        </div>
    </nav>
    <?php
    endif;
    
}