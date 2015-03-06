<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZON_ITEM')) {
	define('EASYAZON_ITEM', 'easyazon_item');
}

if(!defined('EASYAZON_ITEM_CACHE_PERIOD')) {
	define('EASYAZON_ITEM_CACHE_PERIOD', 1 * HOUR_IN_SECONDS);
}

if(!defined('EASYAZON_ITEM_OPTION')) {
	define('EASYAZON_ITEM_OPTION', 'easyazon_item_%s_%s');
}

function easyazon_get_attributes() {
	return array(
		'Actor'                                => __('Actor'),
		'Artist'                               => __('Artist'),
		'AspectRatio'                          => __('Aspect Ratio'),
		'AudienceRating'                       => __('Audience Rating'),
		'AudioFormat'                          => __('Audio Format'),
		'Author'                               => __('Author'),
		'Binding'                              => __('Binding'),
		'Brand'                                => __('Brand'),
		'Category'                             => __('Category'),
		'CEROAgeRating'                        => __('CERO Age Rating'),
		'ClothingSize'                         => __('Clothing Size'),
		'Color'                                => __('Color'),
		'Creator'                              => __('Creator'),
		'Department'                           => __('Department'),
		'Director'                             => __('Director'),
		'EAN'                                  => __('EAN'),
		'Edition'                              => __('Edition'),
		'EISBN'                                => __('EISBN'),
		'EpisodeSequence'                      => __('Episode Sequence'),
		'ESRBAgeRating'                        => __('ESRB Age Rating'),
		'Feature'                              => __('Feature'),
		'Format'                               => __('Format'),
		'Genre'                                => __('Genre'),
		'HardwarePlatform'                     => __('Hardware Platform'),
		'HazardousMaterialType'                => __('Hazardous Material Type'),
		'IsAdultProduct'                       => __('Is Adult Product'),
		'IsAutographed'                        => __('Is Autographed'),
		'ISBN'                                 => __('ISBN'),
		'IsEligibleForTradeIn'                 => __('Is Eligible For Trade In'),
		'IsMemorabilia'                        => __('Is Memorabilia'),
		'IssuesPerYear'                        => __('Issues Per Year'),
		'ItemDimensions'                       => __('Item Dimensions'),
		'Height'                               => __('Height'),
		'Length'                               => __('Length'),
		'Weight'                               => __('Weight'),
		'Width'                                => __('Width'),
		'ItemPartNumber'                       => __('Item Part Number'),
		'Label'                                => __('Label'),
		'Languages'                            => __('Languages'),
		'LegalDisclaimer'                      => __('Legal Disclaimer'),
		'ListPrice'                            => __('List Price'),
		'Manufacturer'                         => __('Manufacturer'),
		'ManufacturerMaximumAge'               => __('Manufacturer Maximum Age'),
		'ManufacturerMinimumAge'               => __('Manufacturer Minimum Age'),
		'ManufacturerPartsWarrantyDescription' => __('Manufacturer Parts Warranty Description'),
		'MediaType'                            => __('Media Type'),
		'Model'                                => __('Model'),
		'MPN'                                  => __('MPN'),
		'NumberOfDiscs'                        => __('Number Of Discs'),
		'NumberOfIssues'                       => __('Number Of Issues'),
		'NumberOfItems'                        => __('Number Of Items'),
		'NumberOfPages'                        => __('Number Of Pages'),
		'NumberOfTracks'                       => __('Number Of Tracks'),
		'OperatingSystem'                      => __('Operating System'),
		'PackageQuantity'                      => __('Package Quantity'),
		'PartNumber'                           => __('Part Number'),
		'Platform'                             => __('Platform'),
		'ProductGroup'                         => __('Product Group'),
		'ProductTypeSubcategory'               => __('Product Type Subcategory'),
		'PublicationDate'                      => __('Publication Date'),
		'Publisher'                            => __('Publisher'),
		'RegionCode'                           => __('Region Code'),
		'ReleaseDate'                          => __('Release Date'),
		'RunningTime'                          => __('Running Time'),
		'SeikodoProductCode'                   => __('Seikodo Product Code'),
		'Size'                                 => __('Size'),
		'SKU'                                  => __('SKU'),
		'Studio'                               => __('Studio'),
		'SubscriptionLength'                   => __('Subscription Length'),
		'Title'                                => __('Title'),
		'TradeInValue'                         => __('Trade In Value'),
		'UPC'                                  => __('UPC'),
		'Warranty'                             => __('Warranty'),
		'WEEETaxValue'                         => __('WEEETaxValue'),
	);
}

function easyazon_get_attribute($attribute) {
	$attributes = easyazon_get_attributes();

	return isset($attributes[$attribute]) ? $attributes[$attribute] : easyazon_split_camel_case($attribute);
}

function easyazon_get_attribute_value($attribute, $value) {
	$transformed = $value;

	if(is_array($transformed)) {
		return implode('<br />', array_map('esc_html', array_values($value)));
	} else {
		return esc_html($transformed);
	}
}

function easyazon_get_identifier_types() {
	return apply_filters(__FUNCTION__, array(
		'ASIN',
		'EAN',
		'ISBN',
		'SKU',
		'UPC',
	));
}

function easyazon_get_identifier_type($identifier_type) {
	$identifier_type = strtoupper($identifier_type);
	$identifier_types = easyazon_get_identifier_types();

	return isset($identifier_types[$identifier_type]) ? $identifier_types[$identifier_type] : current($identifier_types);
}

function easyazon_get_locales() {
	return apply_filters(__FUNCTION__, array(
		'US' => __('United States'),
		'BR' => __('Brazil'),
		'CA' => __('Canada'),
		'CN' => __('China'),
		'FR' => __('France'),
		'DE' => __('Germany'),
		'IT' => __('Italy'),
		'IN' => __('India'),
		'JP' => __('Japan'),
		'ES' => __('Spain'),
		'UK' => __('United Kingdom'),
	));
}

function easyazon_get_locale($locale) {
	$locale = strtoupper($locale);
	$locales = easyazon_get_locales();

	return isset($locales[$locale]) ? $locale : key($locales);
}

function easyazon_get_locale_associate_signup_urls() {
	return apply_filters(__FUNCTION__, array(
		'US' => 'https://affiliate-program.amazon.com/',
		'BR' => 'https://associados.amazon.com.br/',
		'CA' => 'https://associates.amazon.ca/',
		'CN' => 'https://associates.amazon.cn/',
		'DE' => 'https://partnernet.amazon.de/',
		'ES' => 'https://afiliados.amazon.es/',
		'FR' => 'https://partenaires.amazon.fr/',
		'IN' => 'https://affiliate-program.amazon.in/',
		'IT' => 'https://programma-affiliazione.amazon.it/',
		'JP' => 'https://affiliate.amazon.co.jp/',
		'UK' => 'https://affiliate-program.amazon.co.uk/',
	));
}

function easyazon_get_locale_associate_signup_url($locale) {
	$locale = easyazon_get_locale($locale);
	$locale_associate_signup_urls = easyazon_get_locale_associate_signup_urls();

	return isset($locale_associate_signup_urls[$locale]) ? $locale_associate_signup_urls[$locale] : current($locale_associate_signup_urls);
}

function easyazon_get_locale_associate_tags() {
	return apply_filters(__FUNCTION__, array(
		'US' => 'al24-20',
		'BR' => 'al40-20',
		'CA' => 'al25-20',
		'CN' => 'al33-23',
		'DE' => 'al28-21',
		'ES' => 'al32-21',
		'FR' => 'al30-21',
		'IT' => 'al31-21',
		'IN' => 'onlinbouti-21',
		'JP' => 'al32-22',
		'UK' => 'al29-21',
	));
}

function easyazon_get_locale_associate_tag($locale) {
	$locale = easyazon_get_locale($locale);
	$locale_associate_tags = easyazon_get_locale_associate_tags();

	return isset($locale_associate_tags[$locale]) ? $locale_associate_tags[$locale] : current($locale_associate_tags);
}

function easyazon_get_locale_endpoints() {
	return apply_filters(__FUNCTION__, array(
		'US' => 'https://webservices.amazon.com/onca/xml',
		'BR' => 'https://webservices.amazon.com.br/onca/xml',
		'CA' => 'https://webservices.amazon.ca/onca/xml',
		'CN' => 'https://webservices.amazon.cn/onca/xml',
		'DE' => 'https://webservices.amazon.de/onca/xml',
		'ES' => 'https://webservices.amazon.es/onca/xml',
		'FR' => 'https://webservices.amazon.fr/onca/xml',
		'IT' => 'https://webservices.amazon.it/onca/xml',
		'IN' => 'https://webservices.amazon.in/onca/xml',
		'JP' => 'https://webservices.amazon.co.jp/onca/xml',
		'UK' => 'https://webservices.amazon.co.uk/onca/xml',
	));
}

function easyazon_get_locale_endpoint($locale) {
	$locale = easyazon_get_locale($locale);
	$locale_endpoints = easyazon_get_locale_endpoints();

	return isset($locale_endpoints[$locale]) ? $locale_endpoints[$locale] : current($locale_endpoints);
}

function easyazon_get_locale_tlds() {
	return apply_filters(__FUNCTION__, array(
		'US' => 'com',
		'BR' => 'com.br',
		'CA' => 'ca',
		'CN' => 'cn',
		'DE' => 'de',
		'ES' => 'es',
		'FR' => 'fr',
		'IT' => 'it',
		'IN' => 'in',
		'JP' => 'co.jp',
		'UK' => 'co.uk',
	));
}

function easyazon_get_locale_tld($locale) {
	$locale = easyazon_get_locale($locale);
	$locale_tlds = easyazon_get_locale_tlds();

	return isset($locale_tlds[$locale]) ? $locale_tlds[$locale] : current($locale_tlds);
}

function easyazon_get_item_cached($identifier, $locale) {
	$item = wp_cache_get(EASYAZON_ITEM, easyazon_get_item_cache_key($identifier, $locale));

	if(false === $item) {
		$item = get_option(easyazon_get_item_option_name($identifier, $locale), false);
	}

	if(!$item || ($item && (!isset($item['expires']) || $item['expires'] < time()))) {
		$item = false;
	}

	return $item;
}

function easyazon_get_item($identifier, $locale) {
	if(empty($identifier) || empty($locale)) {
		return false;
	} else {
		$item = easyazon_get_item_cached($identifier, $locale);

		if(!$item) {
			$item = easyazon_api_get_item($identifier, 'ASIN', $locale);

			if($item && !is_wp_error($item)) {
				$item = easyazon_set_item($identifier, $locale, $item);
			} else {
				$item = false;
			}
		}
	}

	return $item;
}

function easyazon_get_items($identifiers, $locale) {
	if(empty($identifiers)) { return array(); }

	// This is what we'll return
	$items     = array();

	// Hold the identifiers we need to get information for
	$queryable = array();

	// First we'll look through the identifiers and try to get the cached stuff
	foreach($identifiers as $identifier) {
		$item = easyazon_get_item_cached($identifier, $locale);

		if($item) {
			$items[$identifier] = $item;
		} else {
			$queryable[]        = $identifier;
		}
	}

	// Fetch items from the API that haven't previous been fetched
	$queried_items = easyazon_api_get_items($queryable, 'ASIN', $locale);
	if(is_array($queried_items)) {
		foreach($queried_items as $queried_item) {
			$items[$queried_item['identifier']] = $queried_item;
		}
	}

	// Cache items that need it
	foreach($items as $identifier => $item) {
		if(isset($item['expires'])) { continue; }

		easyazon_set_item($identifier, $locale, $item);
	}

	return $items;
}

function easyazon_get_item_cache_key($identifier, $locale) {
	return "{$identifier}_{$locale}";
}

function easyazon_get_item_option_name($identifier, $locale) {
	return sprintf(EASYAZON_ITEM_OPTION, $identifier, $locale);
}

function easyazon_set_item($identifier, $locale, $item) {
	$item['fetched'] = time();
	$item['expires'] = $expires = time() + EASYAZON_ITEM_CACHE_PERIOD;

	$cache_key = easyazon_get_item_cache_key($identifier, $locale);
	wp_cache_delete(EASYAZON_ITEM, $cache_key);

	$option_name = easyazon_get_item_option_name($identifier, $locale);
	delete_option($option_name);

	wp_cache_set(EASYAZON_ITEM, $item, $cache_key, $expires);
	add_option($option_name, $item, null, 'no');

	return $item;
}

function easyazon_api_get_item($identifier, $identifier_type, $locale = null, $associate_tag = null) {
	$identifier_type = easyazon_get_identifier_type($identifier_type);

	$query = array(
		'AssociateTag' => $associate_tag,
		'IdType' => urlencode($identifier_type),
		'ItemId' => urlencode($identifier),
		'Operation' => 'ItemLookup',
		'ResponseGroup' => 'Images,ItemAttributes,Offers',
		'Sort' => 'relevancerank',
	);

	if('ASIN' !== $identifier_type) {
		$query['SearchIndex'] = 'All';
	}

	return easyazon_api_response_item(easyazon_api_request($query, $locale));
}

function easyazon_api_get_items($identifiers, $identifier_type, $locale = null, $associate_tag = null) {
	$identifiers     = is_array($identifiers) ? array_unique($identifiers) : array();
	$identifier_type = easyazon_get_identifier_type($identifier_type);

	if(empty($identifiers)) {
		return array();
	}

	$items = array();
	foreach(array_chunk($identifiers, 10) as $chunk) {
		$query = array(
			'AssociateTag' => $associate_tag,
			'IdType' => urlencode($identifier_type),
			'ItemId' => urlencode(implode(',', $chunk)),
			'Operation' => 'ItemLookup',
			'ResponseGroup' => 'Images,ItemAttributes,Offers',
			'Sort' => 'relevancerank',
		);

		if('ASIN' !== $identifier_type) {
			$query['SearchIndex'] = 'All';
		}

		$response = easyazon_api_response_items(easyazon_api_request($query, $locale), $locale);

		if(!is_wp_error($response)) {
			$items = array_merge($items, $response['items']);
		}
	}


	return $items;
}

function easyazon_api_search($keywords, $page = 1, $locale = null, $associate_tag = null, $args = array()) {
	$query = array_merge(array(
		'AssociateTag' => $associate_tag,
		'ItemPage' => $page,
		'Keywords' => urlencode($keywords),
		'Operation' => 'ItemSearch',
		'ResponseGroup' => 'BrowseNodes,Images,ItemAttributes,Offers',
		'SearchIndex' => 'All',
	), $args);

	return easyazon_api_response_items(easyazon_api_request($query, $locale), $locale);
}

function easyazon_api_request($query, $locale = null) {
	$locale = easyazon_get_locale($locale);

	if(!isset($query['AssociateTag']) || empty($query['AssociateTag'])) {
		$query['AssociateTag'] = easyazon_get_locale_associate_tag($locale);
	}

	if(!isset($query['AWSAccessKeyId']) || empty($query['AWSAccessKeyId'])) {
		$query['AWSAccessKeyId'] = easyazon_get_setting('access_key');
	}

	if(!isset($query['Service']) || empty($query['Service'])) {
		$query['Service'] = 'AWSECommerceService';
	}

	if(!isset($query['Timestamp']) || empty($query['Timestamp'])) {
		$query['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
	}

	if(!isset($query['Version']) || empty($query['Version'])) {
		$query['Version'] = '2013-08-01';
	}

	$request_url = easyazon_api_request_sign(add_query_arg($query, easyazon_get_locale_endpoint($locale)));
	$response = wp_remote_get($request_url, array(
		'timeout' => 10,
		'user-agent' => sprintf(__('EasyAzon V%s'), EASYAZON_VERSION),
	));

	return is_wp_error($response) ? $response : easyazon_api_response(wp_remote_retrieve_body($response));
}

function easyazon_api_request_sign($url) {
	// Decode anything already encoded
	$url = urldecode($url);

	// Parse the URL into $urlparts
	$urlparts = parse_url($url);

	// Build $params with each name/value pair
	foreach (explode('&', $urlparts['query']) as $part) {
		if (strpos($part, '=')) {
			list($name, $value) = explode('=', $part);
		} else {
			$name = $part;
			$value = '';
		}
		$params[$name] = $value;
	}

	// Sort the array by key
	ksort($params);

	// Build the canonical query string
	$canonical = '';
	foreach ($params as $key=>$val) {
		$canonical .= "{$key}=".rawurlencode($val).'&';
	}
	// Remove the trailing ampersand
	$canonical = preg_replace("/&$/", '', $canonical);

	// Some common replacements and ones that Amazon specifically mentions
	$canonical = str_replace(array(' ', '+', ', ', ';'), array('%20', '%20', urlencode(','), urlencode(':')), $canonical);

	// Build the si
	$string_to_sign = "GET\n{$urlparts['host']}\n{$urlparts['path']}\n$canonical";

	// Calculate our actual signature and base64 encode it
	$signature = base64_encode(hash_hmac('sha256', $string_to_sign, easyazon_get_setting('secret_key'), true));

	// Finally re-build the URL with the proper string and include the Signature
	return "{$urlparts['scheme']}://{$urlparts['host']}{$urlparts['path']}?$canonical&Signature=".rawurlencode($signature);
}

function easyazon_api_response($response_string) {
	$xml = @simplexml_load_string($response_string);

	if(!is_object($xml)) {
		$response = new WP_Error('parse_response_xml_error', __('Could not parse the response from Amazon as XML.'));
	} else if(isset($xml->Error)) {
		$response = new WP_Error((string)$xml->Error->Code, (string)$xml->Error->Message);
	} else if(isset($xml->Items->Request->Errors->Error)) {
		$response = new WP_Error((string)$xml->Items->Request->Errors->Error->Code, (string)$xml->Items->Request->Errors->Error->Message);
	} else {
		$response = json_decode(json_encode($xml), true);

		if(isset($response['Items']) && isset($response['Items']['Item'])) {
			if(isset($response['Items']['Item']) && isset($response['Items']['Item']['ASIN'])) {
				$response['Items']['Item'] = array($response['Items']['Item']);
			}

			foreach($response['Items']['Item'] as $item_key => $item) {
				if(!isset($item['ImageSets']) || !isset($item['ImageSets']['ImageSet']) || !is_array($item['ImageSets']['ImageSet'])) {
					$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] = array();
				}

				if(isset($response['Items']['Item'][$item_key]['ImageSets']['ImageSet'][0])) {
					$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] = $response['Items']['Item'][$item_key]['ImageSets']['ImageSet'][0];
				}
			}
		}
	}

	if(is_wp_error($response)) {
		easyazon_debug($response);
	}

	return $response;
}

function easyazon_api_response_item($response) {
	if(is_wp_error($response)) {
		return $response;
	}

	$item = isset($response['Items']) && is_array($response['Items']) && isset($response['Items']['Item']) && is_array($response['Items']['Item']) ? current($response['Items']['Item']) : false;

	return $item ? easyazon_api_response_normalize($item) : new WP_Error('no_item_found', __('No items were found matching the specified criteria'));
}

function easyazon_api_response_items($response, $locale) {
	if(is_wp_error($response)) {
		return $response;
	}

	$keywords = isset($response['Items']) && isset($response['Items']['Request']) && isset($response['Items']['Request']['ItemSearchRequest']) && isset($response['Items']['Request']['ItemSearchRequest']['Keywords']) ? $response['Items']['Request']['ItemSearchRequest']['Keywords'] : '';
	$page     = isset($response['Items']) && isset($response['Items']['Request']) && isset($response['Items']['Request']['ItemSearchRequest']) && isset($response['Items']['Request']['ItemSearchRequest']['ItemPage']) ? max(1, $response['Items']['Request']['ItemSearchRequest']['ItemPage']) : 1;
	$pages    = isset($response['Items']) && isset($response['Items']['TotalPages']) ? min(5, intval($response['Items']['TotalPages'])) : 1;
	$items    = isset($response['Items']) && isset($response['Items']['Item']) ? array_map('easyazon_api_response_normalize', $response['Items']['Item']) : array();

	return compact(
		'keywords',
		'locale',
		'page',
		'pages',
		'items'
	);
}

function easyazon_api_response_normalize($item) {
	$attributes = isset($item['ItemAttributes']) && is_array($item['ItemAttributes']) ? easyazon_api_response_normalize_attributes($item['ItemAttributes']) : array();
	$identifier = isset($item['ASIN']) ? $item['ASIN'] : false;
	$images     = array();
	$nodes      = isset($item['BrowseNodes']) && is_array($item['BrowseNodes']) ? easyazon_api_response_normalize_browse_nodes($item['BrowseNodes']) : array();
	$title      = isset($attributes['Title']) ? $attributes['Title'] : '';
	$url        = current(explode('?', urldecode($item['DetailPageURL'])));

	$offer = isset($item['Offers']) && is_array($item['Offers']) && isset($item['Offers']['Offer']) && is_array($item['Offers']['Offer']) ? $item['Offers']['Offer'] : array();
	$offer = array(
		'condition' => isset($offer['OfferAttributes']) && isset($offer['OfferAttributes']['Condition']) ? $offer['OfferAttributes']['Condition'] : __('Unknown'),
		'price' => isset($offer['OfferListing']) && isset($offer['OfferListing']['Price']) && isset($offer['OfferListing']['Price']['FormattedPrice']) ? $offer['OfferListing']['Price']['FormattedPrice'] : __('N/A'),
		'saved' => isset($offer['OfferListing']) && isset($offer['OfferListing']['AmountSaved']) && isset($offer['OfferListing']['AmountSaved']['FormattedPrice']) ? $offer['OfferListing']['AmountSaved']['FormattedPrice'] : __('N/A'),
	);

	$lowest_price_n = isset($item['OfferSummary']) && isset($item['OfferSummary']['LowestNewPrice']) && isset($item['OfferSummary']['LowestNewPrice']['FormattedPrice']) ? $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] : false;
	$lowest_price_r = isset($item['OfferSummary']) && isset($item['OfferSummary']['LowestRefurbishedPrice']) && isset($item['OfferSummary']['LowestRefurbishedPrice']['FormattedPrice']) ? $item['OfferSummary']['LowestRefurbishedPrice']['FormattedPrice'] : false;
	$lowest_price_u = isset($item['OfferSummary']) && isset($item['OfferSummary']['LowestUsedPrice']) && isset($item['OfferSummary']['LowestUsedPrice']['FormattedPrice']) ? $item['OfferSummary']['LowestUsedPrice']['FormattedPrice'] : false;

	$image_urls = array();

	if(isset($item['ImageSets']) && is_array($item['ImageSets']) && isset($item['ImageSets']['ImageSet']) && is_array($item['ImageSets']['ImageSet'])) {
		$image_sets = isset($item['ImageSets'][0]) ? $item['ImageSets']['ImageSet'] : array($item['ImageSets']['ImageSet']);

		foreach($image_sets as $image_set) {
			foreach($image_set as $image_key => $image) {
				if('@attributes' === $image_key || in_array($image['URL'], $image_urls)) { continue; }

				$image_urls[] = $image['URL'];

				$images[] = array(
					'url' => $image['URL'],
					'height' => $image['Height'],
					'width' => $image['Width'],
				);
			}
		}
	} else {
		if(isset($item['SmallImage']) && !in_array($item['SmallImage']['URL'], $image_urls)) {
			$image_urls[] = $item['SmallImage']['URL'];

			$images[] = array(
				'url' => $item['SmallImage']['URL'],
				'height' => $item['SmallImage']['Height'],
				'width' => $item['SmallImage']['Width'],
			);
		}

		if(isset($item['MediumImage']) && !in_array($item['MediumImage']['URL'], $image_urls)) {
			$image_urls[] = $item['MediumImage']['URL'];

			$images[] = array(
				'url' => $item['MediumImage']['URL'],
				'height' => $item['MediumImage']['Height'],
				'width' => $item['MediumImage']['Width'],
			);
		}

		if(isset($item['LargeImage']) && !in_array($item['LargeImage']['URL'], $image_urls)) {
			$image_urls[] = $item['LargeImage']['URL'];

			$images[] = array(
				'url' => $item['LargeImage']['URL'],
				'height' => $item['LargeImage']['Height'],
				'width' => $item['LargeImage']['Width'],
			);
		}
	}

	return compact(
		'attributes',
		'identifier',
		'images',
		'lowest_price_n',
		'lowest_price_r',
		'lowest_price_u',
		'nodes',
		'offer',
		'title',
		'url'
	);
}

function easyazon_api_response_normalize_attributes($attributes) {
	$normalized = array();

	// $attributes = array_intersect_key($attributes, easyazon_get_attributes());

	foreach($attributes as $name => $value) {
		if(is_string($value)) {
			$normalized[$name] = $value;
		} else if(is_array($value) && preg_match('#^.*Dimensions$#', $name)) {
			$normalized[$name] = $value;
		} else if(is_array($value) && preg_match('#^.*List$#', $name)) {
			$normalized[$name] = array_values($value);
		} else if(is_array($value) && preg_match('#^.*Price$#', $name)) {
			$normalized[$name] = $value['FormattedPrice'];
		} else if(is_array($value)) {
			$normalized[$name] = array_values($value);
		}
	}

	return $normalized;
}

function easyazon_api_response_normalize_browse_nodes($browse_nodes) {
	if(isset($browse_nodes['BrowseNode'])) {
		$browse_nodes = isset($browse_nodes['BrowseNode'][0]) ? $browse_nodes['BrowseNode'] : array($browse_nodes['BrowseNode']);
	} else {
		$browse_nodes = array();
	}

	$normalized = array();
	foreach($browse_nodes as $browse_node) {
		$normalized[] = array(
			'ancestors' => isset($browse_node['Ancestors']) ? (easyazon_api_response_normalize_browse_nodes($browse_node['Ancestors'])) : array(),
			'children'  => isset($browse_node['Children']) ? (easyazon_api_response_normalize_browse_nodes($browse_node['Children'])) : array(),
			'id'        => $browse_node['BrowseNodeId'],
			'name'      => $browse_node['Name'],
			'root'      => isset($browse_node['IsCategoryRoot']) && '1' == $browse_node['IsCategoryRoot'],
		);
	}

	return $normalized;
}
