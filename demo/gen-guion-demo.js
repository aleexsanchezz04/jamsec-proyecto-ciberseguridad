const fs = require("fs");
const { Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell, Header, Footer,
  AlignmentType, LevelFormat, HeadingLevel, BorderStyle, WidthType, ShadingType, VerticalAlign, PageNumber } = require("docx");

const AC = "16407A", GREEN = "19C37D", CW = 9026;
const border = { style: BorderStyle.SINGLE, size: 1, color: "BBBBBB" };
const borders = { top: border, bottom: border, left: border, right: border };
const H1 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun(t)] });
const H2 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun(t)] });
const P = (t, o = {}) => new Paragraph({ spacing: { after: 120 }, children: [new TextRun({ text: t, ...o })] });
const BUL = (t) => new Paragraph({ numbering: { reference: "bul", level: 0 }, spacing: { after: 50 }, children: [new TextRun(t)] });
const NUM = (t) => new Paragraph({ numbering: { reference: "num", level: 0 }, spacing: { after: 50 }, children: [new TextRun(t)] });
const MONO = (t) => new Paragraph({ spacing: { after: 80 }, shading: { fill: "F0F0F0", type: ShadingType.CLEAR }, children: [new TextRun({ text: t, font: "Consolas", size: 19 })] });
function cell(t, { w, fill, bold, color } = {}) {
  return new TableCell({ borders, width: { size: w, type: WidthType.DXA }, shading: fill ? { fill, type: ShadingType.CLEAR } : undefined,
    margins: { top: 60, bottom: 60, left: 100, right: 100 }, verticalAlign: VerticalAlign.CENTER,
    children: [new Paragraph({ children: [new TextRun({ text: String(t), bold: !!bold, color })] })] });
}

const docOpts = { creator: "JAMSEC", title: "Guion de la Demostración",
  styles: { default: { document: { run: { font: "Arial", size: 22 } } }, paragraphStyles: [
    { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true, run: { size: 28, bold: true, color: AC, font: "Arial" }, paragraph: { spacing: { before: 260, after: 140 }, outlineLevel: 0 } },
    { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true, run: { size: 23, bold: true, color: "1F2A44", font: "Arial" }, paragraph: { spacing: { before: 180, after: 100 }, outlineLevel: 1 } },
  ]},
  numbering: { config: [
    { reference: "bul", levels: [{ level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 540, hanging: 260 } } } }] },
    { reference: "num", levels: [{ level: 0, format: LevelFormat.DECIMAL, text: "%1.", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 540, hanging: 260 } } } }] },
  ]},
};

const portada = { properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } }, children: [
  new Paragraph({ spacing: { before: 2200 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "JAM", bold: true, size: 64, color: "1F2A44" }), new TextRun({ text: "SEC", bold: true, size: 64, color: GREEN })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, border: { top: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 }, bottom: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 } }, spacing: { before: 800, after: 200 }, children: [new TextRun({ text: "GUION DE LA DEMOSTRACIÓN", bold: true, size: 34, color: AC })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 120 }, children: [new TextRun({ text: "Auditoría de seguridad de Apex Gestoría", size: 24 })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, children: [new TextRun({ text: "Duración estimada: 8–12 minutos", italics: true, size: 22, color: "666666" })] }),
]};

const body = [];

body.push(H1("1. Objetivo de la demostración"));
body.push(P("Mostrar en vivo cómo JAMSEC audita la infraestructura del cliente Apex Gestoría: desde el reconocimiento externo hasta la exfiltración de datos de la red interna, y cómo el sistema de monitorización (SIEM/IDS) detecta la actividad."));

body.push(H1("2. Preparación previa (antes de empezar)"));
body.push(P("Ten abiertas y comprobadas estas ventanas:", { bold: true }));
NUM("Panel de Proxmox (https://IP-tailscale:8006): para mostrar las VMs de ambos nodos.");
[ "Panel de Proxmox (https://<ip>:8006) con las VMs de los dos nodos visibles.",
  "Navegador con la web de JAMSEC y la web de Apex Gestoría abiertas.",
  "Dashboard de Wazuh (túnel: ssh -L 8443:172.20.17.231:443 pv2 → https://localhost:8443).",
  "Terminal con sesión SSH en jamsec-redteam (la estación de ataque)." ].forEach(t => body.push(BUL(t)));
body.push(P("Comprobaciones rápidas:", { bold: true }));
body.push(MONO("ssh jamsec-redteam        # acceso a la estación red team"));
body.push(MONO("ping -c1 172.20.17.230     # objetivo (apex-fw) alcanzable"));

body.push(H1("3. Guion paso a paso"));

body.push(H2("Escena 1 — Presentación del entorno (1–2 min)"));
[ "En Proxmox, mostrar el nodo 'proxmox' con las VMs de JAMSEC (firewall, web, samba, wazuh, redteam) y el nodo 'pve2' con las del cliente (firewall, web, db, srv).",
  "Explicar: dos infraestructuras separadas físicamente, cada una con su cortafuegos, DMZ y red interna.",
  "Abrir la web corporativa de JAMSEC y la web de Apex Gestoría para mostrar que son servicios reales." ].forEach(t => body.push(BUL(t)));

body.push(H2("Escena 2 — Auditoría en vivo (4–5 min)"));
body.push(P("En la terminal de jamsec-redteam, lanzar el script de demo (avanza con ENTER entre fases):"));
body.push(MONO("bash ~/demo-auditoria.sh"));
body.push(P("Narrar cada fase mientras se ejecuta:", { bold: true }));
[ "Reconocimiento (nmap): «Identificamos los servicios expuestos del cliente».",
  "Inyección SQL (sqlmap): «La web permite volcar la base de datos; aquí están las nóminas con DNI, salario e IBAN».",
  "FTP anónimo: «El FTP expone un fichero con las credenciales internas, incluida la de la base de datos».",
  "Fuerza bruta SSH (hydra): «Adivinamos la contraseña del usuario soporte».",
  "Pivote DMZ→LAN: «Desde el servidor web saltamos a la red interna y robamos las nóminas con acceso de administrador a la base de datos»." ].forEach(t => body.push(BUL(t)));

body.push(H2("Escena 3 — Detección en el SIEM (2–3 min)"));
body.push(P("Cambiar al dashboard de Wazuh y mostrar que el ataque ha sido detectado:"));
[ "Módulo de alertas / Security events: filtrar por el agente del cliente.",
  "Mostrar la alerta de Suricata de escaneo de red (ET SCAN Nmap).",
  "Mostrar los eventos 'sshd: authentication failed' (fuerza bruta) y el 'authentication success' posterior.",
  "Mostrar el acceso anónimo al FTP y los errores 500 de la web durante la inyección.",
  "Mensaje clave: «aunque el cliente era vulnerable, nuestra monitorización detecta la intrusión en tiempo real»." ].forEach(t => body.push(BUL(t)));

body.push(H2("Escena 4 — Cierre (1 min)"));
[ "Resumir la cadena de ataque completa (Internet → web/FTP → SSH → DMZ → LAN → datos).",
  "Mencionar los informes entregados: técnico (con CVSS y remediación) y ejecutivo (para dirección).",
  "Señalar las medidas de remediación prioritarias: parametrizar SQL, cerrar FTP anónimo, reforzar SSH y segmentar DMZ↔LAN." ].forEach(t => body.push(BUL(t)));

body.push(H1("4. Checklist previo"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [700, 8326],
  rows: [["☐","jamsec-redteam accesible por SSH y con el script demo-auditoria.sh copiado"],
         ["☐","Objetivo 172.20.17.230 responde (apex-fw encendido)"],
         ["☐","Servicios del cliente arriba (web 80, ssh 2222, ftp 21)"],
         ["☐","Dashboard de Wazuh accesible (túnel SSH montado)"],
         ["☐","Las dos webs cargan en el navegador"],
         ["☐","Suricata activo en ambos firewalls (sin ruido tras el ajuste)"]]
    .map(r => new TableRow({ children: [cell(r[0],{w:700}), cell(r[1],{w:8326})] })) }));

body.push(H1("5. Posibles preguntas del tribunal"));
[ "¿Por qué los agentes apuntan a la IP de tránsito y no a la del SOC? Para no romper el aislamiento DMZ/SRV↔SOC; el tráfico de monitorización sale por NAT como si fuera externo.",
  "¿Suricata está en modo IDS o IPS? En modo IDS (detección) por estabilidad; el motor soporta IPS en línea, configurable vía NFQUEUE.",
  "¿Cómo se garantiza la separación entre JAMSEC y el cliente? Están en nodos físicos distintos del clúster, con cortafuegos y redes independientes.",
  "¿Es reproducible la infraestructura? Sí: todo se define con cloud-init y scripts versionados en el repositorio." ].forEach(t => body.push(BUL(t)));

const cuerpo = { properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
  headers: { default: new Header({ children: [ new Paragraph({ alignment: AlignmentType.RIGHT, border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: AC, space: 4 } }, children: [ new TextRun({ text: "JAMSEC · Guion de la Demostración", size: 16, color: "888888" }) ] }) ] }) },
  footers: { default: new Footer({ children: [ new Paragraph({ alignment: AlignmentType.CENTER, children: [ new TextRun({ text: "Página ", size: 16, color: "888888" }), new TextRun({ children: [PageNumber.CURRENT], size: 16, color: "888888" }) ] }) ] }) },
  children: body };

const doc = new Document({ ...docOpts, sections: [portada, cuerpo] });
Packer.toBuffer(doc).then(b => { fs.writeFileSync("JAMSEC_Guion_Demo.docx", b); console.log("OK guion demo generado"); });
