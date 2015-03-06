<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_Performance {
	private static $preparsed_shortcodes;

	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
			add_action('wp', array(__CLASS__, 'prefetch_items'));
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

	#region Performance Requests

	/**
	 * Loop over all the content in the main loop and do a request for all the items present so that we're only getting
	 * a few API hits instead of all of them. Then, when anything else calls `easyazon_get_item` it will get the cached
	 * data and not have to hit the API.
	 */
	public static function prefetch_items() {
		global $wp_query;

		$identifiers = array_fill_keys(array_keys(easyazon_get_locales()), array());
		$preparsed   = array();
		$shortcodes  = easyazon_get_shortcodes();

		foreach($wp_query->posts as $post) {
			preg_match_all('/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER);

			foreach($matches as $match) {
				$shortcode  = trim($match[2]);

				if(in_array($shortcode, $shortcodes)) {
					$attributes = shortcode_parse_atts($match[3]);
					$locale     = isset($attributes['locale']) ? $attributes['locale'] : false;
					$identifier = isset($attributes['identifier']) ? $attributes['identifier'] : false;

					if(!is_array($preparsed[$shortcode])) {
						$preparsed[$shortcode] = array();
					}

					$preparsed[$shortcode][] = $attributes;

					if($locale && $identifier) {
						$identifiers[$locale][] = $identifier;
					}
				}
			}
		}

		foreach($identifiers as $locale => $queryable) {
			$cached_items = easyazon_get_items($queryable, $locale);
		}

		self::$preparsed_shortcodes = $preparsed;
	}

	#endregion Performance Requests

	#region Public API

	public static function get_preparsed_shortcodes() {
		if(is_array(self::$preparsed_shortcodes)) {
			return self::$preparsed_shortcodes;
		} else {
			return array();
		}
	}

	#endregion Public API
}

require_once('lib/performance-functions.php');

EasyAzon_Components_Performance::init();
