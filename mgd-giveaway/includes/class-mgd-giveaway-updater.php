<?php

/**
 * Bindet öffentliche GitHub-Releases in WordPress' regulären Plugin-Updater ein.
 *
 * Die Klasse führt selbst keine Installation aus. WordPress prüft Berechtigungen,
 * lädt das freigegebene ZIP und führt den eigenen Plugin-Lebenszyklus aus.
 */
final class MGD_Giveaway_Updater
{
    private const REPOSITORY = 'MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin';
    private const SLUG = 'mgd-giveaway';
    private const ASSET_NAME = 'mgd-giveaway.zip';
    private const CACHE_KEY = 'mgd_giveaway_github_release';
    private const CACHE_SECONDS = HOUR_IN_SECONDS;

    public static function register(): void
    {
        add_filter('update_plugins_github.com', [self::class, 'updateUriResponse'], 10, 4);
        add_filter('pre_set_site_transient_update_plugins', [self::class, 'injectUpdate']);
        add_filter('plugins_api', [self::class, 'pluginInformation'], 20, 3);
        add_action('upgrader_process_complete', [self::class, 'clearCache']);
    }

    /** @param mixed $transient @return mixed */
    public static function injectUpdate($transient)
    {
        if (!is_object($transient) || !isset($transient->checked) || !is_array($transient->checked)) {
            return $transient;
        }

        $plugin = plugin_basename(MGD_GIVEAWAY_FILE);
        if (!isset($transient->checked[$plugin])) {
            return $transient;
        }

        $update = self::buildUpdate(self::latestRelease());
        if ($update !== null) {
            if (!isset($transient->response) || !is_array($transient->response)) {
                $transient->response = [];
            }
            $transient->response[$plugin] = $update;
        }

        return $transient;
    }

    /**
     * WordPress verwendet diesen Provider für Plugins mit einer externen Update URI.
     * Ein Leerwert verhindert, dass eine fremde Quelle unter demselben Slug einspringt.
     *
     * @param mixed $unused @param mixed $pluginData @param mixed $pluginFile @param mixed $locales
     * @return array<string, mixed>|false|mixed
     */
    public static function updateUriResponse($unused, $pluginData, $pluginFile, $locales)
    {
        if ($pluginFile !== plugin_basename(MGD_GIVEAWAY_FILE)) {
            return $unused;
        }

        $update = self::buildUpdate(self::latestRelease());
        if ($update === null) {
            return false;
        }

        return [
            'id' => $update->id,
            'slug' => $update->slug,
            'version' => $update->new_version,
            'new_version' => $update->new_version,
            'url' => $update->url,
            'package' => $update->package,
            'requires' => $update->requires,
            'requires_php' => $update->requires_php,
        ];
    }

    /** @param mixed $result @param mixed $action @param mixed $args @return mixed */
    public static function pluginInformation($result, $action, $args)
    {
        if ($action !== 'plugin_information' || !is_object($args) || ($args->slug ?? '') !== self::SLUG) {
            return $result;
        }

        $release = self::latestRelease();
        if ($release === null) {
            return $result;
        }

        return (object) [
            'name' => 'MGD Giveaway',
            'slug' => self::SLUG,
            'version' => $release['version'],
            'author' => 'Michael Gahn DESIGN',
            'homepage' => 'https://github.com/' . self::REPOSITORY,
            'download_link' => $release['package'],
            'requires' => '6.0',
            'requires_php' => '7.4',
            'sections' => [
                'description' => '<p>Download-Formulare für Gratis-eBooks und PDFs.</p>',
                'changelog' => '<p>Änderungen: <a href="' . esc_url('https://github.com/' . self::REPOSITORY . '/releases') . '">GitHub-Releases</a>.</p>',
            ],
        ];
    }

    /** @param mixed $upgrader @param mixed $options */
    public static function clearCache($upgrader, $options): void
    {
        if (!is_array($options) || ($options['type'] ?? '') !== 'plugin') {
            return;
        }

        delete_site_transient(self::CACHE_KEY);
    }

    /**
     * @param array<string, mixed>|null $release
     * @return object|null
     */
    private static function buildUpdate(?array $release)
    {
        if ($release === null || !version_compare($release['version'], MGD_GIVEAWAY_VERSION, '>')) {
            return null;
        }

        return (object) [
            'id' => 'https://github.com/' . self::REPOSITORY,
            'slug' => self::SLUG,
            'plugin' => plugin_basename(MGD_GIVEAWAY_FILE),
            'new_version' => $release['version'],
            'url' => 'https://github.com/' . self::REPOSITORY,
            'package' => $release['package'],
            'requires' => '6.0',
            'requires_php' => '7.4',
        ];
    }

    /** @return array{version: string, package: string}|null */
    private static function latestRelease(): ?array
    {
        $cached = get_site_transient(self::CACHE_KEY);
        // Auch ein Release mit derselben Version wird höchstens eine Stunde
        // zwischengespeichert. Neue Veröffentlichungen bleiben so zeitnah sichtbar.
        if (is_array($cached) && isset($cached['version'], $cached['package'])) {
            return $cached;
        }

        $response = wp_safe_remote_get(
            'https://api.github.com/repos/' . self::REPOSITORY . '/releases/latest',
            ['timeout' => 10, 'headers' => [
                'Accept' => 'application/vnd.github+json',
                'User-Agent' => 'MGD-Giveaway/' . MGD_GIVEAWAY_VERSION,
            ]]
        );
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return null;
        }

        $data = json_decode((string) wp_remote_retrieve_body($response), true);
        if (!is_array($data)) {
            return null;
        }

        $release = self::validatedRelease($data);
        if ($release !== null) {
            set_site_transient(self::CACHE_KEY, $release, self::CACHE_SECONDS);
        }

        return $release;
    }

    /**
     * Prüft Release-Identität und Paketadresse ohne Weiterleitungen auf fremde Ziele.
     * Diese reine Funktion wird auch von den paketunabhängigen Tests verwendet.
     *
     * @param array<string, mixed> $data
     * @return array{version: string, package: string}|null
     */
    public static function validatedRelease(array $data): ?array
    {
        $tag = $data['tag_name'] ?? null;
        if (!is_string($tag) || !preg_match('/^v(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)$/D', $tag)
            || ($data['draft'] ?? null) !== false || ($data['prerelease'] ?? null) !== false
            || !isset($data['assets']) || !is_array($data['assets'])) {
            return null;
        }

        foreach ($data['assets'] as $asset) {
            if (!is_array($asset) || ($asset['name'] ?? null) !== self::ASSET_NAME) {
                continue;
            }

            $expected = 'https://github.com/' . self::REPOSITORY . '/releases/download/' . $tag . '/' . self::ASSET_NAME;
            if (($asset['browser_download_url'] ?? null) === $expected) {
                return ['version' => substr($tag, 1), 'package' => $expected];
            }
        }

        return null;
    }
}
