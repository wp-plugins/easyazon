<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_Shortcodes {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
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
		add_filter('easyazon_get_url', array(__CLASS__, 'get_url'), 10, 3);
		add_filter('easyazon_link_atts', array(__CLASS__, 'link_atts'), 10, 2);
		add_filter('easyazon_shortcode_atts', array(__CLASS__, 'shortcode_atts'), 10, 2);
	}

	#region Shortcode

	public static function shortcode_atts($sanitized, $atts) {
		// Identifier is always needed
		$sanitized['identifier'] = isset($atts['identifier']) ? $atts['identifier'] : false;
		$sanitized['identifier'] = ((false === $sanitized['identifier']) && isset($atts['asin'])) ? $atts['asin'] : $sanitized['identifier'];

		// Locale is always needed
		$sanitized['locale']     = isset($atts['locale']) ? $atts['locale'] : easyazon_get_setting('default_search_locale');

		// Tag is always available, although it is optional
		$sanitized['tag']        = isset($atts['tag']) ? $atts['tag'] : false;

		// New window is always available, although it is optional and sometimes depends on the settings
		$sanitized['nw']         = isset($atts['nw']) ? $atts['nw'] : false;
		$sanitized['nw']         = ((false === $sanitized['nw']) && isset($atts['new_window'])) ? $atts['new_window'] : $sanitized['nw'];
		$sanitized['nw']         = str_replace(array('yes', 'no'), array('y', 'n'), $sanitized['nw']);
		$sanitized['nw']         = in_array($sanitized['nw'], array('y', 'n')) ? $sanitized['nw'] : easyazon_get_setting('link_nw');

		// No follow is always available, although it is optional and sometimes depends on the settings
		$sanitized['nf']         = isset($atts['nf']) ? $atts['nf'] : false;
		$sanitized['nf']         = ((false === $sanitized['nf']) && isset($atts['nofollow'])) ? $atts['nofollow'] : $sanitized['nf'];
		$sanitized['nf']         = str_replace(array('yes', 'no'), array('y', 'n'), $sanitized['nf']);
		$sanitized['nf']         = in_array($sanitized['nf'], array('y', 'n')) ? $sanitized['nf'] : easyazon_get_setting('link_nf');

		return $sanitized;
	}

	#endregion Shortcode

	#region URLs

	public static function get_url($url, $shortcode_atts) {
		$item = easyazon_get_item($shortcode_atts['identifier'], $shortcode_atts['locale']);

		if($item && isset($item['url'])) {
			$query_args = array();

			if(isset($shortcode_atts['tag'])) {
				$query_args['tag'] = $shortcode_atts['tag'];
			}

			$url = add_query_arg($query_args, $item['url']);
		}

		return $url;
	}

	#endregion URLs

	#region Attributes

	public static function link_atts($link_atts, $shortcode_atts) {
		$link_atts['class']           = array('easyazon-link');
		$link_atts['data-identifier'] = isset($shortcode_atts['identifier']) ? $shortcode_atts['identifier'] : '';
		$link_atts['data-locale']     = isset($shortcode_atts['locale']) ? $shortcode_atts['locale'] : '';
		$link_atts['href']            = easyazon_get_url($shortcode_atts);

		if(isset($shortcode_atts['nw']) && 'y' === $shortcode_atts['nw']) {
			$link_atts['target'] = '_blank';
		}

		if(isset($shortcode_atts['nf']) && 'y' === $shortcode_atts['nf']) {
			$link_atts['rel'] = array('nofollow');
		}

		return $link_atts;
	}

	#endregion Attributes
}

EasyAzon_Components_Shortcodes::init();
