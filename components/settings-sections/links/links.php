<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_SETTINGS_SECTION_DEFAULTS')) {
	define('EASYAZON_SETTINGS_SECTION_DEFAULTS', 'links');
}

class EasyAzon_Components_SettingsSections_Links {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'), 4);
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
		add_filter('easyazon_sanitize_settings', array(__CLASS__, 'sanitize_settings'), 11, 3);
	}

	#region Settings Section

	public static function add_settings_section_and_fields($page) {
		add_settings_section(EASYAZON_SETTINGS_SECTION_DEFAULTS, __('Defaults'), array(__CLASS__, 'display_settings_section'), $page);

		add_settings_field('link_nw', __('New Window'), array(__CLASS__, 'display_settings_field_link_nw'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('link_nw'),
		));

		add_settings_field('link_nf', __('No Follow'), array(__CLASS__, 'display_settings_field_link_nf'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('link_nf'),
		));
	}

	public static function display_settings_section() {
		do_action('easyazon_settings_section_before_' . EASYAZON_SETTINGS_SECTION_DEFAULTS);

		include('views/section.php');

		do_action('easyazon_settings_section_after_' . EASYAZON_SETTINGS_SECTION_DEFAULTS);
	}

	#endregion Settings Section

	#region Settings Fields

	public static function display_settings_field_link_nw($args) {
		printf('<input type="hidden" name="%s" value="no" />', easyazon_get_setting_field_name('link_nw'));
		printf('<label><input type="checkbox" %s id="%s" name="%s" value="y" /> %s</label>', ('y' === easyazon_get_setting('link_nw') ? 'checked="checked"' : ''), easyazon_get_setting_field_id('link_nw'), easyazon_get_setting_field_name('link_nw'), __('I want EasyAzon links to open in a new window or tab by default'));
	}

	public static function display_settings_field_link_nf($args) {
		printf('<input type="hidden" name="%s" value="no" />', easyazon_get_setting_field_name('link_nf'));
		printf('<label><input type="checkbox" %s id="%s" name="%s" value="y" /> %s</label>', ('y' === easyazon_get_setting('link_nf') ? 'checked="checked"' : ''), easyazon_get_setting_field_id('link_nf'), easyazon_get_setting_field_name('link_nf'), __('I want the <code>nofollow</code> attribute applied to EasyAzon links by default'));
	}

	#endregion Settings Fields

	#region Settings

	public static function add_settings_defaults($defaults) {
		$settings_old = get_option('_easyazon_settings', array());

		$defaults['link_nw'] = isset($settings_old['links_new_window']) && 'no' === $settings_old['links_new_window'] ? 'n' : 'y';
		$defaults['link_nf'] = isset($settings_old['links_nofollow']) && 'no' === $settings_old['links_nofollow'] ? 'n' : 'y';

		return $defaults;
	}

	public static function sanitize_settings($settings, $settings_raw, $settings_defaults) {
		if(isset($settings['link_nw'])) {
			$settings['link_nw'] = easyazon_yn($settings['link_nw']);
		}

		if(isset($settings['link_nf'])) {
			$settings['link_nf'] = easyazon_yn($settings['link_nf']);
		}

		return $settings;
	}

	#endregion Settings
}

EasyAzon_Components_SettingsSections_Links::init();
