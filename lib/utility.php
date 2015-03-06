<?php

if(!defined('ABSPATH')) { exit; }

/**
 * Utility function to redirect using `wp_redirect` without
 * having to remember to `exit` after a call to the function.
 *
 * @param string $url The url to redirect to
 * @param int $code The HTTP response code to use for redirection (301 or 302 are the most common)
 * @return void
 */
function easyazon_redirect($url, $code = 302) {
	if(wp_redirect($url, $code)) {
		exit;
	} else {
		wp_die(sprintf(__('EasyAzon could not redirect to %s with code %s'), $url, $code));
	}
}

/**
 * Utility function to debug information to the error log. This
 * function only logs information if the `EASYAZON_DEBUG` constant
 * is defined and evaluates to true. All arguments passed to the function
 * are logged.
 *
 * @return void
 */
function easyazon_debug() {
	if(defined('EASYAZON_DEBUG') && EASYAZON_DEBUG) {
		$args = func_get_args();

		foreach($args as $arg) {
			if(is_scalar($arg)) {
				error_log($arg);
			} else {
				error_log(print_r($arg, true));
			}
		}
	}
}

/**
 * Collapse an associative array of attribute => value into a string appropriate
 * for HTML insertion.
 *
 * @param array $attributes The attributes to collapse
 * @return string A string for insertion into an HTML tag.
 */
function easyazon_collapse_attributes($attributes) {
	ksort($attributes);

	$parts = array();

	foreach(array_filter($attributes) as $name => $value) {
		if(is_array($value)) {
			$value = implode(' ', array_map('esc_attr', $value));
		} else {
			$value = esc_attr($value);
		}

		$parts[] = "{$name}=\"{$value}\"";
	}

	return implode(' ', $parts);
}

function easyazon_get_shortcodes() {
	return apply_filters('easyazon_get_shortcodes', array());
}

/**
 * Return an item url based on attributes and the item itself.
 *
 * @param array $item An item returned from the Amazon API
 * @return string The URL for the item given the attributes for the shortcode
 */
function easyazon_get_url($shortcode_atts) {
	return apply_filters('easyazon_get_url', '', $shortcode_atts);
}

function easyazon_split_camel_case($word) {
	$regex = '/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/x';

	return join(' ', preg_split($regex, $word));
}

/**
 * Simple utility to sanitize things as a discreet yes/no value.
 *
 * @param string $value The value to check for yes/no.
 * @return string A string 'y' if $value is 'y' or 'n' otherwise
 */
function easyazon_yn($value) {
	return 'y' === $value || 'yes' === $value ? 'y' : 'n';
}
