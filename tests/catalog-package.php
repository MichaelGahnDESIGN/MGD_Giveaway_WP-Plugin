<?php
/** Paketvertrag: gemeinsame Katalogdaten, neue Klasse, Versionsangaben und lokale Grafiken. */
$root = dirname(__DIR__);
$config = json_decode(file_get_contents(__DIR__ . '/catalog-config.json'), true);
$runtime = $root . '/' . $config['runtime'];
function mgd_catalog_assert(bool $ok, string $name): void {
    if (!$ok) { fwrite(STDERR, 'FAIL ' . $name . "\n"); exit(1); }
}
$main = file_get_contents($runtime . '/' . $config['main']);
mgd_catalog_assert((bool) preg_match('/Version:\s*' . preg_quote($config['version'], '/') . '\s/', $main), 'Pluginversion');
$metadata = json_decode(file_get_contents($runtime . '/includes/plugin-catalog/plugins.json'), true);
mgd_catalog_assert(count($metadata) === 7, 'Sieben bekannte WordPress-Plugins');
foreach ($metadata as $repo => $item) {
    mgd_catalog_assert($item['repository'] === $repo && $item['tier'] === 'free', 'Bekannte kostenlose Einträge');
    $file = $runtime . '/assets/plugin-catalog/icons/' . $item['icon'];
    mgd_catalog_assert(is_file($file) && filesize($file) > 100, 'Lokales Icon ' . $repo);
    mgd_catalog_assert(false === strpos(file_get_contents($file), 'https://'), 'Kein externer Bildabruf im Icon');
    $picture = $runtime . '/assets/plugin-catalog/backgrounds/' . pathinfo($item['icon'], PATHINFO_FILENAME) . '.webp';
    $size = is_file($picture) ? getimagesize($picture) : false;
    mgd_catalog_assert($size && $size[0] === 1080 && $size[1] === 360 && $size['mime'] === 'image/webp', 'Lokales Kartenmotiv ' . $repo);
    mgd_catalog_assert(filesize($picture) < 80000, 'Kompaktes Kartenmotiv ' . $repo);
}
foreach (array('plugins.json') as $name) {
    mgd_catalog_assert(is_file($runtime . '/includes/plugin-catalog/' . $name), 'Katalogbestandteil ' . $name);
}
$bootstrap = $main;
foreach (glob($runtime . '/includes/*.php') as $file) { $bootstrap .= file_get_contents($file); }
// ab 0.1.1: alte Katalogseite V4 ganz raus, Weitere Plugins kommt nur noch vom Baukasten
mgd_catalog_assert(false === strpos($bootstrap, 'MGD_WordPress_Plugin_Catalog_V4'), 'Alte Katalogseite entfernt');
mgd_catalog_assert(!is_file($runtime . '/includes/class-mgd-wordpress-plugin-catalog.php') && !is_file($runtime . '/includes/plugin-catalog/view.php'), 'Alte Katalogdatei weg');
mgd_catalog_assert(is_file($runtime . '/includes/Kit/Platform/WordPress/CatalogInstaller.php'), 'Baukasten mit Installieren-Knopf');
mgd_catalog_assert(false === strpos($bootstrap, 'catalogActions'), 'Kein eigener catalogActions-Aufruf');
// ab 0.1.0: Baukasten ab PHP 8.1, alter Weg nur noch als Rückfall für älteres PHP
mgd_catalog_assert(false !== strpos($main, "Setup::updater(__FILE__, MGD_GIVEAWAY_PRODUCT"), 'Baukasten-Updater angemeldet');
mgd_catalog_assert(false !== strpos($main, "define('MGD_GIVEAWAY_PRODUCT', 'mgd-giveaway-wordpress')"), 'Produktname mgd-giveaway-wordpress');
mgd_catalog_assert((bool) preg_match('/if \(MGD_GIVEAWAY_KIT\) \{.*\} else \{.*MGD_Giveaway_Updater::register\(\);.*MGD_Plugin_Admin_Tools_20261023::register/s', $main), 'Alter Updater nur ohne Baukasten');
mgd_catalog_assert(substr_count($main, 'MGD_Giveaway_Updater::register()') === 1, 'Alter Updater nur einmal');
mgd_catalog_assert(false !== strpos(file_get_contents($runtime . '/uninstall.php'), "Setup::uninstall('mgd-giveaway-wordpress', 'wordpress')"), 'Abmeldung in uninstall.php');
mgd_catalog_assert(is_file($runtime . '/includes/Kit/Platform/WordPress/Updater.php'), 'Baukasten vorhanden');
echo "PASS Pluginversion, sieben Katalogeinträge, lokale Icons und vollständige Laufzeitdateien\n";
