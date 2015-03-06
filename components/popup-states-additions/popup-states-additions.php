<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_PopupStatesAdditions {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_link_form_after', array(__CLASS__, 'link_form_upgrade_prompt'));
			add_action('easyazon_search_buttons_before', array(__CLASS__, 'search_buttons_upgrade_prompt'));;
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

	#region Upgrade Prompts

	public static function link_form_upgrade_prompt() {
		include('views/link-form-upgrade-prompt.php');
	}

	public static function search_buttons_upgrade_prompt() {
		include('views/search-buttons-upgrade-prompt.php');
	}

	#endregion Upgrade Prompts
}

EasyAzon_Components_PopupStatesAdditions::init();
