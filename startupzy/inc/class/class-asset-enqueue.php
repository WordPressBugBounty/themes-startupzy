<?php
/**
 * Block Pattern Class
 *
 * @author Jegstudio
 * @package startupzy
 */
namespace Startupzy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Class
 *
 * @package startupzy
 */
class Asset_Enqueue {
	/**
	 * Class constructor.
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
	}

    /**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'startupzy-style', get_stylesheet_uri(), array(), STARTUPZY_VERSION );

				wp_enqueue_style( 'startupzy-custom-styling', trailingslashit( get_template_directory_uri() ) . '/assets/css/startupzy-custom-styling.css', array(), STARTUPZY_VERSION );
		wp_enqueue_script( 'startupzy-animation-script', trailingslashit( get_template_directory_uri() ) . '/assets/js/startupzy-animation-script.js', array(), STARTUPZY_VERSION, true );
		wp_enqueue_style( 'startupzy-presset', trailingslashit( get_template_directory_uri() ) . '/assets/css/startupzy-presset.css', array(), STARTUPZY_VERSION );


        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
    }

	/**
	 * Enqueue admin scripts and styles.
	 */
	public function admin_scripts() {
		
    }
}
