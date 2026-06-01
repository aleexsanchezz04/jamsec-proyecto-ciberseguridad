#!/bin/bash
# Crea jamsec-web (111) y jamsec-srv (112) clonando la plantilla 9000.
set -e

echo "=== jamsec-web (111) — DMZ ==="
qm clone 9000 111 --name jamsec-web --full
qm set 111 --memory 1024 --cores 1
qm set 111 --net0 virtio,bridge=vmbr20
qm set 111 --ipconfig0 ip=10.20.20.10/24,gw=10.20.20.1
qm set 111 --cicustom "user=local:snippets/jamsec-web.yaml"
qm resize 111 scsi0 8G

echo "=== jamsec-srv (112) — SERVICIOS ==="
qm clone 9000 112 --name jamsec-srv --full
qm set 112 --memory 2048 --cores 1
qm set 112 --net0 virtio,bridge=vmbr30
qm set 112 --ipconfig0 ip=10.20.30.10/24,gw=10.20.30.1
qm set 112 --cicustom "user=local:snippets/jamsec-srv.yaml"
qm resize 112 scsi0 15G

echo "=== arrancando ambas ==="
qm start 111
qm start 112
echo "DONE"
