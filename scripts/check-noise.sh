#!/bin/bash
echo "=== offloads (deben estar off) ==="
ethtool -k eth0 2>/dev/null | grep -E 'generic-receive-offload|tcp-segmentation|generic-segmentation|scatter-gather' | head
echo "=== suricata checksum-validation ==="
grep -m1 checksum-validation /etc/suricata/suricata.yaml
echo "=== reglas decoder/stream cargadas (idealmente 0 tras disable) ==="
RULES=$(find /var/lib/suricata/rules -name "*.rules" 2>/dev/null | head -1)
grep -c -iE 'truncated|wrong thread' "$RULES" 2>/dev/null
echo "=== ruido en eve.json desde el reinicio (ultimas 200 lineas) ==="
tail -200 /var/log/suricata/eve.json 2>/dev/null | grep -o '"signature":"[^"]*"' | sort | uniq -c | sort -rn | head
