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
  /* nav */
  header{position:fixed;top:0;left:0;right:0;z-index:50;transition:.3s;padding:14px 0}
  header.sc{background:rgba(255,255,255,.96);backdrop-filter:blur(8px);box-shadow:0 6px 24px rgba(11,59,111,.07)}
  nav{display:flex;align-items:center;justify-content:space-between}
  .brand{display:flex;align-items:center;gap:12px;font-family:var(--serif);font-weight:700;font-size:1.4rem;color:var(--navy)}
  .brand .mk{width:40px;height:40px;border-radius:10px;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-family:var(--serif);position:relative}
  .brand .mk::after{content:"";position:absolute;right:-3px;bottom:-3px;width:14px;height:14px;background:var(--gold);border-radius:4px}
  .menu{display:flex;gap:30px;color:var(--mut);font-size:.96rem}
  .menu a{position:relative;padding:4px 0}
  .menu a:hover{color:var(--navy)}
  .btn{background:var(--navy);color:#fff;font-weight:600;padding:12px 22px;border-radius:30px;border:0;cursor:pointer;transition:.25s;display:inline-block;font-family:inherit}
  .btn:hover{background:var(--navy2);transform:translateY(-2px);box-shadow:0 10px 22px rgba(11,59,111,.22)}
  .btn-gold{background:var(--gold);color:#3a2b06}
  .btn-gold:hover{background:var(--gold2)}
  .btn-out{background:transparent;border:1.5px solid var(--navy);color:var(--navy)}
  .btn-out:hover{background:var(--navy);color:#fff}
  /* hero */
  .hero{padding:150px 0 90px;background:radial-gradient(80% 60% at 90% 10%,#fdf6e6,transparent 60%),linear-gradient(180deg,var(--cream),#fff)}
  .hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:54px;align-items:center}
  .eyebrow{display:inline-flex;align-items:center;gap:8px;color:var(--gold);font-weight:600;letter-spacing:1px;text-transform:uppercase;font-size:.78rem;margin-bottom:18px}
  .eyebrow::before{content:"";width:26px;height:2px;background:var(--gold)}
  .hero h1{font-size:3.5rem;line-height:1.12;color:var(--navy);font-weight:700}
  .hero h1 em{color:var(--gold);font-style:italic}
  .hero p{color:var(--mut);font-size:1.16rem;margin:22px 0 32px;max-width:500px}
  .cta{display:flex;gap:14px;flex-wrap:wrap;align-items:center}
  .hero-art{position:relative}
  .photo{background:linear-gradient(135deg,var(--navy),#0a2f57);border-radius:22px;min-height:360px;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 30px 60px rgba(11,59,111,.25)}
  .photo .big{font-size:4rem}
  .float{position:absolute;background:#fff;border-radius:16px;padding:16px 18px;box-shadow:0 18px 40px rgba(11,59,111,.16);display:flex;align-items:center;gap:12px;animation:bob 4s ease-in-out infinite}
  .float small{color:var(--mut);display:block;font-size:.78rem}
  .float b{font-size:1.05rem}
  .f1{top:24px;left:-26px;animation-delay:-1s}
  .f2{bottom:30px;right:-22px}
  .fic{width:40px;height:40px;border-radius:10px;background:#fdf2da;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
  @keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
  /* logos band */
  .band-soft{background:var(--navy);color:#fff;padding:30px 0}
  .tg{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;text-align:center}
  .tg b{font-family:var(--serif);font-size:2.1rem;display:block;color:var(--gold2)}
  .tg span{opacity:.85;font-size:.9rem}
  /* sections */
  section{padding:90px 0}
  .head{max-width:640px;margin-bottom:48px}
  .head h2{font-size:2.4rem;color:var(--navy);margin:8px 0 12px}
  .head p{color:var(--mut)}
  .reveal{opacity:0;transform:translateY(30px);transition:.8s cubic-bezier(.2,.7,.2,1)}
  .reveal.in{opacity:1;transform:none}
  .srv-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px}
  .srv{background:var(--paper);border:1px solid var(--line);border-radius:18px;padding:30px 24px;transition:.3s;position:relative;overflow:hidden}
  .srv::before{content:"";position:absolute;top:0;left:0;width:100%;height:4px;background:var(--gold);transform:scaleX(0);transform-origin:left;transition:.3s}
  .srv:hover{transform:translateY(-8px);box-shadow:0 24px 50px rgba(11,59,111,.13)}
  .srv:hover::before{transform:scaleX(1)}
  .srv .ic{width:58px;height:58px;border-radius:16px;background:linear-gradient(135deg,#eaf1fa,#fdf2da);display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin-bottom:18px}
  .srv h3{font-size:1.25rem;color:var(--navy);margin-bottom:8px}
  .srv p{color:var(--mut);font-size:.93rem}
  /* about */
  .about{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center}
  .about .quote{font-family:var(--serif);font-size:1.5rem;color:var(--navy);font-style:italic;line-height:1.5;border-left:3px solid var(--gold);padding-left:22px;margin-bottom:24px}
  .about ul{list-style:none}
  .about li{display:flex;gap:14px;margin-bottom:16px;color:var(--mut)}
  .about li .ck{width:26px;height:26px;border-radius:50%;background:#eaf1fa;color:var(--navy);display:flex;align-items:center;justify-content:center;flex:none;font-weight:700}
  .proc{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;counter-reset:s}
  .step{position:relative;padding-top:18px}
  .step .n{font-family:var(--serif);font-size:2.4rem;color:var(--gold);font-weight:700}
  .step h4{color:var(--navy);font-size:1.1rem;margin:6px 0}
  .step p{color:var(--mut);font-size:.9rem}
  /* testimonials */
  .test{background:var(--cream)}
  .slider{position:relative;max-width:760px;margin:0 auto;text-align:center;min-height:170px}
  .slide{position:absolute;inset:0;opacity:0;transition:.6s;display:flex;flex-direction:column;align-items:center;justify-content:center}
  .slide.on{opacity:1}
  .slide p{font-family:var(--serif);font-size:1.4rem;font-style:italic;color:var(--ink);line-height:1.55;margin-bottom:18px}
  .slide .who{color:var(--navy);font-weight:700}.slide .who small{display:block;color:var(--mut);font-weight:400}
  .dots{display:flex;gap:8px;justify-content:center;margin-top:18px}
  .dots i{width:9px;height:9px;border-radius:50%;background:var(--line);cursor:pointer;transition:.2s}
  .dots i.on{background:var(--gold);width:24px;border-radius:5px}
  /* cta */
  .cband{background:linear-gradient(120deg,var(--navy),#0a2f57);border-radius:24px;padding:56px;text-align:center;color:#fff;position:relative;overflow:hidden}
  .cband::after{content:"";position:absolute;width:300px;height:300px;background:radial-gradient(circle,rgba(200,148,31,.35),transparent 70%);top:-100px;right:-50px}
  .cband h2{color:#fff;font-size:2.2rem;margin-bottom:12px}
  /* contact */
  .contact{display:grid;grid-template-columns:1fr 1fr;gap:46px;background:var(--soft);border-radius:24px;padding:48px}
  .field{margin-bottom:16px}
  .field label{display:block;font-size:.85rem;color:var(--mut);margin-bottom:6px}
  .field input,.field textarea{width:100%;background:#fff;border:1px solid var(--line);border-radius:12px;padding:13px;font-family:inherit}
  .field input:focus,.field textarea:focus{outline:0;border-color:var(--navy)}
  /* footer */
  footer{background:var(--ink);color:#c2cad6;padding:54px 0 30px}
  .fcols{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:30px;margin-bottom:30px}
  .fcols h5{color:#fff;font-family:var(--serif);margin-bottom:14px;font-size:1.05rem}
  .fcols a{display:block;color:#9aa6b5;margin-bottom:8px;font-size:.92rem}
  .fcols a:hover{color:var(--gold2)}
  .fbar{border-top:1px solid #2a3342;padding-top:20px;text-align:center;font-size:.85rem;color:#8390a1}
  @media(max-width:880px){.menu{display:none}.hero-grid,.about,.contact{grid-template-columns:1fr}.srv-grid,.proc,.tg,.fcols{grid-template-columns:1fr 1fr}.hero h1{font-size:2.4rem}}
</style>
</head>
<body>

<header id="hdr"><div class="wrap"><nav>
  <div class="brand"><div class="mk">A</div> Apex <span style="color:var(--gold)">Gestoria</span></div>
  <div class="menu"><a href="#serveis">Serveis</a><a href="#nosaltres">Nosaltres</a><a href="#proces">Com treballem</a><a href="#contacte">Contacte</a></div>
  <a href="login.php" class="btn">Àrea de clients</a>
</nav></div></header>

<div class="hero"><div class="wrap"><div class="hero-grid">
  <div class="reveal in">
    <div class="eyebrow">Des de 2001 · Barcelona</div>
    <h1>La gestoria de <em>confiança</em> per al teu negoci</h1>
    <p>Ens ocupem de la teva fiscalitat, les teves nòmines i la teva comptabilitat amb el tracte de sempre i la tecnologia d'avui. Tu dedica't al teu negoci; de la resta ens encarreguem nosaltres.</p>
    <div class="cta">
      <a href="#contacte" class="btn btn-gold">Demana pressupost</a>
      <a href="login.php" class="btn btn-out">Accedir a la meva àrea</a>
    </div>
  </div>
  <div class="hero-art reveal in">
    <div class="photo"><div style="text-align:center"><div class="big">🏛️</div><p class="serif" style="margin-top:10px;font-size:1.2rem">Apex Gestoria</p></div></div>
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
  <div class="head reveal"><div class="eyebrow">Els nostres serveis</div><h2>Tot sota un mateix sostre</h2><p>Un únic equip per a tota la teva gestió administrativa, fiscal i laboral.</p></div>
  <div class="srv-grid reveal">
    <div class="srv"><div class="ic">💼</div><h3>Fiscal</h3><p>IVA, IRPF, impost de societats i planificació fiscal per a autònoms i empreses.</p></div>
    <div class="srv"><div class="ic">👥</div><h3>Laboral</h3><p>Nòmines, contractes, altes i baixes a la Seguretat Social i assessorament laboral.</p></div>
    <div class="srv"><div class="ic">📒</div><h3>Comptable</h3><p>Comptabilitat oficial, llibres, comptes anuals i tancaments d'exercici.</p></div>
    <div class="srv"><div class="ic">⚖️</div><h3>Mercantil</h3><p>Constitució de societats, escriptures, registres i tràmits mercantils.</p></div>
  </div>
</div></section>

<section id="nosaltres" style="background:var(--cream)"><div class="wrap"><div class="about">
  <div class="reveal">
    <div class="eyebrow">Sobre nosaltres</div>
    <p class="quote">"Una gestoria de tota la vida, amb mentalitat actual."</p>
    <p style="color:var(--mut);margin-bottom:8px">Des de 2001 acompanyem autònoms, pimes i famílies. Creiem en el tracte personal: aquí sempre tindràs algú amb qui parlar.</p>
    <ul style="margin-top:18px">
      <li><span class="ck">✓</span> Assessor personal assignat a cada client.</li>
      <li><span class="ck">✓</span> Avisos abans de cada venciment d'impostos.</li>
      <li><span class="ck">✓</span> Àrea privada per consultar factures i pujar documents.</li>
    </ul>
  </div>
  <div class="reveal">
    <div class="photo" style="min-height:300px"><div style="text-align:center"><div style="font-size:3rem">🤝</div><p class="serif" style="margin-top:8px">El teu equip de confiança</p></div></div>
  </div>
</div></div></section>

<section id="proces"><div class="wrap">
  <div class="head reveal"><div class="eyebrow">Com treballem</div><h2>Senzill des del primer dia</h2><p>Et posem fàcil canviar de gestoria o començar el teu projecte.</p></div>
  <div class="proc reveal">
    <div class="step"><div class="n">01</div><h4>Primera reunió</h4><p>Coneixem el teu cas sense compromís.</p></div>
    <div class="step"><div class="n">02</div><h4>Pla a mida</h4><p>T'oferim els serveis que realment necessites.</p></div>
    <div class="step"><div class="n">03</div><h4>Posada en marxa</h4><p>Ens encarreguem del traspàs i la documentació.</p></div>
    <div class="step"><div class="n">04</div><h4>Acompanyament</h4><p>Et mantenim al dia tot l'any.</p></div>
  </div>
</div></section>

<section class="test"><div class="wrap">
  <div class="head reveal" style="text-align:center;margin:0 auto 36px"><div class="eyebrow" style="justify-content:center">El que diuen els clients</div><h2>Confiança que es nota</h2></div>
  <div class="slider reveal" id="slider">
    <div class="slide on"><p>"Vam canviar a Apex fa tres anys i no podríem estar més contents. Sempre tenim resposta ràpida i ens estalvien molts maldecaps."</p><div class="who">Marta Soler<small>Construccions Vidal SL</small></div></div>
    <div class="slide"><p>"El tracte és immillorable. Tenir un assessor que et coneix pel nom marca la diferència."</p><div class="who">Jordi Puig<small>Tallers Puig SCP</small></div></div>
    <div class="slide"><p>"L'àrea de clients és comodíssima: pujo els documents quan vull i sempre estan al dia amb els impostos."</p><div class="who">Anna Camós<small>Perruqueria Anna</small></div></div>
  </div>
  <div class="dots" id="dots"></div>
</div></section>

<section><div class="wrap reveal"><div class="cband">
  <h2 class="serif">Comença avui amb Apex Gestoria</h2>
  <p style="opacity:.9;max-width:520px;margin:0 auto 26px">Demana't una primera consulta gratuïta i sense compromís. T'expliquem com podem ajudar-te.</p>
  <a href="#contacte" class="btn btn-gold">Demana la teva consulta</a>
</div></div></section>

<section id="contacte"><div class="wrap reveal"><div class="contact">
  <div>
    <div class="eyebrow">Contacte</div>
    <h2 class="serif" style="color:var(--navy);font-size:2rem;margin-bottom:14px">Parlem del teu cas</h2>
    <p style="color:var(--mut);margin-bottom:22px">Et responem el mateix dia laborable.</p>
    <p style="margin-bottom:10px">📧 info@apex-gestoria.cat</p>
    <p style="margin-bottom:10px">📞 93 200 00 00</p>
    <p>📍 Carrer Gran de Gràcia 120, Barcelona</p>
  </div>
  <form onsubmit="return false">
    <div class="field"><label>Nom i cognoms</label><input placeholder="El teu nom"></div>
    <div class="field"><label>Email</label><input placeholder="correu@empresa.cat"></div>
    <div class="field"><label>Missatge</label><textarea rows="4" placeholder="En què et podem ajudar?"></textarea></div>
    <button class="btn" type="submit">Enviar consulta</button>
  </form>
</div></div></section>

<footer><div class="wrap">
  <div class="fcols">
    <div>
      <div class="brand" style="color:#fff;margin-bottom:14px"><div class="mk">A</div> Apex Gestoria</div>
      <p style="color:#9aa6b5;font-size:.92rem;max-width:280px">Assessoria fiscal, laboral i comptable a Barcelona des de 2001.</p>
    </div>
    <div><h5>Serveis</h5><a href="#serveis">Fiscal</a><a href="#serveis">Laboral</a><a href="#serveis">Comptable</a><a href="#serveis">Mercantil</a></div>
    <div><h5>Empresa</h5><a href="#nosaltres">Sobre nosaltres</a><a href="#proces">Com treballem</a><a href="#contacte">Contacte</a></div>
    <div><h5>Clients</h5><a href="login.php">Àrea de clients</a><a href="#">Avís legal</a><a href="#">Privacitat</a></div>
  </div>
  <div class="fbar">© 2026 Apex Gestoria · Tots els drets reservats</div>
</div></footer>

<script>
const hdr=document.getElementById('hdr');
addEventListener('scroll',()=>hdr.classList.toggle('sc',scrollY>10));
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');
  e.target.querySelectorAll('.ct').forEach(c=>{const to=+c.dataset.to,suf=c.dataset.suffix||'';let n=0;const st=Math.max(1,to/45);
    const iv=setInterval(()=>{n+=st;if(n>=to){n=to;clearInterval(iv);}c.textContent=Math.floor(n)+suf;},28);});
  io.unobserve(e.target);}}),{threshold:.15});
document.querySelectorAll('.reveal').forEach(r=>io.observe(r));
// testimonials
const slides=document.querySelectorAll('.slide'),dots=document.getElementById('dots');let si=0;
slides.forEach((_,i)=>{const d=document.createElement('i');if(i===0)d.className='on';d.onclick=()=>go(i);dots.appendChild(d);});
function go(i){slides[si].classList.remove('on');dots.children[si].classList.remove('on');si=i;
  slides[si].classList.add('on');dots.children[si].classList.add('on');}
setInterval(()=>go((si+1)%slides.length),5000);
</script>
</body>
</html>
