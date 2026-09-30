<?php

// Reiner Updater-Vertrag: kein WordPress-Testsystem und keine echten Netzaufrufe.
define('HOUR_IN_SECONDS', 3600);
define('MGD_GIVEAWAY_VERSION', '0.0.35');
define('MGD_GIVEAWAY_FILE', '/wordpress/wp-content/plugins/mgd-giveaway/mgd-giveaway.php');
require __DIR__ . '/../mgd-giveaway/includes/class-mgd-giveaway-updater.php';

// Kleine WordPress-Attrappen prüfen die Einbindung ohne Netz und Datenbank.
$GLOBALS['mgd_test_release_response'] = null;
$GLOBALS['mgd_test_cache'] = [];
$GLOBALS['mgd_test_actions'] = [];
function add_filter(...$args) { return true; }
function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['mgd_test_actions'][$hook] = $accepted_args;
    return true;
}
function plugin_basename($file) { return 'mgd-giveaway/' . basename($file); }
function get_site_transient($key) { return $GLOBALS['mgd_test_cache'][$key] ?? false; }
function set_site_transient($key, $value, $ttl) { $GLOBALS['mgd_test_cache'][$key] = $value; return true; }
function delete_site_transient($key) { unset($GLOBALS['mgd_test_cache'][$key]); return true; }
function wp_safe_remote_get($url, $args) { return $GLOBALS['mgd_test_release_response']; }
function is_wp_error($value) { return false; }
function wp_remote_retrieve_response_code($response) { return $response['status']; }
function wp_remote_retrieve_body($response) { return $response['body']; }

// WordPress liefert dem Abschluss-Hook zwei Argumente. Ein einziges führt beim
// echten Backend-Update zu einem PHP-Fatal-Error nach dem Dateiaustausch.
MGD_Giveaway_Updater::register();
if (($GLOBALS['mgd_test_actions']['upgrader_process_complete'] ?? 0) !== 2) {
    fwrite(STDERR, "Abschluss-Hook muss zwei WordPress-Argumente erhalten.\n");
    exit(1);
}

function assertRelease($expected, array $data, string $message): void
{
    $actual = MGD_Giveaway_Updater::validatedRelease($data);
    if ($actual !== $expected) {
        fwrite(STDERR, $message . ': ' . var_export($actual, true) . PHP_EOL);
        exit(1);
    }
}

$valid = [
    'tag_name' => 'v0.0.35',
    'draft' => false,
    'prerelease' => false,
    'assets' => [[
        'name' => 'mgd-giveaway.zip',
        'browser_download_url' => 'https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/releases/download/v0.0.35/mgd-giveaway.zip',
    ]],
];

assertRelease([
    'version' => '0.0.35',
    'package' => $valid['assets'][0]['browser_download_url'],
], $valid, 'Gültiges Release');

$cases = [
    'Entwurf' => ['draft' => true],
    'Vorabversion' => ['prerelease' => true],
    'Ungültiges Tag' => ['tag_name' => 'v0.0.35-beta'],
    'Fremde URL' => ['assets' => [['name' => 'mgd-giveaway.zip', 'browser_download_url' => 'https://example.org/plugin.zip']]],
    'Falscher Dateiname' => ['assets' => [['name' => 'quelle.zip', 'browser_download_url' => $valid['assets'][0]['browser_download_url']]]],
];
foreach ($cases as $name => $change) {
    assertRelease(null, array_replace($valid, $change), $name);
}

$GLOBALS['mgd_test_release_response'] = ['status' => 200, 'body' => json_encode($valid)];
$sameVersion = MGD_Giveaway_Updater::updateUriResponse(false, [], 'mgd-giveaway/mgd-giveaway.php', []);
if ($sameVersion !== false) {
    fwrite(STDERR, "Gleiche Version darf kein Update anbieten.\n");
    exit(1);
}

$newer = $valid;
$newer['tag_name'] = 'v0.0.36';
$newer['assets'][0]['browser_download_url'] = str_replace('v0.0.35', 'v0.0.36', $valid['assets'][0]['browser_download_url']);
$GLOBALS['mgd_test_cache'] = [];
$GLOBALS['mgd_test_release_response'] = ['status' => 200, 'body' => json_encode($newer)];
$offer = MGD_Giveaway_Updater::updateUriResponse(false, [], 'mgd-giveaway/mgd-giveaway.php', []);
if (!is_array($offer) || $offer['version'] !== '0.0.36' || $offer['package'] !== $newer['assets'][0]['browser_download_url']) {
    fwrite(STDERR, "Neue Version wurde WordPress nicht korrekt angeboten.\n");
    exit(1);
}

$transient = (object) ['checked' => ['mgd-giveaway/mgd-giveaway.php' => '0.0.35'], 'response' => []];
$transient = MGD_Giveaway_Updater::injectUpdate($transient);
if (($transient->response['mgd-giveaway/mgd-giveaway.php']->new_version ?? null) !== '0.0.36') {
    fwrite(STDERR, "Plugin-Update-Transient wurde nicht korrekt gesetzt.\n");
    exit(1);
}

echo "Updater-Vertrag: 10 Fälle bestanden.\n";
