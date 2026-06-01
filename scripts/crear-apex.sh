#!/bin/bash
# Crea la infraestructura del cliente Apex Gestoria en pve2 (clones de 9001).
set -e

echo "=== apex-fw (210) — firewall ==="
qm clone 9001 210 --name apex-fw --full
qm set 210 --memory 1024 --cores 1
qm set 210 --net1 virtio,bridge=vmbr110
qm set 210 --net2 virtio,bridge=vmbr120
qm set 210 --ipconfig0 ip=172.20.17.230/24,gw=172.20.17.5
qm set 210 --ipconfig1 ip=10.30.10.1/24
qm set 210 --ipconfig2 ip=10.30.20.1/24
qm set 210 --cicustom "user=local:snippets/apex-fw.yaml"
qm resize 210 scsi0 8G

echo "=== apex-web (211) — DMZ ==="
qm clone 9001 211 --name apex-web --full
qm set 211 --memory 1024 --cores 1
qm set 211 --net0 virtio,bridge=vmbr120
qm set 211 --ipconfig0 ip=10.30.20.10/24,gw=10.30.20.1
qm set 211 --cicustom "user=local:snippets/apex-web.yaml"
qm resize 211 scsi0 10G

echo "=== apex-db (212) — LAN ==="
qm clone 9001 212 --name apex-db --full
qm set 212 --memory 1024 --cores 1
qm set 212 --net0 virtio,bridge=vmbr110
qm set 212 --ipconfig0 ip=10.30.10.10/24,gw=10.30.10.1
qm set 212 --cicustom "user=local:snippets/apex-db.yaml"
qm resize 212 scsi0 10G

echo "=== apex-srv (213) — LAN ==="
qm clone 9001 213 --name apex-srv --full
qm set 213 --memory 768 --cores 1
qm set 213 --net0 virtio,bridge=vmbr110
qm set 213 --ipconfig0 ip=10.30.10.20/24,gw=10.30.10.1
qm set 213 --cicustom "user=local:snippets/apex-srv.yaml"
qm resize 213 scsi0 8G

echo "=== arrancando las 4 ==="
qm start 210
qm start 211
qm start 212
qm start 213
echo "APEX_DONE"
