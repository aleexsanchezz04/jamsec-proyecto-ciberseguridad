#!/bin/bash
# Escaneo de IPs libres en rango alto (menos disputado) de 172.20.17.0/24
for i in 233 234 235 236 237 238 239 240 241 242 243 244 245 246 247 248 249 250; do
  ip="172.20.17.$i"
  ping -c1 -W1 "$ip" >/dev/null 2>&1
  if ip neigh show "$ip" | grep -q lladdr; then
    echo "$ip OCUPADA"
  else
    echo "$ip libre"
  fi
done
