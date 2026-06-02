#!/bin/bash
# Despliega las páginas web (diseño nuevo) en los servidores web.
# Ejecutar desde el PC con los alias SSH configurados (jamsec-web, apex-web).
# Uso: bash deploy-webs.sh   (necesita los ficheros en jamsec/web y apex/web)
set -e
BASE="$(dirname "$0")/.."

echo "== JAMSEC web =="
scp "$BASE/jamsec/web/index.html" jamsec-web:/tmp/index.html
ssh jamsec-web 'sudo cp /tmp/index.html /var/www/html/index.html && sudo chown www-data:www-data /var/www/html/index.html'

echo "== Apex web (no se toca db.php) =="
scp "$BASE/apex/web/index.php" "$BASE/apex/web/login.php" "$BASE/apex/web/panel.php" apex-web:/tmp/
ssh apex-web 'sudo cp /tmp/index.php /tmp/login.php /tmp/panel.php /var/www/html/ && sudo chown www-data:www-data /var/www/html/index.php /var/www/html/login.php /var/www/html/panel.php'

echo "DEPLOY_OK"
