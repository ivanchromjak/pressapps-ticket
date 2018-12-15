<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * Options Page settings
 * @var $settings
 */
$settings = array(
	'header_title' => 'Ticket',
	'menu_title'   => 'Ticket',
	'menu_type'    => 'add_submenu_page',
	'menu_slug'    => 'pressapps-ticket',
	'ajax_save'    => false,
);

/**
 * Options sections & fields
 * @var $options
 */
$options = array();

/**
 * General Tab Section & options
 */
$setup_info = __( 'WordPress provides two template rendering hooks, template_include and template_redirect. Both seam to work similarly but with a big difference in operation as one is action hook and the other is filter hook.', 'pressapps-ticket' );
$setup_info .= __( 'WordPress recommends using “Template Include” as it provides further extensibility. We have added options for both hooks to cater for wide variety of the Free/Premium WordPress themes.', 'pressapps-ticket' );



$options[] = array(
	'name'   => 'ti-setup',
	'title'  => 'Setup',
	'icon'   => 'si-cog3',
	'fields' => array(
		array(
			'id'             => 'add_ticket_page',
			'type'           => 'select',
			'title'          => __( 'Create Ticket Page', 'pressapps-ticket' ),
			'options'        => 'pages',
			'default_option' => 'Select a page'
		),
		/*
		array(
			'id'             => 'profile_page',
			'type'           => 'select',
			'title'          => __( 'Profile Page', 'pressapps-ticket' ),
			'options'        => 'pages',
			'default_option' => 'Select a page'
		),
		*/
		array(
			'id'      => 'slug',
			'type'    => 'text',
			'title'   => __( 'Tickets Page Slug', 'pressapps-ticket' ),
			'default' => 'tickets',
			'after'    => '<br>&nbsp;<i>Flush permalinks after changing the slug</i>',
			'validate' => 'required',
		),
		array(
			'id'      => 'template_hook',
			'type'    => 'radio',
			'title'   => __( 'Template Hook', 'pressapps-ticket' ),
			'options' => array(
				'template_include' => 'template_include',
				'template_redirect' => 'template_redirect'
			),
			'default' => 'template_include',
		),
		array(
			'id'      => 'template_rendering',
			'type'    => 'radio',
			'title'   => __( 'Template Rendering', 'pressapps-ticket' ),
			'options' => array(
				'full' => 'Full Page',
				'wrapper' => 'Wrapper'
			),
			'default' => 'full',
			'dependency' => array( 'patis_template_hook_template_include', '==', 'true' )
		),
		array(
			'type'    => 'notice',
			'class'   => 'info',
			'content' => $setup_info,
		),
	)
);

$options[] = array(
	'name'   => 'ti-tickets',
	'title'  => 'Tickets Page',
	'icon'   => 'si-list',
	'fields' => array(
		array(
			'id'             => 'tickets_columns',
			'type'           => 'sorter',
			'title'          => 'Columns',
			'default'        => array(
				'enabled'		=> array(
					'status'		=> 'Status',
					'title'			=> 'Title',
					'category'		=> 'Category',
					'author'		=> 'Author',
					'priority'      => 'Priority',
				),
				'disabled'		=> array(
					'id'			=> 'ID',
					'updated'		=> 'Updated',
				),
			),
			'enabled_title'  => 'Display',
			'disabled_title' => 'Hide',
		),
		array(
			'id'      => 'tickets_per_page',
			'type'    => 'number',
			'title'   => __( 'Tickets Per Page', 'pressapps-ticket' ),
			'default' => '40'
		),
		array(
			'id'             => 'status_open',
			'type'           => 'select',
			'title'          => 'Open Status',
			'options'        => 'categories',
			'query_args'     => array(
				'type'         => 'ticket',
				'taxonomy'     => 'ticket_status',
				'orderby'      => 'name',
				'order'        => 'ASC',
				'hide_empty'   => 0,
			),
			'default_option' => 'Select open status',
		),
		array(
			'id'             => 'status_closed',
			'type'           => 'select',
			'title'          => 'Closed Status',
			'options'        => 'categories',
			'query_args'     => array(
				'type'         => 'ticket',
				'taxonomy'     => 'ticket_status',
				'orderby'      => 'name',
				'order'        => 'ASC',
				'hide_empty'   => 0,
			),
			'default_option' => 'Select closed status',
		),
		array(
			'id'      => 'status_closed_strikethrough',
			'type'    => 'switcher',
			'title'   => __( 'Strikethrough Closed Tickets', 'pressapps-ticket' ),
			'default' => '1',
			'dependency'   => array( 'patis_status_closed', '!=', '' ),
		),
		array(
			'id'      => 'hide_closed_tickets',
			'type'    => 'switcher',
			'title'   => __( 'Hide Closed Tickets', 'pressapps-ticket' ),
			'default' => '1',
			'dependency'   => array( 'patis_status_closed', '!=', '' ),
		),
	)
);

$options[] = array(
	'name'   => 'ti-single',
	'title'  => 'Single Page',
	'icon'   => 'si-text2',
	'fields' => array(
		array(
			'id'      => 'commnents_reverse',
			'type'    => 'switcher',
			'title'   => __( 'Reverse Comments', 'pressapps-ticket' ),
			'default' => '1',
		),
	)
);

$options[] = array(
	'name'   => 'ti-forms',
	'title'  => 'Forms',
	'icon'   => 'si-menu7',
	'fields' => array(
		array(
			'id'       => 'form_fields_agent',
			'type'     => 'checkbox',
			'title'    => 'Agent Form Fields',
			'options'  => array(
				'category'	=> 'Category',
				'type'		=> 'Type',
				'status'	=> 'Status',
				'priority'	=> 'Priority',
				'assigned'  => 'Assigned',
				'tags'		=> 'Tags',
				'upload'	=> 'Upload',
			),
			'default'  => array( 'category', 'type', 'status', 'priority', 'assigned', 'tags', 'upload' )
		),
		array(
			'id'       => 'form_fields_user',
			'type'     => 'checkbox',
			'title'    => 'User Form Fields',
			'options'  => array(
				'category'	=> 'Category',
				'type'		=> 'Type',
				'priority'	=> 'Priority',
				'tags'		=> 'Tags',
				'upload'	=> 'Upload',
			),
			'default'  => array( 'category', 'type', 'priority', 'assigned', 'tags', 'upload' )
		),
	)
);

/**
 * Email notifications
 */
$new_ticket_body    = '<p>New ticket (#%ticket_id%) <strong> %ticket_title% </strong> has been received and will be processed shortly, ticket details:</p>';
$new_ticket_body   .= '<strong>ID</strong> #%ticket_id%';
$new_ticket_body   .= '<br/>';
$new_ticket_body   .= '<strong>Category</strong> %ticket_category%';
$new_ticket_body   .= '<br/>';
$new_ticket_body   .= '<strong>Status</strong> %ticket_status%';
$new_ticket_body   .= '<br/>';
$new_ticket_body   .= '<strong>Priority</strong> %ticket_priority%';    
$new_ticket_body   .= '<br/>';
$new_ticket_body   .= '<strong>Type</strong> %ticket_type%';
$new_ticket_body   .= '<p>%ticket_content%</p>';
$new_ticket_body   .= '<p>Keep track using the following url: %ticket_url% </p>';
$new_ticket_body   .= '<p>Thank you</p>';

$update_ticket_body    = '<p>Ticket (#%ticket_id%) <strong> %ticket_title% </strong> has been updated by %update_author% further ticket details:</p>';
$update_ticket_body   .= '<strong>ID</strong> #%ticket_id%';
$update_ticket_body   .= '<br/>';
$update_ticket_body   .= '<strong>Category</strong> %ticket_category%';
$update_ticket_body   .= '<br/>';
$update_ticket_body   .= '<strong>Status</strong> %ticket_status%';
$update_ticket_body   .= '<br/>';
$update_ticket_body   .= '<strong>Priority</strong> %ticket_priority%';    
$update_ticket_body   .= '<br/>';
$update_ticket_body   .= '<strong>Type</strong> %ticket_type%';
$update_ticket_body   .= '<br/>';
$update_ticket_body   .= '<strong>Assignee</strong> %update_author%';
$update_ticket_body   .= '<p>%ticket_content%</p>';
$update_ticket_body   .= '<p>Keep track using the following url: %ticket_url% </p>';
$update_ticket_body   .= '<p>Thank you</p>';

$options[]      = array(
    'name'        => 'ti-notifications',
    'title'       => __( 'Notifications', 'pressapps-ticket' ),
    'icon'        => 'si-envelop2',
	'sections' => array(
        array(
			'name'   => 'ti-new-ticket',
			'title'  => 'New Ticket',
			'icon'   => 'si-file-plus',
			'fields' => array(
				array(
				  'type'    => 'heading',
				  'content' => 'New Ticket',
				),		
				array(
					'id'      => 'new_ticket_name',
					'type'    => 'text',
					'title'   => __( 'From Name', 'pressapps-ticket' ),
				),
				array(
					'id'      => 'new_ticket_email',
					'type'    => 'text',
					'title'   => __( 'From Email', 'pressapps-ticket' ),
				),
				array(
					'id'      => 'new_ticket_subject',
					'type'    => 'text',
					'title'   => __( 'Subject', 'pressapps-ticket' ),
					'default'    => 'New Ticket #%ticket_id% Submitted',
				),
				array(
					'id'       => 'new_ticket_body',
					'type'     => 'wysiwyg',
					'title'    => 'Content',
					'settings' => array(
						'editor_height' => 250,
					),
					'after'		=> 'Available variables: %ticket_id%, %ticket_title%, %ticket_priority%, %ticket_type%, %ticket_content%, %ticket_url%',
					'default'    => $new_ticket_body,
				),		

			),
		),
        array(
			'name'   => 'ti-update-ticket',
			'title'  => 'Update Ticket',
			'icon'   => 'si-file-text2',
			'fields' => array(
				array(
				  'type'    => 'heading',
				  'content' => 'Update Ticket',
				),		
				array(
					'id'      => 'update_ticket_name',
					'type'    => 'text',
					'title'   => __( 'From Name', 'pressapps-ticket' ),
				),
				array(
					'id'      => 'update_ticket_email',
					'type'    => 'text',
					'title'   => __( 'From Email', 'pressapps-ticket' ),
				),
				array(
					'id'      => 'update_ticket_subject',
					'type'    => 'text',
					'title'   => __( 'Subject', 'pressapps-ticket' ),
					'default'    => 'Ticket #%ticket_id% has been updated by %update_author%',
				),
				array(
					'id'       => 'update_ticket_body',
					'type'     => 'wysiwyg',
					'title'    => 'Content',
					'settings' => array(
						'editor_height' => 250,
					),
					'after'		=> 'Available variables: %ticket_id%, %ticket_title%, %ticket_priority%, %ticket_type%, %update_author%, %ticket_content%, %ticket_url%',
					'default'    => $update_ticket_body,
				),		

			),
		),
    )
);


/**
 * Redirects Tab Section & options
 */
$options[] = array(
	'name'   => 'ti-redirects',
	'title'  => 'Redirects',
	'icon'   => 'si-redo2',
	'fields' => array(
		array(
			'id'      => 'nonlogein_redirect',
			'type'    => 'radio',
			'title'   => __( 'Redirect Unauthorised User', 'pressapps-ticket' ),
			'options' => array(
				'home_page' => 'Home Page',
				'login_page' => 'Login Page',
				'custom_url' => 'Custom URL'
			),
			'default' => 'login_page',
		),
		array(
			'id'      => 'nonlogein_redirect_url',
			'type'    => 'text',
			'title'   => __( 'Redirect URL', 'pressapps-ticket' ),
			'dependency' => array( 'patis_nonlogein_redirect_custom_url', '==', 'true' )
		),
	)
);

/**
 * Style Tab & Options fields
 */
$options[] = array(
	'name'   => 'ti-style',
	'title'  => 'Styling',
	'icon'   => 'si-brush',
	'fields' => array(
		array(
			'id'      => 'border_color',
			'type'    => 'color_picker',
			'title'   => __( 'Ticket Page Border Color', 'pressapps-ticket' ),
			'default' => '#dddddd',
		),
		array(
			'id'      => 'border_color_focus',
			'type'    => 'color_picker',
			'title'   => __( 'Form Border Focus Color', 'pressapps-ticket' ),
			'default' => '#999999',
		),
		array(
			'id'      => 'btn_bg',
			'type'    => 'color_picker',
			'title'   => __( 'Button Color', 'pressapps-ticket' ),
			'default' => '#27ae60',
		),
		array(
			'id'      => 'btn_bg_hover',
			'type'    => 'color_picker',
			'title'   => __( 'Button Hover Color', 'pressapps-ticket' ),
			'default' => '#222222',
		),
		array(
			'id'      => 'border_radius',
			'type'    => 'number',
			'title'   => __( 'Border Radius', 'pressapps-ticket' ),
			'default' => '3'
		),
		array(
			'id'      => 'status_titles',
			'type'    => 'switcher',
			'title'   => __( 'Display Status Titles', 'pressapps-ticket' ),
			'default' => 'false',
		),
		array(
			'id'    => 'custom_css',
			'type'  => 'textarea',
			'title' => __( 'Custom CSS', 'pressapps-ticket' ),
			'info'  => 'You can add and override stylesheets here.',
		)
	)
);

// Register Framework page settings and options fields 
SkeletFramework::instance( $settings, $options );
