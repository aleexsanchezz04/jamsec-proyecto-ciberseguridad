# Punto 4 — Auditoría / pentest de Apex Gestoría

Fecha: 2026-06-01. Atacante: `jamsec-redteam` (SOC JAMSEC, 10.20.10.50, Debian +
herramientas pentest). Objetivo: perímetro del cliente `172.20.17.230` (apex-fw).

## Resumen ejecutivo de la intrusión

Comprometida la infraestructura del cliente al completo en una cadena de ataque
que va desde reconocimiento externo hasta exfiltración de datos personales y
financieros de la red interna. **Todos los ámbitos** (red, web, servicios,
credenciales, movimiento lateral) resultaron vulnerables.

## Hallazgos (por fase)

| # | Fase / ámbito | Técnica | Resultado | Severidad |
|---|---------------|---------|-----------|-----------|
| 1 | Reconocimiento | nmap -sV | Puertos 21(FTP), 22(SSH-fw), 80(web), 2222(SSH-web) expuestos a internet | Media |
| 2 | Web | SQL injection (sqlmap) en `login.php` | Bypass de auth + **volcado completo de la BBDD** (clientes, nóminas, usuarios) | **Crítica** |
| 3 | Servicios | FTP anónimo (vsftpd) | Lectura de `credencials.txt` (¡root/toor de la BBDD!) y `backup.sql` | **Alta** |
| 4 | Credenciales | Fuerza bruta SSH (hydra) en :2222 | `soporte:soporte123` crackeada | Alta |
| 5 | Movimiento lateral | Pivote DMZ→LAN (túnel SSH) | Foothold en apex-web → acceso **root a MariaDB interna** → exfiltración de nóminas | **Crítica** |
| 6 | Cifrado | Web y FTP en texto claro (sin TLS) | Datos sensibles transmitidos sin cifrar | Media |
| 7 | Segmentación | DMZ con acceso total a la LAN | Permite el pivote del hallazgo 5 | Alta |

## Datos exfiltrados (prueba de impacto)

- **clientes**: nombres, NIF, IBAN, email de 3 empresas cliente.
- **nominas**: empleados, DNI, salario, IBAN (datos laborales sensibles).
- **usuarios**: credenciales de la aplicación en texto claro.

## Detección por el SIEM (Blue Team)

Wazuh + Suricata detectaron la intrusión:
- Suricata: `ET SCAN Possible Nmap User-Agent` (escaneo).
- Wazuh HIDS: `sshd: authentication failed` ×N (fuerza bruta), `authentication
  success` (foothold), `vsftpd: FTP Authentication success` (FTP anónimo),
  `Web server 500 error` (sondeo SQLi).

> Ajuste operativo: se eliminó el ruido de Suricata (falsos positivos
> "truncated packet"/"wrong thread" por offload de NIC virtio) desactivando los
> offloads (ethtool) y fijando af-packet a 1 hilo. apex-fw se amplió a 2 GB de
> RAM (con 1 GB sufría OOM al cargar las 66k reglas).

## Evidencias

En `auditoria/evidencias/`:
- `01-nmap-servicios.txt`, `02-whatweb.txt`, `03-sqlmap-dbs.txt`,
  `04-sqlmap-dump.txt` (volcado BBDD), `05-ftp.txt`, `ftp-credencials.txt`,
  `ftp-backup.sql`, `06-hydra-ssh.txt`, `07-pivote.txt`.
- Log completo: `auditoria/auditoria-full.log`.

## Scripts (repo)

- `scripts/audit-apex.sh` — auditoría automatizada (recon, web, FTP, brute force).
- `scripts/pivot-apex.sh` — movimiento lateral DMZ→LAN.
- `scripts/check-detection.sh` — evidencia de detección en el SIEM.
