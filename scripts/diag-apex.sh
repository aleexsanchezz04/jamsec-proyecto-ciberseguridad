#!/bin/bash
# Diagnóstico de las VMs Apex desde el hipervisor pve2 (vía guest agent).
for id in 210 211 212 213; do
  name=$(qm config "$id" | awk -F': ' '/^name:/{print $2}')
  printf '=== VM %s (%s) ===\n' "$id" "$name"
  printf '  status: '; qm status "$id" | awk '{print $2}'
  if qm guest cmd "$id" ping >/dev/null 2>&1; then
    echo '  agent: OK'
    ci=$(qm guest exec "$id" -- /bin/sh -c 'cloud-init status 2>/dev/null || echo nocloudinit' 2>/dev/null | sed -n 's/.*"out-data" : "//p' | tr -d '\\n')
    echo "  cloud-init: $ci"
  else
    echo '  agent: NO_RESPONDE_TODAVIA'
  fi
done
