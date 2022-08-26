<?php
/**
 * Plugin Name:       Kitchen Sink
 * Plugin URI:        https://wordpress.org/plugins/kitchen-sink/
 * Description:       Generates a "kitchen sink" page with examples of Gutenberg block implementations.
 * Version:           1.0
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Alpha Particle
 * Author URI:        https://alphaparticle.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       kitchen-sink
 */

require_once 'classes/class-dashboard-widget.php';
require_once 'classes/class-frontend-display.php';
require_once 'classes/class-page-generation.php';

$widget   = Dashboard_Widget::get_instance();
$generate = Page_Generation::get_instance();
$frontend = Frontend_Display::get_instance();