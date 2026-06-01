#!/bin/bash
# Instalación de Wazuh all-in-one (indexer + manager + dashboard) en jamsec-wazuh.
set -e
cd /root
curl -sSL -o wazuh-install.sh https://packages.wazuh.com/4.12/wazuh-install.sh
# Verificar que es un script y no una página de error
head -1 wazuh-install.sh | grep -q '#!' || { echo "ERROR: descarga no es script"; head -3 wazuh-install.sh; exit 1; }
# Debian 13 (trixie) retiró 'software-properties-common'; el instalador lo exige
# innecesariamente (solo para add-apt-repository). Se elimina de las dependencias.
sed -i 's/ software-properties-common//' wazuh-install.sh
# -a all-in-one, -o sobreescribe instalación previa si la hubiera
bash wazuh-install.sh -a -o
echo "WAZUH_INSTALL_DONE"
