<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Apex Gestoria · Assessoria fiscal, laboral i comptable</title>
<style>
  :root{--navy:#0b3b6f;--navy2:#0e4a89;--gold:#c8941f;--gold2:#e0ad3a;--ink:#1c2533;--mut:#5d6b7e;
        --cream:#fbf7ef;--paper:#ffffff;--soft:#f3f1ea;--line:#e9e2d4;--serif:Georgia,'Times New Roman',serif}
  *{margin:0;padding:0;box-sizing:border-box}
  html{scroll-behavior:smooth}
  body{font-family:'Segoe UI',system-ui,Arial,sans-serif;color:var(--ink);background:var(--paper);line-height:1.7;overflow-x:hidden}
  a{color:inherit;text-decoration:none}
  .wrap{max-width:1140px;margin:0 auto;padding:0 26px}
  h1,h2,h3,.serif{font-family:var(--serif)}
  header{position:fixed;top:0;left:0;right:0;z-index:50;transition:.3s;padding:14px 0}
  header.sc{background:rgba(255,255,255,.97);backdrop-filter:blur(10px);box-shadow:0 6px 28px rgba(11,59,111,.07)}
  nav{display:flex;align-items:center;justify-content:space-between}
  .brand{display:flex;align-items:center;gap:12px;font-family:var(--serif);font-weight:700;font-size:1.4rem;color:var(--navy)}
  .brand .mk{width:40px;height:40px;border-radius:10px;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-family:var(--serif);position:relative}
  .brand .mk::after{content:"";position:absolute;right:-3px;bottom:-3px;width:14px;height:14px;background:var(--gold);border-radius:4px}
  .menu{display:flex;gap:30px;color:var(--mut);font-size:.96rem}
  .menu a{position:relative;padding:4px 0}
  .menu a::after{content:"";position:absolute;left:0;bottom:-3px;width:0;height:2px;background:var(--gold);transition:.25s}
  .menu a:hover{color:var(--navy)}.menu a:hover::after{width:100%}
  .btn{background:var(--navy);color:#fff;font-weight:600;padding:12px 22px;border-radius:30px;border:0;cursor:pointer;transition:.25s;display:inline-block;font-family:inherit}
  .btn:hover{background:var(--navy2);transform:translateY(-2px);box-shadow:0 12px 26px rgba(11,59,111,.22)}
  .btn-gold{background:var(--gold);color:#3a2b06}
  .btn-gold:hover{background:var(--gold2);box-shadow:0 12px 26px rgba(200,148,31,.28)}
  .btn-out{background:transparent;border:1.5px solid var(--navy);color:var(--navy)}
  .btn-out:hover{background:var(--navy);color:#fff}
  .hero{padding:150px 0 90px;background:radial-gradient(80% 60% at 95% 5%,#fdf6e6,transparent 55%),radial-gradient(60% 40% at 0% 100%,#eef4fc,transparent 60%),linear-gradient(180deg,#faf7f0,#fff)}
  .hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:54px;align-items:center}
  .eyebrow{display:inline-flex;align-items:center;gap:8px;color:var(--gold);font-weight:700;letter-spacing:1px;text-transform:uppercase;font-size:.78rem;margin-bottom:18px}
  .eyebrow::before{content:"";width:26px;height:2px;background:var(--gold)}
  .hero h1{font-size:3.5rem;line-height:1.12;color:var(--navy);font-weight:700}
  .hero h1 em{color:var(--gold);font-style:italic}
  .hero p{color:var(--mut);font-size:1.15rem;margin:22px 0 32px;max-width:500px}
  .cta{display:flex;gap:14px;flex-wrap:wrap;align-items:center}
  .hero-art{position:relative}
  .card-vis{background:linear-gradient(145deg,var(--navy),#0a2f57);border-radius:24px;min-height:380px;display:flex;align-items:center;justify-content:center;box-shadow:0 32px 64px rgba(11,59,111,.28);overflow:hidden;position:relative}
  .card-vis::before{content:"";position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(200,148,31,.22),transparent 70%);top:-80px;right:-80px}
  .dash{padding:28px;width:100%;position:relative;z-index:1}
  .dash-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
  .dash-title{color:#fff;font-family:var(--serif);font-size:1.1rem}
  .dash-sub{color:rgba(255,255,255,.5);font-size:.78rem}
  .dash-badge{background:rgba(200,148,31,.25);color:var(--gold2);font-size:.72rem;padding:4px 10px;border-radius:20px;font-weight:600}
  .bars{display:flex;gap:10px;align-items:flex-end;height:100px;margin-bottom:22px}
  .bar{flex:1;border-radius:6px 6px 0 0;background:rgba(255,255,255,.12);position:relative;overflow:hidden;animation:grow .8s ease both}
  .bar::after{content:"";position:absolute;bottom:0;left:0;right:0;background:linear-gradient(180deg,rgba(200,148,31,.7),rgba(200,148,31,.3));border-radius:6px 6px 0 0}
  .b1{height:65%;animation-delay:.1s}.b2{height:80%;animation-delay:.2s}.b3{height:55%;animation-delay:.3s}.b4{height:90%;animation-delay:.4s}.b5{height:75%;animation-delay:.5s}.b6{height:95%;animation-delay:.6s}.b7{height:70%;animation-delay:.7s}
  .b1::after{height:65%}.b2::after{height:80%}.b3::after{height:55%}.b4::after{height:90%}.b5::after{height:75%}.b6::after{height:95%}.b7::after{height:70%}
  @keyframes grow{from{transform:scaleY(0);transform-origin:bottom}to{transform:scaleY(1)}}
  .kpis{display:grid;grid-template-columns:1fr 1fr;gap:10px}
  .kpi-sm{background:rgba(255,255,255,.08);border-radius:10px;padding:12px 14px}
  .kpi-sm span{color:rgba(255,255,255,.55);font-size:.74rem;display:block;margin-bottom:4px}
  .kpi-sm b{color:#fff;font-size:1.1rem;font-family:var(--serif)}
  .kpi-sm .up{color:#4ade80;font-size:.7rem;margin-left:5px}
  .float{position:absolute;background:#fff;border-radius:16px;padding:14px 18px;box-shadow:0 18px 44px rgba(11,59,111,.18);display:flex;align-items:center;gap:12px;animation:bob 4s ease-in-out infinite}
  .float small{color:var(--mut);display:block;font-size:.76rem}
  .float b{font-size:1.02rem}
  .f1{top:20px;left:-30px;animation-delay:-1s}
  .f2{bottom:26px;right:-24px}
  .fic{width:38px;height:38px;border-radius:10px;background:#fdf2da;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
  @keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
  .band-soft{background:var(--navy);color:#fff;padding:32px 0}
  .tg{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;text-align:center}
  .tg b{font-family:var(--serif);font-size:2.2rem;display:block;color:var(--gold2)}
  .tg span{opacity:.8;font-size:.9rem}
  section{padding:90px 0}
  .head{max-width:640px;margin-bottom:50px}
  .head h2{font-size:2.4rem;color:var(--navy);margin:8px 0 12px}
  .head p{color:var(--mut);font-size:1.02rem}
  .reveal{opacity:0;transform:translateY(30px);transition:.8s cubic-bezier(.2,.7,.2,1)}
  .reveal.in{opacity:1;transform:none}
  .srv-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px}
  .srv{background:var(--paper);border:1px solid var(--line);border-radius:20px;padding:30px 24px;transition:.3s;position:relative;overflow:hidden}
  .srv::before{content:"";position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(90deg,var(--gold),var(--gold2));transform:scaleX(0);transform-origin:left;transition:.3s}
  .srv:hover{transform:translateY(-8px);box-shadow:0 24px 50px rgba(11,59,111,.12)}
  .srv:hover::before{transform:scaleX(1)}
  .srv .ic{width:58px;height:58px;border-radius:16px;background:linear-gradient(135deg,#eaf1fa,#fdf2da);display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin-bottom:18px}
  .srv h3{font-size:1.22rem;color:var(--navy);margin-bottom:8px}
  .srv p{color:var(--mut);font-size:.93rem}
  .about{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center}
  .about .quote{font-family:var(--serif);font-size:1.5rem;color:var(--navy);font-style:italic;line-height:1.55;border-left:3px solid var(--gold);padding-left:22px;margin-bottom:24px}
  .about ul{list-style:none}
  .about li{display:flex;gap:14px;margin-bottom:16px;color:var(--mut);align-items:flex-start}
  .about li .ck{width:26px;height:26px;border-radius:50%;background:#eaf1fa;color:var(--navy);display:flex;align-items:center;justify-content:center;flex:none;font-weight:700;font-size:.9rem;margin-top:2px}
  .team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:26px}
  .tm{background:var(--paper);border:1px solid var(--line);border-radius:20px;padding:28px 24px;text-align:center;transition:.3s}
  .tm:hover{transform:translateY(-6px);box-shadow:0 20px 44px rgba(11,59,111,.11)}
  .tm-av{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--gold));color:#fff;display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:1.8rem;font-weight:700;margin:0 auto 16px}
  .tm h4{font-size:1.1rem;color:var(--navy);font-family:var(--serif);margin-bottom:5px}
  .tm .rol{color:var(--gold);font-size:.82rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;margin-bottom:10px}
  .tm p{color:var(--mut);font-size:.88rem;line-height:1.6}
  .proc{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;counter-reset:s;position:relative}
  .proc::before{content:"";position:absolute;top:22px;left:12%;right:12%;height:1px;background:linear-gradient(90deg,transparent,var(--line),transparent)}
  .step{position:relative;padding-top:0;text-align:center}
  .step .n{font-family:var(--serif);font-size:2.4rem;color:var(--gold);font-weight:700;display:block;background:#fff;width:48px;height:48px;border-radius:50%;line-height:48px;font-size:1.2rem;text-align:center;margin:0 auto 14px;border:2px solid var(--gold);color:var(--gold)}
  .step h4{color:var(--navy);font-size:1.05rem;margin-bottom:6px;font-family:var(--serif)}
  .step p{color:var(--mut);font-size:.88rem}
  .test{background:var(--cream)}
  .slider{position:relative;max-width:760px;margin:0 auto;text-align:center;min-height:190px}
  .slide{position:absolute;inset:0;opacity:0;transition:.6s;display:flex;flex-direction:column;align-items:center;justify-content:center}
  .slide.on{opacity:1}
  .stars{color:var(--gold);font-size:1.3rem;margin-bottom:14px;letter-spacing:2px}
  .slide p{font-family:var(--serif);font-size:1.35rem;font-style:italic;color:var(--ink);line-height:1.6;margin-bottom:18px}
  .slide .who{color:var(--navy);font-weight:700;font-size:.98rem}
  .slide .who small{display:block;color:var(--mut);font-weight:400;font-size:.88rem;margin-top:3px}
  .who-av{width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy2));color:#fff;display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:1.2rem;font-weight:700;margin:0 auto 12px}
  .dots{display:flex;gap:8px;justify-content:center;margin-top:22px}
  .dots i{width:9px;height:9px;border-radius:50%;background:var(--line);cursor:pointer;transition:.2s}
  .dots i.on{background:var(--gold);width:26px;border-radius:5px}
  .faq{max-width:760px;margin:0 auto}
  .fq{border:1px solid var(--line);border-radius:16px;margin-bottom:12px;overflow:hidden;transition:.2s}
  .fq:hover{border-color:rgba(11,59,111,.2)}
  .fq-h{display:flex;justify-content:space-between;align-items:center;padding:20px 24px;cursor:pointer;font-weight:600;color:var(--navy);font-size:1rem;user-select:none}
  .fq-h .arr{width:28px;height:28px;border-radius:50%;background:#eaf1fa;display:flex;align-items:center;justify-content:center;flex:none;transition:.3s;color:var(--navy)}
  .fq.open .fq-h .arr{transform:rotate(180deg);background:var(--navy);color:#fff}
  .fq-b{max-height:0;overflow:hidden;transition:.35s ease;color:var(--mut);font-size:.95rem;line-height:1.7}
  .fq.open .fq-b{max-height:200px}
  .fq-b p{padding:0 24px 20px}
  .cband{background:linear-gradient(125deg,var(--navy),#0a2f57);border-radius:26px;padding:58px;text-align:center;color:#fff;position:relative;overflow:hidden}
  .cband::after{content:"";position:absolute;width:340px;height:340px;background:radial-gradient(circle,rgba(200,148,31,.32),transparent 70%);top:-120px;right:-60px}
  .cband h2{color:#fff;font-size:2.3rem;margin-bottom:12px}
  .cband p{opacity:.88;max-width:520px;margin:0 auto 28px;font-size:1.02rem}
  .contact{display:grid;grid-template-columns:1fr 1fr;gap:46px;background:var(--soft);border-radius:24px;padding:48px}
  .field{margin-bottom:16px}
  .field label{display:block;font-size:.84rem;color:var(--mut);margin-bottom:6px;font-weight:500}
  .field input,.field textarea{width:100%;background:#fff;border:1px solid var(--line);border-radius:12px;padding:13px;font-family:inherit;transition:.2s;font-size:.96rem}
  .field input:focus,.field textarea:focus{outline:0;border-color:var(--navy);box-shadow:0 0 0 3px rgba(11,59,111,.07)}
  footer{background:var(--ink);color:#c2cad6;padding:56px 0 28px}
  .fcols{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:30px;margin-bottom:32px}
  .fcols h5{color:#fff;font-family:var(--serif);margin-bottom:14px;font-size:1.05rem}
  .fcols a{display:block;color:#9aa6b5;margin-bottom:9px;font-size:.92rem;transition:.2s}
  .fcols a:hover{color:var(--gold2)}
  .fbar{border-top:1px solid #2a3342;padding-top:22px;text-align:center;font-size:.85rem;color:#8390a1}
  @media(max-width:960px){.menu{display:none}.hero-grid,.about,.contact{grid-template-columns:1fr}.srv-grid,.proc,.tg,.fcols{grid-template-columns:1fr 1fr}.team-grid{grid-template-columns:1fr 1fr}.hero h1{font-size:2.5rem}}
  @media(max-width:560px){.srv-grid,.tg,.team-grid,.proc{grid-template-columns:1fr}}
</style>
</head>
<body>

<header id="hdr"><div class="wrap"><nav>
  <div class="brand"><div class="mk">A</div> Apex <span style="color:var(--gold)">Gestoria</span></div>
  <div class="menu"><a href="#serveis">Serveis</a><a href="#nosaltres">Nosaltres</a><a href="#equip">Equip</a><a href="#proces">Com treballem</a><a href="#contacte">Contacte</a></div>
  <a href="login.php" class="btn">Àrea de clients</a>
</nav></div></header>

<div class="hero"><div class="wrap"><div class="hero-grid">
  <div class="reveal in">
    <div class="eyebrow">Des de 2001 · Barcelona</div>
    <h1>La gestoria de <em>confiança</em> per al teu negoci</h1>
    <p>Ens ocupem de la teva fiscalitat, les teves nòmines i la teva comptabilitat amb el tracte de sempre i la tecnologia d'avui. Tu dedica't al teu negoci.</p>
    <div class="cta">
      <a href="#contacte" class="btn btn-gold">Demana pressupost</a>
      <a href="login.php" class="btn btn-out">Àrea de clients</a>
    </div>
  </div>
  <div class="hero-art reveal in">
    <div class="card-vis">
      <div class="dash">
        <div class="dash-head">
          <div><div class="dash-title">Resum anual 2026</div><div class="dash-sub">Apex Gestoria · Portal</div></div>
          <div class="dash-badge">+12% vs 2025</div>
        </div>
        <div class="bars"><div class="bar b1"></div><div class="bar b2"></div><div class="bar b3"></div><div class="bar b4"></div><div class="bar b5"></div><div class="bar b6"></div><div class="bar b7"></div></div>
        <div class="kpis">
          <div class="kpi-sm"><span>Clients actius</span><b>852<span class="up">↑</span></b></div>
          <div class="kpi-sm"><span>Impostos presentats</span><b>100%</b></div>
          <div class="kpi-sm"><span>Factures emeses</span><b>3.240</b></div>
          <div class="kpi-sm"><span>Nòmines processades</span><b>1.890</b></div>
        </div>
      </div>
    </div>
    <div class="float f1"><div class="fic">📈</div><div><small>Clients actius</small><b>+850 empreses</b></div></div>
    <div class="float f2"><div class="fic">✅</div><div><small>Impostos presentats</small><b>100% al dia</b></div></div>
  </div>
</div></div></div>

<div class="band-soft"><div class="wrap"><div class="tg">
  <div><b class="ct" data-to="850" data-suffix="+">0</b><span>clients actius</span></div>
  <div><b class="ct" data-to="25" data-suffix=" anys">0</b><span>d'experiència</span></div>
  <div><b class="ct" data-to="4">0</b><span>àrees de servei</span></div>
  <div><b class="ct" data-to="98" data-suffix="%">0</b><span>retenció de clients</span></div>
</div></div></div>

<section id="serveis"><div class="wrap">
  <div class="head reveal"><div class="eyebrow">Els nostres serveis</div><h2>Tot sota un mateix sostre</h2><p>Un únic equip per a tota la teva gestió administrativa, fiscal i laboral, amb tecnologia actual i tracte personal.</p></div>
  <div class="srv-grid reveal">
    <div class="srv"><div class="ic">💼</div><h3>Fiscal</h3><p>IVA, IRPF, impost de societats i planificació fiscal per a autònoms i empreses de qualsevol mida.</p></div>
    <div class="srv"><div class="ic">👥</div><h3>Laboral</h3><p>Nòmines, contractes, altes i baixes a la Seguretat Social i assessorament laboral integral.</p></div>
    <div class="srv"><div class="ic">📒</div><h3>Comptable</h3><p>Comptabilitat oficial, llibres, comptes anuals, tancaments i auditories de revisió.</p></div>
    <div class="srv"><div class="ic">⚖️</div><h3>Mercantil</h3><p>Constitució de societats, escriptures, registres i tràmits davant notaris i registres.</p></div>
  </div>
</div></section>

<section id="nosaltres" style="background:var(--cream)"><div class="wrap"><div class="about">
  <div class="reveal">
    <div class="eyebrow">Sobre nosaltres</div>
    <p class="quote">"Una gestoria de tota la vida, amb mentalitat actual."</p>
    <p style="color:var(--mut);margin-bottom:22px">Des de 2001 acompanyem autònoms, pimes i famílies. Creiem en el tracte personal: aquí sempre tindràs algú amb qui parlar i que coneix el teu cas de primera mà.</p>
    <ul style="margin-top:18px">
      <li><span class="ck">✓</span> Assessor personal assignat a cada client des del primer dia.</li>
      <li><span class="ck">✓</span> Avisos automàtics abans de cada venciment d'impostos.</li>
      <li><span class="ck">✓</span> Àrea privada online per consultar i pujar documents en qualsevol moment.</li>
      <li><span class="ck">✓</span> Resposta garantida el mateix dia laborable.</li>
    </ul>
  </div>
  <div class="reveal">
    <div style="background:linear-gradient(145deg,var(--navy),#0a2f57);border-radius:22px;min-height:320px;display:flex;align-items:center;justify-content:center;box-shadow:0 28px 56px rgba(11,59,111,.22)">
      <div style="text-align:center;color:#fff;padding:32px">
        <div style="font-size:3.5rem;margin-bottom:12px">🤝</div>
        <p class="serif" style="font-size:1.3rem;margin-bottom:8px">El teu equip de confiança</p>
        <p style="opacity:.65;font-size:.9rem">25 anys assessorant negocis a Barcelona</p>
      </div>
    </div>
  </div>
</div></div></section>

<section id="equip"><div class="wrap">
  <div class="head reveal" style="margin:0 auto 50px;text-align:center"><div class="eyebrow" style="justify-content:center">El nostre equip</div><h2>Professionals al teu servei</h2><p>Un equip sènior amb àmplia experiència en fiscalitat, laboral i comptabilitat.</p></div>
  <div class="team-grid reveal">
    <div class="tm"><div class="tm-av">MC</div><h4>Maria Compte</h4><div class="rol">Directora · Fiscal</div><p>Economista col·legiada amb 20 anys d'experiència en tributació d'empreses i autònoms.</p></div>
    <div class="tm"><div class="tm-av" style="background:linear-gradient(135deg,#c8941f,#e0ad3a)">JF</div><h4>Jordi Ferré</h4><div class="rol">Laboral i RRHH</div><p>Graduat social especialitzat en nòmines, contractació i relacions amb la Seguretat Social.</p></div>
    <div class="tm"><div class="tm-av" style="background:linear-gradient(135deg,#0e4a89,#22a0dd)">LP</div><h4>Laura Pons</h4><div class="rol">Comptabilitat</div><p>Auditora certificada. Gestiona comptabilitats des de microempreses fins a grups empresarials.</p></div>
  </div>
</div></section>

<section id="proces"><div class="wrap">
  <div class="head reveal" style="text-align:center;margin:0 auto 50px"><div class="eyebrow" style="justify-content:center">Com treballem</div><h2>Senzill des del primer dia</h2><p>Et posem fàcil canviar de gestoria o arrancar el teu projecte.</p></div>
  <div class="proc reveal">
    <div class="step"><div class="n">01</div><h4>Primera reunió</h4><p>Coneixem el teu cas sense compromís ni cost.</p></div>
    <div class="step"><div class="n">02</div><h4>Pla a mida</h4><p>T'oferim els serveis que realment necessites.</p></div>
    <div class="step"><div class="n">03</div><h4>Posada en marxa</h4><p>Ens encarreguem del traspàs i la documentació.</p></div>
    <div class="step"><div class="n">04</div><h4>Acompanyament</h4><p>Et mantenim al dia tot l'any, sense sorpreses.</p></div>
  </div>
</div></section>

<section class="test"><div class="wrap">
  <div class="head reveal" style="text-align:center;margin:0 auto 36px"><div class="eyebrow" style="justify-content:center">El que diuen els clients</div><h2>Confiança que es nota</h2></div>
  <div class="slider reveal" id="slider">
    <div class="slide on"><div class="who-av">MS</div><div class="stars">★★★★★</div><p>"Vam canviar a Apex fa tres anys i no podríem estar més contents. Sempre tenim resposta ràpida i ens estalvien molts maldecaps fiscals."</p><div class="who">Marta Soler<small>Construccions Vidal SL</small></div></div>
    <div class="slide"><div class="who-av" style="background:linear-gradient(135deg,#c8941f,#e0ad3a)">JP</div><div class="stars">★★★★★</div><p>"El tracte és immillorable. Tenir un assessor que et coneix pel nom i et truca proactivament abans de cada venciment marca la diferència."</p><div class="who">Jordi Puig<small>Tallers Puig SCP</small></div></div>
    <div class="slide"><div class="who-av" style="background:linear-gradient(135deg,#0e4a89,#22a0dd)">AC</div><div class="stars">★★★★★</div><p>"L'àrea de clients és comodíssima: pujo els documents quan vull i sempre estan al dia amb els meus impostos. Molt recomanable."</p><div class="who">Anna Camós<small>Perruqueria Anna</small></div></div>
  </div>
  <div class="dots" id="dots"></div>
</div></section>

<section style="background:var(--soft)"><div class="wrap">
  <div class="head reveal" style="text-align:center;margin:0 auto 40px"><div class="eyebrow" style="justify-content:center">Preguntes freqüents</div><h2>Tens dubtes? Les tenim resoltes</h2></div>
  <div class="faq reveal">
    <div class="fq open">
      <div class="fq-h" onclick="tog(this)"><span>Quant costa canviar de gestoria?</span><div class="arr"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 5l5 5 5-5"/></svg></div></div>
      <div class="fq-b"><p>El canvi és completament gratuït. Ens encarreguem de tota la gestió del traspàs de documentació amb la teva gestoria anterior i amb l'Agència Tributària sense cap cost ni complicació per a tu.</p></div>
    </div>
    <div class="fq">
      <div class="fq-h" onclick="tog(this)"><span>Puc accedir a la meva documentació en línia?</span><div class="arr"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 5l5 5 5-5"/></svg></div></div>
      <div class="fq-b"><p>Sí. Tots els clients disposen d'una àrea privada accessible des de qualsevol dispositiu on poden consultar factures, nòmines, declaracions i pujar documents de forma segura en qualsevol moment.</p></div>
    </div>
    <div class="fq">
      <div class="fq-h" onclick="tog(this)"><span>Treballeu amb autònoms i pimes petites?</span><div class="arr"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 5l5 5 5-5"/></svg></div></div>
      <div class="fq-b"><p>Absolutament. La majoria dels nostres clients són autònoms i petites empreses. Adaptem el servei i la tarifa a la mida i les necessitats de cada cas, sense costos fixos innecessaris.</p></div>
    </div>
    <div class="fq">
      <div class="fq-h" onclick="tog(this)"><span>Com em notifiqueu els venciments fiscals?</span><div class="arr"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 5l5 5 5-5"/></svg></div></div>
      <div class="fq-b"><p>T'enviem un avís per email i per l'àrea de clients almenys dues setmanes abans de cada venciment, juntament amb el resum de l'impost i el que cal que ens facilitis per presentar-lo.</p></div>
    </div>
  </div>
</div></section>

<section><div class="wrap reveal"><div class="cband">
  <h2 class="serif">Comença avui amb Apex Gestoria</h2>
  <p>Primera consulta gratuïta i sense compromís. T'expliquem com podem ajudar-te a gestionar millor el teu negoci.</p>
  <a href="#contacte" class="btn btn-gold">Demana la teva consulta</a>
</div></div></section>

<section id="contacte"><div class="wrap reveal"><div class="contact">
  <div>
    <div class="eyebrow">Contacte</div>
    <h2 class="serif" style="color:var(--navy);font-size:2rem;margin-bottom:14px">Parlem del teu cas</h2>
    <p style="color:var(--mut);margin-bottom:26px">Et responem el mateix dia laborable.</p>
    <p style="margin-bottom:12px;display:flex;align-items:center;gap:10px"><span style="font-size:1.1rem">📧</span> info@apex-gestoria.cat</p>
    <p style="margin-bottom:12px;display:flex;align-items:center;gap:10px"><span style="font-size:1.1rem">📞</span> 93 200 00 00</p>
    <p style="display:flex;align-items:center;gap:10px"><span style="font-size:1.1rem">📍</span> Carrer Gran de Gràcia 120, Barcelona</p>
  </div>
  <form onsubmit="return false">
    <div class="field"><label>Nom i cognoms</label><input placeholder="El teu nom"></div>
    <div class="field"><label>Email</label><input placeholder="correu@empresa.cat"></div>
    <div class="field"><label>Telèfon</label><input placeholder="6XX XXX XXX"></div>
    <div class="field"><label>Missatge</label><textarea rows="4" placeholder="En què et podem ajudar?"></textarea></div>
    <button class="btn" style="width:100%;border-radius:12px;padding:14px" type="submit">Enviar missatge</button>
  </form>
</div></div></section>

<footer><div class="wrap">
  <div class="fcols">
    <div>
      <div class="brand" style="color:#fff;margin-bottom:14px"><div class="mk">A</div> Apex Gestoria</div>
      <p style="color:#9aa6b5;font-size:.92rem;max-width:280px;line-height:1.65">Assessoria fiscal, laboral i comptable a Barcelona des de 2001. Especialistes en autònoms i pimes.</p>
    </div>
    <div><h5>Serveis</h5><a href="#serveis">Fiscal</a><a href="#serveis">Laboral</a><a href="#serveis">Comptable</a><a href="#serveis">Mercantil</a></div>
    <div><h5>Empresa</h5><a href="#nosaltres">Sobre nosaltres</a><a href="#equip">Equip</a><a href="#proces">Com treballem</a><a href="#contacte">Contacte</a></div>
    <div><h5>Clients</h5><a href="login.php">Àrea de clients</a><a href="#">Avís legal</a><a href="#">Privacitat</a><a href="#">Cookies</a></div>
  </div>
  <div class="fbar">© 2026 Apex Gestoria · Tots els drets reservats · Col·legi de Gestors Administratius de Catalunya</div>
</div></footer>

<script>
const hdr=document.getElementById('hdr');
addEventListener('scroll',()=>hdr.classList.toggle('sc',scrollY>10));
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');
  e.target.querySelectorAll('.ct').forEach(c=>{const to=+c.dataset.to,suf=c.dataset.suffix||'';let n=0;const st=Math.max(1,to/45);
    const iv=setInterval(()=>{n+=st;if(n>=to){n=to;clearInterval(iv);}c.textContent=Math.floor(n)+suf;},28);});
  io.unobserve(e.target);}}),{threshold:.15});
document.querySelectorAll('.reveal').forEach(r=>io.observe(r));
const slides=document.querySelectorAll('.slide'),dots=document.getElementById('dots');let si=0;
slides.forEach((_,i)=>{const d=document.createElement('i');if(i===0)d.className='on';d.onclick=()=>go(i);dots.appendChild(d);});
function go(i){slides[si].classList.remove('on');dots.children[si].classList.remove('on');si=i;
  slides[si].classList.add('on');dots.children[si].classList.add('on');}
setInterval(()=>go((si+1)%slides.length),5000);
function tog(h){const fq=h.parentElement;const isOpen=fq.classList.contains('open');
  document.querySelectorAll('.fq.open').forEach(f=>f.classList.remove('open'));
  if(!isOpen)fq.classList.add('open');}
</script>
</body>
</html>
