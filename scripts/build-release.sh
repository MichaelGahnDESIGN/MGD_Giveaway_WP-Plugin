#!/usr/bin/env bash
set -euo pipefail

# Ein WordPress-Update muss denselben Plugin-Ordner wiederherstellen.
# Deshalb enthält das Release-ZIP genau einen Wurzelordner: mgd-giveaway/.
repo_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
version="${1:-}"
if [[ ! "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "Aufruf: scripts/build-release.sh X.Y.Z" >&2
  exit 2
fi

php_file="$repo_dir/mgd-giveaway/mgd-giveaway.php"
readme_file="$repo_dir/mgd-giveaway/readme.txt"
if ! grep -Fqx " * Version: $version" "$php_file" \
  || ! grep -Fqx "define('MGD_GIVEAWAY_VERSION', '$version');" "$php_file" \
  || ! grep -Fqx "Stable tag: $version" "$readme_file"; then
  echo "Plugin-Header, Konstante und Stable tag müssen $version sein." >&2
  exit 1
fi

output_dir="$repo_dir/build"
mkdir -p "$output_dir"
output_file="$output_dir/mgd-giveaway.zip"
if [[ -e "$output_file" ]]; then
  echo "Ausgabedatei existiert bereits: $output_file" >&2
  exit 1
fi

(
  cd "$repo_dir"
  zip -q -r "$output_file" mgd-giveaway -x '*.DS_Store' -x '__MACOSX/*'
)

if unzip -Z1 "$output_file" | grep -Ev '^mgd-giveaway/' >/dev/null; then
  echo "ZIP enthält unerwartete Dateien außerhalb des Plugin-Ordners." >&2
  exit 1
fi
unzip -tq "$output_file"
echo "$output_file"
