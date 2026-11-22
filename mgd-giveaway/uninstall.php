<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-mgd-giveaway-plugin.php';
MGD_Giveaway_Plugin::uninstall();

// Abmelden bei theme.michael-gahn.de (Baukasten), wirft nie
if (PHP_VERSION_ID >= 80100) {
    require_once plugin_dir_path(__FILE__) . 'includes/kit-autoload.php';
    \Mgd\Giveaway\Kit\Platform\WordPress\Setup::uninstall('mgd-giveaway-wordpress', 'wordpress');
}
