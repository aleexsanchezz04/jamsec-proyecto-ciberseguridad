const fs = require("fs");
const {
  Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell,
  Header, Footer, AlignmentType, LevelFormat, HeadingLevel, BorderStyle,
  WidthType, ShadingType, VerticalAlign, PageNumber, PageBreak, TableOfContents
} = require("docx");

// ---------- Paleta / constantes ----------
const AC = "16407A";     // azul corporativo
const GREEN = "19C37D";
const CW = 9026;         // content width A4 con margen 1440 (11906-2880)
const border = { style: BorderStyle.SINGLE, size: 1, color: "BBBBBB" };
const borders = { top: border, bottom: border, left: border, right: border };
const SEVCOL = { "Crítica": "C0392B", "Alta": "E67E22", "Media": "F1C40F", "Baja": "27AE60" };

// ---------- Helpers ----------
const H1 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun(t)] });
const H2 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun(t)] });
const H3 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_3, children: [new TextRun(t)] });
const P = (t, opts = {}) => new Paragraph({ spacing: { after: 120 }, children: [new TextRun({ text: t, ...opts })] });
const BUL = (t) => new Paragraph({ numbering: { reference: "bul", level: 0 }, spacing: { after: 40 }, children: [new TextRun(t)] });
function cell(text, { w, fill, bold, color, align } = {}) {
  const runs = Array.isArray(text) ? text : [new TextRun({ text: String(text), bold: !!bold, color })];
  return new TableCell({
    borders, width: { size: w, type: WidthType.DXA },
    shading: fill ? { fill, type: ShadingType.CLEAR } : undefined,
    margins: { top: 60, bottom: 60, left: 100, right: 100 },
    verticalAlign: VerticalAlign.CENTER,
    children: [new Paragraph({ alignment: align || AlignmentType.LEFT, children: runs })]
  });
}
function kvTable(rows) {
  const w1 = 2400, w2 = CW - w1;
  return new Table({
    width: { size: CW, type: WidthType.DXA }, columnWidths: [w1, w2],
    rows: rows.map(([k, v]) => new TableRow({ children: [
      cell(k, { w: w1, fill: "EEF2F7", bold: true }),
      cell(v, { w: w2 })
    ]}))
  });
}

// ---------- Datos de hallazgos ----------
const findings = [
  { id: "JAM-01", titulo: "Inyección SQL en el formulario de acceso (login.php)", sev: "Crítica",
    cvss: "9.8", vector: "AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H",
    activos: "apex-web (10.30.20.10) — http://172.20.17.230/login.php",
    owasp: "A03:2021 – Injection",
    desc: "El parámetro 'usuario' del formulario de autenticación se concatena directamente en una consulta SQL sin parametrización ni saneamiento. Permite eludir la autenticación y, mediante consultas UNION/booleanas, extraer el contenido íntegro de la base de datos.",
    evidencia: "sqlmap identificó inyección boolean-based, time-based y UNION. Payload de bypass: usuario=admin' OR '1'='1. Se volcaron las tablas clientes, nominas y usuarios de la BBDD 'apexgestoria'. Evidencia: 03-sqlmap-dbs.txt, 04-sqlmap-dump.txt.",
    impacto: "Exposición total de datos personales y financieros (NIF, DNI, IBAN, salarios) de clientes y empleados, y de las credenciales de la aplicación. Incumplimiento del RGPD.",
    rem: "Usar consultas parametrizadas (prepared statements / PDO). Validar y normalizar toda entrada. Aplicar el principio de mínimo privilegio al usuario de BBDD de la aplicación. Desplegar un WAF como medida compensatoria." },

  { id: "JAM-02", titulo: "Base de datos MariaDB expuesta con superusuario remoto y contraseña débil", sev: "Crítica",
    cvss: "9.8", vector: "AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H",
    activos: "apex-db (10.30.10.10:3306)",
    owasp: "A05:2021 – Security Misconfiguration",
    desc: "El servicio MariaDB escucha en todas las interfaces (0.0.0.0) y permite la conexión del usuario root desde cualquier host ('root'@'%') con la contraseña trivial 'toor'. Cualquier sistema con alcance de red puede obtener control total de la base de datos.",
    evidencia: "Credenciales obtenidas del fichero credencials.txt (ver JAM-05) y confirmadas durante el pivote: acceso root a la BBDD interna y exfiltración de la tabla de nóminas. Evidencia: 07-pivote.txt.",
    impacto: "Compromiso total de la confidencialidad, integridad y disponibilidad de todos los datos del cliente. Posibilidad de manipulación o destrucción de registros.",
    rem: "Vincular MariaDB a la interfaz interna (bind-address). Eliminar 'root'@'%'. Establecer contraseñas robustas y únicas. Restringir el acceso por IP de origen (solo el servidor de aplicación). Activar cifrado en tránsito." },

  { id: "JAM-03", titulo: "Subida de ficheros sin validación (ejecución remota de código)", sev: "Crítica",
    cvss: "9.8", vector: "AV:N/AC:L/PR:L/UI:N/S:C/C:H/I:H/A:H",
    activos: "apex-web (10.30.20.10) — panel.php",
    owasp: "A04:2021 – Insecure Design / A03 Injection",
    desc: "El panel autenticado permite subir ficheros sin validar tipo, extensión ni contenido, almacenándolos en un directorio accesible desde la web (/uploads). Combinado con el bypass de autenticación (JAM-01), permite subir y ejecutar una webshell PHP (RCE).",
    evidencia: "Función move_uploaded_file sobre el nombre original sin comprobaciones; directorio /uploads con permisos de escritura (0777). Confirmada la viabilidad de webshell.",
    impacto: "Ejecución de comandos en el servidor web, sirviendo de punto de entrada al resto de la infraestructura (ver cadena de ataque).",
    rem: "Validar extensión/MIME mediante lista blanca, renombrar ficheros, almacenarlos fuera del directorio web y deshabilitar la ejecución de PHP en /uploads. Limitar tamaño y tipo." },

  { id: "JAM-04", titulo: "Credenciales SSH débiles susceptibles de fuerza bruta", sev: "Alta",
    cvss: "8.8", vector: "AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:L",
    activos: "apex-web (SSH publicado en 172.20.17.230:2222)",
    owasp: "A07:2021 – Identification and Authentication Failures",
    desc: "El servidor SSH del servidor web está publicado en Internet y dispone de la cuenta 'soporte' con la contraseña adivinable 'soporte123', obtenida por ataque de diccionario.",
    evidencia: "hydra obtuvo: host 172.20.17.230, login soporte, password soporte123. Evidencia: 06-hydra-ssh.txt.",
    impacto: "Acceso interactivo al servidor de la DMZ con privilegios sudo, habilitando el movimiento lateral hacia la red interna.",
    rem: "Deshabilitar la autenticación por contraseña (solo clave pública). Política de contraseñas robustas. fail2ban / bloqueo por intentos. No publicar SSH en Internet; usar VPN/bastión." },

  { id: "JAM-05", titulo: "Servidor FTP con acceso anónimo y exposición de información sensible", sev: "Alta",
    cvss: "8.2", vector: "AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:L/A:N",
    activos: "apex-srv (FTP publicado en 172.20.17.230:21)",
    owasp: "A05:2021 – Security Misconfiguration",
    desc: "El servicio vsftpd permite acceso anónimo con permisos de lectura y escritura, exponiendo ficheros con credenciales internas y un volcado de base de datos.",
    evidencia: "Descarga anónima de credencials.txt (contiene root/toor de la BBDD, credenciales de la app y del router) y de backup_apexgestoria.sql. Evidencia: 05-ftp.txt, ftp-credencials.txt.",
    impacto: "Filtración de credenciales que habilitan los hallazgos JAM-02 y la cadena de ataque completa. La escritura anónima permite alojar contenido malicioso.",
    rem: "Deshabilitar el acceso anónimo. Eliminar ficheros sensibles del servicio. Forzar FTPS/SFTP. Restringir por IP y aplicar mínimo privilegio." },

  { id: "JAM-06", titulo: "Segmentación de red deficiente: la DMZ accede a la red interna (LAN)", sev: "Alta",
    cvss: "7.4", vector: "AV:N/AC:L/PR:N/UI:N/S:C/C:H/I:N/A:N",
    activos: "apex-fw (política de cortafuegos)",
    owasp: "A01:2021 – Broken Access Control",
    desc: "El cortafuegos perimetral permite tráfico sin restricción desde el segmento DMZ hacia la LAN interna. Un servidor de la DMZ comprometido puede alcanzar directamente la base de datos y el servidor interno.",
    evidencia: "Desde apex-web (DMZ) se alcanzaron 10.30.10.10:3306 y 10.30.10.20:21 (LAN). Evidencia: 07-pivote.txt.",
    impacto: "Anula el propósito de la DMZ: convierte un compromiso perimetral en acceso a los datos internos (movimiento lateral).",
    rem: "Aplicar microsegmentación: permitir desde la DMZ únicamente los puertos estrictamente necesarios (p.ej. la app web hacia la BBDD en 3306) y denegar el resto por defecto." },

  { id: "JAM-07", titulo: "Almacenamiento de contraseñas en texto claro", sev: "Alta",
    cvss: "7.5", vector: "AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:N/A:N",
    activos: "apex-db — tabla apexgestoria.usuarios",
    owasp: "A02:2021 – Cryptographic Failures",
    desc: "Las contraseñas de los usuarios de la aplicación se almacenan sin cifrar ni aplicar funciones hash, visibles directamente al consultar la base de datos.",
    evidencia: "Volcado de la tabla usuarios: admin/Apex.2026!, jmorales/gestoria2024, soporte/soporte123. Evidencia: 04-sqlmap-dump.txt.",
    impacto: "Cualquier acceso a la BBDD revela credenciales reutilizables en otros servicios.",
    rem: "Almacenar contraseñas con un algoritmo de hash robusto y salado (bcrypt/Argon2). Forzar el restablecimiento de todas las contraseñas afectadas." },

  { id: "JAM-08", titulo: "Comunicaciones sin cifrar (HTTP y FTP en texto claro)", sev: "Media",
    cvss: "5.9", vector: "AV:N/AC:H/PR:N/UI:N/S:U/C:H/I:N/A:N",
    activos: "apex-web (HTTP/80), apex-srv (FTP/21)",
    owasp: "A02:2021 – Cryptographic Failures",
    desc: "Las aplicaciones web y de transferencia de ficheros operan sin TLS, transmitiendo credenciales y datos sensibles en texto claro.",
    evidencia: "Servicio web solo en el puerto 80 (443 cerrado); vsftpd sin TLS. Evidencia: 01-nmap-servicios.txt.",
    impacto: "Interceptación de credenciales y datos mediante ataques de interceptación (MITM) en la red.",
    rem: "Implantar HTTPS con certificados válidos y redirección forzada. Habilitar FTPS o sustituir por SFTP. Aplicar HSTS." },

  { id: "JAM-09", titulo: "Interfaz de administración SSH del cortafuegos expuesta", sev: "Media",
    cvss: "5.3", vector: "AV:N/AC:L/PR:N/UI:N/S:U/C:L/I:N/A:N",
    activos: "apex-fw (172.20.17.230:22)",
    owasp: "A05:2021 – Security Misconfiguration",
    desc: "El servicio SSH de gestión del cortafuegos perimetral es accesible desde la red de tránsito, ampliando la superficie de ataque del dispositivo de seguridad.",
    evidencia: "nmap detectó 22/tcp abierto en el perímetro. Evidencia: 01-nmap-servicios.txt.",
    impacto: "Posibilidad de ataques de fuerza bruta o explotación contra el propio cortafuegos.",
    rem: "Restringir la gestión a una red de administración dedicada o VPN. Autenticación por clave y MFA. Filtrado por IP de origen." },
];

const sevCount = findings.reduce((a, f) => (a[f.sev] = (a[f.sev] || 0) + 1, a), {});

// ---------- Documento ----------
const docOpts = {
  creator: "JAMSEC",
  title: "Informe Técnico de Auditoría de Seguridad - Apex Gestoría",
  styles: {
    default: { document: { run: { font: "Arial", size: 22 } } },
    paragraphStyles: [
      { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 30, bold: true, color: AC, font: "Arial" },
        paragraph: { spacing: { before: 280, after: 160 }, outlineLevel: 0 } },
      { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 25, bold: true, color: "1F2A44", font: "Arial" },
        paragraph: { spacing: { before: 200, after: 120 }, outlineLevel: 1 } },
      { id: "Heading3", name: "Heading 3", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 22, bold: true, color: "333333", font: "Arial" },
        paragraph: { spacing: { before: 140, after: 80 }, outlineLevel: 2 } },
    ]
  },
  numbering: { config: [
    { reference: "bul", levels: [{ level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT,
      style: { paragraph: { indent: { left: 540, hanging: 260 } } } }] },
  ]},
};

// ===== Portada =====
const portada = {
  properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
  children: [
    new Paragraph({ spacing: { before: 1800, after: 0 }, alignment: AlignmentType.CENTER,
      children: [new TextRun({ text: "JAM", bold: true, size: 72, color: "1F2A44" }), new TextRun({ text: "SEC", bold: true, size: 72, color: GREEN })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 1200 },
      children: [new TextRun({ text: "Servicios Gestionados de Ciberseguridad", italics: true, size: 24, color: "666666" })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, border: { top: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 }, bottom: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 } }, spacing: { before: 200, after: 200 },
      children: [new TextRun({ text: "INFORME TÉCNICO DE AUDITORÍA DE SEGURIDAD", bold: true, size: 36, color: AC })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, spacing: { before: 200, after: 60 },
      children: [new TextRun({ text: "Pruebas de intrusión sobre la infraestructura tecnológica", size: 24 })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 1400 },
      children: [new TextRun({ text: "Cliente: Apex Gestoría", bold: true, size: 28, color: "1F2A44" })] }),
    new Table({ width: { size: 6000, type: WidthType.DXA }, columnWidths: [2400, 3600], alignment: AlignmentType.CENTER,
      rows: [
        ["Referencia", "JAMSEC-AUD-2026-001"],
        ["Versión", "1.0"],
        ["Fecha", "1 de junio de 2026"],
        ["Autor", "Alex Sánchez García — JAMSEC"],
        ["Clasificación", "CONFIDENCIAL"],
      ].map(([k, v]) => new TableRow({ children: [
        cell(k, { w: 2400, fill: "1F2A44", bold: true, color: "FFFFFF" }),
        cell(v, { w: 3600, fill: "F4F6F9" }),
      ]})) }),
    new Paragraph({ spacing: { before: 1600 }, alignment: AlignmentType.CENTER,
      children: [new TextRun({ text: "Documento confidencial. Su contenido no podrá ser divulgado a terceros sin autorización expresa de Apex Gestoría y JAMSEC.", italics: true, size: 18, color: "888888" })] }),
  ]
};

// ===== Cuerpo =====
const body = [];
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("Índice")] }));
body.push(new TableOfContents("Tabla de contenidos", { hyperlink: true, headingStyleRange: "1-3" }));

// 1. Resumen ejecutivo
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("1. Resumen ejecutivo")] }));
body.push(P("JAMSEC ha realizado una auditoría de seguridad ofensiva (pruebas de intrusión) sobre la infraestructura tecnológica de Apex Gestoría. El objetivo fue evaluar la exposición real de los sistemas frente a un atacante externo y determinar el impacto sobre la información del negocio."));
body.push(P("El resultado es desfavorable: se logró el compromiso total de la infraestructura del cliente, desde el acceso externo hasta la exfiltración de datos personales y financieros almacenados en la red interna. Se identificaron 9 hallazgos, de los cuales 3 son de severidad Crítica y 4 de severidad Alta."));
body.push(P("La cadena de ataque combinó una inyección SQL en la aplicación web, la filtración de credenciales por un servicio FTP mal configurado y una segmentación de red deficiente, permitiendo el salto desde la zona desmilitarizada (DMZ) hasta la base de datos interna con privilegios de superusuario."));
body.push(P("Se recomienda abordar de forma prioritaria los hallazgos críticos y altos conforme al plan de remediación del apartado 8."));

// Cuadro de severidades
body.push(H2("1.1. Cuadro de severidades"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [2256, 2256, 2257, 2257],
  rows: [
    new TableRow({ children: ["Crítica", "Alta", "Media", "Baja"].map((s, i) =>
      cell(s, { w: [2256,2256,2257,2257][i], fill: SEVCOL[s], bold: true, color: "FFFFFF", align: AlignmentType.CENTER })) }),
    new TableRow({ children: ["Crítica", "Alta", "Media", "Baja"].map((s, i) =>
      cell(String(sevCount[s] || 0), { w: [2256,2256,2257,2257][i], align: AlignmentType.CENTER, bold: true })) }),
  ] }));

// 2. Alcance
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("2. Alcance y objetivos")] }));
body.push(P("El alcance de la auditoría comprende la infraestructura perimetral e interna de Apex Gestoría accesible a través de su punto de presencia en red (172.20.17.230) y los segmentos internos alcanzables tras un compromiso inicial."));
body.push(H2("2.1. Activos en alcance"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [3000, 2400, 3626],
  rows: [
    new TableRow({ children: [cell("Activo", {w:3000,fill:AC,bold:true,color:"FFFFFF"}), cell("Dirección", {w:2400,fill:AC,bold:true,color:"FFFFFF"}), cell("Función", {w:3626,fill:AC,bold:true,color:"FFFFFF"})] }),
    ...[["apex-fw","172.20.17.230 (WAN)","Cortafuegos perimetral"],
        ["apex-web","10.30.20.10 (DMZ)","Servidor web público"],
        ["apex-db","10.30.10.10 (LAN)","Base de datos MariaDB"],
        ["apex-srv","10.30.10.20 (LAN)","Servidor interno / FTP"]].map(r =>
      new TableRow({ children: [cell(r[0],{w:3000}), cell(r[1],{w:2400}), cell(r[2],{w:3626})] })) ] }));
body.push(H2("2.2. Objetivos"));
[ "Identificar vulnerabilidades explotables en los servicios expuestos.",
  "Evaluar el impacto real mediante explotación controlada.",
  "Determinar la exposición de datos sensibles del cliente.",
  "Verificar la capacidad de detección y respuesta (SIEM/IDS).",
  "Proporcionar recomendaciones de remediación priorizadas." ].forEach(t => body.push(BUL(t)));

// 3. Metodología
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("3. Metodología")] }));
body.push(P("La auditoría siguió un enfoque de caja negra alineado con el marco PTES (Penetration Testing Execution Standard) y, para la parte web, con el OWASP Top 10 (2021) y la OWASP Testing Guide. La severidad de cada hallazgo se ha cuantificado con CVSS v3.1."));
body.push(H2("3.1. Fases (PTES)"));
[ "Recopilación de información y reconocimiento.",
  "Análisis de vulnerabilidades.",
  "Explotación.",
  "Post-explotación y movimiento lateral.",
  "Documentación e informe." ].forEach(t => body.push(BUL(t)));
body.push(H2("3.2. Escala de severidad (CVSS v3.1)"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [2256,2256,4514],
  rows: [
    new TableRow({ children: [cell("Severidad",{w:2256,fill:AC,bold:true,color:"FFFFFF"}),cell("Rango CVSS",{w:2256,fill:AC,bold:true,color:"FFFFFF"}),cell("Criterio de actuación",{w:4514,fill:AC,bold:true,color:"FFFFFF"})] }),
    ...[["Crítica","9.0 – 10.0","Remediación inmediata"],
        ["Alta","7.0 – 8.9","Remediación urgente (días)"],
        ["Media","4.0 – 6.9","Remediación planificada"],
        ["Baja","0.1 – 3.9","Remediación según disponibilidad"]].map((r,i)=>
      new TableRow({ children:[cell(r[0],{w:2256,bold:true,color:SEVCOL[r[0]]}),cell(r[1],{w:2256}),cell(r[2],{w:4514})] })) ] }));
body.push(H2("3.3. Herramientas utilizadas"));
body.push(P("nmap, sqlmap, hydra, whatweb, cliente FTP, cliente MariaDB, sshpass y utilidades estándar de red, ejecutadas desde la estación de auditoría de JAMSEC (Debian + arsenal de pentest)."));

// 4. Resumen de hallazgos
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("4. Resumen de hallazgos")] }));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [1100, 5426, 1250, 1250],
  rows: [
    new TableRow({ tableHeader: true, children: [cell("ID",{w:1100,fill:AC,bold:true,color:"FFFFFF"}),cell("Hallazgo",{w:5426,fill:AC,bold:true,color:"FFFFFF"}),cell("Severidad",{w:1250,fill:AC,bold:true,color:"FFFFFF",align:AlignmentType.CENTER}),cell("CVSS",{w:1250,fill:AC,bold:true,color:"FFFFFF",align:AlignmentType.CENTER})] }),
    ...findings.map(f => new TableRow({ children: [
      cell(f.id,{w:1100,bold:true}), cell(f.titulo,{w:5426}),
      cell(f.sev,{w:1250,fill:SEVCOL[f.sev],color:"FFFFFF",bold:true,align:AlignmentType.CENTER}),
      cell(f.cvss,{w:1250,align:AlignmentType.CENTER}) ] })) ] }));

// 5. Hallazgos detallados
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("5. Hallazgos detallados")] }));
findings.forEach((f, idx) => {
  body.push(new Paragraph({ heading: HeadingLevel.HEADING_2, pageBreakBefore: idx > 0, children: [new TextRun(`${f.id} — ${f.titulo}`)] }));
  body.push(kvTable([
    ["Severidad", `${f.sev}  (CVSS ${f.cvss})`],
    ["Vector CVSS", f.vector],
    ["Categoría OWASP", f.owasp],
    ["Activos afectados", f.activos],
  ]));
  body.push(H3("Descripción")); body.push(P(f.desc));
  body.push(H3("Evidencia")); body.push(P(f.evidencia));
  body.push(H3("Impacto")); body.push(P(f.impacto));
  body.push(H3("Recomendación")); body.push(P(f.rem));
});

// 6. Cadena de ataque
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("6. Cadena de ataque")] }));
body.push(P("La explotación encadenada de los hallazgos permitió pasar de un acceso externo nulo al control de la base de datos interna:"));
[ "Reconocimiento: descubrimiento de los servicios publicados (web, SSH, FTP) en el perímetro.",
  "Acceso a credenciales: descarga anónima por FTP del fichero de credenciales internas (JAM-05).",
  "Compromiso web: inyección SQL en el login y volcado de la base de datos (JAM-01).",
  "Acceso interactivo: fuerza bruta del usuario SSH 'soporte' en el servidor web (JAM-04).",
  "Movimiento lateral: desde la DMZ se alcanza la LAN interna por la segmentación deficiente (JAM-06).",
  "Exfiltración: acceso root a MariaDB con las credenciales filtradas y robo de las nóminas (JAM-02)." ].forEach(t => body.push(BUL(t)));

// 7. Detección
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("7. Detección y monitorización")] }));
body.push(P("La infraestructura de monitorización de JAMSEC (SIEM Wazuh + IDS Suricata) registró actividad asociada a la auditoría, demostrando capacidad de detección:"));
[ "Suricata (IDS de red): detección de escaneo de puertos ('ET SCAN Possible Nmap User-Agent').",
  "Wazuh (HIDS): múltiples 'sshd: authentication failed' (fuerza bruta) y posterior 'authentication success' (acceso).",
  "Wazuh: 'vsftpd: FTP Authentication success' (acceso anónimo al FTP).",
  "Wazuh: 'Web server 500 error' recurrentes durante el sondeo de inyección SQL." ].forEach(t => body.push(BUL(t)));
body.push(P("Recomendación: definir reglas de correlación y alertado proactivo (notificación) para estos patrones, y considerar el modo de prevención en línea (IPS) en el perímetro."));

// 8. Plan de remediación
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("8. Conclusiones y plan de remediación")] }));
body.push(P("El nivel de seguridad de la infraestructura auditada es insuficiente. La combinación de errores de configuración, validación de entrada y segmentación permite un compromiso total con bajo nivel de esfuerzo. Se propone el siguiente plan priorizado:"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [1500, 6026, 1500],
  rows: [
    new TableRow({ children: [cell("Prioridad",{w:1500,fill:AC,bold:true,color:"FFFFFF"}),cell("Acción",{w:6026,fill:AC,bold:true,color:"FFFFFF"}),cell("Hallazgos",{w:1500,fill:AC,bold:true,color:"FFFFFF"})] }),
    ...[["Inmediata","Parametrizar consultas SQL y desplegar WAF; restablecer credenciales","JAM-01, JAM-07"],
        ["Inmediata","Restringir MariaDB a la LAN y eliminar root remoto con contraseña débil","JAM-02"],
        ["Inmediata","Corregir la subida de ficheros y bloquear ejecución en /uploads","JAM-03"],
        ["Urgente","Endurecer SSH (solo clave, fail2ban) y retirar de Internet","JAM-04, JAM-09"],
        ["Urgente","Deshabilitar FTP anónimo y retirar ficheros sensibles","JAM-05"],
        ["Urgente","Microsegmentar DMZ↔LAN en el cortafuegos","JAM-06"],
        ["Planificada","Implantar HTTPS/FTPS en todos los servicios","JAM-08"]].map(r =>
      new TableRow({ children:[cell(r[0],{w:1500,bold:true}),cell(r[1],{w:6026}),cell(r[2],{w:1500})] })) ] }));

// 9. Anexos
body.push(new Paragraph({ heading: HeadingLevel.HEADING_1, pageBreakBefore: true, children: [new TextRun("9. Anexos")] }));
body.push(P("Las evidencias completas (salidas de nmap, sqlmap, hydra, FTP y del pivote) se adjuntan en el directorio de evidencias del repositorio del proyecto:"));
[ "01-nmap-servicios.txt — reconocimiento de servicios.",
  "03-sqlmap-dbs.txt / 04-sqlmap-dump.txt — inyección SQL y volcado de datos.",
  "05-ftp.txt / ftp-credencials.txt / ftp-backup.sql — acceso FTP anónimo.",
  "06-hydra-ssh.txt — fuerza bruta SSH.",
  "07-pivote.txt — movimiento lateral DMZ→LAN y exfiltración." ].forEach(t => body.push(BUL(t)));

const cuerpo = {
  properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
  headers: { default: new Header({ children: [ new Paragraph({ alignment: AlignmentType.RIGHT,
    border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: AC, space: 4 } },
    children: [ new TextRun({ text: "JAMSEC · Informe Técnico de Auditoría — Apex Gestoría", size: 16, color: "888888" }) ] }) ] }) },
  footers: { default: new Footer({ children: [ new Paragraph({ alignment: AlignmentType.CENTER,
    children: [ new TextRun({ text: "CONFIDENCIAL — Página ", size: 16, color: "888888" }),
                new TextRun({ children: [PageNumber.CURRENT], size: 16, color: "888888" }) ] }) ] }) },
  children: body
};

const doc = new Document({ ...docOpts, sections: [portada, cuerpo] });
Packer.toBuffer(doc).then(buffer => {
  fs.writeFileSync("JAMSEC_Informe_Tecnico_Auditoria_Apex.docx", buffer);
  console.log("OK informe técnico generado");
});
