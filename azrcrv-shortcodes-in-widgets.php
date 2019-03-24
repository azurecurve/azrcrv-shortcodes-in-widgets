<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Shortcodes in Widgets
 * Description: Allows shortcodes to be used in widgets
 * Version: 1.0.0
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets
 * Text Domain: shortcodes-in-widgets
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

/**
 * Setup actions and filters.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('admin_menu', 'azrcrv_siw_create_admin_menu');

// add filters
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');
add_filter('plugin_action_links', 'azrcrv_siw_add_plugin_action_link', 10, 2);

/**
 * Add Shortcodes in Widgets action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_siw_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=azrcrv-siw">'.esc_html__('Settings' ,'shortcodes-in-widgets').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add RSS Feed menu to plugin menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_siw_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Shortcodes in Widgets Settings", "shortcodes-in-widgets")
						,esc_html__("Shortcodes in Widgets", "shortcodes-in-widgets")
						,'manage_options'
						,'azrcrv-siw'
						,'azrcrv_siw_settings');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_siw_settings(){
	if (!current_user_can('manage_options')){
		$error = new WP_Error('not_found', esc_html__('You do not have sufficient permissions to access this page.' , 'azrcrv-rssf'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
	}
	?>
	
	<div id="azrcrv-siw-general" class="wrap">
		<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
		<p>
			<?php esc_html_e('This plugin allows shortcodes to be used in widgets.', ''); ?>
		</p>
		<p>
			azurecurve <?php esc_html_e('has a sister plugin to this one which allows shortcodes to be used in comments:', 'shortcodes-in-widgets'); ?>
			<ul class='azrcrv-plugin-index'>
				<li>
					<?php
					if (azrcrv_siw_is_plugin_active('azrcrv-shortcodes-in-comments/azrcrv-shortcodes-in-comments.php')){
						echo "<a href='admin.php?page=azrcrv-sic' class='azrcrv-plugin-index'>Shortcodes in Comments</a>";
					}else{
						echo "<a href='https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/' class='azrcrv-plugin-index'>Shortcodes in Comments</a>";
					}
					?>
				</li>
			</ul>
		</p>
	</div>
	<?php
}

/**
 * Check if function active (included due to standard function failing due to order of load).
 *
 * @since 1.0.0
 *
 */
function azrcrv_siw_is_plugin_active($plugin){
    return in_array($plugin, (array) get_option('active_plugins', array()));
}

?>