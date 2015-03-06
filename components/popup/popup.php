<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_Popup {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('media_upload_easyazon', array(__CLASS__, 'add_media_upload_output'));
		} else {
			// Actions that only affect the frontend interface or operation

		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('media_upload_tabs', array(__CLASS__, 'add_media_upload_tabs'));
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Popup Output

	public static function add_media_upload_output() {
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));

		return wp_iframe(array(__CLASS__, 'get_media_upload_output'));
	}

	public static function add_media_upload_tabs($tabs) {
		return array_merge($tabs, array('easyazon' => __('EasyAzon')));
	}

	public static function get_media_upload_output() {
		include('views/popup.php');
	}

	#endregion Popup Output

	#region Scripts and Styles

	public static function enqueue_scripts($settings) {
		wp_enqueue_script('knockout', plugins_url('vendor/knockout.js', EASYAZON_PLUGIN_FILE), array(), '3.2.0', true);

		wp_enqueue_script('easyazon-popup', plugins_url('resources/popup.js', __FILE__), array('jquery', 'knockout'), EASYAZON_VERSION, true);
		wp_enqueue_style('easyazon-popup', plugins_url('resources/popup.css', __FILE__), array('media'), EASYAZON_VERSION);

		wp_localize_script('easyazon-popup', 'EasyAzon_Popup', apply_filters('easyazon_popup_localize', array(
			'attributes'   => easyazon_get_attributes(),
			'stateName'    => 'iframe:easyazon',
			'stateTitle'   => __('EasyAzon'),
			'tagNone'      => __('None'),
			'tagNoneValue' => '',
			'tags' => array(
				'BR' => array_filter(array(easyazon_get_setting('associates_br'))),
				'CA' => array_filter(array(easyazon_get_setting('associates_ca'))),
				'CN' => array_filter(array(easyazon_get_setting('associates_cn'))),
				'DE' => array_filter(array(easyazon_get_setting('associates_de'))),
				'ES' => array_filter(array(easyazon_get_setting('associates_es'))),
				'FR' => array_filter(array(easyazon_get_setting('associates_fr'))),
				'IN' => array_filter(array(easyazon_get_setting('associates_in'))),
				'IT' => array_filter(array(easyazon_get_setting('associates_it'))),
				'JP' => array_filter(array(easyazon_get_setting('associates_jp'))),
				'UK' => array_filter(array(easyazon_get_setting('associates_uk'))),
				'US' => array_filter(array(easyazon_get_setting('associates_us'))),
			),
		)));

		do_action('easyazon_popup_enqueue_scripts');
	}

	#endregion Scripts and Styles
}

EasyAzon_Components_Popup::init();
