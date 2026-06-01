# Subpaso 1 — Inventario y renombrado JASOC → JAMSEC

Fecha: 2026-06-01.

## Resumen

Tomada posesión del cluster Proxmox heredado del proyecto JASOC, completado el
inventario READ-ONLY y aplicado el renombrado a JAMSEC **sin reiniciar ningún
nodo** (solo servicios systemd, en caliente, manteniendo quorum continuamente).

## Estado del cluster al cierre

| Campo | Valor |
|-------|-------|
| Cluster name | **JAMSEC-Cluster** |
| Config version | 4 |
| Nodos | proxmox, pve2, pve3 (todos online, quorate) |
| Proxmox VE | 9.1.1 (kernel 6.17.2) |
| RAM total | ~367 GiB |
| Storage total | ~2.2 TiB (ZFS) |
| VMs/CTs existentes | 0 / 0 (lienzo en blanco) |
| Usuario PVE | root@pam (email: alexsanchezgarciarhlm@gmail.com) |
| Reglas firewall PVE | ninguna |

## Acceso

- SSH por Tailscale + aliases definidos en `~/.ssh/config`: `ssh pv2`, `ssh pv3`,
  `ssh proxmox` (los dos últimos van por ProxyJump a través de pv2).
- Autenticación: clave pública `id_ed25519` autorizada en `root@pam` (Proxmox
  sincroniza `authorized_keys` entre nodos automáticamente).

## Cambios aplicados

1. **`/etc/pve/corosync.conf`**: `cluster_name: JAMSEC-Cluster`, `config_version: 4`.
   Aplicado con `systemctl restart corosync` nodo a nodo + `systemctl restart pve-cluster`
   para refrescar el cache pmxcfs (`.members`, API).
2. **`/etc/hosts`** en los 3 nodos: FQDN `*.jasoc.cat` → `*.jamsec.cat`.
3. **`root@pam`**: email cambiado al de Alex (`pveum user modify`).
4. **`/root/.bash_history`** vaciado en los 3 nodos.
5. **NO modificados** (por la política de no-reinicio):
   - hostname corto de los nodos: `proxmox`, `pve2`, `pve3` se mantienen.
     Justificación: cambiar el hostname de un nodo en cluster Proxmox requiere
     procedimiento con reinicio.

## Backups conservados

Por si algo va mal, en cada nodo en `/root/`:

- `corosync.conf.bak-20260601-155638` (solo en proxmox)
- `hosts.bak-20260601-155638`
- `bash_history.bak-20260601-155638`

Y copia local en `inventario/cluster/corosync.conf.original-20260601-155638.bak`.

## Verificaciones realizadas

```
ssh proxmox 'pvecm status'
  → Name: JAMSEC-Cluster, Config Version: 4, Quorate: Yes, Total votes: 3

ssh proxmox 'corosync-cmapctl | grep cluster_name'
  → totem.cluster_name = JAMSEC-Cluster

ssh proxmox 'cat /etc/pve/.members'
  → "cluster": { "name": "JAMSEC-Cluster", ... }

ssh proxmox 'grep -RIin JASOC /etc/pve /etc/hosts'
  → (sin resultados)
```

## Inventario detallado

Los volcados completos están en `inventario/` con la estructura:

- `cluster/` — pveversion, pvecm-status, corosync.conf, storage, users, firewall, grep-jasoc
- `proxmox/`, `pv2/`, `pv3/` — host, network, vms-cts, resources

## Lo que viene

Punto 1b: diseñar y montar la infraestructura JAMSEC (firewall, DMZ, servicios,
multirred) sobre el cluster, ya como VMs/CTs reales.
