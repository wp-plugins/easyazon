<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzon_Components_Editor {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('media_buttons', array(__CLASS__, 'output_easyazon_button'), 11);
			add_action('wp_enqueue_editor', array(__CLASS__, 'enqueue_editor_scripts'));
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('wp_fullscreen_buttons', array(__CLASS__, 'add_fullscreen_button'));
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Media Buttons

	public static function add_fullscreen_button($buttons) {
		$buttons['easyazon-separator'] = 'separator';
		$buttons['easyazon'] = array(
			'both' => true,
			'onclick' => 'EasyAzon_Editor.launchPopup',
			'title' => __('EasyAzon'),
		);

		return $buttons;
	}

	public static function output_easyazon_button($editor_id) {
		printf('<a href="#" class="button insert-easyazon add_media" title="%s" data-editor="%s">%s</a>', __('Link to Amazon'), $editor_id, __('EasyAzon'));
	}

	#endregion Media Buttons

	#region Scripts

	public static function enqueue_editor_scripts() {
		wp_enqueue_script('easyazon-editor', plugins_url('resources/editor.js', __FILE__), array('jquery'), EASYAZON_VERSION, true);
		wp_enqueue_style('easyazon-editor', plugins_url('resources/editor.css', __FILE__), array(), EASYAZON_VERSION);

		wp_localize_script('easyazon-editor', 'EasyAzon_Editor', array(
			'stateName' => 'iframe:easyazon',
			'stateTitle' => __('EasyAzon'),
		));
	}

	#endregion Scripts
}

EasyAzon_Components_Editor::init();
