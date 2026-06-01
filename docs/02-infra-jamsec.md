# Punto 1b — Infraestructura empresarial JAMSEC sobre Proxmox

Fecha: 2026-06-01. Nodo: `proxmox` (172.20.17.202).

## Resultado

Infraestructura empresarial propia de JAMSEC montada como VMs reales en el
cluster Proxmox, con **firewall perimetral, DMZ, servicios internos separados y
multirred**, todo verificado funcionalmente.

## Topología

```
              INTERNET / tránsito  (vmbr0  172.20.17.0/24, gw .5)
                        │  WAN 172.20.17.211
                  ┌─────┴──────┐
                  │ jamsec-fw  │  Debian 13 + nftables (NAT + filtrado por zonas)
                  └─┬───┬───┬──┘
        SOC .1 ─────┘   │   └───── SRV .1
        vmbr10          │          vmbr30
     10.20.10.0/24   DMZ .1     10.20.30.0/24
                      vmbr20
                   10.20.20.0/24
        │             │                 │
   (SOC: Kali     jamsec-web         jamsec-srv
    + Wazuh,      10.20.20.10        10.20.30.10
    Puntos 3/4)   nginx (web corp)  Samba [compartido]
```

## VMs creadas

| VMID | Nombre | Base | Zona / IP | RAM | Servicio |
|------|--------|------|-----------|-----|----------|
| 9000 | debian13-tmpl | Debian 13 cloud | — (plantilla) | 2G | plantilla cloud-init |
| 110 | jamsec-fw | clon 9000 | WAN .211 + SOC/DMZ/SRV .1 | 2G | nftables FW/NAT + Suricata (Punto 3) |
| 111 | jamsec-web | clon 9000 | DMZ 10.20.20.10 | 1G | nginx — web corporativa jamsec.cat |
| 112 | jamsec-srv | clon 9000 | SRV 10.20.30.10 | 2G | Samba — recurso [compartido] |

> Pendientes en SOC (10.20.10.0/24): `jamsec-kali` (Punto 4) y `jamsec-wazuh`
> (Punto 3). La red SOC ya existe (gateway 10.20.10.1 en el firewall).

## Redes (Linux bridges en el nodo proxmox)

| Bridge | Zona | Subred | Aislamiento |
|--------|------|--------|-------------|
| vmbr0 | WAN/tránsito | 172.20.17.0/24 | existente (gestión); NO modificado |
| vmbr10 | SOC | 10.20.10.0/24 | bridge L2 sin NIC física |
| vmbr20 | DMZ | 10.20.20.0/24 | bridge L2 sin NIC física |
| vmbr30 | SRV | 10.20.30.0/24 | bridge L2 sin NIC física |

Definidos en `/etc/network/interfaces.d/jamsec`, aplicados con `ifreload -a`
(sin reiniciar el nodo). Copia en `jamsec/network/interfaces.d-jamsec`.

## Política de firewall (nftables)

Fichero: `jamsec/fw/nftables.conf` (también embebido en el cloud-init del fw).

| Origen → Destino | Acción | Verificado |
|------------------|--------|-----------|
| WAN → DMZ web :80/443 (vía DNAT) | ACCEPT | ✅ curl desde 172.20.17.x OK |
| SOC → cualquiera | ACCEPT | (gateway listo) |
| SRV → internet | ACCEPT | ✅ |
| SRV → SOC / DMZ | DROP | ✅ |
| DMZ → internet | ACCEPT | ✅ |
| DMZ → SOC / SRV | DROP | ✅ (ping y :445 bloqueados) |
| Internas → internet | MASQUERADE | ✅ |

Detalle clave: la publicación de la web (`ip daddr WEB_DMZ dport 80,443`) se
restringe a orígenes **fuera** de 10.20.0.0/16 para que ninguna zona interna
use ese atajo hacia la DMZ.

## Método de despliegue (reproducible)

1. Imagen cloud de Debian 13 → plantilla `9000` con cloud-init, clave SSH y usuario `jamsec`.
2. Clonado de la plantilla por cada VM + `cicustom` apuntando a un snippet
   cloud-init (`/var/lib/vz/snippets/jamsec-*.yaml`) que autoconfigura cada
   servicio en el primer arranque.
3. IPs por zona vía `ipconfigN` de cloud-init de Proxmox.

Artefactos en el repo:
- `jamsec/cloud-init/jamsec-fw.yaml` · `jamsec-web.yaml` · `jamsec-srv.yaml`
- `jamsec/fw/nftables.conf`
- `jamsec/network/interfaces.d-jamsec`
- `scripts/crear-web-srv.sh`

## Acceso

- `ssh jamsec-fw` (WAN .211, ProxyJump pv2)
- `ssh jamsec-web` / `ssh jamsec-srv` (ProxyJump por jamsec-fw)
- Usuario `jamsec`, clave SSH; contraseña de consola `Jamsec2026!` (cambiar).

## Pruebas ejecutadas (evidencias)

- `nft list ruleset` en jamsec-fw → ruleset cargado.
- DMZ→SRV ping y :445 → BLOQUEADO.
- SRV→DMZ :80 → BLOQUEADO (tras corregir regla de publicación).
- web/srv → ping 1.1.1.1 → OK (NAT).
- pv2 → `curl http://172.20.17.211` → página JAMSEC (DNAT OK).

## Incidencias resueltas

- `dnsutils` no existe en Debian 13 → usar `bind9-dnsutils`.
- Formato de contraseña cloud-init → usar `plain_text_passwd` por usuario.
- Regla de publicación web demasiado amplia → restringida por origen.
