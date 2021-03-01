<?php
/*

Copyright 2020-PLUGIN_TILL_YEAR Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'iworks_build_a_house_posttypes_expence' ) ) {
	return;
}

require_once( dirname( dirname( __FILE__ ) ) . '/posttypes.php' );

class iworks_build_a_house_posttypes_expence extends iworks_build_a_house_posttypes {

	protected $post_type_name          = 'ibh_expence';
	protected $taxonomy_name_breakdown = 'iworks_build_a_house_breakdown';
	private $nonce_list                = 'iworks_build_a_house_expence_expences_list_nonce';
	private $users_list                = array();
	private $boats_list                = array();

	public function __construct() {
		parent::__construct();
		add_filter( 'the_content', array( $this, 'the_content' ) );
		/**
		 * change default columns
		 */
		add_filter( "manage_{$this->get_name()}_posts_columns", array( $this, 'add_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
		/**
		 * apply default sort order
		 */
		add_action( 'pre_get_posts', array( $this, 'apply_default_sort_order' ) );
		add_action( 'pre_get_posts', array( $this, 'apply_countries_selector' ) );
		/**
		 * sort next/previous links by title
		 */
		add_filter( 'get_previous_post_sort', array( $this, 'adjacent_post_sort' ), 10, 3 );
		add_filter( 'get_next_post_sort', array( $this, 'adjacent_post_sort' ), 10, 3 );
		add_filter( 'get_previous_post_where', array( $this, 'adjacent_post_where' ), 10, 5 );
		add_filter( 'get_next_post_where', array( $this, 'adjacent_post_where' ), 10, 5 );
		/**
		 * AJAX list
		 */
		if ( is_a( $this->options, 'iworks_options' ) ) {
			$this->nonce_list = $this->options->get_option_name( 'expences_list_nonce' );
		}
		add_action( 'wp_ajax_iworks_build_a_house_expences_list', array( $this, 'get_select2_list' ) );
		/**
		 * add nonce
		 */
		add_filter( 'wp_localize_script_build_a_house_admin', array( $this, 'add_nonce' ) );
		/**
		 * maybe update country
		 */
		add_action( 'maybe_add_expence_nation', array( $this, 'maybe_add_expence_nation' ), 10, 2 );
		/**
		 * fields
		 */
		$this->fields = array(
			'details' => array(
				'cost'       => array(
					'type'  => 'Number',
					'label' => __( 'Cost', 'build_a_house' ),
				),
				'contractor' => array(
					'type'  => 'select2',
					'label' => __( 'Contractor', 'kpir' ),
					'args'  => array(
						'data-source'       => 'contractor',
						'data-nonce-action' => 'get-contractors-list',
					),
				),
				'date_start' => array(
					'type'  => 'date',
					'label' => __( 'Date Start', 'build_a_house' ),
				),
				'date_en'    => array(
					'type'  => 'date',
					'label' => __( 'Date End', 'build_a_house' ),
				),
				'mobile'     => array( 'label' => __( 'Mobile', 'build_a_house' ) ),
				'email'      => array( 'label' => __( 'E-mail', 'build_a_house' ) ),
			),
		);
		/**
		 * add class to metaboxes
		 */
		foreach ( array_keys( $this->fields ) as $name ) {
			if ( 'basic' == $name ) {
				continue;
			}
			$key = sprintf( 'postbox_classes_%s_%s', $this->get_name(), $name );
			add_filter( $key, array( $this, 'add_defult_class_to_postbox' ) );
		}
	}

	/**
	 * Add default class to postbox,
	 */
	public function add_defult_class_to_postbox( $classes ) {
		$classes[] = 'iworks-type';
		return $classes;
	}

	public function register() {
		$parent = true;
		$labels = array(
			'name'                  => _x( 'Expences', 'expence General Name', 'build_a_house' ),
			'singular_name'         => _x( 'Expence', 'expence Singular Name', 'build_a_house' ),
			'menu_name'             => __( 'Build a house', 'build_a_house' ),
			'name_admin_bar'        => __( 'Expence', 'build_a_house' ),
			'archives'              => __( 'Expences', 'build_a_house' ),
			'attributes'            => __( 'Item Attributes', 'build_a_house' ),
			'all_items'             => __( 'Expences', 'build_a_house' ),
			'add_new_item'          => __( 'Add New expence', 'build_a_house' ),
			'add_new'               => __( 'Add New expence', 'build_a_house' ),
			'new_item'              => __( 'New expence', 'build_a_house' ),
			'edit_item'             => __( 'Edit expence', 'build_a_house' ),
			'update_item'           => __( 'Update expence', 'build_a_house' ),
			'view_item'             => __( 'View expence', 'build_a_house' ),
			'view_items'            => __( 'View expences', 'build_a_house' ),
			'search_items'          => __( 'Search expence', 'build_a_house' ),
			'not_found'             => __( 'Not found', 'build_a_house' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'build_a_house' ),
			'featured_image'        => __( 'Featured Image', 'build_a_house' ),
			'set_featured_image'    => __( 'Set featured image', 'build_a_house' ),
			'remove_featured_image' => __( 'Remove featured image', 'build_a_house' ),
			'use_featured_image'    => __( 'Use as featured image', 'build_a_house' ),
			'insert_into_item'      => __( 'Insert into item', 'build_a_house' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'build_a_house' ),
			'items_list'            => __( 'Items list', 'build_a_house' ),
			'items_list_navigation' => __( 'Items list navigation', 'build_a_house' ),
			'filter_items_list'     => __( 'Filter items list', 'build_a_house' ),
		);
		$args   = array(
			'label'                => __( 'Expence', 'build_a_house' ),
			'labels'               => $labels,
			'supports'             => array( 'title', 'editor', 'thumbnail', 'revision' ),
			'public'               => true,
			'show_ui'              => true,
			'show_in_menu'         => $parent,
			'show_in_admin_bar'    => true,
			'show_in_nav_menus'    => true,
			'can_export'           => true,
			'has_archive'          => _x( 'build_a_house_expences', 'slug for archive', 'build_a_house' ),
			'exclude_from_search'  => false,
			'publicly_queryable'   => true,
			'capability_type'      => 'page',
			'menu_icon'            => 'dashicons-admin-home',
			'register_meta_box_cb' => array( $this, 'register_meta_boxes' ),
		);
		$args   = apply_filters( 'build_a_house_register_expence_post_type_args', $args );
		register_post_type( $this->post_type_name, $args );
		/**
		 * expence hull breakdown Taxonomy.
		 */
		$labels = array(
			'name'                       => _x( 'Breakdowns', 'Breakdown General Name', 'build_a_house' ),
			'singular_name'              => _x( 'Breakdown', 'Breakdown Singular Name', 'build_a_house' ),
			'menu_name'                  => __( 'Breakdowns', 'build_a_house' ),
			'all_items'                  => __( 'Breakdowns', 'build_a_house' ),
			'new_item_name'              => __( 'New Breakdown Name', 'build_a_house' ),
			'add_new_item'               => __( 'Add New Breakdown', 'build_a_house' ),
			'edit_item'                  => __( 'Edit Breakdown', 'build_a_house' ),
			'update_item'                => __( 'Update Breakdown', 'build_a_house' ),
			'view_item'                  => __( 'View Breakdown', 'build_a_house' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'build_a_house' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'build_a_house' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'build_a_house' ),
			'popular_items'              => __( 'Popular Breakdowns', 'build_a_house' ),
			'search_items'               => __( 'Search Breakdowns', 'build_a_house' ),
			'not_found'                  => __( 'Not Found', 'build_a_house' ),
			'no_terms'                   => __( 'No items', 'build_a_house' ),
			'items_list'                 => __( 'Breakdowns list', 'build_a_house' ),
			'items_list_navigation'      => __( 'Breakdowns list navigation', 'build_a_house' ),
		);
		$args   = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'public'             => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_quick_edit' => true,
			'rewrite'            => array( 'slug' => 'build_a_house-breakdown' ),
		);
		$args   = apply_filters( 'build_a_house_register_expence_taxonomy_args', $args );
		register_taxonomy( $this->taxonomy_name_breakdown, array( $this->post_type_name ), $args );
	}

	public function save_post_meta( $post_id, $post, $update ) {
		$result = $this->save_post_meta_fields( $post_id, $post, $update, $this->fields );
	}

	public function register_meta_boxes( $post ) {
		add_meta_box( 'expenceal', __( 'Detailed data', 'build_a_house' ), array( $this, 'details' ), $this->post_type_name );
	}

	public function details( $post ) {
		$this->get_meta_box_content( $post, $this->fields, __FUNCTION__ );
	}

	/**
	 * Get custom column values.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column Column name,
	 * @param integer $post_id Current post id (expence),
	 *
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'contractor':
				$id = get_post_meta( $post_id, $this->get_custom_field_basic_contractor_name(), true );
				if ( empty( $id ) ) {
					echo '-';
				} else {
					printf(
						'<a href="%s">%s</a>',
						add_query_arg(
							array(
								'contractor' => $id,
								'post_type'  => 'iworks_kpir_invoice',
							),
							admin_url( 'edit.php' )
						),
						get_post_meta( $id, 'iworks_kpir_contractor_data_full_name', true )
					);
				}
				break;
			case 'email':
				$meta_name = $this->options->get_option_name( 'contact_' . $column );
				$email     = get_post_meta( $post_id, $meta_name, true );
				if ( ! empty( $email ) ) {
					printf( '<a href="mailto:%s">%s</a>', esc_attr( $email ), esc_html( $email ) );
				}
				break;
		}
	}

	/**
	 * change default columns
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns list of columns.
	 * @return array $columns list of columns.
	 */
	public function add_columns( $columns ) {
		unset( $columns['date'] );
		$columns['title']      = __( 'Name', 'build-a-house' );
		$columns['contractor'] = __( 'Contractor', 'build-a-house' );
		$columns['cost']       = __( 'Cost', 'build-a-house' );
		return $columns;
	}

	/**
	 * Add default sorting
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $query WP Query object.
	 */
	public function apply_default_sort_order( $query ) {
		/**
		 * do not change if it is already set by request
		 */
		if ( isset( $_REQUEST['orderby'] ) ) {
			return $query;
		}
		/**
		 * only main query
		 */
		if ( ! $query->is_main_query() ) {
			return $query;
		}
		/**
		 * do not change outsite th admin area
		 */
		$post_type = get_query_var( 'post_type' );
		if ( is_admin() ) {
			/**
			 * check get_current_screen()
			 */
			if ( function_exists( 'get_current_screen' ) ) {
				$screen = get_current_screen();
				if ( isset( $screen->post_type ) && $this->get_name() == $screen->post_type ) {
					$query->set( 'order', 'ASC' );
					$query->set( 'orderby', 'post_title' );
				}
			}
		} else {
			if ( ! empty( $post_type ) && $post_type === $this->post_type_name ) {
				$query->set( 'order', 'ASC' );
				$query->set( 'orderby', 'post_title' );
				return $query;
			}
		}
		return $query;
	}

	public function get_select2_list() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['user_id'] ) ) {
			wp_send_json_error();
		}
		$nonce = $_POST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, $this->nonce_list . $_POST['user_id'] ) ) {
			wp_send_json_error();
		}
		$data      = array();
		$args      = array(
			'nopaging'  => true,
			'post_type' => $this->get_name(),
			'orderby'   => 'post_title',
			'order'     => 'ASC',
		);
		$the_query = new WP_Query( $args );
		// The Loop
		if ( $the_query->have_posts() ) {
			foreach ( $the_query->posts as $post ) {
				$data[] = array(
					'id'   => $post->ID,
					'text' => $post->post_title,
				);
			}
			wp_send_json_success( $data );
		}
		wp_send_json_error();
	}

	public function add_nonce( $data ) {
		$data['nonces'][ $this->nonce_list ] = wp_create_nonce( $this->nonce_list . get_current_user_id() );
		return $data;
	}

	/**
	 *
	 * @since 1.0
	 */
	public function the_content( $content ) {
		if ( ! is_singular() ) {
			return $content;
		}
		$post_type = get_post_type();
		if ( $post_type != $this->post_type_name ) {
			return $content;
		}
		return $content;
	}


}

