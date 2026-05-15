<?php
/**
 * Init Configuration
 *
 * @author Jegstudio
 * @package startupzy
 */

namespace Startupzy;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Notice Class
 *
 * @package startupzy
 */
class Plugin_Notice {

	/**
	 * Instance variable
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Class instance.
	 *
	 * @return Init
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->load_hooks();
	}

	/**
	 * Load initial hooks.
	 */
	private function load_hooks() {
		add_action( 'admin_notices', array( $this, 'notice_install_plugin' ) );
		add_action( 'wp_ajax_startupzy_dismiss_notice', array( $this, 'dismiss_notice' ) );
	}

	/**
	 * Show notification to install Gutenverse Plugin.
	 */
	public function notice_install_plugin() {
		// Skip if gutenverse block activated.
		if ( get_option( 'startupzy_dismiss_notice' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'themes.php' === $screen->parent_file && 'appearance_page_startupzy-dashboard' === $screen->id ) {
			return;
		}

		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		if ( 'true' === get_user_meta( get_current_user_id(), 'gutenverse_install_notice', true ) ) {
			return;
		}

        $active_plugins = get_option( 'active_plugins' );
		$plugins        = array();
		foreach ( $active_plugins as $active ) {
			$plugins[] = explode( '/', $active )[0];
		}
		$all_plugin          = get_plugins();
		$plugins_required    = array(
            array(
					'slug'       		=> 'gutenverse',
					'title'      		=> esc_html__( 'Gutenverse', 'startupzy' ),
					'short_desc' 		=> esc_html__( 'Gutenverse is a WordPress Gutenberg blocks plugin, block editor addon, and Site Editor page builder for creating fast, responsive websites without code. It includes 57 WordPress blocks, 600+ templates, global styles, responsive controls, popup builder tools, and block theme support.', 'startupzy' ),
					'active'    		=> in_array( 'gutenverse', $plugins, true ),
					'installed'  		=> $this->is_installed( 'gutenverse' ),
					'req_version'    	=> '3.6.0',
					'installed_version' => isset( $installed_plugins['gutenverse/gutenverse.php']['Version'] ) ? $installed_plugins['gutenverse/gutenverse.php']['Version'] : '',
					'icons'      		=> array (
  '1x' => 'https://ps.w.org/gutenverse/assets/icon-128x128.gif?rev=3132408',
  '2x' => 'https://ps.w.org/gutenverse/assets/icon-256x256.gif?rev=3132408',
),
					'download_url'      => '',
				),
				array(
					'slug'       		=> 'gutenverse-companion',
					'title'      		=> esc_html__( 'Gutenverse Companion', 'startupzy' ),
					'short_desc' 		=> esc_html__( 'A companion plugin designed specifically to enhance and extend the functionality of Gutenverse base themes. This plugin integrates seamlessly with the base themes, providing additional features, customization options, and advanced tools to optimize the overall user experience and streamline the development process.', 'startupzy' ),
					'active'    		=> in_array( 'gutenverse-companion', $plugins, true ),
					'installed'  		=> $this->is_installed( 'gutenverse-companion' ),
					'req_version'    	=> '2.3.3',
					'installed_version' => isset( $installed_plugins['gutenverse-companion/gutenverse-companion.php']['Version'] ) ? $installed_plugins['gutenverse-companion/gutenverse-companion.php']['Version'] : '',
					'icons'      		=> array (
  '1x' => 'https://ps.w.org/gutenverse-companion/assets/icon-128x128.png?rev=3162415',
),
					'download_url'      => '',
				),
				array(
					'slug'       		=> 'gutenverse-form',
					'title'      		=> esc_html__( 'Gutenverse Form', 'startupzy' ),
					'short_desc' 		=> esc_html__( 'GUTENVERSE FORM – FORM BUILDER FOR GUTENBERG BLOCK EDITOR, MULTI-STEP FORMS, CONDITIONAL LOGIC, PAYMENT, CALCULATION, 15+ FREE USER-FRIENDLY FORM BLOCKS', 'startupzy' ),
					'active'    		=> in_array( 'gutenverse-form', $plugins, true ),
					'installed'  		=> $this->is_installed( 'gutenverse-form' ),
					'req_version'    	=> '2.6.1',
					'installed_version' => isset( $installed_plugins['gutenverse-form/gutenverse-form.php']['Version'] ) ? $installed_plugins['gutenverse-form/gutenverse-form.php']['Version'] : '',
					'icons'      		=> array (
  '1x' => 'https://ps.w.org/gutenverse-form/assets/icon-128x128.png?rev=3135966',
),
					'download_url'      => '',
				)
        );
		$actions             = array();
		$count_plugin_active = 0;
		foreach ( $plugins_required as $plugin ) {
			$slug   = $plugin['slug'];
			$path   = "$slug/$slug.php";
			$active = in_array( $path, $active_plugins, false );

			if ( isset( $all_plugin[ $path ] ) ) {
				if ( $active ) {
					$actions[ $slug ] = 'active';
					++$count_plugin_active;
				} else {
					$actions[ $slug ] = 'inactive';
				}
			} else {
				$actions[ $slug ] = '';
			}
		}

		$count_plugin_requiored = count( $plugins_required );
		if ( $count_plugin_active === $count_plugin_requiored ) {
			return;
		}

		wp_register_style( 'startupzy-theme-notice', false );
		wp_enqueue_style( 'startupzy-theme-notice' );
		ob_start();
		?>
		
				.startupzy-simple-notice {
				
					padding: 24px !important; 
				
					border-left: 4px solid #007cba !important;
				}
				.startupzy-simple-notice p:first-child {
				
					margin-bottom: 20px; 
					margin-top: 0;
				}
				.startupzy-simple-notice p:last-of-type {
				
					margin-top: 0; 
					margin-bottom: 0;
				}
				.startupzy-simple-notice .startupzy-notice-title {
					font-size: 17px;
					font-weight: 600; 
					display: block;
					margin-bottom: 6px;
				}
				.startupzy-simple-notice .startupzy-notice-description {
					font-size: 13px;
					display: block;
				
					margin-top: 0; 
					color: #3c434a;
					line-height: 1.4;
				}
			
				.startupzy-simple-notice .button-primary {
					padding: 4px 16px !important;
				}

			
				.startupzy-simple-notice .notice-dismiss {
					background: none !important;
					box-shadow: none !important;
					opacity: 1;
				}
				
				.startupzy-simple-notice .notice-dismiss:before {
					color: #a7aaad;
					padding: 0; 
				}
				
				.startupzy-simple-notice .notice-dismiss:hover:before,
				.startupzy-simple-notice .notice-dismiss:focus:before {
					color: #c90000;
				}
			
		<?php

		$custom_style = ob_get_clean();

		wp_add_inline_style( 'startupzy-theme-notice', $custom_style );
		wp_register_script(
			'startupzy-theme-notice',
			'',
			array( 'wp-api-fetch' ),
			'1.0.0',
			true
		);

		wp_enqueue_script( 'startupzy-theme-notice' );

		ob_start();
		
		?>
		
		// Retain the core installation/activation logic script
		var promises = [];
		var actions = <?php echo wp_json_encode( $actions ); ?>;
		let site_url = '<?php echo esc_url( admin_url() ); ?>';

		const STARTUPZYPluginUtils = {
			isVersionGreater(v1, v2) {
				const a = v1.split('.').map(Number);
				const b = v2.split('.').map(Number);
				const len = Math.max(a.length, b.length);

				for (let i = 0; i < len; i++) {
					const n1 = a[i] ?? 0;
					const n2 = b[i] ?? 0;
					if (n1 > n2) return true;
					if (n1 < n2) return false;
				}
				return false;
			}
		};

		function sequenceInstall(plugin, pluginsInstalled) {
			return new Promise((resolve, reject) => {
				if (!plugin) return resolve();

				const slug = plugin.slug;
				const path = `${slug}/${slug}`;
				const needUpdate = plugin.installed
					? STARTUPZYPluginUtils.isVersionGreater(plugin.req_version, pluginsInstalled[`${path}.php`].Version)
					: false;

				let request;

				if (needUpdate) {
					wp.apiFetch({
						path: `wp/v2/plugins/plugin?plugin=${path}`,
						method: 'PUT',
						data: { status: 'inactive' }
					})
						.then(() => {
							return wp.apiFetch({
								path: `wp/v2/plugins/plugin?plugin=${path}`,
								method: 'DELETE'
							});
						})
						.then(() => {
							return wp.apiFetch({
								path: 'wp/v2/plugins',
								method: 'POST',
								data: { slug, status: 'active' }
							});
						})
						.then(() => resolve())
						.catch((error) => {
							console.error(`Failed to update plugin ${slug}:`, error);
							resolve();
						});
				} else {
					switch (actions[slug]) {
						case 'active':
							return resolve();

						case 'inactive':
							request = wp.apiFetch({
								path: `wp/v2/plugins/plugin?plugin=${path}`,
								method: 'POST',
								data: { status: 'active' }
							});
							break;

						default:
							request = wp.apiFetch({
								path: 'wp/v2/plugins',
								method: 'POST',
								data: { slug, status: 'active' }
							});
							break;
					}

					request
						.then(() => resolve())
						.catch((error) => {
							console.error(`Failed to install/activate ${slug}:`, error);
							resolve();
						});
				}
			});
		}
		
		document.addEventListener('DOMContentLoaded', () => {
			const notice = document.querySelector('.notice.is-dismissible.startupzy-simple-notice');

			if (notice) {
				setTimeout(() => {
					const dismissBtn = notice.querySelector('.notice-dismiss');
					const nonce      = notice.getAttribute('data-nonce');

					if (dismissBtn) {
						dismissBtn.addEventListener('click', (e) => {
							e.preventDefault();
							jQuery.post(ajaxurl, {
								action: 'startupzy_dismiss_notice',
								_ajax_nonce: nonce
							});
						});
					}
				}, 100);
			}
			
			const button = document.getElementById('gutenverse-install-plugin');
			
			if (!button) return;

			button.addEventListener('click', function (e) {
				// Prevent navigation/default action immediately
				e.preventDefault(); 
				
				// Update button text to show loading/processing state
				button.innerHTML = `<?php esc_html_e( 'Installing...', 'startupzy' ); ?>`;
				button.classList.add('processing');

				const warningEl = document.querySelector('.installing-warning');
				if (warningEl) {
					warningEl.style.display = 'block';
				}

				const hasFinishClass = button.classList.contains('finished');

				if (!hasFinishClass) {
					const pluginsRequired = <?php echo wp_json_encode( $plugins_required ); ?>;

					const pluginsInstalled = <?php echo wp_json_encode( $all_plugin ); ?>;
					let sequence = Promise.resolve();

					pluginsRequired.forEach((plugin, index) => {
						sequence = sequence.then(() => {
							const statusEl = document.querySelector('.installing-status');
							if (statusEl) {
								statusEl.style.display = 'inline';
								statusEl.innerHTML = `${index + 1}/${pluginsRequired.length} Installing ${plugin.title}`;
							}
							return sequenceInstall(plugin, pluginsInstalled);
						});
					});

					sequence.then(() => {
						window.location.href = site_url + 'admin.php?page=gutenverse-onboarding-wizard';
		
					}).catch(() => {
						// Handle errors (optional: show error message)
						const statusEl = document.querySelector('.installing-status');
						if (statusEl) {
							statusEl.style.display = 'none';
						}
						const warningEl = document.querySelector('.installing-warning');
						if (warningEl) {
							warningEl.style.display = 'none';
						}
						button.innerHTML = `<?php esc_html_e( 'Install Failed, Try Again', 'startupzy' ); ?>`;
						button.classList.remove('processing');
					});
				}
			});
		});
		
		<?php
		$custom_script = ob_get_clean();
		wp_add_inline_script( 'startupzy-theme-notice', $custom_script );

		?>
		<div class="notice notice-info is-dismissible startupzy-simple-notice" data-nonce="<?php echo esc_attr( wp_create_nonce( "startupzy_dismiss" ) ); ?>">
				<p>
					<strong class="startupzy-notice-title"><?php esc_html_e( "Thank you For Installing Startupzy Theme", "startupzy" ); ?></strong>
					<span class="startupzy-notice-description">
						<?php esc_html_e( "Unlock the full potential of your website with the recommended plugins.", "startupzy" ); ?>
						<br/>
						<?php esc_html_e( "Activate it to explore exclusive extensions, ready-to-use demo templates, and powerful features that make building your site easier and more enjoyable.", "startupzy" ); ?>
					</span>
				</p>
				<p>
					<a href="#" class="button button-primary" id="gutenverse-install-plugin"><?php echo esc_html__( "Install Recommended Plugins", "startupzy" ); ?></a>
					<span class="installing-status" style="margin-left: 10px; font-size: 13px; color: #666; display: none;"></span>
				</p>
				<p class="installing-warning" style="font-size: 12px; color: #666; display: none; margin-top: 5px;">
					<i><?php esc_html_e( "Don\'t refresh the page when installing recommended plugins", "startupzy" ); ?></i>
				</p>
			</div>
		<?php
	}

	/**
	 * Dismiss Notice After closed.
	 */
	public function dismiss_notice() {
		check_ajax_referer( 'startupzy_dismiss' );

		if ( ! get_option( 'startupzy_dismiss_notice' ) ) {
			update_option( 'startupzy_dismiss_notice', true );
		}

		wp_send_json_success();
	}
    
    /**
	 * Check if plugin is installed.
	 *
	 * @param string $plugin_slug plugin slug.
	 * 
	 * @return boolean
	 */
	public function is_installed( $plugin_slug ) {
		$all_plugins = get_plugins();
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			$plugin_dir = dirname($plugin_file);

			if ($plugin_dir === $plugin_slug) {
				return true;
			}
		}

		return false;
	}
}
