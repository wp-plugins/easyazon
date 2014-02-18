<?php

class EasyAzon_Core {
	/// Constants

	//// Notifications
	const NOTIFICATION_OPTION = 'easyazon_notifier';
	const NOTIFICATION_URL = 'http://easyazon.com/tracker/tracker.php';

	//// Shortcodes
	const SHORTCODE_LINK = 'easyazon_link';
	const SHORTCODE_LINK_LEGACY = 'easyazon-link';
	const SHORTCODE_LINK_SIMPLEAZON_LEGACY = 'simpleazon-link';

	/// Defaults
	private static $default_settings = null;

	public static function init() {
		self::add_actions();
		self::add_filters();
		self::register_shortcodes();
	}

	private static function add_actions() {
		add_action('easyazon_after_search', array(__CLASS__, 'display_search_upgrade_nag'));
		add_action('easyazon_register_resources', array(__CLASS__, 'register_resources'), 10);
		add_action('easyazon_register_shortcodes', array(__CLASS__, 'register_shortcodes'));

		add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));
		add_action('init', array(__CLASS__, 'usage_notifier'));
		add_action('wp_ajax_easyazon', array(__CLASS__, 'ajax_actions'));

		if(is_admin()) {
			add_action('easyazon_admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'), 11);
			add_action('easyazon_load_settings_page', array(__CLASS__, 'load_settings_page'), 10);
			add_action('easyazon_shortcode_link_options', array(__CLASS__, 'add_shortcode_link_options'));
			add_action('easyazon_shortcode_after_actions', array(__CLASS__, 'shortcode_after_actions'));
			add_action('media_buttons', array(__CLASS__, 'add_media_button'), 11);
			add_action('media_upload_easyazon', array(__CLASS__, 'add_media_upload_output'));
		} else {

		}
	}

	private static function add_filters() {
		// Searchable
		add_filter('easyazon_get_associate_tags', array(__CLASS__, 'add_associate_tags'), 10);
		add_filter('easyazon_get_locales', array(__CLASS__, 'add_locales'));
		add_filter('easyazon_get_locales', array(__CLASS__, 'sort_locales'), 100001);
		add_filter('easyazon_get_search_result_actions', array(__CLASS__, 'add_search_result_actions'));

		// Settings filters
		add_filter('easyazon_clean_settings', array(__CLASS__, 'clean_settings'), 10, 2);
		add_filter('easyazon_default_settings', array(__CLASS__, 'default_settings'));
		add_filter('easyazon_sanitize_settings', array(__CLASS__, 'sanitize_settings'), 10, 3);

		// Links
		add_filter('easyazon_get_product_link_attributes', array(__CLASS__, 'get_product_link_attributes'), 10, 2);

		// Shortcodes
		add_filter('easyazon_shortcode_default_attributes', array(__CLASS__, 'add_shortcode_default_attributes'), 10, 2);

		// Media upload tab for EasyAzon
		if(is_admin()) {
			add_filter('media_upload_tabs', array(__CLASS__, 'add_media_upload_tabs'));
		}
	}

	/// Callbacks

	//// AJAX

	public static function ajax_actions() {
		$request = stripslashes_deep($_REQUEST);

		$request['searchTerms'] = isset($request['searchTerms']) ? $request['searchTerms'] : '';
		$request['index'] = isset($request['index']) ? $request['index'] : 'All';
		$request['page'] = isset($request['page']) ? $request['page'] : 1;
		$request['tag'] = isset($request['tag']) ? $request['tag'] : '';
		$request['locale'] = isset($request['locale']) ? $request['locale'] : 'US';

		$amazon_api = new EasyAzon_Amazon_API(easyazon_get_setting('access_key_id'), easyazon_get_setting('secret_access_key'));

		if(preg_match('#^[0-9A-Z]{10}$#', $request['searchTerms'])) {
			$amazon_response = $amazon_api->item_lookup($request['searchTerms'], 'ASIN', $request['tag'], $request['locale']);
		} else {
			$amazon_response = $amazon_api->item_search($request['searchTerms'], $request['index'], $request['page'], $request['tag'], $request['locale']);
		}

		wp_send_json(self::_parse_amazon_response($amazon_response, $request));
	}

	//// Generic

	public static function usage_notifier() {

		$value = get_option(self::NOTIFICATION_OPTION);

		if('yes' !== $value) {
			$response = wp_remote_get(add_query_arg(array('domain' => md5(parse_url(home_url('/'), PHP_URL_HOST))), self::NOTIFICATION_URL));
			update_option(self::NOTIFICATION_OPTION, 'yes');
		}
	}

	//// Output

	public static function get_product_link_attributes($attributes, $input) {
		if(isset($input['new_window']) && ('yes' === $input['new_window'] || ('default' === $input['new_window'] && 'yes' === easyazon_get_setting('links_new_window')))) {
			$attributes['target'] = array('_blank');
		}

		if(isset($input['nofollow']) && ('yes' === $input['nofollow'] || ('default' === $input['nofollow'] && 'yes' === easyazon_get_setting('links_nofollow')))) {
			$attributes['rel'] = isset($attributes['rel']) && is_array($attributes['rel']) ? array_merge($attributes['rel'], array('nofollow')) : array('nofollow');
		}

		return $attributes;
	}

	//// Shortcodes

	public static function add_shortcode_default_attributes($atts, $type) {
		return array_merge($atts, array(
			'asin' => '',
			'locale' => 'US',
			'new_window' => 'default',
			'nofollow' => 'default',
			'tag' => false,
		));
	}

	public static function register_shortcodes() {
		add_shortcode(self::SHORTCODE_LINK, array(__CLASS__, 'shortcode_link'));
		add_shortcode(self::SHORTCODE_LINK_LEGACY, array(__CLASS__, 'shortcode_link'));
		add_shortcode(self::SHORTCODE_LINK_SIMPLEAZON_LEGACY, array(__CLASS__, 'shortcode_link'));
	}

	public static function shortcode_link($atts, $content = null) {
		$atts = shortcode_atts(apply_filters('easyazon_shortcode_default_attributes', array(), 'link'), $atts);

		$link_attributes = easyazon_get_product_link_attributes($atts);
		$link_url = easyazon_get_product_link_url($atts);

		return sprintf('<a %1$s href="%2$s">%3$s</a>', easyazon_attributes_array_to_attributes_string($link_attributes), esc_attr(esc_url($link_url)), $content);
	}

	//// Search

	public static function add_search_result_actions($actions) {
		$actions[] = sprintf('<a href="#" data-bind="click: $parent.shortcodeText">%1$s</a>', __('Text Link'));

		return $actions;
	}

	public static function add_shortcode_link_options($context) {
		include('views/search/_inc/link-options.php');
	}

	public static function shortcode_after_actions($context) {
		if(!class_exists('EasyAzon_Pro')) {
			include('views/search/_inc/upsell.php');
		}
	}

	public static function display_search_upgrade_nag() {
		echo '<p class="easyazon-upsell">' . sprintf(__('Unlock extra affiliate link types with <a href="%1$s" target="_blank">EasyAzon Pro</a> including images, call to actions, info blocks and link to search results pages - <a href="%1$s" target="_blank">Upgrade Today!</a>'), esc_attr(esc_url('http://easyazon.com/why-pro/?utm_source=easyazonplugin&utm_medium=link&utm_campaign=easyazonsearch'))) . '</p>';
	}

	//// Administrative interface

	public static function add_media_button($editor_id = 'content') {
		if(in_array(get_post_type(), easyazon_get_setting('content_types'))) {
			printf('<a href="#" id="insert-easyazon-button" class="button insert-easyazon add_media" title="%1$s" data-editor="%2$s">%3$s</a>', esc_attr(__('Link to Amazon')), esc_attr($editor_id), esc_html(__('EasyAzon')));
		}
	}

	public static function add_media_upload_output() {
		return wp_iframe(array(__CLASS__, 'get_media_upload_output'));
	}

	public static function get_media_upload_output() {
		$placeholder = plugins_url('resources/backend/image/placeholder.gif', __FILE__);

		include('views/search/process.php');
	}

	public static function add_media_upload_tabs($tabs) {
		$screen = get_current_screen();
		if((isset($screen->post_type) && in_array($screen->post_type, easyazon_get_setting('content_types'))) || (isset($screen->id) && 'media-upload' === $screen->id)) {
			return array_merge($tabs, array('easyazon' => __('EasyAzon')));
		}
	}

	public static function admin_enqueue_scripts() {
		$access_key_id = easyazon_get_setting('access_key_id');
		$secret_access_key = easyazon_get_setting('secret_access_key');

		if(!empty($access_key_id) && !empty($secret_access_key) && self::_should_enqueue()) {
			do_action('easyazon_admin_enqueue_scripts');
		}
	}

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazon-backend');
		wp_enqueue_style('easyazon-backend');
	}

	public static function register_resources() {
		wp_register_script('knockout', plugins_url('resources/vendor/knockout.min.js', __FILE__), array(), '3.0.0', true);
		wp_register_script('easyazon-backend', plugins_url('resources/backend/easyazon.js', __FILE__), array('jquery', 'knockout'), EASYAZON_VERSION, true);
		wp_localize_script('easyazon-backend', 'EasyAzon', apply_filters('easyazon_localize_script', array(
			'locales' => array_values(easyazon_get_locales()),
			'locale' => easyazon_get_setting('default_search_locale'),
			'tags' => easyazon_get_associate_tags(),

			'ajaxAction' => 'easyazon',

			'placeholderUrl' => plugins_url('resources/backend/img/placeholder.gif', __FILE__),
			'placeholderHeight' => 75,
			'placeholderWidth' => 75,

			'shortcodeText' => self::SHORTCODE_LINK,

			'noPrice' => __('N/A'),

			'stateName' => 'iframe:easyazon',
			'stateTitle' => __('EasyAzon'),
		)));

		wp_register_style('easyazon-backend', plugins_url('resources/backend/easyazon.css', __FILE__), array('media-views'), EASYAZON_VERSION);
	}

	private static function _should_enqueue() {
		$screen = get_current_screen();

		return (isset($screen->post_type) && in_array($screen->post_type, easyazon_get_setting('content_types')))
				|| (isset($screen->id) && in_array($screen->id, array(sprintf('toplevel_page_%1$s', EasyAzon_Base::SETTINGS_PAGE), 'media-upload')));
	}

	//// Settings page

	public static function load_settings_page() {
		add_settings_section('credentials', __('Amazon Credentials'), array(__CLASS__, 'display_settings_section__credentials'), EasyAzon_Base::SETTINGS_PAGE);
		add_settings_field('access_key_id', __('Access Key ID'), array(__CLASS__, 'display_settings_field__credentials__access_key_id'), EasyAzon_Base::SETTINGS_PAGE, 'credentials', array('label_for' => easyazon_get_settings_id('access_key_id')));
		add_settings_field('secret_access_key', __('Secret Access Key'), array(__CLASS__, 'display_settings_field__credentials__secret_access_key'), EasyAzon_Base::SETTINGS_PAGE, 'credentials', array('label_for' => easyazon_get_settings_id('secret_access_key')));

		add_settings_section('associates', __('Amazon Associates'), array(__CLASS__, 'display_settings_section__associates'), EasyAzon_Base::SETTINGS_PAGE);

		$locales = easyazon_get_locales();
		foreach($locales as $locale_key => $locale_data) {
			$locale_name = $locale_data['name'];
			$settings_id = easyazon_get_settings_id("associates_tags_{$locale_key}");
			$settings_help = isset($locale_data['help']) ? $locale_data['help'] : '';;

			$args = array(
				'label_for' => $settings_id,
				'locale_data' => $locale_data,
				'locale_key' => $locale_key,
				'help_text' => $settings_help,
			);

			add_settings_field("associates_tags_{$locale_key}", $locale_name, array(__CLASS__, 'display_settings_field__associates__associates_tags'), EasyAzon_Base::SETTINGS_PAGE, 'associates', $args);

			$help = '';
		}

		add_settings_section('search', __('Search Options'), array(__CLASS__, 'display_settings_section__search'), EasyAzon_Base::SETTINGS_PAGE);
		add_settings_field('default_search_locale', __('Default Search Locale'), array(__CLASS__, 'display_settings_field__search__default_search_locale'), EasyAzon_Base::SETTINGS_PAGE, 'search');
		add_settings_field('content_types', __('Enabled Content Types'), array(__CLASS__, 'display_settings_field__search__content_types'), EasyAzon_Base::SETTINGS_PAGE, 'search');

		add_settings_section('links', __('Link Options'), array(__CLASS__, 'display_settings_section__links'), EasyAzon_Base::SETTINGS_PAGE);
		add_settings_field('links_new_window', __('New Window'), array(__CLASS__, 'display_settings_field__links__new_window'), EasyAzon_Base::SETTINGS_PAGE, 'links', array('label_for' => easyazon_get_settings_id('links_new_window_yes')));

		add_settings_section('links-extra', '', '__return_false', EasyAzon_Base::SETTINGS_PAGE);
		add_settings_field('links_nofollow', __('No Follow'), array(__CLASS__, 'display_settings_field__links__nofollow'), EasyAzon_Base::SETTINGS_PAGE, 'links-extra', array('label_for' => easyazon_get_settings_id('links_nofollow_yes')));

		if(!class_exists('EasyAzon_Pro')) {
			add_settings_section('upsell', __('Upgrade to EasyAzon Pro'), array(__CLASS__, 'display_settings_section__upsell'), EasyAzon_Base::SETTINGS_PAGE);
		}
	}

	//// Settings sections

	public static function display_settings_section__associates() {
		include('views/settings/associates.php');
	}

	public static function display_settings_section__credentials() {
		include('views/settings/credentials.php');
	}

	public static function display_settings_section__links() {
		include('views/settings/links.php');
	}

	public static function display_settings_section__search() {
		include('views/settings/search.php');
	}

	public static function display_settings_section__upsell() {
		include('views/settings/upsell.php');
	}

	//// Settings fields

	///// Associates

	public static function display_settings_field__associates__associates_tags($args) {
		$locale_data = $args['locale_data'];
		$locale_key = $args['locale_key'];

		$setting_key = "associates_tags_{$locale_key}";
		$signup_url = $locale_data['signup'];

		$associates_tag = easyazon_get_setting($setting_key);

		$settings_id = easyazon_get_settings_id($setting_key);
		$settings_name = easyazon_get_settings_name($setting_key);
		$settings_value = esc_attr($associates_tag);

		printf('<input type="text" class="regular-text code" id="%1$s" name="%2$s" value="%3$s" /> <a href="%4$s" target="_blank">%5$s</a>', $settings_id, $settings_name, $settings_value, $signup_url, __('Sign Up'));
		printf('<p class="description">%1$s</p>', $args['help_text']);
		easyazon_the_settings_error($setting_key);
	}

	///// Credentials

	public static function display_settings_field__credentials__access_key_id() {
		$access_key_id = easyazon_get_setting('access_key_id');

		$settings_error = easyazon_has_settings_error('access_key_id') ? 'easyazon-error' : '';
		$settings_id = easyazon_get_settings_id('access_key_id');
		$settings_name = easyazon_get_settings_name('access_key_id');
		$settings_value = esc_attr($access_key_id);

		printf('<input type="text" class="regular-text code %1$s" id="%2$s" name="%3$s" value="%4$s" />', $settings_error, $settings_id, $settings_name, $settings_value);
		easyazon_the_settings_error('access_key_id');
	}

	public static function display_settings_field__credentials__secret_access_key() {
		$secret_access_key = easyazon_get_setting('secret_access_key');

		$settings_error = easyazon_has_settings_error('secret_access_key') ? 'easyazon-error' : '';
		$settings_id = easyazon_get_settings_id('secret_access_key');
		$settings_name = easyazon_get_settings_name('secret_access_key');
		$settings_value = esc_attr($secret_access_key);

		printf('<input type="text" class="regular-text code %1$s" id="%2$s" name="%3$s" value="%4$s" />', $settings_error, $settings_id, $settings_name, $settings_value);
		easyazon_the_settings_error('secret_access_key');
	}

	///// Links

	public static function display_settings_field__links__new_window() {
		$links_new_window = easyazon_get_setting('links_new_window');

		$settings_checked = 'yes' === $links_new_window ? 'checked="checked"' : '';
		$settings_error = easyazon_has_settings_error('links_new_window') ? 'easyazon-error' : '';
		$settings_id_no = easyazon_get_settings_id('links_new_window_no');
		$settings_id_yes = easyazon_get_settings_id('links_new_window_yes');
		$settings_name = easyazon_get_settings_name('links_new_window');

		printf('<input type="hidden" id="%1$s" name="%2$s" value="no" />', $settings_id_no, $settings_name);
		printf('<label><input type="checkbox" id="%1$s" name="%2$s" %3$s value="yes" /> %4$s</label>', $settings_id_yes, $settings_name, $settings_checked, __('I want all EasyAzon links to open in a new window or tab'));
		easyazon_the_settings_error('links_new_window');
	}

	public static function display_settings_field__links__nofollow() {
		$links_nofollow = easyazon_get_setting('links_nofollow');

		$settings_checked = 'yes' === $links_nofollow ? 'checked="checked"' : '';
		$settings_error = easyazon_has_settings_error('links_nofollow') ? 'easyazon-error' : '';
		$settings_id_no = easyazon_get_settings_id('links_nofollow_no');
		$settings_id_yes = easyazon_get_settings_id('links_nofollow_yes');
		$settings_name = easyazon_get_settings_name('links_nofollow');

		printf('<input type="hidden" id="%1$s" name="%2$s" value="no" />', $settings_id_no, $settings_name);
		printf('<label><input type="checkbox" id="%1$s" name="%2$s" %3$s value="yes" /> %4$s</label>', $settings_id_yes, $settings_name, $settings_checked, __('I want the <code>nofollow</code> attribute applied to all EasyAzon links'));
		easyazon_the_settings_error('links_nofollow');
	}

	public static function display_settings_field__links__cloaking() {
		$links_cloaking = easyazon_get_setting('links_cloaking');

		$settings_checked = '';
		$settings_error = easyazon_has_settings_error('links_cloaking') ? 'easyazon-error' : '';
		$settings_id_no = easyazon_get_settings_id('links_cloaking_no');
		$settings_id_yes = easyazon_get_settings_id('links_cloaking_yes');
		$settings_name = easyazon_get_settings_name('links_cloaking');

		printf('<input type="hidden" id="%1$s" name="%2$s" value="no" />', $settings_id_no, $settings_name);
		printf('<label class="easyazon-disabled"><input disabled="disabled" type="checkbox" id="%1$s" name="%2$s" %3$s value="yes" /> %4$s</label>', $settings_id_yes, $settings_name, $settings_checked, __('I want to automatically cloak all EasyAzon links'));
		easyazon_the_settings_error('links_cloaking');
	}

	public static function display_settings_field__links__popups() {
		$links_popups = easyazon_get_setting('links_popups');

		$settings_checked = '';
		$settings_error = easyazon_has_settings_error('links_popups') ? 'easyazon-error' : '';
		$settings_id_no = easyazon_get_settings_id('links_popups_no');
		$settings_id_yes = easyazon_get_settings_id('links_popups_yes');
		$settings_name = easyazon_get_settings_name('links_popups');

		printf('<input type="hidden" id="%1$s" name="%2$s" value="no" />', $settings_id_no, $settings_name);
		printf('<label class="easyazon-disabled"><input disabled="disabled" type="checkbox" id="%1$s" name="%2$s" %3$s value="yes" /> %4$s</label>', $settings_id_yes, $settings_name, $settings_checked, __('I want to display information popups when visitors hover over Amazon product links'));
		easyazon_the_settings_error('links_popups');
	}

	public static function display_settings_field__links__add_to_cart() {
		$links_add_to_cart = easyazon_get_setting('links_add_to_cart');

		$settings_checked = '';
		$settings_error = easyazon_has_settings_error('links_add_to_cart') ? 'easyazon-error' : '';
		$settings_id_no = easyazon_get_settings_id('links_add_to_cart_no');
		$settings_id_yes = easyazon_get_settings_id('links_add_to_cart_yes');
		$settings_name = easyazon_get_settings_name('links_add_to_cart');

		printf('<input type="hidden" id="%1$s" name="%2$s" value="no" />', $settings_id_no, $settings_name);
		printf('<label class="easyazon-disabled"><input disabled="disabled" type="checkbox" id="%1$s" name="%2$s" %3$s value="yes" /> %4$s</label>', $settings_id_yes, $settings_name, $settings_checked, __('I want products to automatically be added to a visitor\'s cart when they click on an EasyAzon link'));
		printf('<p class="description easyazon-sell">%1$s</p>', __('<strong>Potential Extra Money Maker:</strong> When a visitor adds an item to their shopping cart after clicking through your link you now have an extra 89 day window to earn a commission if the visitor buys the item they added to their shopping cart instead of the usual cookie length of 24 hours.'));
		easyazon_the_settings_error('links_add_to_cart');
	}

	public static function display_settings_field__links__localization() {
		$links_localization = easyazon_get_setting('links_localization');

		$settings_checked = '';
		$settings_error = easyazon_has_settings_error('links_localization') ? 'easyazon-error' : '';
		$settings_id_no = easyazon_get_settings_id('links_localization_no');
		$settings_id_yes = easyazon_get_settings_id('links_localization_yes');
		$settings_name = easyazon_get_settings_name('links_localization');

		printf('<input type="hidden" id="%1$s" name="%2$s" value="no" />', $settings_id_no, $settings_name);
		printf('<label class="easyazon-disabled"><input disabled="disabled" type="checkbox" id="%1$s" name="%2$s" %3$s value="yes" /> %4$s</label>', $settings_id_yes, $settings_name, $settings_checked, __('I want all EasyAzon links to be localized (where possible)'));
		printf('<p class="description easyazon-sell">%1$s</p>', __('<strong>Potential Extra Money Maker:</strong> Automatically change your Amazon affiliate links to match the country your website visitor is viewing your website from (applicable to the countries you\'ve provided Tracking ID\'s for in the EasyAzon Settings above). This feature can help you earn commissions on traffic that you would otherwise not get paid on.'));
		printf('<p class="description easyazon-sell">%1$s</p>', __('<strong>For example:</strong> You create an affiliate link for the Xbox One product where your default search locale as listed in the EasyAzon settings above is the United States. A visitor from the United Kingdom visits your website and clicks your affiliate link to the Xbox One. Instead of going to Amazon.com, they are taken to a product search results page for "Xbox One" on the Amazon.co.uk website where you can now receive a commission if that visitor buys a product from Amazon.co.uk.'));
		easyazon_the_settings_error('links_localization');
	}


	///// Search

	public static function display_settings_field__search__content_types() {
		$content_types_ui = get_post_types(array('show_ui' => true), 'objects');

		$content_types = easyazon_get_setting('content_types');
		$content_types = is_array($content_types) ? $content_types : array();

		$inputs = array();

		foreach($content_types_ui as $post_type_ui_key => $post_type_ui) {
			$checked = in_array($post_type_ui_key, $content_types) ? 'checked="checked"' : '';
			$id = easyazon_get_settings_id("content_types_{$post_type_ui_key}");
			$name = easyazon_get_settings_name("content_types");

			$inputs[] = sprintf('<label><input type="checkbox" id="%1$s" name="%2$s[]" %3$s value="%4$s" /> %5$s</label>', $id, $name, $checked, $post_type_ui_key, $post_type_ui->labels->name);
		}

		echo implode('<br />', $inputs);

		easyazon_the_settings_error('content_types');
	}

	public static function display_settings_field__search__default_search_locale() {
		$default_search_locale = easyazon_get_setting('default_search_locale');

		$settings_error = easyazon_has_settings_error('default_search_locale') ? 'easyazon-error' : '';
		$settings_id = easyazon_get_settings_id('default_search_locale');
		$settings_name = easyazon_get_settings_name('default_search_locale');
		$settings_options = '';

		$locales = easyazon_get_locales();
		foreach($locales as $locale_key => $locale_data) {
			$option_selected = ($locale_key === $default_search_locale) ? 'selected="selected"' : '';

			$locale_name = $locale_data['name'];

			$settings_options .= sprintf('<option %1$s value="%2$s">%3$s</option>', $option_selected, esc_attr($locale_key), esc_html($locale_name));
		}

		printf('<select class="%1$s" id="%2$s" name="%3$s">%4$s</select>', $settings_error, $settings_id, $settings_name, $settings_options);
		easyazon_the_settings_error('default_search_locale');
	}

	//// Settings retrieval and sanitization

	public static function clean_settings($settings, $settings_defaults) {
		if(!isset($settings['content_types']) || !is_array($settings['content_types'])) {
			$settings['content_types'] = $settings_defaults['content_types'];
		}

		return $settings;
	}

	public static function default_settings($settings) {
		$settings_defaults = array(
			// Credentials
			'access_key_id' => '',
			'secret_access_key' => '',

			// Links
			'links_new_window' => 'yes',
			'links_nofollow' => 'yes',

			// Search
			'content_types' => array('page', 'post'),
		);

		// Associates tags
		$locales = easyazon_get_locales();

		foreach($locales as $locale_key => $locale_data) {
			if(!isset($settings_defaults['default_search_locale'])) {
				$settings_defaults['default_search_locale'] = $locale_key;
			}

			$settings_defaults["associates_tags_{$locale_key}"] = '';
		}

		return $settings + $settings_defaults;
	}

	public static function sanitize_settings($settings, $settings_defaults, $settings_errors) {
		// Credentials
		if(empty($settings['access_key_id'])) {
			$settings_errors->add('access_key_id', __('You must supply your Access Key ID in order for EasyAzon to work properly.'));
		}

		if(empty($settings['secret_access_key'])) {
			$settings_errors->add('secret_access_key', __('You must supply your Secret Access Key in order for EasyAzon to work properly.'));
		}

		if(!empty($settings['access_key_id']) && !empty($settings['secret_access_key'])) {
			$amazon_api = new EasyAzon_Amazon_API($settings['access_key_id'], $settings['secret_access_key']);
			$results = $amazon_api->item_search('Kindle', 'All', 1, null, 'US');

			if(is_wp_error($results)) {
				if(($message = $results->get_error_message('InvalidClientTokenId'))) {
					$settings_errors->add('access_key_id', __('The Access Key ID you provided is incorrect. Please double check your credentials.'));
				} else if(($message = $results->get_error_message('SignatureDoesNotMatch'))) {
					$settings_errors->add('secret_access_key', __('The Secret Access Key you provided is incorrect. Please double check your credentials.'));
				} else {
					$settings_errors->add('secret_access_key', sprintf('<strong>%1$s</strong>: %2$s', __('Error'), $results->get_error_message()));
				}
			}
		}

		// Associates
		$locales = easyazon_get_locales();
		foreach($locales as $locale_key => $locale_date) {
			$settings["associates_tags_{$locale_key}"] = isset($settings["associates_tags_{$locale_key}"]) ? $settings["associates_tags_{$locale_key}"] : '';
		}

		// Links
		$settings['links_new_window'] = (isset($settings['links_new_window']) && 'yes' === $settings['links_new_window']) ? 'yes' : 'no';

		// Search
		$settings['content_types'] = (isset($settings['content_types']) && is_array($settings['content_types'])) ? array_intersect($settings['content_types'], get_post_types(array('show_ui' => true))) : array();

		if(empty($settings['content_types'])) {
			$settings_errors->add('content_types', __('Please select at least one post type on which you wish to allow Amazon searches.'));
		}

		return $settings;
	}

	/// Searchable

	public static function add_associate_tags($associate_tags) {
		$associate_tags = array();

		$locales = easyazon_get_locales();
		foreach($locales as $locale_key => $locale_data) {
			if(!isset($associate_tags[$locale_key])) {
				$associate_tags[$locale_key] = array();
			}

			$associate_tags[$locale_key] = array_slice(array_merge($associate_tags[$locale_key], array_map('trim', explode(',', easyazon_get_setting("associates_tags_{$locale_key}")))), 0, 1);
		}

		return array_filter($associate_tags);
	}

	public static function add_locales($locales) {
		if(!isset($locales['US'])) {
			$locales['US'] = array(
				'help' => __('(i.e. yourtrackingid-20)'),
				'key' => 'US',
				'name' => __('United States'),
				'signup' => 'https://affiliate-program.amazon.com/',
			);
		}

		if(!isset($locales['CA'])) {
			$locales['CA'] = array(
				'key' => 'CA',
				'name' => __('Canada'),
				'signup' => 'https://associates.amazon.ca/',
			);
		}

		if(!isset($locales['CN'])) {
			$locales['CN'] = array(
				'key' => 'CN',
				'name' => __('China'),
				'signup' => 'https://associates.amazon.cn/',
			);
		}

		if(!isset($locales['DE'])) {
			$locales['DE'] = array(
				'key' => 'DE',
				'name' => __('Germany'),
				'signup' => 'https://partnernet.amazon.de/',
			);
		}

		if(!isset($locales['ES'])) {
			$locales['ES'] = array(
				'key' => 'ES',
				'name' => __('Spain'),
				'signup' => 'https://afiliados.amazon.es/',
			);
		}

		if(!isset($locales['FR'])) {
			$locales['FR'] = array(
				'key' => 'FR',
				'name' => __('France'),
				'signup' => 'https://partenaires.amazon.fr/',
			);
		}

		if(!isset($locales['IT'])) {
			$locales['IT'] = array(
				'key' => 'IT',
				'name' => __('Italy'),
				'signup' => 'https://programma-affiliazione.amazon.it/',
			);
		}

		if(!isset($locales['IN'])) {
			$locales['IN'] = array(
				'help' => __('Note: You must be a resident of India to join this country\'s affiliate program'),
				'key' => 'IN',
				'name' => __('India'),
				'signup' => 'https://affiliate-program.amazon.in/',
			);
		}

		if(!isset($locales['JP'])) {
			$locales['JP'] = array(
				'key' => 'JP',
				'name' => __('Japan'),
				'signup' => 'https://affiliate.amazon.co.jp/',
			);
		}

		if(!isset($locales['UK'])) {
			$locales['UK'] = array(
				'key' => 'UK',
				'name' => __('United Kingdom'),
				'signup' => 'https://affiliate-program.amazon.co.uk/',
			);
		}

		return $locales;
	}

	public static function sort_locales($locales) {
		uasort($locales, array(__CLASS__, '_sort_locales'));

		return $locales;
	}

	private static function _sort_locales($a, $b) {
		if('US' === $a['key']) {
			return -1;
		} else if('US' === $b['key']) {
			return 1;
		} else {
			return strcmp($a['name'], $b['name']);
		}
	}

	/// Utility

	private static function _parse_amazon_response($response, $request) {
		if(is_wp_error($response)) {
			$parsed = array(
				'error' => true,
				'error_message' => $response->get_error_message(),
			);
		} else {
			$items = $response['Items']['Item'];
			$page = isset($response['Items']['Request']['ItemSearchRequest']) && isset($response['Items']['Request']['ItemSearchRequest']['ItemPage']) ? $response['Items']['Request']['ItemSearchRequest']['ItemPage'] : 1;
			$pages = min(10, isset($response['Items']['TotalPages']) ? $response['Items']['TotalPages'] : 1);

			$tag = isset($request['tag']) ? $request['tag'] : false;

			foreach($items as $key => $item) {
				$url = urldecode($items[$key]['DetailPageURL']);
				if(empty($tag)) {
					$url = remove_query_arg(array('tag'), $url);
				} else {
					$url = add_query_arg(compact('tag'), $url);
				}

				$items[$key]['DetailPageURL'] = $url;
			}

			$parsed = compact('items', 'page', 'pages');
		}

		return $parsed;
	}
}

add_action('plugins_loaded', array('EasyAzon_Core', 'init'), 10);
