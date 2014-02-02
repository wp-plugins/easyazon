<?php

function easyazon_get_product_image_attributes($attributes) {
	return apply_filters(__FUNCTION__, array('class' => 'easyazon-image'), $attributes);
}

function easyazon_get_product_link_attributes($attributes) {
	return apply_filters(__FUNCTION__, array('class' => 'easyazon-link'), $attributes);
}

function easyazon_get_product_link_url($attributes) {
	if(isset($attributes['locale']) && isset($attributes['tag'])) {
		if('NONE' === $attributes['tag']) {
			$attributes['tag'] = '';
		} else if(false === $attributes['tag']) {
			$locale = strtoupper(isset($attributes['locale']) ? $attributes['locale'] : 'US');

			$tags = easyazon_get_associate_tags();
			if(isset($tags[$locale]) && !empty($tags[$locale])) {
				$attributes['tag'] = current($tags[$locale]);
			}
		}
	}

	return apply_filters(__FUNCTION__, EasyAzon_Base::get_product_url($attributes), $attributes);
}

// Utility

function easyazon_attributes_array_to_attributes_string($attributes_array) {
	$attributes = array();

	foreach($attributes_array as $attribute_name => $attribute_values) {
		$attribute_values = is_array($attribute_values) ? $attribute_values : array($attribute_values);
		$attribute_values = array_filter($attribute_values);

		$attributes[] = sprintf('%1$s="%2$s"', esc_attr($attribute_name), implode(' ', array_map('esc_attr', $attribute_values)));
	}

	return implode(' ', $attributes);
}