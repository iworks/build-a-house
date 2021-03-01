<?php
function iworks_build_a_house_options() {
	$iworks_build_a_house_options = array();
	/**
	 * main settings
	 */
	$parent                                = add_query_arg( 'post_type', 'ibh_expence', 'edit.php' );
	$iworks_build_a_house_options['index'] = array(
		'version'    => '0.0',
		'use_tabs'   => true,
		'page_title' => __( 'Configuration', 'build-a-house' ),
		'menu'       => 'submenu',
		'parent'     => $parent,
		'options'    => array(
			array(
				'type'  => 'heading',
				'label' => __( 'General', 'build-a-house' ),
			),
			array(
				'name'              => 'wide_class',
				'type'              => 'checkbox',
				'th'                => __( 'Add wide body class', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'load_frontend_css',
				'type'              => 'checkbox',
				'th'                => __( 'Load frontend CSS', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			/**
			 * Results
			 */
			array(
				'type'  => 'heading',
				'label' => __( 'Results', 'build-a-house' ),
			),
			array(
				'name'              => 'results_show_points',
				'type'              => 'checkbox',
				'th'                => __( 'Show points', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'results_show_trophy',
				'type'              => 'checkbox',
				'th'                => __( 'Show trophy', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'results_show_country',
				'type'              => 'checkbox',
				'th'                => __( 'Show Country', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'result_show_download_link',
				'type'              => 'checkbox',
				'th'                => __( 'Allow download CSV', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'result_show_english_title',
				'type'              => 'checkbox',
				'th'                => __( 'Show English', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
				'description'       => __( 'Allow to show English title on a list.', 'build-a-house' ),
			),
			/**
			 * Persons
			 */
			array(
				'type'  => 'heading',
				'label' => __( 'Persons', 'build-a-house' ),
			),
			array(
				'name'              => 'person_show_social_media',
				'type'              => 'checkbox',
				'th'                => __( 'Show social media links', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_show_boats_table',
				'type'              => 'checkbox',
				'th'                => __( 'Show boats table', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_show_boats_owned_table',
				'type'              => 'checkbox',
				'th'                => __( 'Show boat owned on person details page', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_tag_to_person',
				'type'              => 'checkbox',
				'th'                => __( 'Tag to person', 'build-a-house' ),
				'description'       => __( 'Replace person tag by build_a_house person.', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_show_articles_with_person_tag',
				'type'              => 'checkbox',
				'th'                => __( 'Add posts list', 'build-a-house' ),
				'description'       => __( 'Add posts list to person with matching tag.', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_show_flag_on_single',
				'type'              => 'checkbox',
				'th'                => __( 'Show flag', 'build-a-house' ),
				'description'       => __( 'Show country flag before person on single person page.', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_show_trophy',
				'type'              => 'checkbox',
				'th'                => __( 'Show trophy', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'person_show_download_link',
				'type'              => 'checkbox',
				'th'                => __( 'Allow download CSV', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			/**
			 * Boats
			 */
			array(
				'type'  => 'heading',
				'label' => __( 'Boats', 'build-a-house' ),
			),
			array(
				'name'              => 'boat_show_flag',
				'type'              => 'checkbox',
				'th'                => __( 'Show boat flag', 'build-a-house' ),
				'default'           => 0,
				'description'       => __( 'We recommend to turn off if we show sailors nationality.', 'build-a-house' ),
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_add_crew_manually',
				'type'              => 'checkbox',
				'th'                => __( 'Add crew manually', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_add_extra_data',
				'type'              => 'checkbox',
				'th'                => __( 'Add extra data', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_add_social_media',
				'type'              => 'checkbox',
				'th'                => __( 'Add boat social media', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_add_owners',
				'type'              => 'checkbox',
				'th'                => __( 'Add boat owners', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_show_owners',
				'type'              => 'checkbox',
				'th'                => __( 'Show boat owners on boat details page', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'    => 'boat_taxonomies',
				'type'    => 'checkbox_group',
				'th'      => __( 'Boat taxonomies', 'build-a-house' ),
				'options' => array(
					'sail' => __( 'Sail manufacturer', 'build-a-house' ),
					'mast' => __( 'Mast manufacturer', 'build-a-house' ),
					'hull' => __( 'Hull manufacturer', 'build-a-house' ),
				),
			),
			array(
				'name'              => 'boat_auto_add_feature_image',
				'type'              => 'checkbox',
				'th'                => __( 'Auto add feature image', 'build-a-house' ),
				'description'       => __( 'Automagicaly add feature image, if there is some taged with boat number.', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_show_trophy',
				'type'              => 'checkbox',
				'th'                => __( 'Show trophy', 'build-a-house' ),
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
			array(
				'name'              => 'boat_show_download_link',
				'type'              => 'checkbox',
				'th'                => __( 'Allow download CSV', 'build-a-house' ),
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'classes'           => array( 'switch-button' ),
			),
		),
		//      'metaboxes' => array(),
		'pages'      => array(),
	);

	return $iworks_build_a_house_options;
}





