#!/bin/bash
# Lanza el instalador de Wazuh de forma desacoplada (sobrevive al cierre de SSH).
setsid bash /tmp/install-wazuh.sh >/tmp/wzlog 2>&1 </dev/null &
echo LAUNCHED_PID_$!
