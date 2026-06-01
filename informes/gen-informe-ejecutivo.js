const fs = require("fs");
const {
  Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell,
  Header, Footer, AlignmentType, LevelFormat, HeadingLevel, BorderStyle,
  WidthType, ShadingType, VerticalAlign, PageNumber
} = require("docx");

const AC = "16407A", GREEN = "19C37D", CW = 9026;
const border = { style: BorderStyle.SINGLE, size: 1, color: "BBBBBB" };
const borders = { top: border, bottom: border, left: border, right: border };
const SEVCOL = { "Crítica": "C0392B", "Alta": "E67E22", "Media": "F1C40F" };

const H1 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun(t)] });
const H2 = (t) => new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun(t)] });
const P = (t, o = {}) => new Paragraph({ spacing: { after: 140 }, alignment: AlignmentType.JUSTIFIED, children: [new TextRun({ text: t, ...o })] });
const BUL = (t) => new Paragraph({ numbering: { reference: "bul", level: 0 }, spacing: { after: 60 }, children: [new TextRun(t)] });
function cell(text, { w, fill, bold, color, align } = {}) {
  return new TableCell({ borders, width: { size: w, type: WidthType.DXA },
    shading: fill ? { fill, type: ShadingType.CLEAR } : undefined,
    margins: { top: 70, bottom: 70, left: 120, right: 120 }, verticalAlign: VerticalAlign.CENTER,
    children: [new Paragraph({ alignment: align || AlignmentType.LEFT, children: [new TextRun({ text: String(text), bold: !!bold, color })] })] });
}

const docOpts = {
  creator: "JAMSEC",
  title: "Informe Ejecutivo de Auditoría de Seguridad - Apex Gestoría",
  styles: {
    default: { document: { run: { font: "Arial", size: 22 } } },
    paragraphStyles: [
      { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 30, bold: true, color: AC, font: "Arial" }, paragraph: { spacing: { before: 280, after: 160 }, outlineLevel: 0 } },
      { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 24, bold: true, color: "1F2A44", font: "Arial" }, paragraph: { spacing: { before: 200, after: 120 }, outlineLevel: 1 } },
    ]
  },
  numbering: { config: [ { reference: "bul", levels: [{ level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT,
    style: { paragraph: { indent: { left: 540, hanging: 260 } } } }] } ] },
};

// Portada
const portada = {
  properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
  children: [
    new Paragraph({ spacing: { before: 2000, after: 0 }, alignment: AlignmentType.CENTER,
      children: [new TextRun({ text: "JAM", bold: true, size: 72, color: "1F2A44" }), new TextRun({ text: "SEC", bold: true, size: 72, color: GREEN })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 1200 }, children: [new TextRun({ text: "Servicios Gestionados de Ciberseguridad", italics: true, size: 24, color: "666666" })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, border: { top: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 }, bottom: { style: BorderStyle.SINGLE, size: 6, color: AC, space: 6 } }, spacing: { before: 200, after: 200 },
      children: [new TextRun({ text: "INFORME EJECUTIVO DE AUDITORÍA DE SEGURIDAD", bold: true, size: 34, color: AC })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, spacing: { before: 200, after: 60 }, children: [new TextRun({ text: "Resumen para la Dirección", size: 24 })] }),
    new Paragraph({ alignment: AlignmentType.CENTER, spacing: { after: 1400 }, children: [new TextRun({ text: "Cliente: Apex Gestoría", bold: true, size: 28, color: "1F2A44" })] }),
    new Table({ width: { size: 6000, type: WidthType.DXA }, columnWidths: [2400, 3600], alignment: AlignmentType.CENTER,
      rows: [["Referencia","JAMSEC-AUD-2026-001-EXE"],["Versión","1.0"],["Fecha","Junio de 2026"],["Dirigido a","Dirección de Apex Gestoría"],["Clasificación","CONFIDENCIAL"]]
      .map(([k,v]) => new TableRow({ children: [cell(k,{w:2400,fill:"1F2A44",bold:true,color:"FFFFFF"}), cell(v,{w:3600,fill:"F4F6F9"})] })) }),
  ]
};

const body = [];

// 1. Propósito
body.push(H1("1. De qué va este informe"));
body.push(P("Apex Gestoría nos pidió que comprobáramos si sus sistemas informáticos son seguros. Para hacerlo, en JAMSEC simulamos un ataque real contra ellos, de forma controlada, y aquí resumimos lo que encontramos. Este documento está pensado para la dirección, así que va sin tecnicismos; el detalle técnico, con las pruebas y la forma de corregir cada problema, está en el informe técnico que entregamos aparte."));

// 2. Veredicto
body.push(H1("2. Conclusión principal"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [CW],
  rows: [ new TableRow({ children: [ cell("NIVEL DE RIESGO GLOBAL: CRÍTICO", { w: CW, fill: "C0392B", bold: true, color: "FFFFFF", align: AlignmentType.CENTER }) ] }) ] }));
body.push(P(""));
body.push(P("Durante la prueba conseguimos hacernos con el control completo de los sistemas y acceder a información confidencial de clientes y empleados: datos personales, cuentas bancarias y nóminas. Y lo hicimos partiendo de cero, desde fuera, sin que nadie nos diera ninguna contraseña. Dicho de otro modo: hoy por hoy, alguien con conocimientos medios podría hacer lo mismo desde internet sin demasiado esfuerzo."));

// 3. Qué significa para el negocio
body.push(H1("3. Qué supone esto para el negocio"));
body.push(P("Más allá de lo técnico, lo que está en juego es el negocio. Estos son los riesgos concretos a los que se expone Apex con la situación actual:"));
[ "Que se roben los datos de los clientes (NIF, cuentas bancarias, contactos) y las nóminas de los empleados.",
  "Sanciones por incumplir la Ley de Protección de Datos (RGPD), que para este tipo de información pueden ser elevadas.",
  "Pérdida de confianza y daño a la reputación: una gestoría vive de que sus clientes le confíen datos sensibles.",
  "Que alguien manipule o falsee información contable y financiera.",
  "Que un ataque tipo «ransomware» cifre o borre los datos y deje el negocio parado." ].forEach(t => body.push(BUL(t)));

// 4. Resumen de hallazgos
body.push(H1("4. Resumen de lo que encontramos"));
body.push(P("En total detectamos 9 problemas de seguridad. Los clasificamos según su gravedad:"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [3008,3009,3009],
  rows: [
    new TableRow({ children: [cell("Críticos",{w:3008,fill:SEVCOL["Crítica"],bold:true,color:"FFFFFF",align:AlignmentType.CENTER}),cell("Altos",{w:3009,fill:SEVCOL["Alta"],bold:true,color:"FFFFFF",align:AlignmentType.CENTER}),cell("Medios",{w:3009,fill:SEVCOL["Media"],bold:true,color:"FFFFFF",align:AlignmentType.CENTER})] }),
    new TableRow({ children: [cell("3",{w:3008,align:AlignmentType.CENTER,bold:true}),cell("4",{w:3009,align:AlignmentType.CENTER,bold:true}),cell("2",{w:3009,align:AlignmentType.CENTER,bold:true})] }),
  ] }));
body.push(P(""));
body.push(P("Sin entrar en tecnicismos, los problemas más graves son estos:", { bold: true }));
[ "La página web de clientes deja saltarse el control de acceso y, a través de ella, leer toda la base de datos. Es la puerta de entrada principal.",
  "La base de datos donde se guarda todo está accesible con una contraseña muy débil y muy conocida.",
  "Un servicio para compartir archivos permite descargar, sin pedir ninguna contraseña, un documento que contiene las claves internas de la empresa.",
  "Las redes internas no están bien separadas, así que entrar por la web da acceso directo a la información más sensible." ].forEach(t => body.push(BUL(t)));
body.push(P("Lo importante es entender que estos fallos no actúan por separado: uno lleva al siguiente. Aprovechando esa cadena fue como llegamos desde la web hasta las nóminas."));

// 5. Recomendaciones
body.push(H1("5. Qué recomendamos hacer"));
body.push(P("La buena noticia es que casi todo se arregla con configuración y buenas prácticas, no hace falta una gran inversión. Proponemos actuar por fases, empezando por lo más urgente:"));
body.push(new Table({ width: { size: CW, type: WidthType.DXA }, columnWidths: [2200, 6826],
  rows: [
    new TableRow({ children: [cell("Plazo",{w:2200,fill:AC,bold:true,color:"FFFFFF"}),cell("Acción recomendada",{w:6826,fill:AC,bold:true,color:"FFFFFF"})] }),
    ...[["Inmediato (0-7 días)","Corregir la página web vulnerable y cambiar todas las contraseñas, sobre todo la de la base de datos."],
        ["Corto plazo (2-4 sem.)","Cerrar los servicios que están abiertos a internet sin necesidad, reforzar el acceso remoto y separar bien las redes internas."],
        ["Medio plazo (1-3 meses)","Cifrar todas las comunicaciones, establecer una política de contraseñas y dar una formación básica de seguridad al personal."],
        ["Continuo","Mantener la vigilancia de seguridad (que ya está funcionando) y repetir la auditoría de vez en cuando."]].map(r =>
      new TableRow({ children:[cell(r[0],{w:2200,bold:true}),cell(r[1],{w:6826})] })) ] }));

// 6. Aspecto positivo
body.push(H1("6. Lo que sí funciona"));
body.push(P("No todo son malas noticias. El sistema de vigilancia de seguridad que tiene desplegado JAMSEC detectó nuestro ataque mientras lo estábamos haciendo: vio el rastreo de la red, los intentos de adivinar contraseñas y los accesos. Esto es importante porque quiere decir que, una vez se corrijan los fallos, Apex no se queda «a ciegas»: dispone de la capacidad de darse cuenta de un ataque y reaccionar a tiempo."));

// 7. Conclusión
body.push(H1("7. Conclusión"));
body.push(P("La situación de partida es de riesgo crítico, pero tiene arreglo y no es caro de solucionar. Si se aplican las medidas que proponemos, empezando por las urgentes, el nivel de exposición baja muchísimo en poco tiempo. Desde JAMSEC quedamos a disposición de la dirección para ayudar en la corrección y, cuando esté hecha, volver a comprobar que todo ha quedado bien cerrado."));

const cuerpo = {
  properties: { page: { size: { width: 11906, height: 16838 }, margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 } } },
  headers: { default: new Header({ children: [ new Paragraph({ alignment: AlignmentType.RIGHT,
    border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: AC, space: 4 } },
    children: [ new TextRun({ text: "JAMSEC · Informe Ejecutivo — Apex Gestoría", size: 16, color: "888888" }) ] }) ] }) },
  footers: { default: new Footer({ children: [ new Paragraph({ alignment: AlignmentType.CENTER,
    children: [ new TextRun({ text: "CONFIDENCIAL — Página ", size: 16, color: "888888" }), new TextRun({ children: [PageNumber.CURRENT], size: 16, color: "888888" }) ] }) ] }) },
  children: body
};

const doc = new Document({ ...docOpts, sections: [portada, cuerpo] });
Packer.toBuffer(doc).then(b => { fs.writeFileSync("JAMSEC_Informe_Ejecutivo_Auditoria_Apex.docx", b); console.log("OK informe ejecutivo generado"); });
