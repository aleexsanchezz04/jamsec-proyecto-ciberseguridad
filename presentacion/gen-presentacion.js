const pptxgen = require("pptxgenjs");
const p = new pptxgen();
p.layout = "LAYOUT_WIDE";        // 13.3 x 7.5
p.author = "Alex Sánchez García";
p.title = "Proyecto JAMSEC — Infraestructura, Monitorización y Auditoría";

// Paleta
const DARK = "0E1A2B", NAVY = "16407A", GREEN = "19C37D", INK = "1F2A44",
      MUT = "6B7280", WHITE = "FFFFFF", CARD = "F2F5F9", LINE = "DDE3EA";
const CRIT = "C0392B", ALTA = "E67E22", MEDIA = "E0B000";
const HF = "Trebuchet MS", BF = "Calibri";
const W = 13.3, H = 7.5;
const shadow = () => ({ type: "outer", color: "9AA5B1", blur: 8, offset: 3, angle: 135, opacity: 0.25 });

// Helpers de slide
function titleSlide() {
  const s = p.addSlide(); s.background = { color: DARK };
  s.addShape(p.shapes.OVAL, { x: 11.0, y: -1.6, w: 4.2, h: 4.2, fill: { color: GREEN, transparency: 82 } });
  s.addShape(p.shapes.OVAL, { x: -1.4, y: 5.2, w: 3.6, h: 3.6, fill: { color: NAVY, transparency: 55 } });
  s.addText([{ text: "JAM", options: { color: WHITE } }, { text: "SEC", options: { color: GREEN } }],
    { x: 0.9, y: 1.5, w: 8, h: 1.1, fontFace: HF, fontSize: 60, bold: true });
  s.addText("Servicios Gestionados de Ciberseguridad", { x: 0.95, y: 2.6, w: 9, h: 0.5, fontFace: BF, fontSize: 18, italic: true, color: "AEB9C7" });
  s.addText("Infraestructura empresarial, monitorización y auditoría de seguridad",
    { x: 0.9, y: 3.5, w: 11.4, h: 1.0, fontFace: HF, fontSize: 30, bold: true, color: WHITE });
  s.addText([
    { text: "Cliente auditado: ", options: { color: "AEB9C7" } },
    { text: "Apex Gestoría", options: { color: GREEN, bold: true } },
  ], { x: 0.95, y: 4.6, w: 10, h: 0.5, fontFace: BF, fontSize: 18 });
  s.addText("Alex Sánchez García · Curso de Especialización en Ciberseguridad · 2026",
    { x: 0.95, y: 6.4, w: 11, h: 0.5, fontFace: BF, fontSize: 14, color: "8A97A8" });
  return s;
}

function sectionSlide(num, titulo, subtitulo) {
  const s = p.addSlide(); s.background = { color: DARK };
  s.addShape(p.shapes.RECTANGLE, { x: 0.9, y: 2.5, w: 0.18, h: 2.0, fill: { color: GREEN } });
  s.addText(String(num).padStart(2, "0"), { x: 1.3, y: 2.2, w: 3, h: 1.2, fontFace: HF, fontSize: 64, bold: true, color: "23344A" });
  s.addText(titulo, { x: 1.3, y: 3.4, w: 11, h: 1.1, fontFace: HF, fontSize: 34, bold: true, color: WHITE });
  if (subtitulo) s.addText(subtitulo, { x: 1.32, y: 4.5, w: 11, h: 0.6, fontFace: BF, fontSize: 17, color: "AEB9C7" });
  return s;
}

function contentSlide(titulo) {
  const s = p.addSlide(); s.background = { color: WHITE };
  s.addText(titulo, { x: 0.7, y: 0.45, w: 12, h: 0.8, fontFace: HF, fontSize: 30, bold: true, color: INK, margin: 0 });
  return s;
}

function card(s, x, y, w, h, fill) {
  s.addShape(p.shapes.ROUNDED_RECTANGLE, { x, y, w, h, fill: { color: fill || CARD }, line: { color: LINE, width: 1 }, rectRadius: 0.08, shadow: shadow() });
}

// ---- Slide 1: portada ----
titleSlide();

// ---- Slide 2: agenda ----
(() => {
  const s = contentSlide("Agenda");
  const items = [
    ["1", "El proyecto", "Contexto, objetivos y actores"],
    ["2", "Infraestructura propia (JAMSEC)", "Firewall, DMZ, servicios y SOC"],
    ["3", "Infraestructura del cliente (Apex)", "Objetivo de la auditoría"],
    ["4", "Desarrollo y tecnologías", "Cómo se construyó y por qué"],
    ["5", "Conclusiones", "Dificultades, mejoras y costes"],
  ];
  let y = 1.6;
  items.forEach(it => {
    card(s, 0.9, y, 11.5, 0.92);
    s.addShape(p.shapes.OVAL, { x: 1.15, y: y + 0.21, w: 0.5, h: 0.5, fill: { color: GREEN } });
    s.addText(it[0], { x: 1.15, y: y + 0.21, w: 0.5, h: 0.5, align: "center", valign: "middle", fontFace: HF, fontSize: 20, bold: true, color: WHITE });
    s.addText(it[1], { x: 1.9, y: y + 0.12, w: 7.2, h: 0.4, fontFace: HF, fontSize: 18, bold: true, color: INK, margin: 0 });
    s.addText(it[2], { x: 1.9, y: y + 0.5, w: 9.8, h: 0.35, fontFace: BF, fontSize: 13, color: MUT, margin: 0 });
    y += 1.06;
  });
})();

// ---- Slide 3: sección 1 ----
sectionSlide(1, "El proyecto", "Una empresa de ciberseguridad y su cliente");

// ---- Slide 4: presentación del proyecto ----
(() => {
  const s = contentSlide("Presentación del proyecto");
  s.addText("JAMSEC es una empresa de ciberseguridad que despliega su propia infraestructura, monitoriza la de sus clientes y audita su seguridad. El proyecto recrea ese ciclo completo de extremo a extremo sobre un clúster de virtualización.",
    { x: 0.9, y: 1.5, w: 11.5, h: 1.0, fontFace: BF, fontSize: 16, color: INK });
  const cols = [
    [GREEN, "JAMSEC", "Empresa de ciberseguridad. Infraestructura propia + SOC (centro de operaciones)."],
    [NAVY, "Apex Gestoría", "Cliente / víctima. Infraestructura objetivo de la auditoría de intrusión."],
    ["7A869A", "Plataforma", "Clúster Proxmox VE de 3 nodos; dos infraestructuras separadas físicamente."],
  ];
  let x = 0.9;
  cols.forEach(c => {
    card(s, x, 2.8, 3.73, 1.9);
    s.addShape(p.shapes.RECTANGLE, { x: x, y: 2.8, w: 3.73, h: 0.12, fill: { color: c[0] } });
    s.addText(c[1], { x: x + 0.25, y: 3.05, w: 3.3, h: 0.5, fontFace: HF, fontSize: 18, bold: true, color: INK, margin: 0 });
    s.addText(c[2], { x: x + 0.25, y: 3.6, w: 3.3, h: 1.0, fontFace: BF, fontSize: 13, color: MUT, margin: 0 });
    x += 3.95;
  });
  s.addText("Objetivos", { x: 0.9, y: 5.0, w: 6, h: 0.4, fontFace: HF, fontSize: 17, bold: true, color: GREEN });
  s.addText([
    { text: "Infraestructura empresarial propia (FW, DMZ, multirred).", options: { bullet: true, breakLine: true } },
    { text: "Infraestructura de cliente separada.", options: { bullet: true, breakLine: true } },
    { text: "IDS/IPS/SIEM que controle ambas.", options: { bullet: true, breakLine: true } },
    { text: "Auditoría de intrusión e informes técnico y ejecutivo.", options: { bullet: true } },
  ], { x: 1.0, y: 5.4, w: 11.3, h: 1.7, fontFace: BF, fontSize: 14, color: INK, paraSpaceAfter: 6 });
})();

// ---- Slide 5: sección 2 ----
sectionSlide(2, "Infraestructura propia (JAMSEC)", "Firewall · DMZ · servicios separados · SOC");

// ---- Slide 6: arquitectura JAMSEC ----
(() => {
  const s = contentSlide("Arquitectura de JAMSEC");
  s.addText("Nodo «proxmox» · Cortafuegos Debian + nftables (NAT y filtrado por zonas) · 4 redes",
    { x: 0.9, y: 1.4, w: 11.5, h: 0.5, fontFace: BF, fontSize: 14, color: MUT });
  // firewall central
  card(s, 5.4, 2.2, 2.5, 0.9, NAVY);
  s.addText("jamsec-fw", { x: 5.4, y: 2.32, w: 2.5, h: 0.35, align: "center", fontFace: HF, fontSize: 15, bold: true, color: WHITE, margin: 0 });
  s.addText("nftables + Suricata", { x: 5.4, y: 2.68, w: 2.5, h: 0.32, align: "center", fontFace: BF, fontSize: 11, color: "CADCFC", margin: 0 });
  const zonas = [
    ["SOC — 10.20.10.0/24", "Red team (Kali) + Wazuh (SIEM)", 0.9],
    ["DMZ — 10.20.20.0/24", "Web corporativa (nginx)", 5.0],
    ["Servicios — 10.20.30.0/24", "Ficheros internos (Samba)", 9.1],
  ];
  zonas.forEach(z => {
    card(s, z[2], 4.1, 3.3, 1.5);
    s.addText(z[0], { x: z[2] + 0.2, y: 4.3, w: 2.95, h: 0.5, fontFace: HF, fontSize: 13.5, bold: true, color: NAVY, margin: 0 });
    s.addText(z[1], { x: z[2] + 0.2, y: 4.85, w: 2.95, h: 0.6, fontFace: BF, fontSize: 12, color: MUT, margin: 0 });
  });
  // conectores
  [2.55, 6.65, 10.75].forEach(cx => s.addShape(p.shapes.LINE, { x: cx, y: 3.1, w: 0, h: 1.0, line: { color: GREEN, width: 1.5 } }));
  s.addText("Política: la DMZ y los servicios internos están aislados entre sí y del SOC; salida a Internet controlada y publicación de la web por DNAT. Verificado: la DMZ NO alcanza la red interna.",
    { x: 0.9, y: 5.95, w: 11.5, h: 0.9, fontFace: BF, fontSize: 13, italic: true, color: INK });
})();

// ---- Slide 7: sección 3 ----
sectionSlide(3, "Infraestructura del cliente (Apex)", "El objetivo de la auditoría — con debilidades reales");

// ---- Slide 8: arquitectura Apex ----
(() => {
  const s = contentSlide("Arquitectura del cliente Apex Gestoría");
  s.addText("Nodo «pve2», separado físicamente de JAMSEC · cortafuegos propio · DMZ + LAN interna",
    { x: 0.9, y: 1.4, w: 11.5, h: 0.5, fontFace: BF, fontSize: 14, color: MUT });
  const items = [
    ["apex-fw", "Cortafuegos perimetral", "Segmentación débil: la DMZ accede a la LAN"],
    ["apex-web (DMZ)", "Web Apache + PHP", "SQL injection · subida de ficheros · sin TLS"],
    ["apex-db (LAN)", "Base de datos MariaDB", "Expuesta · root remoto con contraseña 'toor'"],
    ["apex-srv (LAN)", "Servidor FTP (vsftpd)", "Acceso anónimo · credenciales SSH débiles"],
  ];
  let x = 0.9, y = 2.2;
  items.forEach((it, i) => {
    const cx = x + (i % 2) * 5.95;
    const cy = y + Math.floor(i / 2) * 1.95;
    card(s, cx, cy, 5.7, 1.7);
    s.addShape(p.shapes.RECTANGLE, { x: cx, y: cy, w: 0.12, h: 1.7, fill: { color: CRIT } });
    s.addText(it[0], { x: cx + 0.3, y: cy + 0.18, w: 5.2, h: 0.4, fontFace: HF, fontSize: 16, bold: true, color: INK, margin: 0 });
    s.addText(it[1], { x: cx + 0.3, y: cy + 0.62, w: 5.2, h: 0.35, fontFace: BF, fontSize: 13, color: NAVY, margin: 0 });
    s.addText(it[2], { x: cx + 0.3, y: cy + 1.0, w: 5.2, h: 0.55, fontFace: BF, fontSize: 12, italic: true, color: CRIT, margin: 0 });
  });
  s.addText("Las vulnerabilidades son intencionadas y representan malas prácticas reales en pymes.",
    { x: 0.9, y: 6.35, w: 11.5, h: 0.5, fontFace: BF, fontSize: 12.5, italic: true, color: MUT });
})();

// ---- Slide 9: IDS/IPS/SIEM ----
(() => {
  const s = contentSlide("Monitorización: IDS / IPS / SIEM");
  s.addText("Wazuh (SIEM + HIDS) en el SOC y Suricata (IDS de red) en los dos cortafuegos vigilan ambas infraestructuras de forma centralizada.",
    { x: 0.9, y: 1.45, w: 11.5, h: 0.7, fontFace: BF, fontSize: 15, color: INK });
  const stats = [["7", "agentes\nWazuh"], ["2", "infraestructuras\nmonitorizadas"], ["66k", "reglas IDS\n(ET Open)"], ["100%", "del ataque\ndetectado"]];
  let x = 0.9;
  stats.forEach(st => {
    card(s, x, 2.4, 2.78, 1.8);
    s.addText(st[0], { x: x, y: 2.6, w: 2.78, h: 0.8, align: "center", fontFace: HF, fontSize: 40, bold: true, color: GREEN, margin: 0 });
    s.addText(st[1], { x: x, y: 3.45, w: 2.78, h: 0.6, align: "center", fontFace: BF, fontSize: 12.5, color: MUT, margin: 0 });
    x += 2.95;
  });
  s.addText([
    { text: "HIDS / logs: ", options: { bold: true, color: INK } },
    { text: "agentes en las 7 VMs de ambas infraestructuras (integridad, logs, detección).", options: { color: INK }, breakLine: true },
    { text: "NIDS: ", options: { bold: true, color: INK } },
    { text: "Suricata en los firewalls; sus alertas se integran y correlacionan en Wazuh.", options: { color: INK }, breakLine: true },
    { text: "Aislamiento: ", options: { bold: true, color: INK } },
    { text: "los agentes reportan por una red dedicada, sin romper la segmentación.", options: { color: INK } },
  ], { x: 0.95, y: 4.5, w: 11.5, h: 1.8, fontFace: BF, fontSize: 14, paraSpaceAfter: 8 });
})();

// ---- Slide 10: sección 4 ----
sectionSlide(4, "Desarrollo y tecnologías", "Cómo se construyó y por qué");

// ---- Slide 11: desarrollo (proceso) ----
(() => {
  const s = contentSlide("Desarrollo del proyecto");
  const fases = [
    ["1", "Adopción del clúster", "Inventario y renombrado a JAMSEC sin reiniciar nodos"],
    ["2", "Infraestructuras", "Redes, firewalls y servicios con cloud-init (IaC)"],
    ["3", "Monitorización", "Despliegue de Wazuh + Suricata sobre ambas"],
    ["4", "Auditoría", "Pentest PTES/OWASP desde la estación red team"],
    ["5", "Documentación", "Informes, memoria, repositorio y demo"],
  ];
  let y = 1.55;
  fases.forEach(f => {
    s.addShape(p.shapes.OVAL, { x: 0.9, y: y, w: 0.7, h: 0.7, fill: { color: NAVY } });
    s.addText(f[0], { x: 0.9, y: y, w: 0.7, h: 0.7, align: "center", valign: "middle", fontFace: HF, fontSize: 20, bold: true, color: WHITE, margin: 0 });
    s.addText(f[1], { x: 1.8, y: y + 0.02, w: 4.2, h: 0.4, fontFace: HF, fontSize: 16, bold: true, color: INK, margin: 0 });
    s.addText(f[2], { x: 1.8, y: y + 0.4, w: 10.5, h: 0.4, fontFace: BF, fontSize: 13, color: MUT, margin: 0 });
    y += 1.02;
  });
  s.addText("Todo reproducible: configuración como código (cloud-init + scripts) versionada en Git.",
    { x: 0.9, y: 6.75, w: 11.5, h: 0.4, fontFace: BF, fontSize: 12.5, italic: true, color: GREEN, margin: 0 });
})();

// ---- Slide 12: justificación tecnologías ----
(() => {
  const s = contentSlide("Justificación de las tecnologías");
  const rows = [
    [{ text: "Tecnología", options: { bold: true, color: WHITE, fill: { color: NAVY }, fontFace: HF } },
     { text: "Por qué se eligió", options: { bold: true, color: WHITE, fill: { color: NAVY }, fontFace: HF } }],
    ["Proxmox VE", "Virtualización empresarial real (clúster), gratuito y muy extendido."],
    ["Debian 13 + cloud-init", "Base estable; despliegue automatizado y reproducible (IaC)."],
    ["nftables", "Cortafuegos nativo de Linux, flexible y sin coste; demuestra control de bajo nivel."],
    ["Wazuh", "SIEM open source completo (HIDS, logs, alertas, panel)."],
    ["Suricata", "IDS/IPS de red estándar, con reglas de la comunidad (ET Open)."],
    ["nmap · sqlmap · hydra", "Herramientas estándar de auditoría para cada ámbito (red, web, credenciales)."],
  ];
  s.addTable(rows, { x: 0.9, y: 1.6, w: 11.5, colW: [3.3, 8.2], rowH: 0.62,
    fontFace: BF, fontSize: 13.5, color: INK, valign: "middle",
    border: { type: "solid", pt: 1, color: LINE }, fill: { color: WHITE }, align: "left",
    margin: [4, 8, 4, 8] });
})();

// ---- Slide 13: resultados auditoría ----
(() => {
  const s = contentSlide("Resultado de la auditoría");
  const sev = [["3", "Críticos", CRIT], ["4", "Altos", ALTA], ["2", "Medios", MEDIA]];
  let x = 0.9;
  sev.forEach(v => {
    card(s, x, 1.6, 2.4, 1.5);
    s.addText(v[0], { x: x, y: 1.72, w: 2.4, h: 0.75, align: "center", fontFace: HF, fontSize: 38, bold: true, color: v[2], margin: 0 });
    s.addText(v[1], { x: x, y: 2.5, w: 2.4, h: 0.4, align: "center", fontFace: BF, fontSize: 14, color: MUT, margin: 0 });
    x += 2.6;
  });
  s.addText("Cadena de ataque", { x: 9.0, y: 1.6, w: 4, h: 0.4, fontFace: HF, fontSize: 16, bold: true, color: GREEN });
  s.addText([
    { text: "FTP anónimo → credenciales internas", options: { bullet: true, breakLine: true } },
    { text: "SQLi en la web → volcado de la BBDD", options: { bullet: true, breakLine: true } },
    { text: "Fuerza bruta SSH → acceso a la DMZ", options: { bullet: true, breakLine: true } },
    { text: "Pivote DMZ→LAN → datos internos", options: { bullet: true } },
  ], { x: 9.05, y: 2.05, w: 3.9, h: 1.6, fontFace: BF, fontSize: 12, color: INK, paraSpaceAfter: 5 });
  s.addText("Impacto demostrado", { x: 0.9, y: 3.5, w: 7, h: 0.4, fontFace: HF, fontSize: 16, bold: true, color: INK });
  s.addText("Compromiso total del cliente: desde Internet hasta el robo de datos personales y financieros (NIF, IBAN, nóminas) almacenados en la red interna. El SIEM detectó el escaneo, la fuerza bruta y los accesos.",
    { x: 0.9, y: 3.95, w: 11.5, h: 1.2, fontFace: BF, fontSize: 14, color: INK });
  s.addText("Entregables: informe técnico (CVSS 3.1, OWASP) e informe ejecutivo para dirección.",
    { x: 0.9, y: 5.2, w: 11.5, h: 0.5, fontFace: BF, fontSize: 13, italic: true, color: MUT });
})();

// ---- Slide 14: sección 5 ----
sectionSlide(5, "Conclusiones", "Dificultades, mejoras y costes");

// ---- Slide 15: conclusiones ----
(() => {
  const s = contentSlide("Conclusiones");
  const blocks = [
    [CRIT, "Dificultades encontradas", [
      "Red de aula compartida: conflictos de IP (resueltos verificando con ping/ARP).",
      "Instalador de Wazuh incompatible con Debian 13 (parcheado).",
      "Versión de agentes Wazuh > manager (fijada a la del manager).",
      "Falsos positivos de Suricata sobre virtio (ajuste de NIC y captura).",
    ]],
    [GREEN, "Posibles mejoras", [
      "Suricata en modo IPS (bloqueo en línea) en el perímetro.",
      "Alta disponibilidad y copias de seguridad automatizadas.",
      "Gestión de identidades centralizada (LDAP/AD) y MFA.",
      "Alertado proactivo y cuadros de mando a medida en Wazuh.",
    ]],
    [NAVY, "Costes", [
      "Hardware: clúster reutilizado → 0 € adicionales.",
      "Software: 100% libre (Proxmox, Debian, Wazuh, Suricata).",
      "Coste principal: horas de ingeniería / despliegue.",
    ]],
  ];
  let x = 0.9;
  blocks.forEach(b => {
    card(s, x, 1.55, 3.73, 5.2);
    s.addShape(p.shapes.RECTANGLE, { x: x, y: 1.55, w: 3.73, h: 0.12, fill: { color: b[0] } });
    s.addText(b[1], { x: x + 0.25, y: 1.8, w: 3.3, h: 0.6, fontFace: HF, fontSize: 16, bold: true, color: INK, margin: 0 });
    s.addText(b[2].map((t, i) => ({ text: t, options: { bullet: true, breakLine: true } })),
      { x: x + 0.25, y: 2.45, w: 3.3, h: 4.1, fontFace: BF, fontSize: 12.5, color: INK, paraSpaceAfter: 8, valign: "top" });
    x += 3.95;
  });
})();

// ---- Slide 16: cierre ----
(() => {
  const s = p.addSlide(); s.background = { color: DARK };
  s.addShape(p.shapes.OVAL, { x: 10.6, y: 4.6, w: 4.2, h: 4.2, fill: { color: GREEN, transparency: 82 } });
  s.addText("Gracias", { x: 0.9, y: 2.4, w: 11, h: 1.1, fontFace: HF, fontSize: 48, bold: true, color: WHITE });
  s.addText("¿Preguntas?", { x: 0.95, y: 3.6, w: 8, h: 0.6, fontFace: BF, fontSize: 20, color: GREEN });
  s.addText("Repositorio del proyecto: github.com/aleexsanchezz04/jamsec-proyecto-ciberseguridad",
    { x: 0.95, y: 5.6, w: 11.4, h: 0.5, fontFace: BF, fontSize: 14, color: "AEB9C7" });
  s.addText("Alex Sánchez García · JAMSEC · 2026", { x: 0.95, y: 6.2, w: 11, h: 0.4, fontFace: BF, fontSize: 13, color: "8A97A8" });
})();

p.writeFile({ fileName: "JAMSEC_Presentacion.pptx" }).then(f => console.log("OK presentacion generada:", f));
