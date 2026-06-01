#!/bin/bash
# Espera a que termine la instalación de Wazuh (o detecta error).
for i in $(seq 1 60); do
  if grep -qE "Installation finished|WAZUH_INSTALL_DONE" /tmp/wzlog 2>/dev/null; then
    echo "=== READY ==="
    break
  fi
  sleep 12
done
echo "--- ultimas lineas del log ---"
tail -25 /tmp/wzlog
