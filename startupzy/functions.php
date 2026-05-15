<?php
/**
 * Theme Functions
 *
 * @author Jegstudio
 * @package startupzy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

defined( 'STARTUPZY_VERSION' ) || define( 'STARTUPZY_VERSION', '1.2.2' );
defined( 'STARTUPZY_DIR' ) || define( 'STARTUPZY_DIR', trailingslashit( get_template_directory() ) );

defined( 'GUTENVERSE_COMPANION_REQUIRED_VERSION' ) || define( 'GUTENVERSE_COMPANION_REQUIRED_VERSION', '2.3.3' );
defined( 'GUTENVERSE_LIBRARY_SERVER' ) || define( 'GUTENVERSE_LIBRARY_SERVER', 'https://gutenverse.com' );

require get_parent_theme_file_path( 'inc/autoload.php' );

Startupzy\Init::instance();
