# Gemeinsame MGD-Plugin-Kollektion

Die GitHub-Ausgabe 0.0.38 liefert das bereits entwickelte gemeinsame V4-Katalogbundle jetzt auch im tatsächlichen Release-ZIP aus. So hängt die Darstellung nicht davon ab, ob etwa Maintenance Studio oder ein anderes MGD-Plugin zusätzlich installiert ist.

## Was enthalten ist

- Eine gemeinsame Verwaltungsseite „Weitere Plugins“, auch bei alleiniger Giveaway-Installation.
- Sieben bekannte WordPress-Plugins mit eigenen Icons, lokalem WebP-Hintergrundmotiv, Suche, Kategorien und Detailansicht.
- Öffentliche Metadaten aus dem zentralen Plattformkatalog; keine nachgeladene Programmlogik.
- Native Installation bzw. Aktualisierung nur nach bewusstem Klick, mit WordPress-Rechte- und Nonceprüfung. Keine automatische Aktivierung.

Die gemeinsamen Dateien liegen unter `mgd-giveaway/includes/plugin-catalog/`, die lokale Oberfläche unter `mgd-giveaway/assets/plugin-catalog/`. Die Bootstrap-Klasse heißt `MGD_WordPress_Plugin_Catalog_V4`. Bestehende MGD-Plugins können denselben Menüeintrag gemeinsam verwenden, ohne eine zweite Oberfläche anlegen zu müssen.

## Datenschutz und Ausfall

Nur im Verwaltungsbereich werden öffentliche Katalog- und GitHub-Release-Metadaten abgerufen. Die Dienste erhalten die Server-IP sowie technische HTTP-Anfragedaten, keine Formularinhalte, Kontakte, WordPress-Kennwörter oder Tokens. Externe Bilder und Skripte werden für diese Oberfläche nicht benötigt. Der Feed wird sechs Stunden zwischengespeichert. Bei einem Quellenfehler bleibt ein lokaler Ersatzstand bzw. ein vorhandener letzter Stand nutzbar; ein Ausfall beweist nicht, dass keine Updates existieren.

Dies ist die GitHub-Ausgabe. Eine WordPress.org-Ausgabe wird getrennt vorbereitet, ohne externen Installer und GitHub-Updateweg.

## Paketprüfung und Rückfall

`mgd-plugin.json` benennt die Pflichtdateien. `scripts/check_plugin_ready.py` prüft sie und vergleicht Icon und Kartenmotiv mit dem ZIP. `tests/catalog-package.php` prüft alle sieben Motive und Icons sowie die vollständige Kataloglaufzeit. `scripts/check-catalog-zip.py` vergleicht sämtliche gemeinsamen Katalogdateien bytegenau mit dem tatsächlich gebauten Paket. Der kanonische Bundlevergleich erfolgt im MGD-Plattform-Builder mit `node scripts/sync-wordpress-plugin-catalog.mjs --target <Pluginwurzel> --check`.

Der lokale Prüflauf ist im [Release-Playbook](playbooks/2026-10-26-katalog-release.md) dokumentiert. Der kanonische Sync-Check ist bestanden; zusätzlich stimmen die 21 gemeinsamen Dateien bytegenau mit dem bereits geprüften KI-Kennzeichnungs-Verbraucher überein. Das ist keine Behauptung über eine bereits veröffentlichte Version oder eine Produktivinstallation. Vor einem echten Website-Update ein vollständiges Datei-/Datenbankbackup erstellen. Bei Problemen lässt sich das vorherige Release-ZIP 0.0.37 wieder installieren; Formulare und Kontakte werden durch den reinen Paketwechsel nicht gelöscht.
