<?php defined('ABSPATH') || exit;

/**
 * 
 * @package BP Story
 * @version 3.1.0
 * 
 * Plugin Name: BP Story
 * Version:     3.1.0
 * Plugin URI:  https://1.envato.market/eO0yz
 * Description: Instagram style stories for WordPress
 * Author:      BeycanPress
 * Author URI:  https://www.beycanpress.com
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.tr.html
 * Text Domain: bpstory
 * Domain Path: /languages
 * Tags: instagram, style, stories, wordpress, plugin, story, buddypress, bbpress, woocommerce, products, posts
 * Requires at least: 5.0
 * Tested up to: 5.9
 * Requires PHP: 7.3
*/

// plugin path and url
$url = trailingslashit(plugin_dir_url( __FILE__ ));
$path = trailingslashit(plugin_dir_path( __FILE__ ));

// Constants
define('BPSTORY',
    array(
        'url' => $url,
        'path' => $path,
        'viewDir' => trailingslashit($path.'views'),
        'langDir' => trailingslashit($path.'languages'),
        'version' => '3.1.0',
        'pluginFile' => __FILE__,
        'textDomain' => 'bpstory',
        'settingsName' => 'bpstory_settings'
    )
);

// Autoload classes.
require_once BPSTORY['path'] . 'vendor/autoload.php';

// Install process
require_once BPSTORY['path'] . 'install.php';
        
// Load plugin
new \BeycanPress\Story\PluginLoader;
