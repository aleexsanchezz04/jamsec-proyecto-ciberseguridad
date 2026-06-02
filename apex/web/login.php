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
  /* left brand panel */
  .left{flex:1;background:linear-gradient(150deg,var(--navy),#072a50);color:#fff;padding:56px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
  .left::after{content:"";position:absolute;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(200,148,31,.30),transparent 70%);bottom:-140px;left:-100px}
  .brand{display:flex;align-items:center;gap:12px;font-family:var(--serif);font-size:1.5rem;font-weight:700;position:relative;z-index:1}
  .brand .mk{width:44px;height:44px;border-radius:11px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-weight:700;position:relative}
  .brand .mk::after{content:"";position:absolute;right:-3px;bottom:-3px;width:14px;height:14px;background:var(--gold);border-radius:4px}
  .left .mid{position:relative;z-index:1}
  .left h2{font-family:var(--serif);font-size:2.3rem;line-height:1.25;margin-bottom:18px}
  .left h2 em{color:var(--gold2);font-style:italic}
  .feat{display:flex;align-items:center;gap:14px;margin-bottom:16px;opacity:.95}
  .feat .ic{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex:none}
  .left small{position:relative;z-index:1;opacity:.7}
  /* right form */
  .right{width:480px;display:flex;align-items:center;justify-content:center;padding:40px;background:#fdfbf6}
  .box{width:100%;max-width:360px}
  .box .top{margin-bottom:28px}
  .box h1{font-family:var(--serif);font-size:1.9rem;color:var(--navy)}
  .box .sub{color:var(--mut);font-size:.95rem;margin-top:6px}
  .field{margin-bottom:18px}
  .field label{display:block;font-size:.85rem;color:var(--mut);margin-bottom:7px;font-weight:600}
  .ip{display:flex;align-items:center;border:1.5px solid var(--line);border-radius:12px;padding:0 14px;background:#fff;transition:.15s}
  .ip:focus-within{border-color:var(--navy);box-shadow:0 0 0 4px rgba(11,59,111,.10)}
  .ip svg{flex:none;opacity:.55}
  .ip input{border:0;outline:0;padding:14px 10px;width:100%;font-size:.98rem;font-family:inherit;background:transparent}
  .row{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;font-size:.86rem;color:var(--mut)}
  .row a{color:var(--navy)}
  .btn{width:100%;background:var(--navy);color:#fff;font-weight:600;font-size:1rem;padding:14px;border:0;border-radius:12px;cursor:pointer;transition:.2s}
  .btn:hover{background:var(--navy2);box-shadow:0 10px 22px rgba(11,59,111,.22)}
  .err{background:#fdecea;color:#b3261e;border:1px solid #f3c0bb;border-radius:11px;padding:12px 14px;font-size:.9rem;margin-bottom:18px;display:flex;align-items:center;gap:8px}
  .back{display:inline-block;margin-top:22px;font-size:.9rem;color:var(--mut)}
  .back:hover{color:var(--navy)}
  @media(max-width:840px){.left{display:none}.right{width:100%}}
</style>
</head>
<body>
  <div class="left">
    <div class="brand"><div class="mk">A</div> Apex Gestoria</div>
    <div class="mid">
      <h2>La teva gestió,<br>sempre <em>a mà</em>.</h2>
      <div class="feat"><div class="ic">📄</div> Consulta les teves factures i documents</div>
      <div class="feat"><div class="ic">📤</div> Puja documentació de forma segura</div>
      <div class="feat"><div class="ic">🔔</div> Rep avisos abans de cada venciment</div>
    </div>
    <small>© 2026 Apex Gestoria · Connexió privada i xifrada</small>
  </div>

  <div class="right"><div class="box">
    <div class="top">
      <h1>Àrea de clients</h1>
      <div class="sub">Introdueix les teves credencials per accedir</div>
    </div>
    <?php if ($err): ?><div class="err">⚠️ <?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post">
      <div class="field">
        <label>Usuari</label>
        <div class="ip">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b3b6f" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
          <input name="usuario" placeholder="El teu usuari" autofocus>
        </div>
      </div>
      <div class="field">
        <label>Contrasenya</label>
        <div class="ip">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b3b6f" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
          <input name="password" type="password" placeholder="La teva contrasenya">
        </div>
      </div>
      <div class="row">
        <label style="display:flex;gap:7px;align-items:center;cursor:pointer"><input type="checkbox"> Recorda'm</label>
        <a href="#">Has oblidat la contrasenya?</a>
      </div>
      <button class="btn" type="submit">Entrar a la meva àrea</button>
    </form>
    <a class="back" href="index.php">&larr; Tornar a la web</a>
  </div></div>
</body>
</html>
