<?php

if(!defined('ABSPATH')) { exit; }

// Settings icon (for menu page)
if(!defined('EASYAZON_SETTINGS_ICON')) {
	define('EASYAZON_SETTINGS_ICON', 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjUwMHB4IiBoZWlnaHQ9IjUwMHB4IiB2aWV3Qm94PSIwIDAgNTAwIDUwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgNTAwIDUwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQoJPHBhdGggZmlsbD0iI0ZGRkZGRiIgc3Ryb2tlPSIjRkZGRkZGIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0yMTIuNjM3LDI1OC4zMzNINzEuNzc1VjM4OC45OGgxNTYuOTl2MzkuMjQ2SDI1VjY1Ljg1OWgxOTUuNzAxDQoJCXYzOS4yNDhINzEuNzc1djExNC41MTdoMTQwLjg2MVYyNTguMzMzeiIvPg0KCTxwYXRoIGZpbGw9IiNGRkZGRkYiIHN0cm9rZT0iI0ZGRkZGRiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNNDMxLjk4OCw0MjguMjI3bC0zLjc2My0zMi43OTVoLTEuNjEyDQoJCWMtMTQuNTE3LDIwLjQzLTQyLjQ3NSwzOC43MS03OS41NzEsMzguNzFjLTUyLjY4OCwwLTc5LjU3MS0zNy4wOTgtNzkuNTcxLTc0LjczMmMwLTYyLjkwNCw1NS45MTYtOTcuMzEzLDE1Ni40NTQtOTYuNzc1di01LjM3NQ0KCQljMC0yMS41MDYtNS45MTQtNjAuMjE2LTU5LjE0Mi02MC4yMTZjLTI0LjE5NCwwLTQ5LjQ2Miw3LjUyNy02Ny43NDIsMTkuMzU1bC0xMC43NTItMzEuMTgzDQoJCWMyMS41MDUtMTMuOTc5LDUyLjY4OS0yMy4xMTksODUuNDg0LTIzLjExOWM3OS41NjksMCw5OC45MjcsNTQuMzAyLDk4LjkyNywxMDYuNDUydjk3LjMxM2MwLDIyLjU4MiwxLjA3Myw0NC42MjUsNC4zLDYyLjM2Ng0KCQlINDMxLjk4OHogTTQyNC45OTksMjk1LjQzYy01MS42MTItMS4wNzMtMTEwLjIxNSw4LjA2NC0xMTAuMjE1LDU4LjYwNGMwLDMwLjY0NCwyMC40MzEsNDUuMTYyLDQ0LjYyNCw0NS4xNjINCgkJYzMzLjg2OSwwLDU1LjM3Ni0yMS41MDYsNjIuOTAzLTQzLjU1YzEuNjEzLTQuODM3LDIuNjg4LTEwLjIxNSwyLjY4OC0xNS4wNTRWMjk1LjQzeiIvPg0KPC9zdmc+');
}

// Settings name
if(!defined('EASYAZON_SETTINGS_NAME')) {
	define('EASYAZON_SETTINGS_NAME', 'easyazon-settings');
}

// Settings page slug
if(!defined('EASYAZON_SETTINGS_PAGE')) {
	define('EASYAZON_SETTINGS_PAGE', 'easyazon-settings');
}

class EasyAzon_Components_Settings {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('admin_init', array(__CLASS__, 'register_setting'));
			add_action('admin_menu', array(__CLASS__, 'add_admin_pages'));

			add_action('easyazon_add_admin_pages', array(__CLASS__, 'add_settings_page'));
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
		add_filter('easyazon_pre_get_settings', array(__CLASS__, 'pre_get_settings'));
	}

	#region Administrative Interface

	public static function add_admin_pages() {
		$easyazon = add_menu_page(__('EasyAzon'), __('EasyAzon'), 'manage_options', EASYAZON_SETTINGS_PAGE, array(__CLASS__, 'display_settings_page'), EASYAZON_SETTINGS_ICON, 10001);

		do_action('easyazon_add_admin_pages', EASYAZON_SETTINGS_PAGE);
	}

	public static function add_settings_page($parent) {
		$page = add_submenu_page($parent, __('EasyAzon - Settings'), __('Settings'), 'manage_options', $parent, array(__CLASS__, 'display_settings_page'));

		add_action("load-{$page}", array(__CLASS__, 'load_settings_page'));
	}

	public static function display_settings_page() {
		do_action('easyazon_display_settings_page', EASYAZON_SETTINGS_PAGE);

		$settings = self::_get_settings();

		include('views/settings.php');
	}

	public static function load_settings_page() {
		wp_enqueue_script('easyazon-settings', plugins_url('resources/settings.js', __FILE__), array('jquery'), EASYAZON_VERSION, true);
		wp_enqueue_style('easyazon-settings', plugins_url('resources/settings.css', __FILE__), array(), EASYAZON_VERSION);

		do_action('easyazon_load_settings_page');
	}

	#endregion Administrative Interface

	#region Settings

	public static function get_setting($settings_key, $default = null) {
		$settings = self::_get_settings();

		return isset($settings[$settings_key]) ? $settings[$settings_key] : $default;
	}

	public static function pre_get_settings($settings) {
		$settings = is_array($settings) ? $settings : array();

		return shortcode_atts(self::_get_settings_defaults(), $settings);
	}

	public static function register_setting() {
		register_setting(EASYAZON_SETTINGS_PAGE, EASYAZON_SETTINGS_NAME, array(__CLASS__, 'sanitize_settings'));
	}

	public static function sanitize_settings($settings) {
		$settings = is_array($settings) ? $settings : array();
		$settings_defaults = self::_get_settings_defaults();

		wp_cache_delete(EASYAZON_SETTINGS_NAME);

		$settings = apply_filters('easyazon_sanitize_settings', $settings, $settings, $settings_defaults);

		return shortcode_atts($settings_defaults, $settings);
	}

	private static function _get_settings() {
		$settings = wp_cache_get(EASYAZON_SETTINGS_NAME);

		if(!is_array($settings)) {
			$settings = apply_filters('easyazon_pre_get_settings', get_option(EASYAZON_SETTINGS_NAME, self::_get_settings_defaults()));

			wp_cache_set(EASYAZON_SETTINGS_NAME, $settings, null, EASYAZON_CACHE_PERIOD);
		}

		return $settings;
	}

	private static function _get_settings_defaults() {
		return apply_filters('easyazon_pre_get_settings_defaults', array());
	}

	#endregion Settings
}

require_once('lib/settings-functions.php');

EasyAzon_Components_Settings::init();
