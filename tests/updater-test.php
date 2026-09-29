<?php

// Reiner Updater-Vertrag: kein WordPress-Testsystem und keine echten Netzaufrufe.
define('HOUR_IN_SECONDS', 3600);
require __DIR__ . '/../mgd-giveaway/includes/class-mgd-giveaway-updater.php';

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

echo "Updater-Vertrag: 6 Fälle bestanden.\n";
