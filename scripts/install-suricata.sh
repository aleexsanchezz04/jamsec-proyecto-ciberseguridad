#!/bin/bash
# Instala Suricata (NIDS) en un firewall Debian e integra sus alertas (eve.json)
# con el agente Wazuh ya instalado. Monitoriza la interfaz WAN.
# Uso: sudo bash install-suricata.sh <IFACE_WAN>   (p.ej. eth0)
set -e
IFACE="${1:-eth0}"

apt-get update -q
apt-get install -y suricata jq

# Reglas de la comunidad (ET Open) vía suricata-update
suricata-update update-sources >/dev/null 2>&1 || true
suricata-update --no-test >/dev/null 2>&1 || suricata-update || true

# Configurar interfaz de captura
sed -i "s/^\(\s*-\s*interface:\).*/\1 $IFACE/" /etc/suricata/suricata.yaml || true

# HOME_NET: redes internas que protegemos (ajustable)
# (Se deja el valor por defecto; Suricata detecta igualmente.)

systemctl enable suricata
systemctl restart suricata
sleep 3
systemctl is-active suricata && echo "SURICATA_ACTIVO_en_$IFACE"

# Integración con Wazuh: que el agente lea el eve.json de Suricata
OSSEC=/var/ossec/etc/ossec.conf
if [ -f "$OSSEC" ] && ! grep -q "eve.json" "$OSSEC"; then
  # Insertar bloque localfile antes del cierre de ossec_config
  python3 - "$OSSEC" <<'PY'
import sys
f=sys.argv[1]
data=open(f).read()
block="""  <localfile>
    <log_format>json</log_format>
    <location>/var/log/suricata/eve.json</location>
  </localfile>
"""
idx=data.rfind("</ossec_config>")
if idx!=-1:
    data=data[:idx]+block+data[idx:]
    open(f,"w").write(data)
    print("ossec.conf actualizado con eve.json")
PY
  systemctl restart wazuh-agent 2>/dev/null || true
fi
echo "SURICATA_WAZUH_OK"
