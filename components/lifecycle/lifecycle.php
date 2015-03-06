<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_VERSION_NAME')) {
 	define('EASYAZON_VERSION_NAME', 'easyazon-version');
}

class EasyAzon_Components_Lifecycle {
	public static function init() {
		self::_add_actions();
		self::_add_filters();

		register_activation_hook(EASYAZON_PLUGIN_FILE, array(__CLASS__, 'activation'));

		register_deactivation_hook(EASYAZON_PLUGIN_FILE, array(__CLASS__, 'deactivation'));
	}

	private static function _add_actions() {
		add_action('init', array(__CLASS__, 'upgrade'), 1);
	}

	private static function _add_filters() {

	}

	#region Activation

	public static function activation() {
		easyazon_debug(__METHOD__);
	}

	#endregion Activation

	#region Deactivation

	public static function deactivation() {
		easyazon_debug(__METHOD__);
	}

	#endregion Deactivation

	#region Upgrade

	public static function upgrade() {
		$version_current = EASYAZON_VERSION;
		$version_stored  = get_option(EASYAZON_VERSION_NAME, '0.0.0');

		if($version_current !== $version_stored) {
			switch($version_stored) {
				case '0.0.0':

				default:
					self::_delete_outdated_data();
					break;
			}

			update_option(EASYAZON_VERSION_NAME, $version_current);
		}
	}

	#endregion Upgrade

	#region Data Management

	public static function _delete_outdated_data() {
		global $wpdb;

		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", '_eapasin_%'));
	}

	#endregion Data Management
}

EasyAzon_Components_Lifecycle::init();
