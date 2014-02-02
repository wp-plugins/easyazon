<?php

class EasyAzon_Base {
	/// Constants
	const ERRORS_NAME = '_easyazon_errors';
	const SETTINGS_NAME = '_easyazon_settings';
	const SETTINGS_PAGE = 'easyazon-settings';

	/// Defaults
	private static $default_settings = null;

	public static function init() {
		self::add_actions();
		self::add_filters();

		register_activation_hook(__FILE__, array(__CLASS__, 'do_activation_actions'));
		register_deactivation_hook(__FILE__, array(__CLASS__, 'do_deactivation_actions'));
	}

	private static function add_actions() {
		// Common actions
		add_action('init', array(__CLASS__, 'register_resources'), 0);
		add_action('init', array(__CLASS__, 'register_shortcodes'), 0);

		if(is_admin()) {
			// Administrative only actions
			add_action('admin_init', array(__CLASS__, 'register_settings'));
			add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
		} else {
			// Frontend only actions
		}
	}

	private static function add_filters() {
		// Common filters
		add_filter('option_' . self::SETTINGS_NAME, array(__CLASS__, 'clean_settings'));

		if(is_admin()) {
			// Administrative only filters
		} else {
			// Frontend only filters
		}
	}

	/// Callbacks

	//// Activation, deactivation, shortcodes, and uninstall

	public static function do_activation_actions() {
		// Perform settings upgrade
	}

	public static function do_deactivation_actions() {

	}

	public static function register_shortcodes() {
		do_action('easyazon_register_shortcodes');
	}

	//// Generic operation

	public static function register_resources() {
		do_action('easyazon_register_resources');
	}

	//// Settings UI

	public static function add_settings_page() {
		$settings_page_hook_suffix = add_menu_page(__('EasyAzon Settings'), __('EasyAzon'), 'manage_options', self::SETTINGS_PAGE, array(__CLASS__, 'display_settings_page'), plugins_url('resources/backend/img/logo-16x16.png', __FILE__), 10001);

		if($settings_page_hook_suffix) {
			add_action("load-{$settings_page_hook_suffix}", array(__CLASS__, 'load_settings_page'));
		}
	}

	public static function display_settings_page() {
		include('views/backend/settings.php');
	}

	public static function load_settings_page() {
		do_action('easyazon_load_settings_page');
	}

	//// Settings registration and sanitization

	public static function clean_settings($settings) {
		$settings_defaults = self::get_settings_default();

		if(isset($settings['affiliate-links-new-window'])) {
			$settings = self::_migrate_settings($settings);
		}

		return apply_filters('easyazon_clean_settings', $settings, $settings_defaults);
	}

	public static function register_settings() {
		register_setting(self::SETTINGS_NAME, self::SETTINGS_NAME, array(__CLASS__, 'sanitize_settings'));
	}

	public static function sanitize_settings($settings) {
		$settings_defaults = self::get_settings_default();
		$settings_errors = new WP_Error;

		$settings = apply_filters('easyazon_sanitize_settings', $settings, $settings_defaults, $settings_errors);

		update_option(self::ERRORS_NAME, $settings_errors);

		return shortcode_atts($settings_defaults, $settings);
	}

	/// Settings API

	public static function get_settings_default() {
		if(is_null(self::$default_settings)) {
			self::$default_settings = apply_filters('easyazon_default_settings', array());
		}

		return self::$default_settings;
	}

	public static function get_settings() {
		return get_option(self::SETTINGS_NAME, self::get_settings_default());
	}

	public static function get_setting() {
		$keys = func_get_args();
		$setting = self::get_settings();

		while(is_array($setting) && !empty($keys)) {
			$key = array_shift($keys);
			$setting = isset($setting[$key]) ? $setting[$key] : false;
		}

		$setting = empty($keys) ? $setting : false;

		return apply_filters('easyazon_get_setting', $setting, $keys);
	}

	public static function get_settings_errors() {
		$errors = get_option(self::ERRORS_NAME, false);

		return is_wp_error($errors) ? $errors : new WP_Error;
	}

	public static function get_settings_error($name) {
		$errors = self::get_settings_errors();
		$message = $errors->get_error_message($name);

		return empty($message) ? false : $message;
	}

	public static function get_settings_link($query_args) {
		$query_args['page'] = self::SETTINGS_PAGE;

		return add_query_arg($query_args, admin_url('admin.php'));
	}

	public static function settings_id($name) {
		return self::SETTINGS_NAME . '-' . $name;
	}

	public static function settings_name($name) {
		return self::SETTINGS_NAME . '[' . $name . ']';
	}

	private static function _migrate_settings($old) {
		$settings = array(
			// Credentials
			'access_key_id' => $old['access-key-id'],
			'secret_access_key' => $old['secret-access-key'],

			// Links
			'links_add_to_cart' => 'yes' === $old['affiliate-links-cart'] ? 'yes' : 'no',
			'links_cloaking' => 'yes' === $old['affiliate-links-cloaking'] ? 'yes' : 'no',
			'links_cloaking_prefix' => isset($old['affiliate-links-cloaking-prefix']) ? $old['affiliate-links-cloaking-prefix'] : '',
			'links_popups' => 'yes' === $old['output-js'] ? 'yes' : 'no',
			'links_localization' => 'yes' === $old['enable-link-localization'] ? 'yes' : 'no',
			'links_new_window' => 'yes' === $old['affiliate-links-new-window'] ? 'yes' : 'no',
			'links_nofollow' => 'yes' === $old['affiliate-links-nofollow'] ? 'yes' : 'no',

			// Search
			'default_search_locale' => strtoupper($old['default-search-locale']),
			'content_types' => is_array($old['post-types']) ? array_keys($old['post-types']) : array('page', 'post'),
		);

		if(is_array($old['affiliate-locale'])) {
			foreach($old['affiliate-locale'] as $locale_key => $locale_tag) {
				$locale_key = strtoupper($locale_key);
				$settings["associates_tags_{$locale_key}"] = $locale_tag;
			}
		}

		remove_filter('option_' . self::SETTINGS_NAME, array(__CLASS__, 'clean_settings'));
		update_option(self::SETTINGS_NAME, $settings);
		add_filter('option_' . self::SETTINGS_NAME, array(__CLASS__, 'clean_settings'));

		return $settings;
	}

	/// Utility

	public static function get_product_url($attributes) {
		$asin = isset($attributes['asin']) ? $attributes['asin'] : false;
		$locale = strtoupper(isset($attributes['locale']) ? $attributes['locale'] : 'US');
		$keywords = isset($attributes['keywords']) ? $attributes['keywords'] : false;
		$tag = isset($attributes['tag']) ? $attributes['tag'] : false;
		$tld = EasyAzon_Amazon_API::get_locale_tld($locale);

		$base_url = sprintf('http://amazon.%1$s', $tld);

		if($asin) {
			$url = sprintf('%1$s/dp/%2$s/', $base_url, $asin);
		} else if($keywords) {
			$url = add_query_arg(array('field-keywords' => urlencode($keywords)), "{$base_url}/s/");
		} else {
			$url = $base_url;
		}

		if($tag) {
			$url = add_query_arg(compact('tag'), $url);
		}

		return $url;
	}
}

require_once('lib/amazon-api.php');
require_once('lib/output-api.php');
require_once('lib/settings-api.php');
require_once('lib/utility.php');
EasyAzon_Base::init();