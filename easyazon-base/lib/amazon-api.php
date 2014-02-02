<?php

class EasyAzon_Amazon_API {
	private static $locale_associate_tags = array(
		'CA' => 'al25-20',
		'CN' => 'al33-23',
		'DE' => 'al28-21',
		'ES' => 'al32-21',
		'FR' => 'al30-21',
		'IT' => 'al31-21',
		'IN' => 'onlinbouti-21',
		'JP' => 'al32-22',
		'UK' => 'al29-21',
		'US' => 'al24-20'
	);

	private static $locale_endpoints = array(
		'CA' => 'http://webservices.amazon.ca/onca/xml',
		'CN' => 'http://webservices.amazon.cn/onca/xml',
		'DE' => 'http://webservices.amazon.de/onca/xml',
		'ES' => 'http://webservices.amazon.es/onca/xml',
		'FR' => 'http://webservices.amazon.fr/onca/xml',
		'IT' => 'http://webservices.amazon.it/onca/xml',
		'IN' => 'http://webservices.amazon.in/onca/xml',
		'JP' => 'http://webservices.amazon.co.jp/onca/xml',
		'UK' => 'http://webservices.amazon.co.uk/onca/xml',
		'US' => 'http://webservices.amazon.com/onca/xml',
	);

	private static $locale_tlds = array(
		'CA' => 'ca',
		'CN' => 'cn',
		'DE' => 'de',
		'ES' => 'es',
		'FR' => 'fr',
		'IT' => 'it',
		'IN' => 'in',
		'JP' => 'co.jp',
		'UK' => 'co.uk',
		'US' => 'com'
	);

	private static $item_lookup_identifier_types = array('SKU', 'UPC', 'EAN', 'ISBN', 'ASIN');

	private $access_key_id = '';
	private $secret_access_key = '';

	/// Construction

	public function __construct($access_key_id, $secret_access_key) {
		$this->set_credentials($access_key_id, $secret_access_key);
	}

	/// API Operations

	public function item_lookup($identifier, $identifier_type, $associate_tag, $locale) {
		$query_parameters = array(
			'AssociateTag' => $associate_tag,
			'IdType' => urlencode($identifier_type),
			'ItemId' => urlencode($identifier),
			'Operation' => 'ItemLookup',
			'ResponseGroup' => 'Images,ItemAttributes,Offers,Reviews',
			'Sort' => 'relevancerank',
		);

		if('ASIN' != $identifier_type) {
			$query_parameters['SearchIndex'] = 'All';
		}

		return $this->make_request($query_parameters, $locale);
	}

	public function item_search($keywords, $search_index, $item_page, $associate_tag, $locale) {
		$query_parameters = array(
			'AssociateTag' => $associate_tag,
			'ItemPage' => $item_page,
			'Keywords' => urlencode($keywords),
			'Operation' => 'ItemSearch',
			'ResponseGroup' => 'Images,ItemAttributes,Offers',
			'SearchIndex' => $search_index,
		);

		return $this->make_request($query_parameters, $locale);
	}

	/// Requests

	private function make_request($query_parameters, $locale) {
		$locale = strtoupper($locale);

		if(empty($query_parameters['AssociateTag'])) {
			$query_parameters['AssociateTag'] = self::$locale_associate_tags[$locale];
		}

		if(!isset($query_parameters['AWSAccessKeyId'])) {
			$query_parameters['AWSAccessKeyId'] = $this->access_key_id;
		}

		if(!isset($query_parameters['Service'])) {
			$query_parameters['Service'] = 'AWSECommerceService';
		}

		if(!isset($query_parameters['Timestamp'])) {
			$query_parameters['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
		}

		if(!isset($query_parameters['Version'])) {
			$query_parameters['Version'] = '2011-08-01';
		}

		$query_url = self::sign_request(add_query_arg($query_parameters, self::$locale_endpoints[$locale]));
		$response = wp_remote_get($query_url, array('timeout' => 10, 'user-agent' => __('EasyAzon for WordPress')));

		return is_wp_error($response) ? $response : $this->parse_response(wp_remote_retrieve_body($response));
	}


	private function sign_request($url) {
		$original = $url;

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
		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $this->secret_access_key, true));

		// Finally re-build the URL with the proper string and include the Signature
		return "{$urlparts['scheme']}://{$urlparts['host']}{$urlparts['path']}?$canonical&Signature=".rawurlencode($signature);
	}

	/// Responses

	private function parse_response($response_string) {
		$xml = @simplexml_load_string($response_string);

		if(!is_object($xml)) {
			$response = new WP_Error('parse_response_xml_error', __('Could not parse the response from Amazon as XML.'));
		} else if(isset($xml->Error)) {
			$response = new WP_Error((string)$xml->Error->Code, (string)$xml->Error->Message);
		} else if(isset($xml->Items->Request->Errors->Error)) {
			$response = new WP_Error((string)$xml->Items->Request->Errors->Error->Code, (string)$xml->Items->Request->Errors->Error->Message);
		} else {
			$response = json_decode(json_encode($xml), true);

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

		return $response;
	}

	/// Utility

	public function set_credentials($access_key_id, $secret_access_key) {
		$this->access_key_id = $access_key_id;
		$this->secret_access_key = $secret_access_key;
	}

	public static function get_locale_tld($locale) {
		return isset(self::$locale_tlds[$locale]) ? self::$locale_tlds[$locale] : self::$locale_tlds['US'];
	}
}
