<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_SETTINGS_SECTION_CREDENTIALS')) {
	define('EASYAZON_SETTINGS_SECTION_CREDENTIALS', 'credentials');
}

class EasyAzon_Components_SettingsSections_Credentials {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'), 1);
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

		add_filter('easyazon_get_setting_access_key', array(__CLASS__, 'override_access_key'), 11);
		add_filter('easyazon_get_setting_secret_key', array(__CLASS__, 'override_secret_key'), 11);

		add_filter('easyazon_sanitize_settings', array(__CLASS__, 'sanitize_settings'), 11, 3);
	}

	#region Settings Section

	public static function add_settings_section_and_fields($page) {
		add_settings_section(EASYAZON_SETTINGS_SECTION_CREDENTIALS, __('Credentials'), array(__CLASS__, 'display_settings_section'), $page);

		add_settings_field('access_key', __('Access Key ID'), array(__CLASS__, 'display_settings_field_access_key'), $page, EASYAZON_SETTINGS_SECTION_CREDENTIALS, array(
			'label_for' => easyazon_get_setting_field_id('access_key'),
		));

		add_settings_field('secret_key', __('Secret Access Key'), array(__CLASS__, 'display_settings_field_secret_key'), $page, EASYAZON_SETTINGS_SECTION_CREDENTIALS, array(
			'label_for' => easyazon_get_setting_field_id('secret_key'),
		));
	}

	public static function display_settings_section() {
		$items = easyazon_api_search('Kindle');

		do_action('easyazon_settings_section_before_' . EASYAZON_SETTINGS_SECTION_CREDENTIALS);

		include('views/section.php');

		do_action('easyazon_settings_section_after_' . EASYAZON_SETTINGS_SECTION_CREDENTIALS);
	}

	#endregion Settings Section

	#region Settings Fields

	public static function display_settings_field_access_key($args) {
		if(defined('EASYAZON_ACCESS_KEY')) {
			printf('<p class="description">%s</p>', __('You have defined this value in your <code>wp-config.php</code> file with the <code>EASYAZON_ACCESS_KEY</code> constant'));
		} else {
			printf('<input type="text" class="code large-text" id="%s" name="%s" value="%s" />', esc_attr(easyazon_get_setting_field_id('access_key')), esc_attr(easyazon_get_setting_field_name('access_key')), esc_attr(easyazon_get_setting('access_key', '')));
		}
	}

	public static function display_settings_field_secret_key($args) {
		if(defined('EASYAZON_SECRET_KEY')) {
			printf('<p class="description">%s</p>', __('You have defined this value in your <code>wp-config.php</code> file with the <code>EASYAZON_SECRET_KEY</code> constant'));
		} else {
			printf('<input type="text" class="code large-text" id="%s" name="%s" value="%s" />', esc_attr(easyazon_get_setting_field_id('secret_key')), esc_attr(easyazon_get_setting_field_name('secret_key')), esc_attr(easyazon_get_setting('secret_key', '')));
		}
	}

	#endregion Settings Fields

	#region Settings

	public static function add_settings_defaults($defaults) {
		$settings_old = get_option('_easyazon_settings', array());

		$defaults['access_key'] = isset($settings_old['access_key_id']) ? $settings_old['access_key_id'] : '';
		$defaults['secret_key'] = isset($settings_old['secret_access_key']) ? $settings_old['secret_access_key'] : '';

		return $defaults;
	}

	public static function sanitize_settings($settings, $settings_raw, $settings_defaults) {
		if(isset($settings['access_key'])) {
			$settings['access_key'] = trim($settings['access_key']);
		}

		if(isset($settings['secret_key'])) {
			$settings['secret_key'] = trim($settings['secret_key']);
		}

		return $settings;
	}

	#endregion Settings

	#region Setting Overrides

	public static function override_access_key($value) {
		if(defined('EASYAZON_ACCESS_KEY')) {
			$value = EASYAZON_ACCESS_KEY;
		}

		return trim($value);
	}

	public static function override_secret_key($value) {
		if(defined('EASYAZON_SECRET_KEY')) {
			$value = EASYAZON_SECRET_KEY;
		}

		return trim($value);
	}

	#region Setting Overrides
}

EasyAzon_Components_SettingsSections_Credentials::init();
