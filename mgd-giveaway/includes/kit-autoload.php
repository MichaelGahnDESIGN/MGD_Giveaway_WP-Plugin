<?php
// Autoloader Baukasten > includes/Kit, Namensraum Mgd\Giveaway\Kit
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

spl_autoload_register(static function ($class) {
    $prefix = 'Mgd\\Giveaway\\Kit\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $file = __DIR__ . '/Kit/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
