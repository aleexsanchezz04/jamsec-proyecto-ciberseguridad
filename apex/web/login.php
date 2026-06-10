<?php
session_start();
require 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['usuario'] ?? '';
    $p = $_POST['password'] ?? '';
    // VULNERABLE (intencionado): consulta construida por concatenación -> SQL injection
    $q = "SELECT * FROM usuarios WHERE usuario='$u' AND password='$p'";
    $r = mysqli_query($conn, $q);
    if ($r && mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        $_SESSION['user'] = $row['usuario'];
        $_SESSION['rol']  = $row['rol'];
        header('Location: panel.php');
        exit;
    } else {
        $err = 'Credencials incorrectes. Torna-ho a provar.';
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Àrea de clients · Apex Gestoria</title>
<style>
  :root{--navy:#0b3b6f;--navy2:#0e4a89;--gold:#c8941f;--gold2:#e0ad3a;--ink:#1c2533;--mut:#5d6b7e;--line:#e9e2d4;--serif:Georgia,'Times New Roman',serif}
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,Arial,sans-serif;color:var(--ink);min-height:100vh;display:flex}
  .left{flex:1;background:linear-gradient(155deg,#0b3b6f,#061e3a);color:#fff;padding:56px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
  .left::before{content:"";position:absolute;inset:0;background-image:radial-gradient(circle at 20% 80%,rgba(200,148,31,.18) 0%,transparent 50%),radial-gradient(circle at 80% 20%,rgba(14,74,137,.4) 0%,transparent 50%)}
  .left .rings{position:absolute;bottom:-120px;left:-80px;width:480px;height:480px;z-index:0}
  .ring{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.06)}
  .ring:nth-child(1){width:200px;height:200px;top:50%;left:50%;transform:translate(-50%,-50%)}
  .ring:nth-child(2){width:310px;height:310px;top:50%;left:50%;transform:translate(-50%,-50%)}
  .ring:nth-child(3){width:420px;height:420px;top:50%;left:50%;transform:translate(-50%,-50%)}
  .brand{display:flex;align-items:center;gap:12px;font-family:var(--serif);font-size:1.5rem;font-weight:700;position:relative;z-index:1}
  .brand .mk{width:44px;height:44px;border-radius:11px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-weight:700;position:relative}
  .brand .mk::after{content:"";position:absolute;right:-3px;bottom:-3px;width:14px;height:14px;background:var(--gold);border-radius:4px}
  .mid{position:relative;z-index:1}
  .mid h2{font-family:var(--serif);font-size:2.4rem;line-height:1.22;margin-bottom:22px}
  .mid h2 em{color:var(--gold2);font-style:italic}
  .feat{display:flex;align-items:center;gap:14px;margin-bottom:16px}
  .feat .ic{width:40px;height:40px;border-radius:11px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex:none;font-size:1rem}
  .feat span{opacity:.88;font-size:.95rem}
  .trust{position:relative;z-index:1;display:flex;align-items:center;gap:14px;padding:16px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:14px}
  .trust .ico{font-size:1.6rem}
  .trust span{font-size:.82rem;opacity:.75;line-height:1.5}
  .right{width:500px;display:flex;align-items:center;justify-content:center;padding:48px 44px;background:#fdfbf6}
  .box{width:100%;max-width:380px}
  .box-top{margin-bottom:32px}
  .box-top .lbl{display:inline-flex;align-items:center;gap:7px;background:#eaf1fa;color:var(--navy2);font-size:.78rem;font-weight:600;padding:5px 12px;border-radius:20px;margin-bottom:14px;letter-spacing:.3px}
  .box-top h1{font-family:var(--serif);font-size:2rem;color:var(--navy);margin-bottom:6px}
  .box-top .sub{color:var(--mut);font-size:.95rem}
  .field{margin-bottom:18px;position:relative}
  .field label{display:block;font-size:.82rem;color:var(--mut);margin-bottom:7px;font-weight:600;letter-spacing:.2px}
  .ip{display:flex;align-items:center;border:1.5px solid var(--line);border-radius:12px;padding:0 14px;background:#fff;transition:.2s}
  .ip:focus-within{border-color:var(--navy);box-shadow:0 0 0 4px rgba(11,59,111,.08)}
  .ip svg{flex:none;opacity:.5;transition:.2s}
  .ip:focus-within svg{opacity:.9}
  .ip input{border:0;outline:0;padding:14px 10px;width:100%;font-size:.97rem;font-family:inherit;background:transparent;color:var(--ink)}
  .row{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;font-size:.85rem;color:var(--mut)}
  .row a{color:var(--navy);font-weight:500}
  .row a:hover{text-decoration:underline}
  .btn{width:100%;background:linear-gradient(135deg,var(--navy),var(--navy2));color:#fff;font-weight:600;font-size:1rem;padding:15px;border:0;border-radius:12px;cursor:pointer;transition:.25s;position:relative;overflow:hidden}
  .btn::before{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.1),transparent);opacity:0;transition:.25s}
  .btn:hover{box-shadow:0 12px 28px rgba(11,59,111,.28);transform:translateY(-1px)}
  .btn:hover::before{opacity:1}
  .btn:active{transform:translateY(0)}
  .err{background:#fdecea;color:#b3261e;border:1px solid #f3c0bb;border-radius:12px;padding:13px 16px;font-size:.9rem;margin-bottom:20px;display:flex;align-items:flex-start;gap:9px}
  .err svg{flex:none;margin-top:1px}
  .divider{display:flex;align-items:center;gap:12px;margin:22px 0;color:var(--mut);font-size:.82rem}
  .divider::before,.divider::after{content:"";flex:1;height:1px;background:var(--line)}
  .back{display:inline-flex;align-items:center;gap:6px;margin-top:22px;font-size:.9rem;color:var(--mut);transition:.2s}
  .back:hover{color:var(--navy)}
  @media(max-width:840px){.left{display:none}.right{width:100%;padding:40px 26px}}
</style>
</head>
<body>
  <div class="left">
    <div class="brand"><div class="mk">A</div> Apex Gestoria</div>
    <div class="mid">
      <h2>La teva gestió,<br>sempre <em>a mà</em>.</h2>
      <div class="feat"><div class="ic">📄</div><span>Consulta les teves factures i declaracions</span></div>
      <div class="feat"><div class="ic">📤</div><span>Puja documentació de forma ràpida i segura</span></div>
      <div class="feat"><div class="ic">🔔</div><span>Rep avisos personalitzats de venciments</span></div>
      <div class="feat"><div class="ic">💬</div><span>Contacta directament amb el teu assessor</span></div>
    </div>
    <div class="rings"><div class="ring"></div><div class="ring"></div><div class="ring"></div></div>
    <div class="trust"><div class="ico">🔒</div><span>Connexió xifrada SSL · Les teves dades protegides<br>amb els estàndards de seguretat més exigents</span></div>
  </div>

  <div class="right"><div class="box">
    <div class="box-top">
      <div class="lbl">
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#0e4a89" stroke-width="3"><path d="M9 12l2 2 4-4"/></svg>
        Portal de clients
      </div>
      <h1>Àrea de clients</h1>
      <div class="sub">Introdueix les teves credencials per accedir</div>
    </div>
    <?php if ($err): ?><div class="err"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#b3261e" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post">
      <div class="field">
        <label>Usuari</label>
        <div class="ip">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b3b6f" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
          <input name="usuario" placeholder="El teu usuari" autofocus autocomplete="username">
        </div>
      </div>
      <div class="field">
        <label>Contrasenya</label>
        <div class="ip">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b3b6f" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
          <input name="password" type="password" placeholder="La teva contrasenya" autocomplete="current-password">
        </div>
      </div>
      <div class="row">
        <label style="display:flex;gap:7px;align-items:center;cursor:pointer"><input type="checkbox"> Recorda'm en aquest dispositiu</label>
        <a href="#">Has oblidat la contrasenya?</a>
      </div>
      <button class="btn" type="submit">Entrar a la meva àrea →</button>
    </form>
    <a class="back" href="index.php">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 3L5 8l5 5"/></svg>
      Tornar a la web principal
    </a>
  </div></div>
</body>
</html>
