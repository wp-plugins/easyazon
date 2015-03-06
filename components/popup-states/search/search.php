<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_PopupStates_Search {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('easyazon_popup_states', array(__CLASS__, 'output_state'));
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('easyazon_search_results_columns', array(__CLASS__, 'add_search_result_columns'));
			add_filter('easyazon_search_result_column_image', array(__CLASS__, 'get_search_result_column_image_markup'), 0);
			add_filter('easyazon_search_result_column_title', array(__CLASS__, 'get_search_result_column_title_markup'), 0);
			add_filter('easyazon_search_result_column_insert', array(__CLASS__, 'get_search_result_column_insert_markup'), 0);
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region State

	public static function output_state() {
		include('views/state.php');
	}

	#endregion State

	#region Search Result Columns

	public static function add_search_result_columns($columns) {
		$columns['image'] = __('Image');
		$columns['title'] = __('Title');
		$columns['insert'] = __('Insert');

		return $columns;
	}

	public static function get_search_result_column_image_markup($markup) {
		$markup = sprintf('<a href="http://www.amazon.com" target="_blank" data-bind="attr: { href: url }"><img alt="" height="90" src="%s" width="90" data-bind="attr: { alt: title, height: image.height, src: image.url, width: image.width }" /></a>', 'http://placehold.it/90/ffffff/ffffff.jpg&text=%20');

		return $markup;
	}

	public static function get_search_result_column_title_markup($markup) {
		$markup = '<a href="http://www.amazon.com" target="_blank" data-bind="attr: { href: url }, text: title"></a>';

		return $markup;
	}

	public static function get_search_result_column_insert_markup($markup) {
		$links = apply_filters('easyazon_search_result_column_insert_links', array());

		return implode(' | ', $links);
	}

	#endregion Search Result Columns

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazon-popup-states-search', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup'), EASYAZON_VERSION, true);
		wp_enqueue_style('easyazon-popup-states-search', plugins_url('resources/popup-state.css', __FILE__), array('easyazon-popup'), EASYAZON_VERSION);

		wp_localize_script('easyazon-popup-states-search', 'EasyAzon_PopupStates_Search', array(
			'ajaxActionQueryProducts' => 'easyazon_query_products',
			'locale'                  => easyazon_get_setting('default_search_locale'),
		));
	}

	#endregion Scripts and Styles
}

EasyAzon_Components_PopupStates_Search::init();
