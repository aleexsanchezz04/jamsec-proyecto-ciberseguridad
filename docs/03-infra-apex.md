# Punto 2 — Infraestructura del cliente Apex Gestoría

Fecha: 2026-06-01. Nodo: `pve2` (172.20.17.203). Separada físicamente de JAMSEC.

## Topología

```
        INTERNET / tránsito (vmbr0 172.20.17.0/24)
                  │ WAN 172.20.17.230
            ┌─────┴──────┐
            │  apex-fw   │  Debian 13 + nftables (política DÉBIL a propósito)
            └──┬──────┬──┘
       LAN .1 ─┘      └─ DMZ .1
       vmbr110         vmbr120
    10.30.10.0/24    10.30.20.0/24
       │      │           │
   apex-db  apex-srv   apex-web
  10.30.10.10 10.30.10.20 10.30.20.10
   MariaDB    vsftpd      Apache+PHP (web vulnerable)
```

## VMs (nodo pve2, clones de plantilla 9001)

| VMID | Nombre | Zona / IP | Servicio | Vulnerabilidades plantadas |
|------|--------|-----------|----------|----------------------------|
| 9001 | debian13-tmpl | — | plantilla cloud-init | — |
| 210 | apex-fw | WAN .230 + LAN .1 + DMZ .1 | nftables/NAT | DMZ con acceso total a LAN; expone SSH(2222) y FTP a internet |
| 211 | apex-web | DMZ 10.30.20.10 | Apache+PHP | SQLi en login, subida de ficheros sin validar, web sin TLS, usuario SSH `soporte`/`soporte123` |
| 212 | apex-db | LAN 10.30.10.10 | MariaDB | escucha en 0.0.0.0, `root@%` con `toor`, datos personales/financieros sin cifrar |
| 213 | apex-srv | LAN 10.30.10.20 | vsftpd | FTP anónimo con escritura, ficheros sensibles (credenciales, backup SQL), usuario SSH `backup`/`backup2024` |

## Redes (bridges en pve2)

| Bridge | Zona | Subred |
|--------|------|--------|
| vmbr110 | LAN interna | 10.30.10.0/24 |
| vmbr120 | DMZ | 10.30.20.0/24 |

Definidos en `/etc/network/interfaces.d/apex`, aplicados con `ifreload -a`.

## Servicios publicados a internet (DNAT en apex-fw WAN 172.20.17.230)

| Puerto WAN | Destino | Servicio |
|-----------|---------|----------|
| 80 | apex-web:80 | web pública |
| 2222 | apex-web:22 | SSH del servidor web (hallazgo) |
| 21 + 30000-30100 | apex-srv:21 | FTP (hallazgo) |

## Vulnerabilidades verificadas (vivas)

- SQLi: `POST /login.php usuario=admin' OR '1'='1` → 302 a panel.php (bypass de auth).
- FTP anónimo: lista `credencials.txt`, `backup_apexgestoria.sql`.
- SSH target: banner OpenSSH en :2222.
- MariaDB: `apexgestoria` con tablas clientes/nominas/usuarios; `apexweb/apexweb` y `root@%`/`toor`.
- Cadena de ataque prevista: web (DMZ) → pivota a LAN (DMZ→LAN permitido) → BBDD/FTP.

## Datos de acceso (gestión)

- `ssh apex-fw` (WAN .230, ProxyJump pv2)
- `ssh apex-web` / `apex-db` / `apex-srv` (ProxyJump apex-fw)
- Usuario `apex`, clave SSH; contraseña consola `Apex2026!`.

## Incidencias resueltas (importantes)

1. **Conflicto de IP**: la `172.20.17.221` elegida inicialmente ya estaba en uso
   por otra máquina del aula (MAC `0c:c4:7a:...`). Síntomas: SSH a un host ajeno,
   guest-agents caídos, sin internet. Se reasignó la WAN a `172.20.17.230`
   (verificada libre por ping+ARP). La red de tránsito es COMPARTIDA del aula
   → siempre verificar IP libre antes de asignar.
2. **php-mysqli**: en Debian 13 el paquete correcto es `php-mysql` (no `php-mysqli`);
   sin él, la web daba HTTP 500 (`undefined function mysqli_connect`).
3. **YAML cloud-init**: un bloque `content: |` con la primera línea más indentada
   que las siguientes rompe el parseo. Validar con `yaml.safe_load`.

## Artefactos en el repo

- `apex/cloud-init/apex-fw.yaml` · `apex-web.yaml` · `apex-db.yaml` · `apex-srv.yaml`
- `apex/network/interfaces.d-apex`
- `scripts/crear-apex.sh` · `destruir-apex.sh` · `scan-ips.sh` · `verif-apex-vulns.sh`
