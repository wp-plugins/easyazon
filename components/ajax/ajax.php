<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_Ajax {
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
		add_action('wp_ajax_easyazon_query_products', array(__CLASS__, 'easyazon_query_products'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Ajax Requests

	public static function easyazon_query_products() {
		$data = stripslashes_deep($_POST);

		$args     = apply_filters('easyazon_query_products_args', array(), $data);
		$keywords = isset($data['keywords']) ? $data['keywords'] : '';
		$locale   = isset($data['locale']) ? $data['locale'] : 'US';
		$page     = isset($data['page']) ? $data['page'] : 1;

		$response = easyazon_api_search($keywords, $page, $locale, null, $args);

		if(is_wp_error($response)) {
			$response = array(
				'error'   => true,
				'message' => __('There was an issue with your search and no items were found.'),
				'items'   => array(),
				'locale'  => $locale,
				'page'    => 1,
				'pages'   => 1,
			);
		} else {
			$response = array_merge(array(
				'error'    => false,
				'messages' => false,
			), $response);
		}

		$response = apply_filters('easyazon_query_products_response', $response, $data);

		wp_send_json($response);
	}

	#endregion Ajax Requests
}

EasyAzon_Components_Ajax::init();
