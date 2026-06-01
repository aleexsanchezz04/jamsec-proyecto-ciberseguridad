#!/bin/bash
echo "=== version / estado ==="
suricata --build-info 2>/dev/null | head -1
systemctl is-active suricata
echo "=== reglas cargadas ==="
RULES=$(find /var/lib/suricata/rules -name "*.rules" 2>/dev/null | head -1)
[ -n "$RULES" ] && wc -l "$RULES" || echo "sin fichero de reglas"
echo "=== eve.json ==="
ls -la /var/log/suricata/eve.json 2>/dev/null
echo "lineas: $(wc -l < /var/log/suricata/eve.json 2>/dev/null)"
echo "=== tipos de evento (top) ==="
grep -o '"event_type":"[a-z_]*"' /var/log/suricata/eve.json 2>/dev/null | sort | uniq -c | sort -rn | head
echo "=== alertas registradas ==="
grep -c '"event_type":"alert"' /var/log/suricata/eve.json 2>/dev/null || echo 0
