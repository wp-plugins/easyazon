<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_SETTINGS_SECTION_UPGRADE')) {
	define('EASYAZON_SETTINGS_SECTION_UPGRADE', 'upgrade');
}

class EasyAzon_Components_SettingsSections_Upgrade {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'), 1001);
			add_action('easyazon_load_settings_page', array(__CLASS__, 'load_settings_page'));
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

	#region Settings Section

	public static function add_settings_section_and_fields($page) {
		add_settings_section(EASYAZON_SETTINGS_SECTION_UPGRADE, __('How to Earn More from Amazon'), array(__CLASS__, 'display_settings_section'), $page);
	}

	public static function display_settings_section() {
		do_action('easyazon_settings_section_before_' . EASYAZON_SETTINGS_SECTION_UPGRADE);

		include('views/section.php');

		do_action('easyazon_settings_section_after_' . EASYAZON_SETTINGS_SECTION_UPGRADE);
	}

	#endregion Settings Section

	#region Settings Page

	public static function load_settings_page() {
		wp_enqueue_script('easyazon-settings-upgrade', plugins_url('resources/upgrade.js', __FILE__), array('jquery'), EASYAZON_VERSION, true);
		wp_localize_script('easyazon-settings-upgrade', 'EasyAzon_Settings_Upgrade', array(
			'day'    => __('day'),
			'days'   => __('days'),
			'month'  => __('month'),
			'months' => __('months'),
		));
	}

	#endregion Settings Page
}

EasyAzon_Components_SettingsSections_Upgrade::init();
