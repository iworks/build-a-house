<?php
/*
 * Plugin Name:       Build a House
 * Plugin URI:        http://iworks.pl/build-a-house/
 * Description:       PLUGIN_DESCRIPTION
 * Requires at least: PLUGIN_REQUIRES_WORDPRESS
 * Requires PHP:      PLUGIN_REQUIRES_PHP
 * Version:           PLUGIN_VERSION
 * Author:            AUTHOR_NAME
 * Author URI:        AUTHOR_URI
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       build-a-house
 * Domain Path:       /languages
 *

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

/**
 * static options
 */
define( 'IWORKS_BUILD_A_HOUSE_VERSION', 'PLUGIN_VERSION' );
define( 'IWORKS_BUILD_A_HOUSE_PREFIX', 'iworks_build_a_house_' );
$base     = dirname( __FILE__ );
$includes = $base . '/includes';

/**
 * require: Iworksbuild-a-house Class
 */
if ( ! class_exists( 'iworks_build_a_house' ) ) {
	require_once $includes . '/iworks/build-a-house.php';
}
/**
 * configuration
 */
require_once $base . '/etc/options.php';
/**
 * require: IworksOptions Class
 */
if ( ! class_exists( 'iworks_options' ) ) {
	require_once $includes . '/iworks/options/options.php';
}

/**
 * load options
 */

global $iworks_build_a_house_options;
$iworks_build_a_house_options = iworks_build_a_house_get_options_object();

function iworks_build_a_house_get_options_object() {
	global $iworks_build_a_house_options;
	if ( is_object( $iworks_build_a_house_options ) ) {
		return $iworks_build_a_house_options;
	}
	$iworks_build_a_house_options = new iworks_options();
	$iworks_build_a_house_options->set_option_function_name( 'iworks_build_a_house_options' );
	$iworks_build_a_house_options->set_option_prefix( IWORKS_BUILD_A_HOUSE_PREFIX );
	if ( method_exists( $iworks_build_a_house_options, 'set_plugin' ) ) {
		$iworks_build_a_house_options->set_plugin( basename( __FILE__ ) );
	}
	return $iworks_build_a_house_options;
}

function iworks_build_a_house_options_init() {
	global $iworks_build_a_house_options;
	$iworks_build_a_house_options->options_init();
}

function iworks_build_a_house_activate() {
	$iworks_build_a_house_options = new iworks_options();
	$iworks_build_a_house_options->set_option_function_name( 'iworks_build_a_house_options' );
	$iworks_build_a_house_options->set_option_prefix( IWORKS_BUILD_A_HOUSE_PREFIX );
	$iworks_build_a_house_options->activate();
	/**
	 * install tables
	 */
	$iworks_build_a_house = new iworks_build_a_house;
	$iworks_build_a_house->db_install();
}

function iworks_build_a_house_deactivate() {
	global $iworks_build_a_house_options;
	$iworks_build_a_house_options->deactivate();
}

global $iworks_build_a_house;
$iworks_build_a_house = new iworks_build_a_house();

/**
 * install & uninstall
 */
register_activation_hook( __FILE__, 'iworks_build_a_house_activate' );
register_deactivation_hook( __FILE__, 'iworks_build_a_house_deactivate' );
/**
 * Ask for vote
 */
include_once $includes . '/iworks/rate/rate.php';
do_action(
	'iworks-register-plugin',
	plugin_basename( __FILE__ ),
	__( 'Build a House', 'build-a-house' ),
	'build-a-house'
);
