#!/bin/bash
# Escanea IPs de la red de tránsito 172.20.17.0/24 y reporta libres/ocupadas
# de forma fiable (ping + comprobación de vecino ARP).
for i in 213 214 215 216 217 218 219 222 223 224 225 226 227 228 230 231 232; do
  ip="172.20.17.$i"
  ping -c1 -W1 "$ip" >/dev/null 2>&1
  mac=$(ip neigh show "$ip" | awk '{for(j=1;j<=NF;j++) if($j=="lladdr") print $(j+1)}')
  if [ -n "$mac" ]; then
    echo "$ip OCUPADA ($mac)"
  else
    echo "$ip libre"
  fi
done
