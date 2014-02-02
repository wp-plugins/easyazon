<?php

// Utility

function easyazon_get_associate_tags() {
	return apply_filters(__FUNCTION__, array());
}

function easyazon_get_locales() {
	return apply_filters(__FUNCTION__, array());
}

function easyazon_redirect($url, $code = 302) {
	wp_redirect($url, $code); exit;
}

// Urls

function easyazon_get_help_url() {
	return apply_filters(__FUNCTION__, 'http://easyazon.com/help/');
}

function easyazon_get_upgrade_url() {
	return apply_filters(__FUNCTION__, 'http://easyazon.com/why-pro/');
}