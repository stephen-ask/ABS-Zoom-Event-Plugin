<?php
namespace Zoom\Core;

class zoom_cpt implements \Zoom\Base\Cpt  {
    
    

    // set custom post type name
    public function get_name(){
        return 'abs-zoom-meeting';
    }

     /**
     * set custom post type options data
     */
    public function post_type() {
        $options = $this->user_modifiable_option();

        $labels = [
            'name'                  => esc_html_x( 'Attendees', 'Post Type General Name', 'abs-zoom-event' ),
            'singular_name'         => $options['etn_attendee_singular_name'],
            'menu_name'             => esc_html__( 'Attendee', 'abs-zoom-event' ),
            'name_admin_bar'        => esc_html__( 'Attendee', 'abs-zoom-event' ),
            'archives'              => $options['etn_attendee_archive'],
            'attributes'            => esc_html__( 'Attendee Attributes', 'abs-zoom-event' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'abs-zoom-event' ),
            'all_items'             => $options['etn_attendee_all'],
            'add_new_item'          => esc_html__( 'Add New Attendee', 'abs-zoom-event' ),
            'add_new'               => esc_html__( 'Add New', 'abs-zoom-event' ),
            'new_item'              => esc_html__( 'New Attendee', 'abs-zoom-event' ),
            'edit_item'             => esc_html__( 'Edit Attendee', 'abs-zoom-event' ),
            'update_item'           => esc_html__( 'Update Attendee', 'abs-zoom-event' ),
            'view_item'             => esc_html__( 'View Attendee', 'abs-zoom-event' ),
            'view_items'            => esc_html__( 'View Attendee', 'abs-zoom-event' ),
            'search_items'          => esc_html__( 'Search Attendee', 'abs-zoom-event' ),
            'not_found'             => esc_html__( 'Not found', 'abs-zoom-event' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'abs-zoom-event' ),
            'featured_image'        => esc_html__( 'Featured Image', 'abs-zoom-event' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'abs-zoom-event' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'abs-zoom-event' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'abs-zoom-event' ),
            'insert_into_item'      => esc_html__( 'Insert into Attendee', 'abs-zoom-event' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Attendee', 'abs-zoom-event' ),
            'items_list'            => esc_html__( 'Attendee list', 'abs-zoom-event' ),
            'items_list_navigation' => esc_html__( 'Attendee list navigation', 'abs-zoom-event' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'abs-zoom-event' ),
        ];

        $rewrite = [
            'slug'       => apply_filters( 'attendee_slug', $options['attendee_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];

        $args = [
            'label'               => esc_html__( 'Attendee', 'abs-zoom-event' ),
            'description'         => esc_html__( 'Attendee', 'abs-zoom-event' ),
            'labels'              => $labels,
            'supports'            => false,
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => current_user_can( 'manage_etn_attendee' ),
            'show_admin_column'   => false,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 10,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'publicly_queryable'  => false,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => $this->get_name(),
            'map_meta_cap' => true, // Allow edit / delete
        ];

        // Can't create manual attendee
        if ( !class_exists('Wpeventin_Pro') ) {

            $args['capabilities'] = array(
                  'create_posts'    => 'do_not_allow', // Removes support for the "Add New" 
            );
			
        }
        if( current_user_can( 'manage_etn_attendee' ) ){
            $args['show_in_menu']        = 'etn-events-manager';
        }

        return $args;
    }

      /**
     * Operation custom post type
     */ 
    public function flush_rewrites() {
        $name = $this->get_name();
        $args = $this->post_type();

        register_post_type( $name, $args );
        flush_rewrite_rules();
    }
}
