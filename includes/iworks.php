<?php

/**
 * Copyright 2016-PLUGIN_TILL_YEAR Marcin Pietrzak (marcin@iworks.pl)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'iworks' ) ) {
	return;
}
/**
 * Main class.
 *
 * @package iworks
 * @author  Marcin Pietrzak <marcin@iworks.pl>
 * @since   1.0.0
 *
 * @version 1.0.0
 * @license GPL-3.0+
 */
class iworks {

	/**
	 * Development mode.
	 *
	 * @var bool
	 */
	protected $dev;
	/**
	 * Meta prefix.
	 *
	 * @var string
	 */
	protected $meta_prefix = '_';
	/**
	 * Base directory.
	 *
	 * @var string
	 */
	protected $base;
	/**
	 * Directory.
	 *
	 * @var string
	 */
	protected $dir;
	/**
	 * Version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * static settings
		 */
		$this->dev  = ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE ) ? '' : '.min';
		$this->base = __DIR__;
		$this->dir  = basename( dirname( $this->base ) );
	}

	/**
	 * Get version.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file File.
	 *
	 * @return string
	 */
	public function get_version( $file = null ) {
		if ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE ) {
			if ( null != $file ) {
				$file = dirname( $this->base ) . $file;
				if ( is_file( $file ) ) {
					return md5_file( $file );
				}
			}
			return rand( 0, PHP_INT_MAX );
		}
		return $this->version;
	}

	/**
	 * Get meta name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Name.
	 *
	 * @return string
	 */
	public function get_meta_name( $name ) {
		return sprintf( '%s_%s', $this->meta_prefix, sanitize_title( $name ) );
	}

	/**
	 * Get post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get this capability.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_this_capability() {
		return $this->capability;
	}

	/**
	 * Slug name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Name.
	 *
	 * @return string
	 */
	private function slug_name( $name ) {
		return preg_replace( '/[_ ]+/', '-', strtolower( __CLASS__ . '_' . $name ) );
	}

	/**
	 * Get post meta.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post_id Post ID.
	 * @param string $meta_key Meta key.
	 *
	 * @return mixed
	 */
	public function get_post_meta( $post_id, $meta_key ) {
		return get_post_meta( $post_id, $this->get_meta_name( $meta_key ), true );
	}

	/**
	 * Print table body.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post_id Post ID.
	 * @param array  $fields Fields.
	 */
	protected function print_table_body( $post_id, $fields ) {
		echo '<table class="widefat striped"><tbody>';
		foreach ( $fields as $name => $data ) {
			$key   = $this->get_meta_name( $name );
			$value = $this->get_post_meta( $post_id, $name );
			/**
			 * extra
			 */
			$extra = isset( $data['placeholder'] ) ? sprintf( ' placeholder="%s" ', esc_attr( $data['placeholder'] ) ) : '';
			foreach ( array( 'placeholder', 'style', 'class', 'id' ) as $extra_key ) {
				if ( isset( $data[ $extra_key ] ) ) {
					$extra .= sprintf( ' min="%d" ', esc_attr( $data[ $extra_key ] ) );
				}
			}
			/**
			 * start row
			 */
			echo '<tr>';
			printf( '<th scope="row" style="width: 130px">%s</th>', $data['title'] );
			echo '<td>';
			switch ( $data['type'] ) {
				case 'number':
					foreach ( array( 'min', 'max', 'step' ) as $extra_key ) {
						if ( isset( $data[ $extra_key ] ) ) {
							$extra .= sprintf( ' min="%d" ', intval( $data[ $extra_key ] ) );
						}
					}
					printf(
						'<input type="number" name="%s" value="%d" %s />',
						esc_attr( $key ),
						intval( $value ),
						$extra
					);
					break;
				case 'date':
					$date = intval( $this->get_post_meta( $post_id, $name ) );
					if ( empty( $date ) ) {
						$date = strtotime( 'now' );
					}
					printf(
						'<input type="text" class="datepicker" name="%s" value="%s" />',
						$this->get_meta_name( $name ),
						$date
					);
					break;
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}

	/**
	 * Get module file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filename Filename.
	 * @param string $vendor Vendor.
	 *
	 * @return string
	 */
	protected function get_module_file( $filename, $vendor = 'iworks' ) {
		return realpath(
			sprintf(
				'%s/%s/%s/%s.php',
				$this->base,
				$vendor,
				$this->dir,
				$filename
			)
		);
	}

	/**
	 * HTML title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Text.
	 */
	protected function html_title( $text ) {
		printf( '<h1 class="wp-heading-inline">%s</h1>', esc_html( $text ) );
	}
}
