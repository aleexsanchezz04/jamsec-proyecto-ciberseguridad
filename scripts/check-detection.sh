#!/bin/bash
# Evidencia Blue Team: qué detectó Wazuh/Suricata durante la auditoría.
A=/var/ossec/logs/alerts/alerts.json
echo "=== total de alertas registradas ==="
wc -l < "$A"
echo
echo "=== TOP 15 tipos de alerta (descripcion) ==="
grep -o '"description":"[^"]*"' "$A" | sed 's/"description":"//;s/"$//' | sort | uniq -c | sort -rn | head -15
echo
echo "=== Detección de FUERZA BRUTA / autenticación ==="
grep -iE 'brute|authentication failed|maximum auth|multiple auth' "$A" | grep -o '"description":"[^"]*"' | sort | uniq -c | sort -rn | head
echo
echo "=== Alertas de Suricata (NIDS) por firma ==="
grep '"suricata"' "$A" | grep -o '"signature":"[^"]*"' | sort | uniq -c | sort -rn | head -10
echo
echo "=== Logins SSH aceptados (posible foothold) ==="
grep -iE 'sshd.*accepted|authentication success' "$A" | grep -o '"description":"[^"]*"' | sort | uniq -c | head
