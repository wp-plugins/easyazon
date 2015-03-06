<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_ProCheck {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('admin_init', array(__CLASS__, 'check'));

		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Check on Initialization

	public static function check() {
		$plugins = get_plugins();
		foreach($plugins as $key => $plugin) {
			if(false !== strpos($plugin['Name'], 'EasyAzon') && false !== strpos($plugin['Name'], 'Pro') && is_plugin_active($key) && version_compare($plugin['Version'], '4.0', 'lt')) {
				add_action('admin_notices', array(__CLASS__, 'notice'));

				deactivate_plugins($key, false);
			}
		}
	}

	#endregion Check on Initialization

	#region Notices

	public static function notice() {
		printf('<div id="easyazon-check-pro-notice" class="error"><p>%s</p></div>', __('Your current version of EasyAzon Pro is out-of-date and has been deactivated. Please upgrade to <a href="http://easyazon.com/v4upgrade/" target="_blank">Version 4 of EasyAzon Pro</a> or <a href="https://downloads.wordpress.org/plugin/easyazon.3.0.8.zip" target="_blank">download Version 3 of EasyAzon Core</a> to keep using Version 3 of EasyAzon Pro.'));
	}

	#endregion Notices
}

EasyAzon_Components_ProCheck::init();
