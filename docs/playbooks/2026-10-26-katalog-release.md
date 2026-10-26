# Playbook · Gemeinsame Plugin-Kollektion veröffentlichungsfertig machen

## Ziel und Grenzen

Version 0.0.38 enthält auch bei alleiniger Installation die vorhandene gemeinsame MGD-Pluginseite mit sieben Karten, Icons, Hintergrundmotiven, Suche und Details. Der vorhandene native GitHub-Updater bleibt erhalten. Dieser Lauf bereitet Dateien, Tests und ZIP vor; Commit, Push und Veröffentlichung übernimmt der Hauptagent nach Prüfung. Keine Produktivänderung und keine Änderung einer WordPress.org-Ausgabe.

## Grundlinie

- Lokal `main` bei `d796e755d00116aa052956fa4edcc384c2ebd21f`, sauberer Arbeitsbaum; Pluginversion 0.0.37.
- Veröffentlichte Version 0.0.37 enthält das bereits lokal eingecheckte gemeinsame V4-Bundle noch nicht. Die sieben vorhandenen Motive bleiben unverändert.
- Remote `main` nach lesendem Abgleich bei `1c9cb453ae175ca7f08ed8e2288f85ce6e4e22ee`; lokale und entfernte Historie haben je einen eigenen Commit. Der entfernte Commit ergänzt ausschließlich `.github/dependabot.yml`. Vor Veröffentlichung muss dieser erhalten bleiben; keine erzwungene Veröffentlichung.
- Vor Änderung: Updater-Vertrag 10 Fälle, Plugin-Icon-Test, Katalogpaket und sieben Updatezustandsfälle erfolgreich.
- Rückfallpunkt: Git-Grundlinie und veröffentlichtes ZIP 0.0.37. Bestehendes `build/mgd-giveaway.zip` bleibt erhalten.

## Schritte

| ID | Schritt / Dateien | Voraussetzung | Erfolgskriterium / Prüfung | Rückfall | Status | Beleg |
| --- | --- | --- | --- | --- | --- | --- |
| G1 | Version, Readmes und Änderungsnachweise | Grundlinie | 0.0.38 in Header, Konstante, Readme und Testkonfiguration; keine Fachlogikänderung | Git-Grundlinie | geprüft | Header/Konstante/Stable tag und Paketkopf geprüft; Dokumentation synchron |
| G2 | Paketmanifest und Paketprüfung im CI | G1 | vollständige Bilder und Katalogdateien in ZIP; Sicherheits-/Paketprüfung erfolgreich | neue Dateien zurücknehmen | geprüft | Manifestprüfung bestanden; 21 Katalogdateien bytegleich im ZIP; Negativpaket ohne fremdes Kartenmotiv wird abgelehnt; Prüfungen im bestehenden Workflow ergänzt |
| G3 | PHP-/Katalogtests, kanonischer Bundlevergleich und ZIP | G1, G2 | PHP-Minimum und aktuelle PHP-Laufzeit geprüft, Standardbundle identisch, SHA-256 dokumentiert | altes ZIP bleibt erhalten | geprüft | PHP 7.4 und 8.5.12: je 14 PHP-Syntaxdateien, Updater 10 Fälle, Iconvertrag, Katalogpaket und sieben Updatezustände erfolgreich; kanonischer Sync-Check bestanden; 42 Paketdateien bytegleich zum Arbeitsstand |

## Planänderungen und Übergabe

Remote-Sicherheitskonfiguration nicht überschreiben. Der Hauptagent integriert den entfernten Commit kontrolliert vor dem Release. Live- und Einzelplugin-Browserabnahme der endgültigen Release-Datei folgen separat beim Hauptagenten. Keine Veröffentlichung als bereits erledigt darstellen.

Der zuerst gefundene ältere Builder-Checkout enthielt den Sync-Befehl noch nicht. Der anschließend gefundene aktuelle Checkout wurde ausschließlich im Prüfmodus verwendet; `--target mgd-giveaway --check` ist bestanden. Zusätzlich sind die 21 gemeinsamen Dateien bytegleich zum KI-Kennzeichnungs-Verbraucher. Der Maintenance-Verbraucher unterscheidet sich in zwei gleichwertigen Zeichenkettenprüfungen (`str_contains`/`str_starts_with` gegenüber PHP-7.4-kompatiblem `strpos`); der maßgebliche kanonische Check bleibt unverändert.

## Vorbereitetes Paket

- Pfad: `build/0.0.38/mgd-giveaway.zip`, 391215 Bytes, 42 Dateien.
- SHA-256: `9ce680e0d03c7fa988587e8ea62b795b981d59aa6e26b08a15d5518139a52585`.
- Das vorherige `build/mgd-giveaway.zip` und der veröffentlichte Release 0.0.37 wurden nicht überschrieben.
- Paket, Syntax und lokale Vertragstests wurden in der Vorbereitung geprüft; der folgende Abschluss ergänzt die tatsächliche Veröffentlichung.

## Abschluss durch den Hauptagenten

Die entfernte Dependabot-Konfiguration wurde in `97040c7` erhalten und mit dem lokalen Katalogstand zusammengeführt. Hauptbranch- und Release-CI für PHP 7.4 und 8.4 sind erfolgreich. [Release 0.0.38](https://github.com/MichaelGahnDESIGN/MGD_Giveaway_WP-Plugin/releases/tag/v0.0.38) ist veröffentlicht. Das zurückgeladene ZIP hat SHA-256 `9851e28f86e89674bb14cdd616ecc85998141233ffba91fcd87d20a1efee35fa`; alle 42 Dateien stimmen bytegleich mit den Quellen überein. Eine passende öffentliche Prüfsummendatei wurde ergänzt. Die CI-Dateizeitstempel erklären die Abweichung zum lokalen ZIP-Hash.

Der Katalog wurde im Browser mit ausschließlich Giveaway als aktivem MGD-Plugin geöffnet: sieben Karten, 17 Bilder und Katalogskript aus dem eigenen Pluginordner. Anschließend wurde Release 0.0.37 erneut eingesetzt und über ein echtes GitHub-Update nativ auf 0.0.38 aktualisiert. Zwölf fachliche Optionen blieben unverändert; `mgd_giveaway_version` wechselte separat geprüft von 0.0.37 auf 0.0.38. Kein Kunden-/Plattform-Deployment. Das Ziel dieses Playbooks ist erreicht.
