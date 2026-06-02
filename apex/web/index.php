<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Apex Gestoria · Assessoria fiscal, laboral i comptable</title>
<style>
  :root{--blue:#16407a;--blue2:#1d5aa8;--teal:#0aa6a6;--ink:#1f2a44;--mut:#5b6b85;--bg:#f5f8fc;--line:#e3eaf3;--white:#fff}
  *{margin:0;padding:0;box-sizing:border-box}
  html{scroll-behavior:smooth}
  body{font-family:'Segoe UI',system-ui,Roboto,Arial,sans-serif;color:var(--ink);background:var(--white);line-height:1.65}
  a{color:inherit;text-decoration:none}
  .wrap{max-width:1120px;margin:0 auto;padding:0 24px}
  header{position:sticky;top:0;z-index:40;background:rgba(255,255,255,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
  nav{display:flex;align-items:center;justify-content:space-between;height:72px}
  .brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:1.35rem;color:var(--blue)}
  .brand .mark{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--blue),var(--teal));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800}
  .menu{display:flex;gap:28px;color:var(--mut);font-size:.95rem}
  .menu a:hover{color:var(--blue)}
  .btn{background:var(--blue);color:#fff;font-weight:700;padding:11px 20px;border-radius:8px;border:0;cursor:pointer;transition:.2s;display:inline-block}
  .btn:hover{background:var(--blue2)}
  .btn-out{background:#fff;color:var(--blue);border:1px solid var(--blue)}
  .hero{background:linear-gradient(160deg,#eaf2fb,#f5f8fc 60%);padding:80px 0}
  .hero-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:50px;align-items:center}
  .pill{display:inline-block;background:#e3effb;color:var(--blue);font-weight:600;font-size:.82rem;padding:6px 14px;border-radius:999px;margin-bottom:20px}
  .hero h1{font-size:3rem;line-height:1.12;font-weight:800;color:var(--ink)}
  .hero h1 em{color:var(--blue);font-style:normal}
  .hero p{color:var(--mut);font-size:1.15rem;margin:20px 0 30px;max-width:520px}
  .cta{display:flex;gap:14px;flex-wrap:wrap}
  .herocard{background:#fff;border:1px solid var(--line);border-radius:18px;padding:26px;box-shadow:0 20px 40px rgba(22,64,122,.10)}
  .herocard h4{color:var(--blue);margin-bottom:14px}
  .hc-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--line)}
  .hc-row:last-child{border:0}
  .hc-ic{width:38px;height:38px;border-radius:10px;background:#eaf2fb;display:flex;align-items:center;justify-content:center;flex:none}
  .hc-row small{color:var(--mut);display:block}
  .trust{background:var(--blue);color:#fff;padding:26px 0}
  .trust-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;text-align:center}
  .trust b{display:block;font-size:1.9rem}
  .trust span{opacity:.8;font-size:.9rem}
  section{padding:80px 0}
  .kicker{color:var(--teal);font-weight:700;letter-spacing:2px;text-transform:uppercase;font-size:.78rem}
  h2{font-size:2.1rem;font-weight:800;margin:8px 0 12px}
  .lead{color:var(--mut);max-width:620px;margin-bottom:42px}
  .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
  .srv{background:#fff;border:1px solid var(--line);border-radius:14px;padding:26px;transition:.2s}
  .srv:hover{transform:translateY(-4px);box-shadow:0 14px 30px rgba(22,64,122,.10);border-color:#c9dcf2}
  .srv .ic{width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#eaf2fb,#e0f5f5);display:flex;align-items:center;justify-content:center;margin-bottom:16px}
  .srv h3{font-size:1.15rem;margin-bottom:8px}
  .srv p{color:var(--mut);font-size:.92rem}
  .about{display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:center}
  .about ul{list-style:none;margin-top:18px}
  .about li{display:flex;gap:12px;margin-bottom:14px;color:var(--mut)}
  .about li svg{flex:none;margin-top:3px}
  .ph{background:linear-gradient(135deg,var(--blue),var(--teal));border-radius:18px;min-height:300px;display:flex;align-items:center;justify-content:center;color:#fff;text-align:center;padding:30px}
  .contact{background:var(--bg);border-radius:20px;padding:48px;display:grid;grid-template-columns:1fr 1fr;gap:40px}
  .field{margin-bottom:16px}
  .field label{display:block;font-size:.85rem;color:var(--mut);margin-bottom:6px}
  .field input,.field textarea{width:100%;background:#fff;border:1px solid var(--line);border-radius:10px;padding:12px;font-family:inherit}
  footer{background:var(--ink);color:#c7d2e2;padding:40px 0;font-size:.9rem}
  .fgrid{display:flex;justify-content:space-between;flex-wrap:wrap;gap:20px;align-items:center}
  @media(max-width:880px){.menu{display:none}.hero-grid,.about,.contact{grid-template-columns:1fr}.grid{grid-template-columns:1fr 1fr}.trust-grid{grid-template-columns:1fr 1fr}.hero h1{font-size:2.2rem}}
</style>
</head>
<body>

<header><div class="wrap"><nav>
  <div class="brand"><div class="mark">A</div> Apex Gestoria</div>
  <div class="menu">
    <a href="#serveis">Serveis</a>
    <a href="#nosaltres">Sobre nosaltres</a>
    <a href="#contacte">Contacte</a>
  </div>
  <a href="login.php" class="btn">Àrea de clients</a>
</nav></div></header>

<div class="hero"><div class="wrap"><div class="hero-grid">
  <div>
    <span class="pill">+25 anys al teu costat</span>
    <h1>La teva <em>gestoria</em> de confiança a Catalunya</h1>
    <p>Ens ocupem de la teva fiscalitat, les nòmines i la comptabilitat perquè tu et dediquis al teu negoci. Tracte proper i tecnologia per estar sempre al dia.</p>
    <div class="cta">
      <a href="#contacte" class="btn">Demana pressupost</a>
      <a href="login.php" class="btn btn-out">Accedir a la meva àrea</a>
    </div>
  </div>
  <div class="herocard">
    <h4>La teva gestió, en ordre</h4>
    <div class="hc-row"><div class="hc-ic">🧾</div><div><b>Impostos al dia</b><small>Presentacions trimestrals i anuals</small></div></div>
    <div class="hc-row"><div class="hc-ic">👥</div><div><b>Nòmines i Seguretat Social</b><small>Altes, baixes i contractes</small></div></div>
    <div class="hc-row"><div class="hc-ic">📊</div><div><b>Comptabilitat clara</b><small>Informes mensuals per al teu negoci</small></div></div>
    <div class="hc-row"><div class="hc-ic">🔒</div><div><b>Documents segurs</b><small>Àrea privada de clients</small></div></div>
  </div>
</div></div></div>

<div class="trust"><div class="wrap"><div class="trust-grid">
  <div><b>+850</b><span>clients actius</span></div>
  <div><b>25 anys</b><span>d'experiència</span></div>
  <div><b>4 àrees</b><span>de servei</span></div>
  <div><b>98%</b><span>de retenció de clients</span></div>
</div></div></div>

<section id="serveis"><div class="wrap">
  <div class="kicker">Serveis</div>
  <h2>Tot el que la teva empresa necessita</h2>
  <p class="lead">Un únic equip per a tota la teva gestió administrativa, fiscal i laboral.</p>
  <div class="grid">
    <div class="srv"><div class="ic">💼</div><h3>Fiscal</h3><p>IVA, IRPF, impost de societats i planificació fiscal per a autònoms i empreses.</p></div>
    <div class="srv"><div class="ic">👥</div><h3>Laboral</h3><p>Nòmines, contractes, altes i baixes a la Seguretat Social i assessorament laboral.</p></div>
    <div class="srv"><div class="ic">📒</div><h3>Comptable</h3><p>Comptabilitat oficial, llibres, comptes anuals i tancaments d'exercici.</p></div>
    <div class="srv"><div class="ic">⚖️</div><h3>Mercantil</h3><p>Constitució de societats, escriptures, registres i tràmits mercantils.</p></div>
  </div>
</div></section>

<section id="nosaltres" style="background:var(--bg)"><div class="wrap"><div class="about">
  <div>
    <div class="kicker">Sobre nosaltres</div>
    <h2>Una gestoria de tota la vida, amb mentalitat actual</h2>
    <p class="lead">Des de 2001 acompanyem autònoms, pimes i famílies en la seva gestió administrativa. Creiem en el tracte personal: aquí tindràs sempre algú amb qui parlar.</p>
    <ul>
      <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0aa6a6" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg> Assessor personal assignat a cada client.</li>
      <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0aa6a6" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg> Avisos abans de cada venciment d'impostos.</li>
      <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0aa6a6" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg> Àrea privada per consultar i pujar documents.</li>
    </ul>
  </div>
  <div class="ph"><div><div style="font-size:3rem">🏢</div><p style="margin-top:10px">Apex Gestoria<br><span style="opacity:.85">Carrer Gran de Gràcia 120, Barcelona</span></p></div></div>
</div></div></section>

<section id="contacte"><div class="wrap">
  <div class="contact">
    <div>
      <div class="kicker">Contacte</div>
      <h2>Parlem del teu cas</h2>
      <p class="lead">Demana'ns informació sense compromís. Et responem el mateix dia.</p>
      <p style="margin-bottom:8px">📧 info@apex-gestoria.cat</p>
      <p style="margin-bottom:8px">📞 93 200 00 00</p>
      <p>📍 Carrer Gran de Gràcia 120, Barcelona</p>
    </div>
    <form onsubmit="return false">
      <div class="field"><label>Nom i cognoms</label><input placeholder="El teu nom"></div>
      <div class="field"><label>Email</label><input placeholder="correu@empresa.cat"></div>
      <div class="field"><label>Missatge</label><textarea rows="4" placeholder="En què et podem ajudar?"></textarea></div>
      <button class="btn" type="submit">Enviar consulta</button>
    </form>
  </div>
</div></section>

<footer><div class="wrap"><div class="fgrid">
  <div class="brand" style="color:#fff"><div class="mark">A</div> Apex Gestoria</div>
  <div>© 2026 Apex Gestoria · Assessoria fiscal, laboral i comptable</div>
  <div>Avís legal · Privacitat</div>
</div></div></footer>

</body>
</html>
