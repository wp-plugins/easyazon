<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_SHORTCODE_LINK')) {
	define('EASYAZON_SHORTCODE_LINK', 'easyazon_link');
}

if(!defined('EASYAZON_SHORTCODE_LINK_LEGACY')) {
	define('EASYAZON_SHORTCODE_LINK_LEGACY', 'easyazon-link');
}

if(!defined('EASYAZON_SHORTCODE_LINK_LEGACY_SIMPLEAZON')) {
	define('EASYAZON_SHORTCODE_LINK_LEGACY_SIMPLEAZON', 'simpleazon-link');
}

class EasyAzon_Components_Shortcodes_Link {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
		self::_add_shortcodes();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
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
		add_filter('easyazon_get_shortcodes', array(__CLASS__, 'get_shortcodes'));
	}

	private static function _add_shortcodes() {
		add_shortcode(EASYAZON_SHORTCODE_LINK,                   array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZON_SHORTCODE_LINK_LEGACY,            array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZON_SHORTCODE_LINK_LEGACY_SIMPLEAZON, array(__CLASS__, 'shortcode'));
	}

	#region Shortcode

	public static function get_shortcodes($shortcodes) {
		$shortcodes[] = EASYAZON_SHORTCODE_LINK;
		$shortcodes[] = EASYAZON_SHORTCODE_LINK_LEGACY;
		$shortcodes[] = EASYAZON_SHORTCODE_LINK_LEGACY_SIMPLEAZON;

		return $shortcodes;
	}

	public static function shortcode($atts, $content = null) {
		$atts = apply_filters('easyazon_shortcode_atts', array(), $atts);

		$content    = trim($content);

		if(empty($content)) {
			return '';
		} else {
			$link_atts = apply_filters('easyazon_link_atts', array(), $atts);

			return sprintf('<a %1$s>%2$s</a>', easyazon_collapse_attributes($link_atts), $content);
		}
	}

	#endregion Shortcode
}

EasyAzon_Components_Shortcodes_Link::init();
