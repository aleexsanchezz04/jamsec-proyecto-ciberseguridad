# Acceso a los servicios para la demo

Resumen rápido de cómo entrar a todo según desde dónde presentes.

## ¿Desde dónde vas a presentar?

### Caso A — PC del profesor / aula (IP que empieza por `172.20.17.x`)  ← lo más probable
**No necesitas túnel.** Ese PC está en la misma red que el clúster, así que llegas
**directamente por IP** a todos los servicios. Y funciona **sea cual sea la IP** de
ese PC (los cortafuegos no filtran por IP de origen).

1. (Opcional) Ejecuta el test para confirmar que llegas:
   ```powershell
   powershell -ExecutionPolicy Bypass -File test-conectividad.ps1
   ```
2. Abre en el navegador:

| Servicio | URL directa |
|----------|-------------|
| 🌐 Web JAMSEC | http://172.20.17.240 |
| 🌐 Web Apex Gestoría | http://172.20.17.230 |
| 🛡️ Wazuh (SIEM) | https://172.20.17.231 |
| 🖥️ Proxmox (panel) | https://172.20.17.202:8006 |

> Wazuh y Proxmox usan certificado propio → el navegador avisa: **Avanzado → Continuar**.

### Caso B — Tu propio portátil (desde casa / fuera del aula, por Tailscale)
Necesitas el túnel (tu PC entra por Tailscale, no está en la red del aula):
```powershell
& ".\abrir-accesos.ps1"
```
Y usas: http://localhost:8080 (JAMSEC), http://localhost:8081 (Apex),
https://localhost:8443 (Wazuh), https://localhost:8006 (Proxmox).

> El túnel **funciona desde cualquier IP** porque sale por Tailscale; no depende
> de la red en la que estés.

## La auditoría en vivo (estación Red Team)
La auditoría NO se lanza desde el navegador, sino desde la estación de ataque:
```
ssh jamsec-redteam
bash auditoria-paso-a-paso.sh
```
Si presentas desde el PC del aula y no tienes los alias SSH, puedes saltar por un nodo:
```
ssh -J root@172.20.17.203 jamsec@10.20.10.50
```
(usuario `jamsec`, contraseña de consola `Jamsec2026!`).

## Credenciales útiles para la demo
| Para | Acceso |
|------|--------|
| Wazuh | admin / (ver CREDENCIALES.txt) |
| Proxmox | root@pam / (contraseña de root del clúster) |
| Web Apex (login normal) | admin / Apex.2026! |
| Web Apex (bypass SQLi) | usuario: `admin' OR '1'='1`  ·  contraseña: cualquiera |

## Recomendado (lo piden los profes)
- Llega 20 min antes y pasa el `test-conectividad.ps1`.
- Ten preparado el **vídeo de respaldo** de la demo por si la red falla.
