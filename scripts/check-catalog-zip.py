"""Vergleicht Baukasten + Katalogdaten mit dem Release-ZIP, alte Katalogseite darf nicht mehr drin sein."""
from pathlib import Path
import sys
from zipfile import ZipFile

root = Path(__file__).resolve().parent.parent
runtime = root / 'mgd-giveaway'
files = []
# ab 0.1.1: Weitere Plugins kommt aus dem Baukasten > includes/Kit
for folder in ('includes/Kit', 'includes/plugin-catalog', 'assets/plugin-catalog'):
    files.extend(file for file in (runtime / folder).rglob('*') if file.is_file())
gone = ['mgd-giveaway/includes/class-mgd-wordpress-plugin-catalog.php', 'mgd-giveaway/includes/plugin-catalog/view.php']

try:
    with ZipFile(sys.argv[1]) as package:
        names = set(package.namelist())
        for file in files:
            relative = file.relative_to(root).as_posix()
            if package.read(relative) != file.read_bytes():
                raise ValueError('Katalogdatei weicht ab: ' + relative)
        for old in gone:
            if old in names:
                raise ValueError('Alte Katalogdatei noch im ZIP: ' + old)
    print('PASS Baukasten und Katalogdaten im ZIP: ' + str(len(files)) + ' Dateien bytegleich, alte Seite weg')
except (IndexError, KeyError, OSError, ValueError) as error:
    print('FAIL ' + str(error), file=sys.stderr)
    sys.exit(1)
