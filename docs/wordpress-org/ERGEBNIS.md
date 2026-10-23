# Plugin Check – aktualisierter lokaler Lauf

Stand: 23.10.2026. Kandidat 0.0.37; WordPress 7.1.3, PHP 8.3, offizieller Plugin Check 2.1.0 mit Runtime-Bootstrap.

**0 Fehler, 63 Warnungen.** Alle Prüfgruppen aktiv, keine Fehlercodes ausgeschlossen. Vollständiger Bericht: `plugin-check.txt`.

- `WordPress.Security.NonceVerification.Recommended`: 20
- `WordPress.DB.DirectDatabaseQuery.DirectQuery`: 18
- `WordPress.DB.DirectDatabaseQuery.NoCaching`: 16
- `WordPress.Security.NonceVerification.Missing`: 6
- `WordPress.DB.DirectDatabaseQuery.SchemaChange`: 2
- `WordPress.Security.ValidatedSanitizedInput.InputNotSanitized`: 1

Die CI läuft zusätzlich mit einer eigenen wp-env-Installation und kann andere Laufzeit-/Performance-Hinweise liefern. Die Gesamtübergabe bewahrt beide Rohberichte getrennt auf. Ein grüner Lauf ist keine WordPress.org-Zulassung.

## Prüfung der Resthinweise

Eigene Plugin-Tabellen werden über das WordPress-Präfix und feste Namen erzeugt. Die Laufzeitabfragen binden Tabellenbezeichner mit `%i` und Werte mit `%s`/`%d`. Direkte Abfragen bleiben für eigene Tabellen notwendig. Ein Cache für Empfänger-, Lese-, Audit- und Downloadstatus wurde nicht ergänzt: Änderungen müssen unmittelbar sichtbar bleiben. Hinweise zu Schemaänderungen gehören zur Installation bzw. ausdrücklich gewählten Datenlöschung. Die SQL-Änderungen wurden mit echten Datenbankzugriffen in der lokalen Testkopie geprüft.

Nonce-Hinweise betreffen lesende Listen-/Statusfilter, signierte Download- und Bestätigungstokens sowie private Hilfsmethoden, die nach der Nonce-Prüfung des Formular-Handlers aufgerufen werden. Exporte, Löschaktionen, Import und Einstellungen besitzen eigene Capability- und Nonce-Prüfungen. `$_FILES` wird als Uploadstruktur gelesen; Dateiname, Größe, Erweiterung und `is_uploaded_file()` werden geprüft, bevor der begrenzte CSV-Stream geöffnet wird. Der temporäre Uploadpfad wird nicht in einen frei angegebenen Dateipfad umgewandelt. Formulare mit Nonce brauchen eine Ausnahme von dauerhaftem Seiten-Cache.

## Weitere Belege

Sieben Backends tatsächlich im Browser geöffnet, keine JavaScript-Ausnahmen; echte Screenshots mit künstlichen Daten. 22 lokale Integrationstests zu Formularschutz, Herstellerlink, Entwurf, Menü und privaten/öffentlichen Daten. PHP-Syntax und Verzeichnis-Paketvertrag geprüft. Kein Produktivsystem verändert und keine Einreichung ausgelöst.
