# Punto 3 — IDS/IPS/SIEM (Wazuh + Suricata)

Fecha: 2026-06-01. Controla **ambas** infraestructuras (JAMSEC y Apex).

## Arquitectura

```
        ┌──────────────────────── jamsec-wazuh (SOC 10.20.10.20) ─────────┐
        │  Wazuh 4.12 all-in-one: indexer + manager + dashboard + filebeat │
        │  2ª NIC en tránsito 172.20.17.231 (punto de recogida de agentes) │
        └───────▲──────────────────────────────────▲──────────────────────┘
                │ agentes (1514/1515)               │
   ┌────────────┴───────────┐          ┌────────────┴────────────────┐
   │  Infra JAMSEC           │          │  Infra CLIENTE (Apex)        │
   │  jamsec-fw  (002)+Suri  │          │  apex-fw (004) + Suricata    │
   │  jamsec-web (001)       │          │  apex-web (005)              │
   │  jamsec-srv (003)       │          │  apex-db  (006)              │
   └─────────────────────────┘          │  apex-srv (007)              │
                                         └──────────────────────────────┘
   Todos los agentes apuntan a 172.20.17.231 (alcanzable por NAT sin
   romper el aislamiento DMZ/SRV↔SOC).
```

## Componentes

| Capa | Producto | Dónde | Estado |
|------|----------|-------|--------|
| SIEM | Wazuh 4.12.0 (manager+indexer+dashboard) | jamsec-wazuh | ✅ activo |
| HIDS / log collection | Wazuh agent 4.12.0 | 7 VMs (ambas infras) | ✅ 7/7 Active |
| NIDS | Suricata 7.0.10 (66.218 reglas ET Open) | jamsec-fw eth0, apex-fw eth0 | ✅ activo |
| Integración NIDS→SIEM | eve.json leído por el agente | ambos firewalls | ✅ 427+ alertas decodificadas |

## Acceso al dashboard

- URL interna: `https://172.20.17.231:443` (o `10.20.10.20:443` desde el SOC)
- Desde el PC (Tailscale): `ssh -L 8443:172.20.17.231:443 pv2` → `https://localhost:8443`
- Usuario `admin` (password en CREDENCIALES.txt, fuera de git)

## Agentes Wazuh (manager `agent_control -l`)

```
000 jamsec-wazuh (manager)   001 jamsec-web   002 jamsec-fw   003 jamsec-srv
004 apex-fw   005 apex-web   006 apex-db   007 apex-srv      → todos Active
```

## Modo IDS vs IPS

Suricata se ha desplegado en **modo IDS (detección)** sobre la interfaz WAN de cada
firewall (af-packet). Decisión: mantener detección por estabilidad. Suricata es un
motor **IDS/IPS**; el modo IPS (bloqueo en línea) se puede habilitar redirigiendo la
cadena FORWARD de nftables a NFQUEUE y ejecutando Suricata en modo inline, sin afectar
a la gestión del firewall. Queda documentado como capacidad disponible.

## Verificación end-to-end

- `agent_control -l` → 8 entradas Active (manager + 7 agentes).
- apex-fw eve.json: event_type alert/flow/dns/ssh/tls; reglas cargadas 66.218.
- Manager alerts.json: 427 alertas con grupo `["ids","suricata"]` (regla 86601),
  procedentes del agente 002 (jamsec-fw) → pipeline Suricata→Wazuh confirmado.

> Nota: aparecen alertas benignas "SURICATA AF-PACKET truncated packet" por el
> offload de la NIC virtio; son ruido inofensivo (no afectan a la detección real).

## Scripts (repo)

- `scripts/install-wazuh.sh` (manager, parcheado para Debian 13)
- `scripts/install-wazuh-agent.sh <MANAGER_IP> <NOMBRE>` (agente 4.12 fijado)
- `scripts/install-suricata.sh <IFACE>` (NIDS + integración eve.json↔Wazuh)
- `scripts/verif-suricata.sh`, `check-suricata-wazuh.sh` (verificación)
