#!/bin/bash
# Comprueba en el manager si llegan alertas decodificadas de Suricata (eve.json).
A=/var/ossec/logs/alerts/alerts.json
echo "=== alertas con event_type (propio de eve.json de Suricata) ==="
grep -c 'event_type' "$A" 2>/dev/null
echo "=== alertas del grupo ids/suricata ==="
grep -c '"ids"' "$A" 2>/dev/null
echo "=== ejemplo de alerta Suricata (si hay) ==="
grep 'event_type' "$A" 2>/dev/null | tail -1 | cut -c1-400
