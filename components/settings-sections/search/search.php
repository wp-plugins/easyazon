<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_SETTINGS_SECTION_SEARCH')) {
	define('EASYAZON_SETTINGS_SECTION_SEARCH', 'search');
}

class EasyAzon_Components_SettingsSections_Search {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'), 3);
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
		add_filter('easyazon_pre_get_settings_defaults', array(__CLASS__, 'add_settings_defaults'));
	}

	#region Settings Section

	public static function add_settings_section_and_fields($page) {
		add_settings_section(EASYAZON_SETTINGS_SECTION_SEARCH, __('Search'), array(__CLASS__, 'display_settings_section'), $page);

		add_settings_field('default_search_locale', __('Default Search Locale'), array(__CLASS__, 'display_settings_field_default_search_locale'), $page, EASYAZON_SETTINGS_SECTION_SEARCH, array(
			'label_for' => easyazon_get_setting_field_id('default_search_locale'),
		));
	}

	public static function display_settings_section() {
		do_action('easyazon_settings_section_before_' . EASYAZON_SETTINGS_SECTION_SEARCH);

		include('views/section.php');

		do_action('easyazon_settings_section_after_' . EASYAZON_SETTINGS_SECTION_SEARCH);
	}

	#endregion Settings Section

	#region Settings Fields

	public static function display_settings_field_default_search_locale($args) {
		$options = array();
		$default = easyazon_get_setting('default_search_locale');
		$locales = easyazon_get_locales();

		foreach($locales as $locale => $locale_name) {
			$options[] = sprintf('<option %s value="%s">%s</option>', ($default === $locale ? 'selected="selected"' : ''), esc_attr($locale), esc_html($locale_name));
		}

		printf('<select id="%s" name="%s">%s</select>', easyazon_get_setting_field_id('default_search_locale'), easyazon_get_setting_field_name('default_search_locale'), implode('', $options));
	}

	#endregion Settings Fields

	#region Settings

	public static function add_settings_defaults($defaults) {
		$settings_old = get_option('_easyazon_settings', array());

		$defaults['default_search_locale'] = isset($settings_old['default_search_locale']) ? $settings_old['default_search_locale'] : 'US';

		return $defaults;
	}

	#endregion Settings
}

EasyAzon_Components_SettingsSections_Search::init();
