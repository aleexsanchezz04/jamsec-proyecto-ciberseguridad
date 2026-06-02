<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$msg = '';
// VULNERABLE (intencionado): subida sin validar tipus ni extensió -> webshell/RCE
if (!empty($_FILES['archivo']['name'])) {
    $dest = 'uploads/' . basename($_FILES['archivo']['name']);
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $dest)) {
        $msg = 'Document pujat correctament: ' . $dest;
    }
}
$res = mysqli_query($conn, "SELECT nombre,nif,email,iban FROM clientes");
$nclients = ($res ? mysqli_num_rows($res) : 0);
$inicial = strtoupper(substr($_SESSION['user'], 0, 1));
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Panell · Apex Gestoria</title>
<style>
  :root{--blue:#16407a;--blue2:#1d5aa8;--teal:#0aa6a6;--ink:#1f2a44;--mut:#6b7a92;--bg:#eef3f9;--line:#e3eaf3;--white:#fff}
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,Roboto,Arial,sans-serif;color:var(--ink);background:var(--bg);display:flex;min-height:100vh}
  a{text-decoration:none;color:inherit}
  /* SIDEBAR */
  .side{width:250px;background:linear-gradient(180deg,#16407a,#123461);color:#cdd9ec;flex:none;display:flex;flex-direction:column;padding:22px 16px}
  .side .brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:1.25rem;color:#fff;padding:6px 8px 22px}
  .side .mark{width:34px;height:34px;border-radius:9px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-weight:800}
  .nav a{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;margin-bottom:4px;font-size:.95rem;color:#cdd9ec}
  .nav a.active,.nav a:hover{background:rgba(255,255,255,.12);color:#fff}
  .side .bottom{margin-top:auto;font-size:.8rem;opacity:.7;padding:10px 14px}
  /* MAIN */
  .main{flex:1;display:flex;flex-direction:column;min-width:0}
  .top{background:#fff;border-bottom:1px solid var(--line);height:68px;display:flex;align-items:center;justify-content:space-between;padding:0 28px}
  .top h2{font-size:1.15rem}
  .user{display:flex;align-items:center;gap:12px}
  .avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--teal));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800}
  .logout{font-size:.85rem;color:var(--mut);border:1px solid var(--line);padding:8px 14px;border-radius:8px}
  .logout:hover{color:var(--blue);border-color:var(--blue)}
  .content{padding:28px;overflow:auto}
  .hello{margin-bottom:22px}
  .hello h1{font-size:1.6rem}
  .hello p{color:var(--mut)}
  .cards{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:26px}
  .kpi{background:#fff;border:1px solid var(--line);border-radius:14px;padding:20px}
  .kpi .ic{width:42px;height:42px;border-radius:10px;background:#eaf2fb;display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:1.2rem}
  .kpi b{font-size:1.7rem;display:block}
  .kpi span{color:var(--mut);font-size:.85rem}
  .row2{display:grid;grid-template-columns:1.7fr 1fr;gap:18px}
  .box{background:#fff;border:1px solid var(--line);border-radius:14px;padding:22px}
  .box h3{font-size:1.05rem;margin-bottom:16px;display:flex;align-items:center;gap:8px}
  table{width:100%;border-collapse:collapse;font-size:.9rem}
  th{text-align:left;color:var(--mut);font-weight:600;padding:10px 12px;border-bottom:2px solid var(--line);font-size:.8rem;text-transform:uppercase;letter-spacing:.5px}
  td{padding:11px 12px;border-bottom:1px solid var(--line)}
  tr:hover td{background:#f7fafd}
  .up{border:2px dashed #c9dcf2;border-radius:12px;padding:22px;text-align:center;background:#f7fafd}
  .up input[type=file]{width:100%;margin:12px 0;font-size:.88rem}
  .btn{background:var(--blue);color:#fff;font-weight:700;padding:11px 18px;border:0;border-radius:9px;cursor:pointer;width:100%}
  .btn:hover{background:var(--blue2)}
  .ok{background:#e7f6ec;color:#1b7a3d;border:1px solid #b7e2c5;border-radius:10px;padding:11px 14px;font-size:.9rem;margin-bottom:16px}
  .docitem{display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--line);font-size:.9rem;color:var(--mut)}
  .docitem:last-child{border:0}
  @media(max-width:880px){.side{display:none}.cards{grid-template-columns:1fr 1fr}.row2{grid-template-columns:1fr}}
</style>
</head>
<body>

  <aside class="side">
    <div class="brand"><div class="mark">A</div> Apex Gestoria</div>
    <nav class="nav">
      <a class="active" href="panel.php">📊 Resum</a>
      <a href="#">📁 Documents</a>
      <a href="#">👥 Clients</a>
      <a href="#">🧾 Factures</a>
      <a href="#">⚙️ Configuració</a>
    </nav>
    <div class="bottom">Apex Gestoria<br>Portal de clients v2.4</div>
  </aside>

  <div class="main">
    <div class="top">
      <h2>Panell de control</h2>
      <div class="user">
        <span style="color:var(--mut);font-size:.9rem">Hola, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b> (<?php echo htmlspecialchars($_SESSION['rol']); ?>)</span>
        <div class="avatar"><?php echo htmlspecialchars($inicial); ?></div>
        <a class="logout" href="login.php">Sortir</a>
      </div>
    </div>

    <div class="content">
      <div class="hello">
        <h1>Benvingut/da de nou 👋</h1>
        <p>Aquí tens un resum de la teva gestió. Última connexió: avui.</p>
      </div>

      <?php if ($msg): ?><div class="ok"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

      <div class="cards">
        <div class="kpi"><div class="ic">👥</div><b><?php echo $nclients; ?></b><span>Clients a la base de dades</span></div>
        <div class="kpi"><div class="ic">🧾</div><b>12</b><span>Impostos aquest trimestre</span></div>
        <div class="kpi"><div class="ic">📁</div><b>34</b><span>Documents arxivats</span></div>
        <div class="kpi"><div class="ic">⏰</div><b>3</b><span>Tasques pendents</span></div>
      </div>

      <div class="row2">
        <div class="box">
          <h3>📇 Cartera de clients</h3>
          <table>
            <tr><th>Nom</th><th>NIF</th><th>Email</th><th>IBAN</th></tr>
            <?php while ($res && $row = mysqli_fetch_assoc($res)) {
              echo '<tr><td>'.htmlspecialchars($row['nombre']).'</td><td>'.htmlspecialchars($row['nif']).
                   '</td><td>'.htmlspecialchars($row['email']).'</td><td>'.htmlspecialchars($row['iban']).'</td></tr>';
            } ?>
          </table>
        </div>
        <div class="box">
          <h3>📤 Pujar document</h3>
          <form method="post" enctype="multipart/form-data">
            <div class="up">
              <div style="font-size:2rem">📄</div>
              <p style="color:var(--mut);font-size:.88rem;margin-top:6px">Arrossega o selecciona un fitxer per enviar-lo a la teva gestoria</p>
              <input type="file" name="archivo">
              <button class="btn" type="submit">Pujar a la gestoria</button>
            </div>
          </form>
          <div style="margin-top:18px">
            <h3 style="font-size:.95rem">Documents recents</h3>
            <div class="docitem">📄 Model_303_T1.pdf</div>
            <div class="docitem">📄 Nòmines_gener.pdf</div>
            <div class="docitem">📄 Factura_2026_014.pdf</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
