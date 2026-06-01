#!/bin/bash
# Instala/enrola el agente Wazuh (versión fijada al manager) de forma robusta.
# Uso: sudo bash install-wazuh-agent.sh <MANAGER_IP> <NOMBRE_AGENTE>
set -e
MANAGER="$1"
NAME="$2"
WAZUH_VERSION="4.12.0-1"
export DEBIAN_FRONTEND=noninteractive

if [ -z "$MANAGER" ] || [ -z "$NAME" ]; then
  echo "Uso: $0 <MANAGER_IP> <NOMBRE_AGENTE>"; exit 1
fi

# Limpiar posibles instalaciones colgadas
pkill -9 -f wazuh-install 2>/dev/null || true

# Dependencias + repositorio Wazuh
apt-get update -q
apt-get install -y gnupg curl apt-transport-https
curl -s https://packages.wazuh.com/key/GPG-KEY-WAZUH | gpg --no-default-keyring --keyring gnupg-ring:/usr/share/keyrings/wazuh.gpg --import
chmod 644 /usr/share/keyrings/wazuh.gpg
echo "deb [signed-by=/usr/share/keyrings/wazuh.gpg] https://packages.wazuh.com/4.x/apt/ stable main" > /etc/apt/sources.list.d/wazuh.list
apt-get update -q

# Instalar versión fijada (= manager). Usamos confnew para que el ossec.conf
# corresponda a la versión instalada (evita tags incompatibles de otra versión).
apt-get install -y --allow-downgrades --allow-change-held-packages \
  -o Dpkg::Options::=--force-confnew wazuh-agent="$WAZUH_VERSION"
dpkg --configure -a --force-confnew || true
apt-mark hold wazuh-agent

# Fijar el manager de forma determinista (no depender del postinst)
sed -i "s|<address>[^<]*</address>|<address>$MANAGER</address>|" /var/ossec/etc/ossec.conf

systemctl daemon-reload
systemctl enable wazuh-agent
systemctl restart wazuh-agent
sleep 5
echo -n "estado agente: "; systemctl is-active wazuh-agent
echo "AGENTE_CONFIGURADO $NAME -> $MANAGER"
