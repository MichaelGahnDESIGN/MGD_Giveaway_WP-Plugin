"""Vergleicht das vollständige gemeinsame Katalogbundle mit dem Release-ZIP."""
from pathlib import Path
import sys
from zipfile import ZipFile

root = Path(__file__).resolve().parent.parent
runtime = root / 'mgd-giveaway'
files = [runtime / 'includes/class-mgd-wordpress-plugin-catalog.php']
for folder in ('includes/plugin-catalog', 'assets/plugin-catalog'):
    files.extend(file for file in (runtime / folder).rglob('*') if file.is_file())

try:
    with ZipFile(sys.argv[1]) as package:
        for file in files:
            relative = file.relative_to(root).as_posix()
            if package.read(relative) != file.read_bytes():
                raise ValueError('Katalogdatei weicht ab: ' + relative)
    print('PASS vollständiges Katalogbundle im ZIP: ' + str(len(files)) + ' Dateien bytegleich')
except (IndexError, KeyError, OSError, ValueError) as error:
    print('FAIL ' + str(error), file=sys.stderr)
    sys.exit(1)
