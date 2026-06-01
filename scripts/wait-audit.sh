#!/bin/bash
# Espera a que termine la auditoría y muestra el resumen.
while pgrep -f audit-apex.sh >/dev/null 2>&1; do
  sleep 15
done
echo "=== AUDITORIA TERMINADA ==="
grep -E "FASE|FINALIZADA|robado|sqlmap|host:|login:|password:|Database:" /home/jamsec/auditoria-full.log 2>/dev/null | head -60
echo "=== ficheros de evidencia ==="
ls -la /home/jamsec/auditoria/ 2>/dev/null
