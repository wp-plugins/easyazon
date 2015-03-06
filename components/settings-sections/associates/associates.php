<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_SETTINGS_SECTION_ASSOCIATES')) {
	define('EASYAZON_SETTINGS_SECTION_ASSOCIATES', 'associates');
}

class EasyAzon_Components_SettingsSections_Associates {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'), 2);
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
		add_settings_section(EASYAZON_SETTINGS_SECTION_ASSOCIATES, __('Amazon Associates'), array(__CLASS__, 'display_settings_section'), $page);

		add_settings_field('associates_us', __('United States'), array(__CLASS__, 'display_settings_field_associates_us'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_us'),
		));

		add_settings_field('associates_br', __('Brazil'), array(__CLASS__, 'display_settings_field_associates_br'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_br'),
		));

		add_settings_field('associates_ca', __('Canada'), array(__CLASS__, 'display_settings_field_associates_ca'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_ca'),
		));

		add_settings_field('associates_cn', __('China'), array(__CLASS__, 'display_settings_field_associates_cn'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_cn'),
		));

		add_settings_field('associates_fr', __('France'), array(__CLASS__, 'display_settings_field_associates_fr'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_fr'),
		));

		add_settings_field('associates_de', __('Germany'), array(__CLASS__, 'display_settings_field_associates_de'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_de'),
		));

		add_settings_field('associates_in', __('India'), array(__CLASS__, 'display_settings_field_associates_in'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_in'),
		));

		add_settings_field('associates_it', __('Italy'), array(__CLASS__, 'display_settings_field_associates_it'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_it'),
		));

		add_settings_field('associates_jp', __('Japan'), array(__CLASS__, 'display_settings_field_associates_jp'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_jp'),
		));

		add_settings_field('associates_es', __('Spain'), array(__CLASS__, 'display_settings_field_associates_es'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_es'),
		));

		add_settings_field('associates_uk', __('United Kingdom'), array(__CLASS__, 'display_settings_field_associates_uk'), $page, EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(
			'label_for' => easyazon_get_setting_field_id('associates_uk'),
		));
	}

	public static function display_settings_section() {
		do_action('easyazon_settings_section_before_' . EASYAZON_SETTINGS_SECTION_ASSOCIATES);

		include('views/section.php');

		do_action('easyazon_settings_section_after_' . EASYAZON_SETTINGS_SECTION_ASSOCIATES);
	}

	#endregion Settings Section

	#region Settings Fields

	public static function display_settings_field_associates_br($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_br')), esc_attr(easyazon_get_setting_field_name('associates_br')), esc_attr(easyazon_get_setting('associates_br')), easyazon_get_locale_associate_signup_url('BR'), __('Sign up'));
	}

	public static function display_settings_field_associates_ca($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_ca')), esc_attr(easyazon_get_setting_field_name('associates_ca')), esc_attr(easyazon_get_setting('associates_ca')), easyazon_get_locale_associate_signup_url('CA'), __('Sign up'));
	}

	public static function display_settings_field_associates_cn($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_cn')), esc_attr(easyazon_get_setting_field_name('associates_cn')), esc_attr(easyazon_get_setting('associates_cn')), easyazon_get_locale_associate_signup_url('CN'), __('Sign up'));
	}

	public static function display_settings_field_associates_de($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_de')), esc_attr(easyazon_get_setting_field_name('associates_de')), esc_attr(easyazon_get_setting('associates_de')), easyazon_get_locale_associate_signup_url('DE'), __('Sign up'));
	}

	public static function display_settings_field_associates_es($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_es')), esc_attr(easyazon_get_setting_field_name('associates_es')), esc_attr(easyazon_get_setting('associates_es')), easyazon_get_locale_associate_signup_url('ES'), __('Sign up'));
	}

	public static function display_settings_field_associates_fr($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_fr')), esc_attr(easyazon_get_setting_field_name('associates_fr')), esc_attr(easyazon_get_setting('associates_fr')), easyazon_get_locale_associate_signup_url('FR'), __('Sign up'));
	}

	public static function display_settings_field_associates_in($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_in')), esc_attr(easyazon_get_setting_field_name('associates_in')), esc_attr(easyazon_get_setting('associates_in')), easyazon_get_locale_associate_signup_url('IN'), __('Sign up'));
		printf('<p class="description">%s</p>', __('You must be a resident of India to join this country\'s affiliate program'));
	}

	public static function display_settings_field_associates_it($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_it')), esc_attr(easyazon_get_setting_field_name('associates_it')), esc_attr(easyazon_get_setting('associates_it')), easyazon_get_locale_associate_signup_url('IT'), __('Sign up'));
	}

	public static function display_settings_field_associates_jp($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_jp')), esc_attr(easyazon_get_setting_field_name('associates_jp')), esc_attr(easyazon_get_setting('associates_jp')), easyazon_get_locale_associate_signup_url('JP'), __('Sign up'));
	}

	public static function display_settings_field_associates_uk($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_uk')), esc_attr(easyazon_get_setting_field_name('associates_uk')), esc_attr(easyazon_get_setting('associates_uk')), easyazon_get_locale_associate_signup_url('UK'), __('Sign up'));
	}

	public static function display_settings_field_associates_us($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" /> <a href="%s" target="_blank">%s</a>', esc_attr(easyazon_get_setting_field_id('associates_us')), esc_attr(easyazon_get_setting_field_name('associates_us')), esc_attr(easyazon_get_setting('associates_us')), easyazon_get_locale_associate_signup_url('US'), __('Sign up'));
		printf('<p class="description">%s</p>', __('(i.e. yourtrackingid-20'));
	}

	#endregion Settings Fields

	#region Settings

	public static function add_settings_defaults($defaults) {
		$settings_old = get_option('_easyazon_settings', array());

		$defaults['associates_br'] = isset($settings_old['associates_tags_BR']) ? $settings_old['associates_tags_BR'] : '';
		$defaults['associates_ca'] = isset($settings_old['associates_tags_CA']) ? $settings_old['associates_tags_CA'] : '';
		$defaults['associates_cn'] = isset($settings_old['associates_tags_CN']) ? $settings_old['associates_tags_CN'] : '';
		$defaults['associates_de'] = isset($settings_old['associates_tags_DE']) ? $settings_old['associates_tags_DE'] : '';
		$defaults['associates_es'] = isset($settings_old['associates_tags_ES']) ? $settings_old['associates_tags_ES'] : '';
		$defaults['associates_fr'] = isset($settings_old['associates_tags_FR']) ? $settings_old['associates_tags_FR'] : '';
		$defaults['associates_in'] = isset($settings_old['associates_tags_IN']) ? $settings_old['associates_tags_IN'] : '';
		$defaults['associates_it'] = isset($settings_old['associates_tags_IT']) ? $settings_old['associates_tags_IT'] : '';
		$defaults['associates_jp'] = isset($settings_old['associates_tags_JP']) ? $settings_old['associates_tags_JP'] : '';
		$defaults['associates_uk'] = isset($settings_old['associates_tags_UK']) ? $settings_old['associates_tags_UK'] : '';
		$defaults['associates_us'] = isset($settings_old['associates_tags_US']) ? $settings_old['associates_tags_US'] : '';

		return $defaults;
	}

	#endregion Settings
}

EasyAzon_Components_SettingsSections_Associates::init();
