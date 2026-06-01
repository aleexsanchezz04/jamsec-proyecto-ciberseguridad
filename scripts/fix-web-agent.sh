#!/bin/bash
set -e
export DEBIAN_FRONTEND=noninteractive
apt-get install -y --reinstall --allow-change-held-packages -o Dpkg::Options::=--force-confnew wazuh-agent=4.12.0-1
sed -i "s|<address>[^<]*</address>|<address>172.20.17.231</address>|" /var/ossec/etc/ossec.conf
systemctl daemon-reload
systemctl restart wazuh-agent
sleep 5
echo -n "estado: "; systemctl is-active wazuh-agent
echo -n "address: "; grep -m1 -o "<address>[^<]*</address>" /var/ossec/etc/ossec.conf
