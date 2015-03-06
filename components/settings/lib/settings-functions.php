<?php

if(!defined('ABSPATH')) { exit; }

function easyazon_get_setting($setting_name, $default = false) {
	return apply_filters(__FUNCTION__ . '_' . $setting_name, apply_filters(__FUNCTION__, EasyAzon_Components_Settings::get_setting($setting_name, $default), $setting_name, $default), $default);
}

function easyazon_get_setting_field_id($setting_name) {
	return apply_filters(__FUNCTION__, sprintf('%s-%s', EASYAZON_SETTINGS_NAME, esc_attr($setting_name)));
}

function easyazon_get_setting_field_name($setting_name) {
	return apply_filters(__FUNCTION__, sprintf('%s[%s]', EASYAZON_SETTINGS_NAME, esc_attr($setting_name)));
}
