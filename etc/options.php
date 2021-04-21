<?php
function iworks_build_a_house_options() {
	$iworks_build_a_house_options = array();
	/**
	 * main settings
	 * /
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
				'name'              => 'street',
				'th'                => __( 'Street', 'build-a-house' ),
			),
			array(
				'name'              => 'zip',
				'th'                => __( 'Zip code', 'build-a-house' ),
			),
			array(
				'name'              => 'city',
				'th'                => __( 'City', 'build-a-house' ),
			),
		),
		//      'metaboxes' => array(),
		'pages'      => array(),
	);
	 */

	return $iworks_build_a_house_options;
}

