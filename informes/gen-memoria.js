const fs = require("fs");
const { Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell, Header, Footer,
  AlignmentType, LevelFormat, HeadingLevel, BorderStyle, WidthType, ShadingType, VerticalAlign,
  PageNumber, TableOfContents } = require("docx");

const AC = "16407A", GREEN = "19C37D", CW = 9026;
const border = { style: BorderStyle.SINGLE, size: 1, color: "BBBBBB" };
const borders = { top: border, bottom: border, left: border, right: border };
const H1 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun(t)] });
const H2 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun(t)] });
const P = (t, o = {}) => new Paragraph({ spacing: { after: 130 }, alignment: AlignmentType.JUSTIFIED, children: [new TextRun({ text: t, ...o })] });
const BUL = (t) => new Paragraph({ numbering: { reference: "bul", level: 0 }, spacing: { after: 50 }, children: [new TextRun(t)] });
function cell(t, { w, fill, bold, color } = {}) {
  return new TableCell({ borders, width: { size: w, type: WidthType.DXA }, shading: fill ? { fill, type: ShadingType.CLEAR } : undefined,
    margins: { top: 60, bottom: 60, left: 100, right: 100 }, verticalAlign: VerticalAlign.CENTER,
    children: [new Paragraph({ children: [new TextRun({ text: String(t), bold: !!bold, color })] })] });
}
const table = (head, rows, widths) => new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: widths,
  rows: [ new TableRow({ children: head.map((h, i) => cell(h, { w: widths[i], fill: AC, bold: true, color: "FFFFFF" })) }),
          ...rows.map(r => new TableRow({ children: r.map((c, i) => cell(c, { w: widths[i] })) })) ] });

const docOpts = {
  creator: "JAMSEC", title: "Memoria del Proyecto JAMSEC",
  styles: { default: { document: { run: { font: "Arial", size: 22 } } }, paragraphStyles: [
    { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true, run: { size: 30, bold: true, color: AC, font: "Arial" }, paragraph: { spacing: { before: 280, after: 160 }, outlineLevel: 0 } },
    { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true, run: { size: 24, bold: true, color: "1F2A44", font: "Arial" }, paragraph: { spacing: { before: 200, after: 110 }, outlineLevel: 1 } },
  ]},
  numbering: { config: [ { reference: "bul", levels: [{ level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 540, hanging: 260 } } } }] } ] },
};

const portada = { properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } }, children: [
  new Paragraph({ spacing: { before: 2000 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "JAM", bold: true, size: 72, color: "1F2A44" }), new TextRun({ text: "SEC", bold: true, size: 72, color: GREEN })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 1200 }, children: [new TextRun({ text: "Servicios Gestionados de Ciberseguridad", italics: true, size: 24, color: "666666" })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, border: { top: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 }, bottom: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 } }, spacing: { before: 200, after: 200 }, children: [new TextRun({ text: "MEMORIA TÉCNICA DEL PROYECTO", bold: true, size: 36, color: AC })] }),
  new Paragraph({ alignment: AlignmentType.CENTER, spacing: { before: 200, after: 1400 }, children: [new TextRun({ text: "Infraestructura empresarial, monitorización y auditoría de seguridad", size: 24 })] }),
  new Table({ width: { size: 6000, type: WidthType.DXA }, columnWidths: [2400, 3600], alignment: AlignmentType.CENTER,
    rows: [["Proyecto","JAMSEC / Apex Gestoría"],["Versión","1.0"],["Fecha","1 de junio de 2026"],["Autor","Alex Sánchez García"],["Plataforma","Clúster Proxmox VE (3 nodos)"]]
    .map(([k,v]) => new TableRow({ children: [cell(k,{w:2400,fill:"1F2A44",bold:true,color:"FFFFFF"}), cell(v,{w:3600,fill:"F4F6F9"})] })) }),
]};

const body = [];
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("Índice")] }));
body.push(new TableOfContents("Tabla de contenidos", { hyperlink: true, headingStyleRange: "1-2" }));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("1. Introducción y contexto")] }));
body.push(P("El presente proyecto desarrolla, de extremo a extremo, el caso de una empresa de ciberseguridad (JAMSEC) que despliega su propia infraestructura, monitoriza la de un cliente (Apex Gestoría) y realiza una auditoría de intrusión sobre ella, entregando los informes correspondientes."));
body.push(P("La infraestructura se ha construido sobre un clúster Proxmox VE de tres nodos. Toda la configuración se ha realizado de forma reproducible mediante cloud-init y scripts versionados, sin reiniciar los nodos físicos del clúster en ningún momento, por requisito operativo."));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("2. Objetivos")] }));
[ "Montar una infraestructura empresarial propia (cortafuegos, DMZ, servicios separados, multirred).",
  "Montar por separado la infraestructura de un cliente.",
  "Implantar un IDS/IPS/SIEM que controle ambas infraestructuras.",
  "Auditar la infraestructura del cliente mediante pruebas de intrusión de distintos ámbitos.",
  "Elaborar un informe técnico y un informe ejecutivo de la auditoría.",
  "Documentar el proyecto y publicar el código en un repositorio.",
  "Preparar una demostración de la auditoría." ].forEach(t => body.push(BUL(t)));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("3. Infraestructura de JAMSEC")] }));
body.push(P("La infraestructura propia de JAMSEC se aloja en el nodo 'proxmox' y se segmenta en cuatro redes mediante un cortafuegos perimetral basado en Debian 13 y nftables, que realiza NAT y filtrado entre zonas."));
body.push(table(["Zona","Red","Contenido"], [
  ["WAN","172.20.17.0/24","Salida a Internet / tránsito"],
  ["SOC","10.20.10.0/24","Estación red team y SIEM (Wazuh)"],
  ["DMZ","10.20.20.0/24","Web corporativa (nginx)"],
  ["Servicios","10.20.30.0/24","Servidor de ficheros (Samba)"]], [2000, 3000, 4026]));
body.push(P("La política del cortafuegos aísla la DMZ y la red de servicios entre sí y respecto del SOC, permitiendo únicamente la salida controlada a Internet y la publicación de la web mediante DNAT. Se verificó que la DMZ no puede alcanzar la red interna."));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("4. Infraestructura del cliente (Apex Gestoría)")] }));
body.push(P("La infraestructura del cliente se aloja en un nodo distinto ('pve2'), garantizando una separación física real respecto de JAMSEC. Dispone de su propio cortafuegos, una DMZ con la web pública y una LAN interna con la base de datos y un servidor de ficheros."));
body.push(P("Con fines de auditoría se introdujeron vulnerabilidades representativas de entornos reales: inyección SQL en la web, subida de ficheros sin validación, base de datos con superusuario remoto y contraseña débil, FTP con acceso anónimo, credenciales SSH débiles, segmentación deficiente y ausencia de cifrado en tránsito."));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("5. Monitorización: IDS/IPS/SIEM")] }));
body.push(P("Se desplegó Wazuh 4.12 (gestor, indexador y panel) como SIEM en el SOC de JAMSEC, con agentes instalados en las siete máquinas de ambas infraestructuras para la monitorización basada en host (HIDS) y la recolección de logs. Para evitar romper la segmentación, los agentes se comunican con el gestor a través de una interfaz dedicada en la red de tránsito."));
body.push(P("Adicionalmente se instaló Suricata como IDS de red en los dos cortafuegos, integrando sus alertas en Wazuh. El conjunto proporciona visibilidad sobre ambas infraestructuras de forma centralizada."));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("6. Auditoría de seguridad")] }));
body.push(P("Desde una estación de red team situada en el SOC se ejecutó una auditoría de caja negra siguiendo el marco PTES y el OWASP Top 10, puntuando los hallazgos con CVSS v3.1. Se logró el compromiso total del cliente y la exfiltración de datos personales y financieros desde la red interna, encadenando varias debilidades."));
body.push(P("El resultado se resume en 3 hallazgos críticos, 4 altos y 2 medios. El detalle figura en el informe técnico; la versión para dirección, en el informe ejecutivo. El SIEM registró la actividad del ataque (escaneo, fuerza bruta, accesos), demostrando capacidad de detección."));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("7. Incidencias y soluciones")] }));
body.push(table(["Incidencia","Solución aplicada"], [
  ["Conflictos de IP en la red de tránsito compartida del aula","Verificación de IPs libres (ping + ARP) desde varios nodos antes de asignar; reasignación de las WAN afectadas"],
  ["Instalador de Wazuh incompatible con Debian 13 (paquete retirado)","Parcheo de la lista de dependencias del instalador"],
  ["Agentes Wazuh más nuevos que el gestor (rechazo de enrolamiento)","Fijación de la versión del agente a la del gestor (4.12.0)"],
  ["Falsos positivos masivos de Suricata sobre virtio","Desactivación de offloads de NIC y captura a un solo hilo"],
  ["Cortafuegos del cliente sin memoria al cargar Suricata","Ampliación de RAM de la VM (reinicio de VM, permitido)"]], [4000, 5026]));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("8. Conclusiones")] }));
body.push(P("El proyecto cumple los objetivos planteados: se han desplegado dos infraestructuras empresariales separadas y funcionales, una solución de monitorización IDS/IPS/SIEM que cubre ambas, y una auditoría de intrusión completa con sus informes técnico y ejecutivo. Todo el trabajo es reproducible y está documentado y versionado."));
body.push(P("La auditoría evidencia la importancia de la defensa en profundidad: la segmentación de red, la gestión de credenciales, la validación de entradas y el cifrado son controles que, combinados, habrían impedido la cadena de ataque demostrada."));

body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("9. Anexos")] }));
body.push(P("El código, las configuraciones (cloud-init, nftables), los scripts de automatización, las evidencias de la auditoría y los informes se encuentran en el repositorio del proyecto, organizados según se describe en su fichero README. La documentación técnica por fases está en el directorio docs/."));

const cuerpo = { properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
  headers: { default: new Header({ children: [ new Paragraph({ alignment: AlignmentType.RIGHT, border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: AC, space: 4 } }, children: [ new TextRun({ text: "JAMSEC · Memoria del Proyecto", size: 16, color: "888888" }) ] }) ] }) },
  footers: { default: new Footer({ children: [ new Paragraph({ alignment: AlignmentType.CENTER, children: [ new TextRun({ text: "Página ", size: 16, color: "888888" }), new TextRun({ children: [PageNumber.CURRENT], size: 16, color: "888888" }) ] }) ] }) },
  children: body };

const doc = new Document({ ...docOpts, sections: [portada, cuerpo] });
Packer.toBuffer(doc).then(b => { fs.writeFileSync("JAMSEC_Memoria_Proyecto.docx", b); console.log("OK memoria generada"); });
