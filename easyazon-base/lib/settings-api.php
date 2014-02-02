<?php

function easyazon_get_settings_link($query_args = array()) {
	return apply_filters(__FUNCTION__, EasyAzon_Base::get_settings_link($query_args), $query_args);
}

function easyazon_get_settings_defaults() {
	return apply_filters(__FUNCTION__, EasyAzon_Base::get_settings_default());
}
function easyazon_get_settings() {
	return apply_filters(__FUNCTION__, EasyAzon_Base::get_settings());
}
function easyazon_get_setting($name) {
	return apply_filters(__FUNCTION__, EasyAzon_Base::get_setting($name));
}

function easyazon_get_settings_id($name) {
	return apply_filters(__FUNCTION__, EasyAzon_Base::settings_id($name));
}
function easyazon_the_settings_id($name) {
	echo apply_filters(__FUNCTION__, easyazon_get_settings_id($name));
}

function easyazon_get_settings_name($name) {
	return apply_filters(__FUNCTION__, EasyAzon_Base::settings_name($name));
}
function easyazon_the_settings_name($name) {
	echo apply_filters(__FUNCTION__, easyazon_get_settings_name($name));
}

function easyazon_has_settings_error($name) {
	return apply_filters(__FUNCTION__, !!(easyazon_get_settings_error($name)), $name);
}
function easyazon_get_settings_error($name) {
	return apply_filters(__FUNCTION__, EasyAzon_Base::get_settings_error($name), $name);
}
function easyazon_the_settings_error($name) {
	if(easyazon_has_settings_error($name)) {
		$output = sprintf('<p class="description easyazon-error"><label for="%1$s">%2$s</label></p>', easyazon_get_settings_id($name), easyazon_get_settings_error($name));
	} else {
		$output = '';
	}

	echo apply_filters(__FUNCTION__, $output, $name);
}