# Plugin Check – nachgezogene Warnungen

Kandidat 0.0.37. Lokaler offizieller Plugin Check 2.1.0 auf WordPress 7.1.3/PHP 8.3 mit Runtime-Bootstrap: **0 Fehler, 0 statische Warnungen**. Der vollständige lokale Bericht steht in `plugin-check.txt`. Der zusätzliche wp-env-Lauf in GitHub Actions kann funktional notwendige Enqueue-Scope-Hinweise ausgeben; sein vollständiger Bericht gehört zur Paketübergabe.

## Tatsächliche Änderungen

Nur Statistiken zu Einsendungen, Loganzahl und Loggröße werden während einer Anfrage zwischengespeichert. Schreibaktionen invalidieren diese Werte. Kontaktlisten, Exporte und signierte Bestätigungswege lesen weiterhin frische Daten ohne personenbezogenen Exportcache. Die Einzel-Ausnahmen benennen jeweils den Grund. Die Rohstruktur von $_FILES wird nicht mehr übernommen: Uploadfehler, Typen, Endung, Größe und der echte temporäre PHP-Uploadpfad werden vor dem CSV-Stream geprüft. Das Frontend lädt CSS und JS nur für erkannte Shortcodes bzw. direkt beim Rendern; späte Ausgaben drucken einen noch ausstehenden registrierten Stil im Footer.

## Umgang mit Prüfregeln

Keine Prüfgruppe und kein Laufzeitcheck wird ausgeschaltet. Technisch notwendige Direktabfragen, rein lesende URL-Auswahl, signierte öffentliche Tokens und die feste Cache-Konstante sind nach Einzelprüfung mit dem jeweils engsten PHPCS-Code und einer Begründung an der konkreten Stelle gekennzeichnet. Diese statischen Einzel-Ausnahmen sind **keine zusätzlichen Sicherheitsprüfungen** und werden ausdrücklich offengelegt. Verzeichnis: `GEPRUEFTE-AUSNAHMEN.md`. Keine Zusage einer WordPress.org-Zulassung.

## Nachweise

41 Integrationstests bestanden: ursprüngliche 22 Schutzgrenzen-/Datenprüfungen plus 19 Cache-, Invalidierungs-, Empfänger- und AI-Seiten-ID-Prüfungen. Browser: keine Giveaway-Ressourcen ohne Formular, normale und nach wp_head gerenderte Shortcodes, Modal/Escape, 390px sowie echter CSV-Multipart-Import. Ungültige Upload-Nonce und falsche Endung werden abgewiesen. PHP-Syntax aller 86 Laufzeitdateien unter PHP 8.1 und der 15 MCP-Dateien zusätzlich unter PHP 7.4 geprüft. Keine produktiven Daten, keine Live-Einreichung.
