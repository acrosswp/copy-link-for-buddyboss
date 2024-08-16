<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/WPBoilerplate/copy-link-for-buddyboss
 * @since      1.0.0
 *
 * @package    Copy_Link_For_BuddyBoss
 * @subpackage Copy_Link_For_BuddyBoss/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Copy_Link_For_BuddyBoss
 * @subpackage Copy_Link_For_BuddyBoss/admin
 * @author     WPBoilerplate <contact@wpboilerplate.com>
 */
class Copy_Link_For_BuddyBoss_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The js_asset_file of the backend
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $js_asset_file;

	/**
	 * The css_asset_file of the backend
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $css_asset_file;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->js_asset_file = include( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'build/js/backend.asset.php' );
		$this->css_asset_file = include( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_PATH . 'build/css/backend.asset.php' );

		$this->activity_key_id = '_copy_link_for_buddyboss_activity_access_control';
		$this->comment_key_id = '_copy_link_for_buddyboss_comment_access_control';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Copy_Link_For_BuddyBoss_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Copy_Link_For_BuddyBoss_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, COPY_LINK_FOR_BUDDYBOSS_PLUGIN_URL . 'build/css/backend.css', $this->css_asset_file['dependencies'], $this->css_asset_file['version'], 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Copy_Link_For_BuddyBoss_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Copy_Link_For_BuddyBoss_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, COPY_LINK_FOR_BUDDYBOSS_PLUGIN_URL . 'build/js/backend.js', $this->js_asset_file['dependencies'], $this->js_asset_file['version'], false );

	}

	/**
	 * Add setting for Copy Comment
	 */
	public function register_fields( $bp_admin_settings_activity ) {

		$bp_admin_settings_activity->add_section( 
			'copy_link_for_buddyboss_setttings', 
			__( 'Copy Link', 'copy-link-for-buddyboss' ) 
		);

		// Allow scopes/tabs.
		$type['class'] = 'child-no-padding-first';
		$bp_admin_settings_activity->add_field( 
			'_copy_link_for_buddyboss_activity_access_control',
			__( 'Copy Link', 'copy-link-for-buddyboss' ),
			array( $this, 'activity_access_control_setting_callback' ),
			'string',
			$type
		);

		// Allow scopes/tabs.
		$type['class'] = 'child-no-padding-first';
		$bp_admin_settings_activity->add_field( 
			'_copy_link_for_buddyboss_comment_access_control',
			__( 'Copy Link', 'copy-link-for-buddyboss' ),
			array( $this, 'comment_access_control_setting_callback' ),
			'string',
			$type
		);
	}

	/**
	 * Empty Callback function for the display notices only.
	 *
	 * @since 1.1.0
	 */
	public function activity_access_control_setting_callback() {
		
		bb_platform_pro()->access_control->bb_admin_print_access_control_setting( 
			$this->activity_key_id, 
			$this->activity_key_id, 
			'', 
			__( 'Select which members should have access to copy activity and comment posts link, based on:', 'copy-link-for-buddyboss' ),
			$this->access_control_settings( $this->activity_key_id ), 
			false, 
			''
		);
	}

	/**
	 * Empty Callback function for the display notices only.
	 *
	 * @since 1.1.0
	 */
	public function comment_access_control_setting_callback() {
		
		bb_platform_pro()->access_control->bb_admin_print_access_control_setting( 
			$this->comment_key_id, 
			$this->comment_key_id,
			'', 
			__( 'Select which members should have access to copy comment posts link, based on:', 'copy-link-for-buddyboss' ),
			$this->access_control_settings( $this->comment_key_id ), 
			false, 
			''
		);
	}

	/**
	 * Function will return the create activity field settings data.
	 *
	 * @since 1.1.0
	 *
	 * @return array upload document settings data.
	 */
	public function access_control_settings( $key_id ) {
		return bp_get_option( $key_id );
	}
}
