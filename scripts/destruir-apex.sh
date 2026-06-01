#!/bin/bash
# Destruye las VMs Apex (210-213) para recrearlas limpias.
for id in 210 211 212 213; do
  echo "=== destruyendo $id ==="
  qm stop "$id" --skiplock 1 2>/dev/null
  sleep 1
  qm destroy "$id" --purge 1 --destroy-unreferenced-disks 1 2>&1
done
echo "=== discos zfs huerfanos restantes con prefijo 21x ==="
pvesm list local-zfs | grep -E "vm-21[0-3]-" || echo "ninguno"
echo "DESTROY_DONE"
