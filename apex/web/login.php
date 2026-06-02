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
  :root{--blue:#16407a;--blue2:#1d5aa8;--teal:#0aa6a6;--ink:#1f2a44;--mut:#5b6b85;--line:#e3eaf3}
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,Roboto,Arial,sans-serif;color:var(--ink);
       min-height:100vh;display:flex;align-items:center;justify-content:center;
       background:linear-gradient(135deg,#16407a,#0aa6a6)}
  .card{background:#fff;width:100%;max-width:410px;margin:20px;border-radius:18px;
        box-shadow:0 30px 60px rgba(0,0,0,.25);overflow:hidden}
  .top{background:linear-gradient(135deg,var(--blue),var(--blue2));color:#fff;padding:34px 34px 28px;text-align:center}
  .mark{width:54px;height:54px;border-radius:14px;background:rgba(255,255,255,.15);
        display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;margin:0 auto 14px}
  .top h1{font-size:1.4rem}
  .top p{opacity:.85;font-size:.9rem;margin-top:4px}
  .body{padding:32px 34px 34px}
  .field{margin-bottom:18px}
  .field label{display:block;font-size:.85rem;color:var(--mut);margin-bottom:7px;font-weight:600}
  .ip{display:flex;align-items:center;border:1px solid var(--line);border-radius:10px;padding:0 12px;transition:.15s}
  .ip:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(22,64,122,.12)}
  .ip svg{flex:none;opacity:.5}
  .ip input{border:0;outline:0;padding:13px 10px;width:100%;font-size:.98rem;font-family:inherit;background:transparent}
  .btn{width:100%;background:var(--blue);color:#fff;font-weight:700;font-size:1rem;padding:13px;
       border:0;border-radius:10px;cursor:pointer;transition:.2s}
  .btn:hover{background:var(--blue2)}
  .err{background:#fdecea;color:#b71c1c;border:1px solid #f5c6c0;border-radius:10px;
       padding:11px 14px;font-size:.9rem;margin-bottom:18px;text-align:center}
  .row{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:.85rem;color:var(--mut)}
  .row a{color:var(--blue);text-decoration:none}
  .back{display:block;text-align:center;margin-top:18px;font-size:.88rem;color:var(--mut);text-decoration:none}
  .back:hover{color:var(--blue)}
  .foot{text-align:center;font-size:.78rem;color:#9aa7bd;padding:0 34px 26px}
</style>
</head>
<body>
  <div class="card">
    <div class="top">
      <div class="mark">A</div>
      <h1>Àrea de clients</h1>
      <p>Apex Gestoria · accés privat</p>
    </div>
    <div class="body">
      <?php if ($err): ?><div class="err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
      <form method="post">
        <div class="field">
          <label>Usuari</label>
          <div class="ip">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16407a" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
            <input name="usuario" placeholder="El teu usuari" autofocus>
          </div>
        </div>
        <div class="field">
          <label>Contrasenya</label>
          <div class="ip">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16407a" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
            <input name="password" type="password" placeholder="La teva contrasenya">
          </div>
        </div>
        <div class="row">
          <label style="display:flex;gap:7px;align-items:center;cursor:pointer"><input type="checkbox"> Recorda'm</label>
          <a href="#">Has oblidat la contrasenya?</a>
        </div>
        <button class="btn" type="submit">Entrar</button>
      </form>
      <a class="back" href="index.php">&larr; Tornar a la web</a>
    </div>
    <div class="foot">Connexió privada · © 2026 Apex Gestoria</div>
  </div>
</body>
</html>
