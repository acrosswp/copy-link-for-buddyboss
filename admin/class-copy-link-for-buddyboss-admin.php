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
	}


	/**
	 * Add setting for Copy Comment
	 */
	public function register_fields( $bp_admin_settings_activity ) {

		$bp_admin_settings_activity->add_section( 
			Copy_Link_For_BuddyBoss::instance()->settings_key_id, 
			__( 'Copy link for BuddyBoss', 'copy-link-for-buddyboss' ) 
		);

		// Allow scopes/tabs.
		$type['class'] = 'child-no-padding-first';
		$bp_admin_settings_activity->add_field( 
			'_copy_link_for_buddyboss_activity_access_control',
			__( 'Copy link setting for Activity', 'copy-link-for-buddyboss' ),
			array( $this, 'activity_access_control_setting_callback' ),
			'string',
			$type
		);

		// Allow scopes/tabs.
		$type['class'] = 'child-no-padding-first';
		$bp_admin_settings_activity->add_field( 
			'_copy_link_for_buddyboss_comment_access_control',
			__( 'Copy link setting for Comments', 'copy-link-for-buddyboss' ),
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
		
		/**
		 * Check if platform pro plugin is activated or not
		 */
		if ( Copy_Link_For_BuddyBoss::instance()->is_buddyboss_platform_pro_active() ) {
			bb_platform_pro()->access_control->bb_admin_print_access_control_setting( 
				Copy_Link_For_BuddyBoss::instance()->activity_key_id,
				Copy_Link_For_BuddyBoss::instance()->activity_key_id,
				'', 
				__( 'Select which members should have access to copy activity posts link, based on:', 'copy-link-for-buddyboss' ),
				Copy_Link_For_BuddyBoss::instance()->access_control_settings( Copy_Link_For_BuddyBoss::instance()->activity_key_id ), 
				false, 
				''
			);
		} else { 
			$this->show_message_for_platform_pro_plugin();
		}
	}

	/**
	 * Empty Callback function for the display notices only.
	 *
	 * @since 1.1.0
	 */
	public function comment_access_control_setting_callback() {

		/**
		 * Check if platform pro plugin is activated or not
		 */
		if ( Copy_Link_For_BuddyBoss::instance()->is_buddyboss_platform_pro_active() ) {
			
			bb_platform_pro()->access_control->bb_admin_print_access_control_setting( 
				Copy_Link_For_BuddyBoss::instance()->comment_key_id, 
				Copy_Link_For_BuddyBoss::instance()->comment_key_id,
				'', 
				__( 'Select which members should have access to copy comment posts link, based on:', 'copy-link-for-buddyboss' ),
				Copy_Link_For_BuddyBoss::instance()->access_control_settings( Copy_Link_For_BuddyBoss::instance()->comment_key_id ), 
				false, 
				''
			);

		} else { 
			$this->show_message_for_platform_pro_plugin();
		}
	}

	/**
	 * Show the message if the BB platform pro plugin is not activated
	 */
	public function show_message_for_platform_pro_plugin() {
		?>
		<p class="description"><?php esc_html_e( 'Please install and activate the BuddyBoss Platform Pro plugin to manage the Copy link button via Acess Control.', 'copy-link-for-buddyboss' ); ?></p>
		<?php
	}

	/**
	 * Add Settings link to plugins area.
	 *
	 * @since    1.0.0
	 *
	 * @param array  $links Links array in which we would prepend our link.
	 * @param string $file  Current plugin basename.
	 * @return array Processed links.
	 */
	public function plugin_action_links( $links, $file ) {

		// Return normal links if not BuddyPress.
		if ( COPY_LINK_FOR_BUDDYBOSS_PLUGIN_BASENAME !== $file ) {
			return $links;
		}

		$settings_link = bp_get_admin_url( add_query_arg( array( 'page' => 'bp-settings', 'tab' => 'bp-activity' ), 'admin.php' ) );

		// Add a few links to the existing links array.
		return array_merge(
			$links,
			array(
				'settings'	=> sprintf( '<a href="%s#%s">%s</a>', $settings_link, Copy_Link_For_BuddyBoss::instance()->settings_key_id, esc_html__( 'Settings', 'copy-link-for-buddyboss' ) ),
			)
		);
	}
}
