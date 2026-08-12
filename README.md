# MGD Giveaway

Copyright (c) 2026 Michael Gahn DESIGN - https://Michael-Gahn.de

[![License: GPL-2.0-or-later](https://img.shields.io/badge/License-GPL--2.0--or--later-blue.svg)](LICENSE)
[![WordPress: ab 6.0](https://img.shields.io/badge/WordPress-ab%206.0-21759B.svg)](#)
[![PHP: ab 7.4](https://img.shields.io/badge/PHP-ab%207.4-777BB4.svg)](#)

Ein WordPress-Plugin von Michael Gahn DESIGN zum Erstellen von Formularen, die nach Anmeldung einen Download-Link für Gratis-eBooks oder PDFs bereitstellen.

## Das Problem

Wer ein Gratis-eBook oder PDF gegen Anmeldung anbieten möchte, braucht dafür sonst mehrere Bausteine: ein Formular-Plugin, einen Schutz gegen das direkte Verlinken der Datei und meist einen externen Dienst für die Mail-Liste. MGD Giveaway bündelt Formular-Builder, maskierten Download und lokale Mail-Liste in einem Plugin — ohne externe Schriften, Icons, Skripte oder Drittanbieter-Dienste.

## Installation

> [!NOTE]
> **👤 FÜR ENTWICKLER**
>
> 1. ZIP-Datei aus `dist/mgd-giveaway-v0.0.34.zip` in WordPress hochladen (oder den Ordner `mgd-giveaway` direkt nach `wp-content/plugins/` kopieren).
> 2. Plugin im WordPress-Backend aktivieren.
> 3. Unter `MGD Giveaway` ein neues Formular anlegen.
> 4. Eine Datei aus der WordPress-Mediathek als Download hinterlegen.
> 5. Den angezeigten Shortcode in eine Seite oder einen Beitrag einfügen.

Anforderungen laut Plugin-Header (`mgd-giveaway/mgd-giveaway.php`) und `readme.txt`:

| Anforderung | Wert |
| --- | --- |
| WordPress | ab Version 6.0 (getestet bis 6.5) |
| PHP | ab Version 7.4 |
| Lizenz | GPL-2.0-or-later |

## Erste Schritte

Das Formular wird über einen Shortcode mit der jeweiligen Formular-ID eingebunden:

```text
[mgd_giveaway id="123"]
```

Nach erfolgreicher Anmeldung zeigt das Plugin den Download-Button direkt anstelle des Formulars an — es gibt keine separate Erfolgsseite.

> [!WARNING]
> **⚠️ FALLSTRICK** — Seiten-Caching kann das Absenden des Formulars beeinflussen. Laut `readme.txt`-Changelog (Version 0.0.23 und 0.0.26) wurde das Absenden bereits „cache-toleranter" gemacht, inklusive JavaScript-Fallback und sichtbarer Fehlermeldung. Trotzdem gehört „Absenden auf gecachten Frontend-Seiten" laut `Dokumentation/Deployment.md` weiterhin zu den Punkten, die vor einem produktiven Release manuell geprüft werden sollen.

## Die wichtigsten Funktionen

| Funktion | Beschreibung |
| --- | --- |
| Formular-Builder | Tabs, Element-Palette, Canvas, Drag & Drop und Feld-Inspector; Feldtypen: Text, E-Mail, Zahl, Datum, Checkbox, Textarea, Datenschutz |
| Maskierte Downloads | Download-Link als Plugin-Link statt direktem Mediathek-Pfad, geschützte Kopie im Upload-Verzeichnis |
| DSGVO-Werkzeuge | Einzelner Kontakt-Export und -Löschung, Datenschutz-Element mit Popup, optionales Double-Opt-In |
| Mail-Liste | Suche, CSV-Import (bis 2 MB / 5000 Zeilen), CSV-Export mit Schutz vor Formel-Injection |
| Log-Reiter | Suche, Level-Filter, CSV-Export, Speicheranzeige, Leeren-Button |
| E-Mail-Versand | Über WordPress/PHP-Mail oder SMTP, eigenes E-Mail-Design pro Formular, E-Mail-Vorschau im Backend |

> [!NOTE]
> **👤 FÜR ENTWICKLER** — Für den SMTP-Versand empfiehlt der Sicherheitsbericht (`Dokumentation/security_best_practices_report.md`) dedizierte SMTP-Zugangsdaten mit minimalen Rechten statt Hauptkonto-Passwörtern. Das SMTP-Passwort wird in den WordPress-Optionen (Datenbank) gespeichert.

## Grenzen

- Bereits bekannte, direkte Upload-URLs aus der WordPress-Mediathek werden durch die maskierte, geschützte Kopie **nicht rückwirkend ungültig**. Wer eine solche URL schon kennt, kann die Datei weiterhin direkt aufrufen; dafür muss die originale Mediathek-Datei zusätzlich entfernt oder serverseitig blockiert werden.
- Der letzte dokumentierte Sicherheitsreview (`Dokumentation/security_best_practices_report.md`, Prüfdatum 2026-04-29, geprüfte Version 0.0.4) konnte keinen vollständigen WordPress-Runtime-Test durchführen, da keine WordPress-Testinstallation, kein WP-CLI, kein Docker und kein lokaler MySQL-Server verfügbar waren.
- Formularanmeldungen liegen lokal in der WordPress-Datenbank; für die Absicherung ist der Betreiber der Website selbst verantwortlich (Backups, Zugriffsschutz, Serverhärtung).

> [!WARNING]
> **⚠️ FALLSTRICK** — Das Plugin speichert Formularanmeldungen lokal in der WordPress-Datenbank. E-Mail-Adressen und Formulardaten können personenbezogene Daten sein. Vor produktiver Nutzung müssen Datenschutzerklärung, Einwilligungstexte, Speicherfristen und E-Mail-Prozesse für Deutschland/EU rechtlich geprüft werden.

## Wiki

| Seite | Inhalt |
|---|---|
| [Home](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/wiki) | Überblick, Funktionen, Anforderungen |
| [Schnellstart](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/wiki/Schnellstart) | Plugin installieren und erstes Formular anlegen |
| [Formular-Builder](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/wiki/Formular-Builder) | Feldtypen, Tabs und Spam-Schutz |
| [Mail-Liste-und-Log](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/wiki/Mail-Liste-und-Log) | Anmeldungen, CSV-Import/Export, Log-Reiter |
| [Datenschutz-und-Sicherheit](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/wiki/Datenschutz-und-Sicherheit) | DSGVO, maskierte Downloads, geprüfte Restrisiken, Lizenzen |
| [Fehlerbehebung](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/wiki/Fehlerbehebung) | Bekannte Einschränkungen und was vor einem Live-Release zu prüfen ist |

## Kosten und Lizenzen

Die erste Version nutzt nur kostenlose, kommerziell nutzbare Komponenten:

- WordPress, GPL-2.0-or-later
- PHP, PHP License
- PHPMailer, LGPL-2.1-only, über WordPress
- Dashicons, GPL-2.0-or-later, über WordPress

## Version

Aktuelle Version: `0.0.34`

---

## Verwandte MGD Projekte

| Projekt | Beschreibung |
|---------|-------------|
| [MGD-Divi5-Dev](https://github.com/MichaelGahnDESIGN/MGD_Divi5-Dev_SKILL) | Divi 5 Entwicklungs-Workflow für WordPress |
| [MGD-AI-Project-Updater-Skill](https://github.com/MichaelGahnDESIGN/MGD_AI-Project-Updater_SKILL) | Sichere Update-Workflows für WordPress |
| [MGD-AI-Basic-Projektordner](https://github.com/MichaelGahnDESIGN/MGD_AI-Basic-Projektordner_TOOL) | Projektvorlage für KI-Agenten |

→ Alle öffentlichen Projekte: [github.com/MichaelGahnDESIGN](https://github.com/MichaelGahnDESIGN)

---

## Impressum

Angaben gemäß § 5 DDG — Siehe [`IMPRESSUM.md`](IMPRESSUM.md).
