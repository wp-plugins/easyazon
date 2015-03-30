<?php
/*
Plugin Name: EasyAzon
Plugin URI: http://boostwp.com/products/easyazon-pro/
Description: Quickly and easily insert Amazon affiliate links into your site's content. By installing this plugin, you agree to the <a href="http://easyazon.com/terms/" target="_blank">EasyAzon terms of service</a>. <a href="http://easyazon.com/why-pro/?utm_source=easyazonplugin&utm_medium=link&utm_campaign=pluginpage" target="_blank">Upgrade to Pro</a> for more link options and affiliate link types.
Version: 4.0.12
Author: BoostWP
Author URI: http://boostwp.com/
*/

if(!defined('EASYAZON_PHP_VERSION_REQUIRED')) {
	define('EASYAZON_PHP_VERSION_REQUIRED', '5.3.0');
}

if(!defined('EASYAZON_VERSION')) {
	define('EASYAZON_VERSION', '4.0.12');
}

function easyazon_initialization() {
	if(version_compare(phpversion(), EASYAZONPRO_EASYAZON_VERSION_REQUIRED, 'ge')) {
		// All requirements are met

		if(!defined('EASYAZON_LOADED')) {
			define('EASYAZON_LOADED', true);
		}

		if(!defined('EASYAZON_CACHE_PERIOD')) {
			define('EASYAZON_CACHE_PERIOD', 6 * HOUR_IN_SECONDS);
		}

		if(!defined('EASYAZON_PLUGIN_BASENAME')) {
			define('EASYAZON_PLUGIN_BASENAME', plugin_basename(__FILE__));
		}

		if(!defined('EASYAZON_PLUGIN_DIRECTORY')) {
			define('EASYAZON_PLUGIN_DIRECTORY', dirname(__FILE__));
		}

		if(!defined('EASYAZON_PLUGIN_FILE')) {
			define('EASYAZON_PLUGIN_FILE', __FILE__);
		}

		// Amazon library for making requests to the Amazon Product Advertising API
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'lib/amazon.php'));

		// Require the utility functions that this plugin depends on in various ways
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'lib/utility.php'));

		// Require the lifecycle component regardless of whether we're at the initialization phase
		// so that activation and deactivation events can occur and items can be upgraded as necessary
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/lifecycle/lifecycle.php'));

		// Check for old version of Pro
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/pro-check/pro-check.php'));

		// Settings component adds the EasyAzon top level menu and settings page, as well as registering the settings
		// and introducing functions allowing access to those settings
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/settings/settings.php'));

		// Settings sections are separate components for each section that will be on the settings page, done
		// this way to allow for easy expandability in the future
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/settings-sections/associates/associates.php'));
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/settings-sections/credentials/credentials.php'));
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/settings-sections/links/links.php'));
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/settings-sections/search/search.php'));
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/settings-sections/upgrade/upgrade.php'));

		// About component adds the About page to the EasyAzon top level menu
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/about/about.php'));

		// Editor component adds the button to the media button area of the editor and also enqueues
		// the script needed to pop up the EasyAzon workflow
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/editor/editor.php'));

		// Interface for the popup
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/popup/popup.php'));

		// Interface for popup states
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/popup-states/search/search.php'));
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/popup-states/link/link.php'));

		// Additions to the popup states
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/popup-states-additions/popup-states-additions.php'));

		// Answer queries from the popup editor
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/ajax/ajax.php'));

		// Improve site performance by prefetching items
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/performance/performance.php'));

		// Shortcodes for replacement content
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/shortcodes/shortcodes.php'));

		// Specific shortcodes
		require_once(path_join(EASYAZON_PLUGIN_DIRECTORY, 'components/shortcodes/link/link.php'));
	} else {
		// PHP version does meet minimum requirement

		function easyazon_phpversion_notice() {
			printf('<div id="easyazon-phpversion-notice" class="error"><p>%s</p></div>', sprintf(__('EasyAzon %s requires at least of PHP %s. Please upgrade your PHP installation.'), EASYAZON_VERSION, EASYAZON_PHP_VERSION_REQUIRED));
		}
		add_action('admin_notices', 'easyazon_phpversion_notice');
	}
}
add_action('plugins_loaded', 'easyazon_initialization', 9);

function easyazon_registration() {
	if(function_exists('boostwp_client_register_plugin')) {
		boostwp_client_register_plugin(1053, __FILE__, EASYAZON_VERSION);
	}
}
add_action('plugins_loaded', 'easyazon_registration', 11);
