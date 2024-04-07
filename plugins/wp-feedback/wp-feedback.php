<?php
/**
 * Plugin Name:     WP Feedback
 * Plugin URI:      https://ykamal.com
 * Description:     A Wordpress plugin to gather feedback about a post or page.
 * Author:          Yousof K
 * Author URI:      https://ykamal.com
 * Text Domain:     wp-feedback
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         WP_FEEDBACK
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// constants
define("WPFB_DIR",  plugin_dir_path( __FILE__ ));
define("WPFB_PATH",  plugin_dir_url( __FILE__ ));
define("WPFB_PLUGIN_FILE",  __FILE__);

 // add the composer autolaoder
require WPFB_DIR . 'vendor/autoload.php';



use WPFB\Main;

Main::init();