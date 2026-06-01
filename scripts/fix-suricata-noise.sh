#!/bin/bash
# Elimina los falsos positivos de Suricata por offload de la NIC virtio.
# Uso: sudo bash fix-suricata-noise.sh <IFACE>
IFACE="${1:-eth0}"

apt-get install -y ethtool >/dev/null 2>&1

# 1) Desactivar offloads que provocan "truncated packet" / "wrong thread"
for f in gro gso tso lro rx tx sg; do
  ethtool -K "$IFACE" "$f" off 2>/dev/null
done
echo "offloads desactivados en $IFACE"

# 2) Hacerlo persistente (servicio systemd al arrancar)
cat > /etc/systemd/system/nic-offload-off.service <<EOF
[Unit]
Description=Desactivar offloads NIC para Suricata
After=network.target
[Service]
Type=oneshot
ExecStart=/sbin/ethtool -K $IFACE gro off gso off tso off lro off sg off
RemainAfterExit=yes
[Install]
WantedBy=multi-user.target
EOF
systemctl daemon-reload
systemctl enable --now nic-offload-off.service 2>/dev/null

# 3) Desactivar validación de checksum (irrelevante con NAT/offload) y eventos de decoder ruidosos
sed -i 's/checksum-validation: yes/checksum-validation: no/' /etc/suricata/suricata.yaml
sed -i 's/checksum-checks: auto/checksum-checks: no/' /etc/suricata/suricata.yaml

# 4) Desactivar las firmas de eventos de decoder/stream que generan el ruido
cat > /etc/suricata/disable.conf <<'EOF'
group: decoder-events.rules
group: stream-events.rules
EOF
suricata-update --disable-conf /etc/suricata/disable.conf >/dev/null 2>&1 || true

systemctl restart suricata
sleep 3
systemctl is-active suricata && echo "suricata reiniciado en $IFACE"
