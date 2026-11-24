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
// ab 0.1.1: MGD Tools vom Baukasten, kein eigenes Menü, alter Updater und Admin-Tools raus
mgd_catalog_assert(false !== strpos($main, "Setup::updater(__FILE__, 'mgd-giveaway-wordpress'"), 'Baukasten-Updater angemeldet');
mgd_catalog_assert(false !== strpos($main, 'Setup::tools(__FILE__'), 'MGD Tools vom Baukasten');
mgd_catalog_assert(!preg_match('/\badd_(menu|submenu|options|management|media)_page\s*\(/', $bootstrap), 'Kein eigenes Menü');
mgd_catalog_assert(!is_file($runtime . '/includes/class-mgd-plugin-admin-tools.php') && !is_file($runtime . '/includes/class-mgd-giveaway-updater.php'), 'Alter Updater und Admin-Tools weg');
mgd_catalog_assert(false === strpos($bootstrap, 'MGD_Giveaway_Updater') && false === strpos($bootstrap, 'MGD_GIVEAWAY_KIT'), 'Keine Reste vom alten Weg');
mgd_catalog_assert(false === strpos($bootstrap, 'type="color"') && false !== strpos($bootstrap, 'data-mgd-color'), 'Farbfelder mit data-mgd-color');
mgd_catalog_assert(is_file($runtime . '/assets/icon-128x128.png') && is_file($runtime . '/assets/icon-256x256.png'), 'Icons 128 und 256');
mgd_catalog_assert(false !== strpos(file_get_contents($runtime . '/uninstall.php'), "Setup::uninstall('mgd-giveaway-wordpress', 'wordpress')"), 'Abmeldung in uninstall.php');
mgd_catalog_assert(is_file($runtime . '/includes/Kit/Platform/WordPress/Updater.php'), 'Baukasten vorhanden');
echo "PASS Pluginversion, sieben Katalogeinträge, lokale Icons und vollständige Laufzeitdateien\n";
