<?php
namespace Zoom\Base;

class base {
	function __construct()
	{
		$this->register_cpt();
	}
    public function rest_is_user_logged_in($request) {
		$authorization = $request->get_header('authorization');
	
		$token = str_replace('Bearer ','',$authorization);
		$tkn_user = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
		
		$userdata = !empty($tkn_user->id) ? get_userdata($tkn_user->id) : '';
		
		if(!empty($authorization) && !empty($userdata)) {	
			$usermeta = get_user_meta($tkn_user->id); 
			return array( 'user_data' => $userdata, 'user_meta' => $usermeta, 
			'user_role' => $userdata->roles);
		}
		return false; 
	}
	public function register_cpt() {
		add_action( 'init', function() {
			$cpts = array(
				'abs_event' => array(
					'singular' => 'Event Meeting',
					'plular' => 'Event Meetings',
					'description' => 'Meetings',
					'slug' => 'abs_event',
					'taxonomies' => array(),
				),
				'abs_zoom' => array(
					'singular' => 'Zoom Meeting',
					'plular' => 'Zoom Meetings',
					'description' => 'Zoom Meetings',
					'slug' => 'abs_zoom',
					'taxonomies' => array(),
				)
			);
			foreach( $cpts as $key => $cpt ) {

				// Set UI labels for Custom Post Type
				$labels = array(
					'name'                => _x( $cpt['plular'], $cpt['description'], 'abs_zoom_event' ),
					'singular_name'       => _x( $cpt['singular'], $cpt['description'], 'abs_zoom_event' ),
					'menu_name'           => __(  $cpt['plular'], 'abs_zoom_event' ),
					'parent_item_colon'   => __( 'Parent '.$cpt['singular'], 'abs_zoom_event' ),
					'all_items'           => __( 'All '. $cpt['plular'], 'abs_zoom_event' ),
					'view_item'           => __( 'View '.$cpt['singular'], 'abs_zoom_event' ),
					'add_new_item'        => __( 'Add New '.$cpt['singular'], 'abs_zoom_event' ),
					'add_new'             => __( 'Add New', 'abs_zoom_event' ),
					'edit_item'           => __( 'Edit '.$cpt['singular'], 'abs_zoom_event' ),
					'update_item'         => __( 'Update '.$cpt['singular'], 'abs_zoom_event' ),
					'search_items'        => __( 'Search '.$cpt['singular'], 'abs_zoom_event' ),
					'not_found'           => __( 'Not Found', 'abs_zoom_event' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'abs_zoom_event' ),
				);
				
				// Set other options for Custom Post Type  
				$args = array(
					'label'               => __( $cpt['plular'] , 'abs_zoom_event' ),
					'description'         => __( $cpt['description'], 'abs_zoom_event' ),
					'labels'              => $labels,
					'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
					'taxonomies'          => $cpt['taxonomies'],
					'hierarchical'        => true,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'show_in_admin_bar'   => true,
					'menu_position'       => 5,
					'can_export'          => true,
					'has_archive'         => true,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'capability_type'     => 'post',
					'show_in_rest' => true,

				);
				
				// Registering your Custom Post Type
				register_post_type( $key , $args );
			}
		});

	}
}