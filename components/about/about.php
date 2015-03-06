<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_ABOUT_PAGE')) {
	define('EASYAZON_ABOUT_PAGE', 'easyazon-about');
}

class EasyAzon_Components_About {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_add_admin_pages', array(__CLASS__, 'add_about_page'), 9999);
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
	}

	#region About Page

	public static function add_about_page($parent) {
		$page = add_submenu_page($parent, __('EasyAzon - Free Amazon Course'), __('Free Amazon Course'), 'manage_options', EASYAZON_ABOUT_PAGE, array(__CLASS__, 'display_about_page'));

		add_action("load-{$page}", array(__CLASS__, 'load'));
	}

	public static function display_about_page() {
		$about_page_file = apply_filters('easyazon_about_page_file', path_join(dirname(__FILE__), 'views/about.php'));

		if(file_exists($about_page_file)) {
			include($about_page_file);
		}
	}

	public static function load() {
		do_action('easyazon_load_about_page');
	}

	#endregion About Page
}

EasyAzon_Components_About::init();
