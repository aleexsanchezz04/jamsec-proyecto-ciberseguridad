#!/bin/bash
# v2: elimina el ruido de Suricata de forma fiable.
#  - af-packet a 1 hilo  -> elimina "STREAM pkt seen on wrong thread"
#  - desactiva grupos decoder-events/stream-events -> elimina "truncated packet"
set -e
# 1 hilo de captura
sed -i 's/#threads: auto/threads: 1/' /etc/suricata/suricata.yaml

# desactivar firmas de eventos de motor (ruido, no amenazas)
cat > /etc/suricata/disable.conf <<'EOF'
group: decoder-events.rules
group: stream-events.rules
group: app-layer-events.rules
EOF
suricata-update --disable-conf /etc/suricata/disable.conf 2>&1 | tail -3

systemctl restart suricata
sleep 5
echo -n "suricata: "; systemctl is-active suricata
echo "reglas truncated/wrong-thread restantes:"
RULES=$(find /var/lib/suricata/rules -name "*.rules" 2>/dev/null | head -1)
grep -c -iE 'truncated|wrong thread' "$RULES" 2>/dev/null || echo 0
