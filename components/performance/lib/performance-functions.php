<?php

if(!defined('ABSPATH')) { exit; }

function easyazon_get_preparsed_shortcodes() {
	return apply_filters(__FUNCTION__, EasyAzon_Components_Performance::get_preparsed_shortcodes());
}
