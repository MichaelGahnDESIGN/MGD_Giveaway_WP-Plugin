# Deployment

Das versionierte Release-ZIP wird mit `bash scripts/build-release.sh 0.0.35` nach `build/mgd-giveaway.zip` gebaut. Der Tag muss `v0.0.35` heißen. Die GitHub-Aktion prüft PHP-Syntax, Updater-Vertrag und Paketstruktur und veröffentlicht genau diese ZIP als Release-Asset. `dist/` enthält nur historische ZIP-Dateien.

## Updates im WordPress-Backend

Ab Version 0.0.35 erkennt WordPress freigegebene öffentliche GitHub-Releases automatisch. Die Plugin-Version im PHP-Header, die PHP-Konstante, der Stable Tag und der Git-Tag müssen übereinstimmen. Das Asset heißt immer `mgd-giveaway.zip` und enthält den Ordner `mgd-giveaway/` als einzige Wurzel. Der Updater akzeptiert weder Entwürfe noch Vorabversionen oder fremde Download-Adressen. Die Release-Antwort wird höchstens eine Stunde zwischengespeichert; WordPress' eigener Prüfplan kann zusätzlich verzögern. Für eine schnellere manuelle Prüfung: **Dashboard → Aktualisierungen → Erneut prüfen**.

Die Veröffentlichung eines GitHub-Releases installiert nichts unbeaufsichtigt. WordPress zeigt die neue Version an; ein Administrator entscheidet über die Installation beziehungsweise über die WordPress-eigene Auto-Update-Einstellung. Nach dem Update die Plugin-Aktivierung, Formularausgabe und einen Test-Download prüfen. Bei Problemen das vorherige, geprüfte Release-ZIP erneut installieren und das Website-/Datenbank-Backup zur Wiederherstellung bereithalten. Bestehende Installationen bis 0.0.34 benötigen zuerst einmalig ein manuelles ZIP-Update auf 0.0.35.

Vor einem produktiven Release prüfen:

- Plugin-Aktivierung
- Formular-Erstellung
- Shortcode-Ausgabe
- Download nach Anmeldung
- Inline-Erfolgsmeldung anstelle des Formulars
- Maskierter Download-Link ohne sichtbaren Mediathek-Pfad
- Geschützte Download-Kopie und Datei-Auslieferung
- Optionales Double-Opt-In
- Design-Einstellungen im Frontend
- Absenden auf gecachten Frontend-Seiten
- Einzelner Kontakt-Export und Kontakt-Löschung
- Speichern-Feedback und Tab-Erhalt im Formular-Editor
- Datenschutz-Popup im Frontend
- Robuster Speichern-Button im Formular-Editor
- Statische Backend-Vorschau ohne Frontend-Submit
- E-Mail-Versand mit PHP-Mail und/oder SMTP
- Mail-Liste Import/Export
- Log Suche, Filter, Export und Leeren-Funktion
- Drag & Drop Sortierung im Formular-Editor
- Element-Palette, Canvas und Feld-Inspector im Formular-Builder
- Tabs für Felder, Formular, Download, E-Mail und Vorschau
- Datenschutz-Element im Frontend
- Spam-Schutz mit Honeypot und Zeitprüfung
- Datenschutz- und Rechtstexte
