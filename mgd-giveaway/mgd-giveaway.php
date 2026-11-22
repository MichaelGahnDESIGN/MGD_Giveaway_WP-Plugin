<?php
/**
 * Plugin Name: MGD Giveaway
 * Plugin URI: https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin
 * Description: Erstellt Download-Formulare für Gratis-eBooks und PDFs mit Shortcode, Backend-Verwaltung und Credits.
 * Version: 0.1.1
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

define('MGD_GIVEAWAY_VERSION', '0.1.1');
define('MGD_GIVEAWAY_FILE', __FILE__);
define('MGD_GIVEAWAY_DIR', plugin_dir_path(__FILE__));
define('MGD_GIVEAWAY_URL', plugin_dir_url(__FILE__));

require_once MGD_GIVEAWAY_DIR . 'includes/class-mgd-giveaway-plugin.php';

// Baukasten nur ab PHP 8.1 (readonly usw.), drunter läuft Giveaway mit dem alten GitHub-Updater weiter
define('MGD_GIVEAWAY_KIT', PHP_VERSION_ID >= 80100);
define('MGD_GIVEAWAY_PRODUCT', 'mgd-giveaway-wordpress');

register_activation_hook(__FILE__, array('MGD_Giveaway_Plugin', 'activate'));
register_uninstall_hook(__FILE__, array('MGD_Giveaway_Plugin', 'uninstall'));

add_action('plugins_loaded', function () {
    MGD_Giveaway_Plugin::instance();
});

// Icon in der Pluginverwaltung.
(require __DIR__ . '/includes/plugin-icon.php')(__FILE__);

if (MGD_GIVEAWAY_KIT) {
    // Baukasten aus MichaThemeV5-Pro > includes/Kit (bin/build-plugin-kit.php)
    require_once MGD_GIVEAWAY_DIR . 'includes/kit-autoload.php';

    // Updater vom Baukasten, startet auch den Install-Melder - alter GitHub-Updater + Admin-Tools sind raus
    MGD_Giveaway_Plugin::$kit_updater = \Mgd\Giveaway\Kit\Platform\WordPress\Setup::updater(__FILE__, MGD_GIVEAWAY_PRODUCT, 'wordpress', MGD_GIVEAWAY_VERSION, 'MGD Giveaway', admin_url('admin.php?page=mgd-giveaway-info'));
    // Reste vom alten Updater wegräumen, 1x pro Version
    \Mgd\Giveaway\Kit\Platform\WordPress\Setup::cleanupLegacy(MGD_GIVEAWAY_PRODUCT, MGD_GIVEAWAY_VERSION, array('mgd_giveaway_github_release', 'mgd_admin_release_*'));
} else {
    // altes PHP: nur noch alter Updater + Admin-Tools, alte Seite Weitere Plugins ist raus (ab 0.1.1)
    require_once MGD_GIVEAWAY_DIR . 'includes/class-mgd-giveaway-updater.php';
    MGD_Giveaway_Updater::register();
    require_once __DIR__ . '/includes/class-mgd-plugin-admin-tools.php';
    \MGD_Plugin_Admin_Tools_20261023::register([
        'file' => __FILE__,
        'repo' => 'MGD_Giveaway_WP-Plugin',
        'slug' => 'mgd-giveaway',
        'name' => 'MGD Giveaway',
        'description' => 'Erstellt Download-Formulare für Gratis-eBooks und PDFs mit Shortcode, Backend-Verwaltung und Credits.',
        'version' => MGD_GIVEAWAY_VERSION,
        'settings' => 'admin.php?page=mgd-giveaway',
        'requires' => '6.0',
        'php' => '7.4',
        'caches' => ['mgd_giveaway_github_release'],
        'package' => '',
    ]);
}
