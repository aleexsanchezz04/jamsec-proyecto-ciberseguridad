# Proyecto JAMSEC — Infraestructura, Monitorización y Auditoría

Proyecto del Curso de Especialización en Ciberseguridad. Despliegue de **dos
infraestructuras empresariales separadas** sobre un clúster **Proxmox VE** de 3
nodos, monitorización con **IDS/IPS/SIEM**, y **auditoría de intrusión** completa
de la infraestructura del cliente con sus informes técnico y ejecutivo.

- **JAMSEC**: empresa de ciberseguridad (infraestructura propia + SOC).
- **Apex Gestoría**: cliente auditado (infraestructura objetivo).

> ⚠️ Entorno de laboratorio con vulnerabilidades **intencionadas** en la parte del
> cliente. No reutilizar estas configuraciones en producción.

---

## 1. Arquitectura

```
                        Internet / red de tránsito (172.20.17.0/24)
                                        │
              ┌─────────────────────────┼─────────────────────────┐
              │ WAN .240                 │                 WAN .230 │
        ┌─────┴──────┐                   │            ┌────────────┴─┐
        │ jamsec-fw  │ nftables+Suricata │            │   apex-fw    │ nftables+Suricata
        └─┬───┬───┬──┘                   │            └───┬──────┬────┘
      SOC │ DMZ│SRV│                      │           LAN  │  DMZ │
   10.20.10  .20  .30                     │         10.30.10   10.30.20
     │    │    │                          │            │          │
  redteam web  srv                     wazuh .231    db  srv     web
  +wazuh                               (SIEM)
  ── NODO proxmox · INFRA JAMSEC ──     (transit)   ── NODO pve2 · INFRA APEX ──
```

| Infraestructura | Nodo | Componentes |
|-----------------|------|-------------|
| JAMSEC | `proxmox` | firewall, web corporativa (DMZ), Samba (interna), SOC (Kali/redteam + Wazuh) |
| Apex Gestoría (cliente) | `pve2` | firewall, web vulnerable (DMZ), MariaDB + FTP (LAN interna) |
| Monitorización | ambos | Wazuh 4.12 (SIEM/HIDS) + Suricata 7 (NIDS) |

Detalle de direccionamiento, bridges y reglas de firewall en [`docs/`](docs/).

## 2. Tecnologías

- **Virtualización**: Proxmox VE 9.1, cloud-init, plantillas Debian 13.
- **Red/Firewall**: Linux bridges, nftables (NAT + segmentación por zonas).
- **Servicios**: nginx, Apache+PHP, Samba, MariaDB, vsftpd.
- **Seguridad**: Wazuh (SIEM), Suricata (IDS), reglas ET Open.
- **Auditoría**: nmap, sqlmap, hydra, whatweb, sshpass.

## 3. Estructura del repositorio

```
infraestructura-proxmox/
├── README.md                ← este documento
├── docs/                    ← documentación técnica por fases
│   ├── 01-inventario-y-renombrado.md
│   ├── 02-infra-jamsec.md
│   ├── 03-infra-apex.md
│   ├── 04-siem-ids-ips.md
│   └── 05-auditoria.md
├── jamsec/                  ← cloud-init, red y firewall de JAMSEC
│   ├── cloud-init/          (jamsec-fw, web, srv, wazuh, redteam)
│   ├── fw/nftables.conf
│   └── network/interfaces.d-jamsec
├── apex/                    ← cloud-init, red y firewall del cliente
│   ├── cloud-init/          (apex-fw, web, db, srv)
│   └── network/interfaces.d-apex
├── scripts/                 ← automatización (despliegue, SIEM, auditoría)
├── auditoria/evidencias/    ← evidencias del pentest (nmap, sqlmap, hydra, pivote)
├── informes/                ← informes técnico y ejecutivo (.docx)
└── inventario/              ← inventario inicial del clúster heredado
```

## 4. Cómo se despliega (resumen reproducible)

1. **Redes**: aplicar los bridges aislados en cada nodo (`network/interfaces.d-*`) con `ifreload -a`.
2. **Plantilla**: crear plantilla cloud-init de Debian 13 (`scripts/` documenta el `qm create`).
3. **VMs**: clonar la plantilla y aplicar el snippet cloud-init correspondiente (`*/cloud-init/*.yaml`) — cada VM se autoconfigura en el primer arranque.
4. **SIEM**: `scripts/install-wazuh.sh` (manager) + `scripts/install-wazuh-agent.sh` (agentes) + `scripts/install-suricata.sh` (IDS en firewalls).
5. **Auditoría**: `scripts/audit-apex.sh` + `scripts/pivot-apex.sh` desde la estación red team.

## 5. Resultados de la auditoría

Compromiso total de la infraestructura del cliente: **3 hallazgos críticos, 4 altos, 2 medios**.
Cadena de ataque: SQLi en la web → volcado de BBDD; FTP anónimo → filtración de
credenciales; fuerza bruta SSH → acceso a la DMZ; segmentación deficiente → pivote
a la LAN interna y exfiltración de nóminas. El SIEM detectó la actividad.

Informes completos en [`informes/`](informes/):
- `JAMSEC_Informe_Tecnico_Auditoria_Apex.docx`
- `JAMSEC_Informe_Ejecutivo_Auditoria_Apex.docx`

## 6. Demo

Guion y script de demostración en [`demo/`](demo/).

## 7. Notas y lecciones aprendidas

- Red de tránsito compartida: verificar siempre IP libre (ping + ARP) antes de asignar.
- Debian 13: `software-properties-common` retirado (parchear instalador Wazuh); usar `php-mysql` y `bind9-dnsutils`.
- Wazuh: fijar la versión del agente a la del manager (`4.12.0-1`).
- Suricata sobre virtio: desactivar offloads de NIC y usar 1 hilo para evitar falsos positivos.

---

*Autor: Alex Sánchez García — Proyecto JAMSEC, 2026. Documento de uso académico.*
