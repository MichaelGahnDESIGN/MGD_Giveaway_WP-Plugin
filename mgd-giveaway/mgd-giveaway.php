<?php
/**
 * Plugin Name: MGD Giveaway
 * Plugin URI: https://theme.michael-gahn.de/plugins/plugin-giveaway-wordpress
 * Description: Download-Formulare für Gratis-eBooks und PDFs mit Shortcode, Mail-Liste und Credits.
 * Version: 2.1.1
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * Author: Michael Gahn DESIGN
 * Author URI: https://michael-gahn.de
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin
 * Text Domain: mgd-giveaway
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MGD_GIVEAWAY_VERSION', '2.1.1');
define('MGD_GIVEAWAY_FILE', __FILE__);
define('MGD_GIVEAWAY_DIR', plugin_dir_path(__FILE__));
define('MGD_GIVEAWAY_URL', plugin_dir_url(__FILE__));

require_once MGD_GIVEAWAY_DIR . 'includes/class-mgd-giveaway-plugin.php';

register_activation_hook(__FILE__, array('MGD_Giveaway_Plugin', 'activate'));
register_uninstall_hook(__FILE__, array('MGD_Giveaway_Plugin', 'uninstall'));

add_action('plugins_loaded', function () {
    MGD_Giveaway_Plugin::instance();
});

// Icon in der Pluginverwaltung.
(require __DIR__ . '/includes/plugin-icon.php')(__FILE__);

// Baukasten aus MichaThemeV5-Pro > includes/Kit (bin/build-plugin-kit.php)
require_once MGD_GIVEAWAY_DIR . 'includes/kit-autoload.php';

use Mgd\Giveaway\Kit\Platform\WordPress\Setup;
use Mgd\Giveaway\Kit\Platform\WordPress\Tools;

// Updater + MGD Tools Menü vom Baukasten
$mgd_giveaway_updater = Setup::updater(__FILE__, 'mgd-giveaway-wordpress', 'wordpress', MGD_GIVEAWAY_VERSION, 'MGD Giveaway', Tools::url());
// Reste vom alten Updater wegräumen, 1x pro Version
Setup::cleanupLegacy('mgd-giveaway-wordpress', MGD_GIVEAWAY_VERSION, array('mgd_giveaway_github_release', 'mgd_admin_release_*'));

// Seite unter MGD Tools - Seiten-Kennung bleibt mgd-giveaway, Ansichten als Reiter
Setup::tools(__FILE__, 'mgd-giveaway-wordpress', 'wordpress', MGD_GIVEAWAY_VERSION, 'MGD Giveaway', $mgd_giveaway_updater, array(
    'slug' => 'plugin-giveaway-wordpress',
    'repo' => 'MGD_Giveaway_WP-Plugin',
    'settings' => array('page' => 'mgd-giveaway', 'title' => 'Giveaway', 'cap' => 'manage_options', 'render' => function () {
        MGD_Giveaway_Plugin::instance()->render_tools_page();
    }),
    'license' => false,
));
