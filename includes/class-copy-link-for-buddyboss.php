<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/WPBoilerplate/copy-link-for-buddyboss
 * @since      1.0.0
 *
 * @package    Copy_Link_For_BuddyBoss
 * @subpackage Copy_Link_For_BuddyBoss/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Copy_Link_For_BuddyBoss
 * @subpackage Copy_Link_For_BuddyBoss/includes
 * @author     WPBoilerplate <contact@wpboilerplate.com>
 */
final class Copy_Link_For_BuddyBoss {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Copy_Link_For_BuddyBoss
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Copy_Link_For_BuddyBoss_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The plugin dir path
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_path    The string for plugin dir path
	 */
	protected $plugin_path;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The unique identifier of activity key id.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify activity key id.
	 */
	public $activity_key_id;

	/**
	 * The unique identifier of comment key id.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify comment key id.
	 */
	public $comment_key_id;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'copy-link-for-buddyboss';

		$this->settings_key_id = 'copy_link_for_buddyboss_setttings';
		$this->activity_key_id = '_copy_link_for_buddyboss_activity_access_control';
		$this->comment_key_id = '_copy_link_for_buddyboss_comment_access_control';

		$this->define_constants();

		if ( defined( 'COPY_LINK_FOR_BUDDYBOSS_VERSION' ) ) {
			$this->version = COPY_LINK_FOR_BUDDYBOSS_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->load_composer_dependencies();

		$this->load_dependencies();

		$this->set_locale();

		$this->load_hooks();

	}

	/**
	 * Main Copy_Link_For_BuddyBoss Instance.
	 *
	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Copy_Link_For_BuddyBoss()
	 * @return Copy_Link_For_BuddyBoss - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Define WCE Constants
	 */
	private function define_constants() {

		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_FILE', COPY_LINK_FOR_BUDDYBOSS_FILES );
		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_BASENAME', plugin_basename( COPY_LINK_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH', plugin_dir_path( COPY_LINK_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_URL', plugin_dir_url( COPY_LINK_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_NAME_SLUG', $this->plugin_name );
		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_NAME', 'Copy Link For BuddyBoss' );

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_FILE );
		$version = $plugin_data['Version'];
		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_VERSION', $version );

		$this->define( 'COPY_LINK_FOR_BUDDYBOSS_PLUGIN_URL', $version );

		$this->plugin_dir = COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH;
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Register all the hook once all the active plugins are loaded
	 *
	 * Uses the plugins_loaded to load all the hooks and filters
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function load_hooks() {

		/**
		 * Check if plugin can be loaded safely or not
		 * 
		 * @since    1.0.0
		 */
		if ( apply_filters( 'copy-link-for-buddyboss-load', true ) ) {
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

	}

	/**
	 * Load the required composer dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_composer_dependencies() {

		/**
		 * Add composer file
		 */
		if ( file_exists( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
			require_once( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'vendor/autoload.php' );
		}

		/**
		 * For Plugin to check if BuddyBoss Platform plugin is active or not
		 */
		if ( class_exists( 'WPBoilerplate_BuddyBoss_Platform_Dependency' ) ) {
			new WPBoilerplate_BuddyBoss_Platform_Dependency( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_NAME_SLUG, COPY_LINK_FOR_BUDDYBOSS_PLUGIN_FILE, array( 'activity' ) );
		}

		/**
		 * For Plugin Update via Github
		 */
		if ( class_exists( 'WPBoilerplate_Updater_Checker_Github' ) ) {

			$package = array(
				'repo' 		        => 'https://github.com/acrosswp/copy-link-for-buddyboss',
				'file_path' 		=> COPY_LINK_FOR_BUDDYBOSS_PLUGIN_FILE,
				'name_slug'			=> COPY_LINK_FOR_BUDDYBOSS_PLUGIN_NAME_SLUG,
				'release_branch' 	=> 'main',
				'release-assets' 	=> false
			);

			new WPBoilerplate_Updater_Checker_Github( $package );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Copy_Link_For_BuddyBoss_Loader. Orchestrates the hooks of the plugin.
	 * - Copy_Link_For_BuddyBoss_i18n. Defines internationalization functionality.
	 * - Copy_Link_For_BuddyBoss_Admin. Defines all hooks for the admin area.
	 * - Copy_Link_For_BuddyBoss_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Add composer file
		 */
		if ( file_exists( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
			require_once( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'vendor/autoload.php' );
		}

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/class-copy-link-for-buddyboss-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/class-copy-link-for-buddyboss-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'admin/class-copy-link-for-buddyboss-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'public/class-copy-link-for-buddyboss-public.php';

		$this->loader = Copy_Link_For_BuddyBoss_Loader::instance();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Copy_Link_For_BuddyBoss_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Copy_Link_For_BuddyBoss_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Copy_Link_For_BuddyBoss_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'bp_admin_setting_activity_register_fields', $plugin_admin, 'register_fields', 2 );

		$this->loader->add_action( 'plugin_action_links', $plugin_admin, 'plugin_action_links', 1000, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Copy_Link_For_BuddyBoss_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'bb_nouveau_get_activity_entry_bubble_buttons', $plugin_public, 'activity_buttons', 10, 2 );

		$this->loader->add_action( 'bb_nouveau_get_activity_comment_bubble_buttons', $plugin_public, 'comment_buttons',100, 3 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Copy_Link_For_BuddyBoss_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Function will return the create activity field settings data.
	 *
	 * @since 1.1.0
	 *
	 * @return array upload document settings data.
	 */
	public function access_control_settings( $key_id ) {

		$default = array(
			'access-control-type'           => '',
			'plugin-access-control-type'    => '',
			'gamipress-access-control-type' => '',
			'access-control-options'        => array(),
		);

		return bp_get_option( $key_id, $default );
	}

	/**
	 * Function will check if user can create a activity or not.
	 *
	 * @param boolean $create whether user can create a activity or not.
	 *
	 * @since 1.1.0
	 *
	 * @return boolean whether user can create a activity or not.
	 */
	function is_access_control( $key_id, $create = true ) {

		/**
		 * Check if platform pro plugin is activated or not
		 */
		if ( $this->is_buddyboss_platform_pro_active() ) {
			return $create;
		}

		$create_activity_settings = $this->access_control_settings( $key_id );
		$has_access               = false;
		if ( empty( $create_activity_settings ) || ( isset( $create_activity_settings['access-control-type'] ) && empty( $create_activity_settings['access-control-type'] ) ) ) {
			$has_access = $create;
		} elseif ( is_array( $create_activity_settings ) && isset( $create_activity_settings['access-control-type'] ) && ! empty( $create_activity_settings['access-control-type'] ) ) {
			$access_controls        = BB_Access_Control::bb_get_access_control_lists();
			$option_access_controls = $create_activity_settings['access-control-type'];
			$can_accept             = bb_access_control_has_access( bp_loggedin_user_id(), $access_controls, $option_access_controls, $create_activity_settings );

			if ( $can_accept ) {
				$has_access = $create;
			}
		}

		return $has_access;
	}

	/**
	 * Check if the BB platform pro plugin is activated
	 */
	function is_buddyboss_platform_pro_active() {

		if ( ! class_exists( 'BB_Platform_Pro' ) ) {
			return false;
		}

		return true;
	}

}
