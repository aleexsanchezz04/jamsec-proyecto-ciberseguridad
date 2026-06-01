#!/bin/bash
# Se ejecuta en jamsec-fw. Comprueba alcance a jamsec-wazuh (SOC 10.20.10.20).
echo "=== ping a wazuh SOC ==="
ping -c2 -W2 10.20.10.20 >/dev/null 2>&1 && echo "  ping OK" || echo "  ping FAIL"
echo "=== puerto 22 ==="
nc -z -w3 10.20.10.20 22 2>/dev/null && echo "  22 abierto" || echo "  22 cerrado"
echo "=== banner SSH (5s) ==="
timeout 6 bash -c 'exec 3<>/dev/tcp/10.20.10.20/22; head -1 <&3' 2>/dev/null || echo "  sin banner"
