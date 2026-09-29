<?php
/**
 * Plugin Name: MGD Giveaway
 * Plugin URI: https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin
 * Description: Erstellt Download-Formulare für Gratis-eBooks und PDFs mit Shortcode, Backend-Verwaltung und Credits.
 * Version: 0.0.35
 * Author: Michael Gahn DESIGN
 * Author URI: https://Michael-Gahn.de
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mgd-giveaway
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Update URI: https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MGD_GIVEAWAY_VERSION', '0.0.35');
define('MGD_GIVEAWAY_FILE', __FILE__);
define('MGD_GIVEAWAY_DIR', plugin_dir_path(__FILE__));
define('MGD_GIVEAWAY_URL', plugin_dir_url(__FILE__));

require_once MGD_GIVEAWAY_DIR . 'includes/class-mgd-giveaway-plugin.php';
require_once MGD_GIVEAWAY_DIR . 'includes/class-mgd-giveaway-updater.php';

// Der öffentliche Release-Kanal ist unabhängig von Formularen und Besuchern.
// Nur WordPress' Updateprüfung darf ihn aufrufen; keine Frontend-Daten werden versendet.
MGD_Giveaway_Updater::register();

register_activation_hook(__FILE__, array('MGD_Giveaway_Plugin', 'activate'));
register_uninstall_hook(__FILE__, array('MGD_Giveaway_Plugin', 'uninstall'));

add_action('plugins_loaded', function () {
    MGD_Giveaway_Plugin::instance();
});
