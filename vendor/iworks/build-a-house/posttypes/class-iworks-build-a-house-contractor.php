<?php
/*
Copyright 2017-PLUGIN_TILL_YEAR Marcin Pietrzak (marcin@iworks.pl)

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

if ( class_exists( 'iworks_build_a_house_posttypes_contractor' ) ) {
	return;
}

require_once dirname( dirname( __FILE__ ) ) . '/posttypes.php';

class iworks_build_a_house_posttypes_contractor extends iworks_build_a_house_posttypes {

	protected $post_type_name = 'ibh_contractor'; // iworks_build_a_house_contractor (varchar(20))

	public function __construct() {
		parent::__construct();
		$this->fields                                 = array(
			'contractor_data' => array(
				'full_name'    => array(
					'label' => __( 'Full Name:', 'build_a_house' ),
				),
				'street1'      => array(
					'label' => __( 'Street Address 1:', 'build_a_house' ),
				),
				'street2'      => array(
					'label' => __( 'Street Address 2:', 'build_a_house' ),
				),
				'zip'          => array(
					'label' => __( 'ZIP Code:', 'build_a_house' ),
				),
				'city'         => array(
					'label' => __( 'City', 'build_a_house' ),
				),
				'country'      => array(
					'label' => __( 'Country', 'build_a_house' ),
				),
				'nip'          => array(
					'label' => __( 'NIP', 'build_a_house' ),
				),
				'regon'        => array(
					'label' => __( 'REGON', 'build_a_house' ),
				),
				'krs'          => array(
					'label' => __( 'KRS', 'build_a_house' ),
				),
				'bank'         => array(
					'label' => __( 'Bank', 'build_a_house' ),
				),
				'bank_account' => array(
					'label' => __( 'Bank account', 'build_a_house' ),
				),
			),
			'contact'         => array(
				'website' => array( 'label' => __( 'Website', 'build_a_house' ) ),
				'email'   => array( 'label' => __( 'email', 'build_a_house' ) ),
				'mobile'  => array( 'label' => __( 'mobile', 'build_a_house' ) ),
				'phone'   => array( 'label' => __( 'phone', 'build_a_house' ) ),
				'website' => array( 'label' => __( 'Website', 'build_a_house' ) ),
				'website' => array( 'label' => __( 'Website', 'build_a_house' ) ),
			),
		);
		$this->post_type_objects[ $this->get_name() ] = $this;
		add_action( 'wp_ajax_iworks_build_a_house_details_contractor', array( $this, 'get_contractors_json' ) );
		/**
		 * change default columns
		 */
		add_filter( "manage_{$this->get_name()}_posts_columns", array( $this, 'add_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
		/**
		 * apply default sort order
		 */
		add_action( 'pre_get_posts', array( $this, 'apply_default_sort_order' ) );
		/**
		 * add Contractors to invoices as a filter
		 */
		add_action( 'restrict_manage_posts', array( $this, 'add_contacators_to_invoices_list' ), 10, 2 );
	}

	public function register() {
		$labels = array(
			'name'                  => _x( 'Contractors', 'Contractor General Name', 'build_a_house' ),
			'singular_name'         => _x( 'Contractor', 'Contractor Singular Name', 'build_a_house' ),
			'menu_name'             => __( 'Contractors', 'build_a_house' ),
			'name_admin_bar'        => __( 'Contractor', 'build_a_house' ),
			'archives'              => __( 'Contractor Archives', 'build_a_house' ),
			'attributes'            => __( 'Contractor Attributes', 'build_a_house' ),
			'parent_item_colon'     => __( 'Parent Contractor:', 'build_a_house' ),
			'all_items'             => __( 'Contractors', 'build_a_house' ),
			'add_new_item'          => __( 'Add New Contractor', 'build_a_house' ),
			'add_new'               => __( 'Add New', 'build_a_house' ),
			'new_item'              => __( 'New Contractor', 'build_a_house' ),
			'edit_item'             => __( 'Edit Contractor', 'build_a_house' ),
			'update_item'           => __( 'Update Contractor', 'build_a_house' ),
			'view_item'             => __( 'View Contractor', 'build_a_house' ),
			'view_items'            => __( 'View Contractors', 'build_a_house' ),
			'search_items'          => __( 'Search Contractor', 'build_a_house' ),
			'not_found'             => __( 'Not found', 'build_a_house' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'build_a_house' ),
			'featured_image'        => __( 'Featured Image', 'build_a_house' ),
			'set_featured_image'    => __( 'Set featured image', 'build_a_house' ),
			'remove_featured_image' => __( 'Remove featured image', 'build_a_house' ),
			'use_featured_image'    => __( 'Use as featured image', 'build_a_house' ),
			'insert_into_item'      => __( 'Insert into contractor', 'build_a_house' ),
			'uploaded_to_this_item' => __( 'Uploaded to this contractor', 'build_a_house' ),
			'items_list'            => __( 'Contractors list', 'build_a_house' ),
			'items_list_navigation' => __( 'Contractors list navigation', 'build_a_house' ),
			'filter_items_list'     => __( 'Filter contractors list', 'build_a_house' ),
		);
		$args   = array(
			'label'                => __( 'Contractor', 'build_a_house' ),
			'description'          => __( 'Contractor Description', 'build_a_house' ),
			'labels'               => $labels,
			'supports'             => array( 'title', 'thumbnail' ),
			'taxonomies'           => array(),
			'hierarchical'         => false,
			'public'               => false,
			'show_ui'              => true,
			'show_in_menu'         => add_query_arg( array( 'post_type' => 'ibh_expence' ), 'edit.php' ),
			'show_in_admin_bar'    => false,
			'show_in_nav_menus'    => false,
			'show_in_rest'         => true,
			'can_export'           => true,
			'has_archive'          => true,
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'capability_type'      => 'page',
			'register_meta_box_cb' => array( $this, 'register_meta_boxes' ),
		);
		register_post_type( $this->post_type_name, $args );
	}

	public function register_meta_boxes( $post ) {
		add_meta_box( 'contractor-data', __( 'Contractor Data', 'build_a_house' ), array( $this, 'contractor_data' ), $this->post_type_name );
		add_meta_box( 'contact-data', __( 'Contact Data', 'build_a_house' ), array( $this, 'contact' ), $this->post_type_name );
	}

	public function contractor_data( $post ) {
		$this->get_meta_box_content( $post, $this->fields, __FUNCTION__ );
	}

	public function contact( $post ) {
		$this->get_meta_box_content( $post, $this->fields, __FUNCTION__ );
	}

	public function save_post_meta( $post_id, $post, $update ) {
		$this->save_post_meta_fields( $post_id, $post, $update, $this->fields );
	}

	public function get_contractors( $get_nip = true ) {
		$data = array(
			'total_count'        => 0,
			'incomplete_results' => false,
			'items'              => array(),
		);
		$args = array(
			'post_type'        => $this->get_name(),
			'nopaging'         => true,
			'orderby'          => 'title',
			'order'            => 'ASC',
			'suppress_filters' => true,
		);
		if ( isset( $_REQUEST['q'] ) ) {
			$args['s'] = $_REQUEST['q'];
		}
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$one = array(
					'id'        => get_the_ID(),
					'full_name' => get_the_title(),
				);
				if ( $get_nip ) {
					$one['nip'] = get_post_meta( get_the_ID(), $this->options->get_option_name( 'contractor_data_nip' ), true );
				}
				$data['items'][] = $one;
			}
			wp_reset_postdata();
		}
		return $data;
	}

	public function get_contractors_json() {
		$data = $this->get_contractors();
		echo wp_json_encode( $data );
		die;
	}

	/**
	 * Get custom column values.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $column Column name,
	 * @param integer $post_id Current post id (contractor),
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'full_name':
				echo get_post_meta( $post_id, $this->options->get_option_name( 'contractor_data_full_name' ), true );
				break;
			case 'nip':
				echo get_post_meta( $post_id, $this->options->get_option_name( 'contractor_data_nip' ), true );
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
		$columns['full_name'] = __( 'Full Name', 'build_a_house' );
		$columns['nip']       = __( 'NIP', 'build_a_house' );
		return $columns;
	}

	/**
	 * Add default sorting: post title
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
		 * do not change outsite th admin area
		 */
		if ( ! is_admin() ) {
			return $query;
		}
		/**
		 * check get_current_screen()
		 */
		if ( ! function_exists( 'get_current_screen' ) ) {
			return $query;
		}
		/**
		 * check screen post type
		 */
		if ( ! function_exists( 'get_current_screen' ) ) {
			return $query;
		}
		$screen = get_current_screen();
		if ( isset( $screen->post_type ) && $this->get_name() == $screen->post_type ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}
		return $query;
	}

	public function add_contacators_to_invoices_list( $post_type, $which ) {
		if ( 'top' != $which ) {
			return;
		}
		if ( 'iworks_build_a_house_invoice' != $post_type ) {
			return;
		}
		$data = $this->get_contractors( false );
		if ( empty( $data['items'] ) ) {
			return;
		}
		$id = isset( $_REQUEST['contractor'] ) ? $_REQUEST['contractor'] : 0;
		echo '<select name="contractor">';
		printf( '<option value="">%s</option>', esc_html__( 'All contractors', 'build_a_house' ) );
		foreach ( $data['items'] as $one ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $one['id'] ),
				selected( $one['id'], $id ),
				esc_html( $one['full_name'] )
			);
		}
		echo '</select>';
	}
}

