<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$sec = preg_replace('/[^a-z]/','', $_GET['seccion'] ?? 'resum');
$msg = '';
// VULNERABLE (intencionado): subida sense validar tipus ni extensió -> webshell/RCE
if (!empty($_FILES['archivo']['name'])) {
    $dest = 'uploads/' . basename($_FILES['archivo']['name']);
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $dest)) {
        $msg = 'Document pujat correctament: ' . $dest;
    }
}
// KPIs
$nclients = 0;
if ($r = mysqli_query($conn, "SELECT COUNT(*) c FROM clientes")) { $nclients = mysqli_fetch_assoc($r)['c']; }
$nfact = 0; $pend = 0; $facturat = 0;
if ($r = mysqli_query($conn, "SELECT COUNT(*) c, SUM(total) t, SUM(estado='Pendent') p FROM facturas")) {
    $row = mysqli_fetch_assoc($r); $nfact = (int)$row['c']; $facturat = (float)$row['t']; $pend = (int)$row['p'];
}
$inicial = strtoupper(substr($_SESSION['user'], 0, 1));
function eur($n){ return number_format((float)$n, 2, ',', '.') . ' €'; }
function badge($e){ $m=['Pagada'=>'g','Pendent'=>'y','Vençuda'=>'r']; $c=$m[$e]??'y'; return '<span class="bdg '.$c.'">'.htmlspecialchars($e).'</span>'; }
$titol = ['resum'=>'Resum','clients'=>'Cartera de clients','factures'=>'Factures','documents'=>'Documents','config'=>'Configuració'][$sec] ?? 'Resum';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Panell · Apex Gestoria</title>
<style>
  :root{--navy:#0b3b6f;--navy2:#0e4a89;--gold:#c8941f;--ink:#1c2533;--mut:#6b7a90;--bg:#f4f6fa;--line:#e7ebf2;--serif:Georgia,'Times New Roman',serif}
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,Arial,sans-serif;color:var(--ink);background:var(--bg);display:flex;min-height:100vh}
  a{text-decoration:none;color:inherit}
  .side{width:255px;background:linear-gradient(180deg,#0b3b6f,#072c52);color:#cfdcec;flex:none;display:flex;flex-direction:column;padding:22px 16px;position:sticky;top:0;height:100vh}
  .side .brand{display:flex;align-items:center;gap:11px;font-family:var(--serif);font-weight:700;font-size:1.25rem;color:#fff;padding:6px 8px 24px}
  .side .mk{width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-weight:700;position:relative}
  .side .mk::after{content:"";position:absolute;right:-3px;bottom:-3px;width:12px;height:12px;background:var(--gold);border-radius:3px}
  .nav a{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;margin-bottom:4px;font-size:.95rem;color:#cfdcec;transition:.18s}
  .nav a:hover{background:rgba(255,255,255,.10);color:#fff}
  .nav a.active{background:#fff;color:var(--navy);font-weight:600}
  .nav a.active .em{filter:none}
  .side .bottom{margin-top:auto;font-size:.78rem;opacity:.65;padding:10px 14px;line-height:1.5}
  .main{flex:1;display:flex;flex-direction:column;min-width:0}
  .top{background:#fff;border-bottom:1px solid var(--line);height:70px;display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:10}
  .top h2{font-family:var(--serif);font-size:1.3rem;color:var(--navy)}
  .user{display:flex;align-items:center;gap:14px}
  .badgeu{color:var(--mut);font-size:.9rem}
  .avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--gold));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
  .logout{font-size:.85rem;color:var(--mut);border:1px solid var(--line);padding:9px 15px;border-radius:9px}
  .logout:hover{color:#b3261e;border-color:#f0c4bf}
  .content{padding:30px;overflow:auto}
  .hello{margin-bottom:24px}
  .hello h1{font-family:var(--serif);font-size:1.7rem;color:var(--navy)}
  .hello p{color:var(--mut)}
  .cards{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:26px}
  .kpi{background:#fff;border:1px solid var(--line);border-radius:16px;padding:20px;position:relative;overflow:hidden;animation:up .5s both}
  .kpi:nth-child(2){animation-delay:.06s}.kpi:nth-child(3){animation-delay:.12s}.kpi:nth-child(4){animation-delay:.18s}
  @keyframes up{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
  .kpi .ic{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#eaf1fa,#fdf2da);display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:1.25rem}
  .kpi b{font-size:1.7rem;display:block;color:var(--navy);font-family:var(--serif)}
  .kpi span{color:var(--mut);font-size:.84rem}
  .box{background:#fff;border:1px solid var(--line);border-radius:16px;padding:24px;margin-bottom:20px;animation:up .5s both}
  .box h3{font-family:var(--serif);font-size:1.15rem;color:var(--navy);margin-bottom:16px;display:flex;align-items:center;gap:9px}
  .row2{display:grid;grid-template-columns:1.6fr 1fr;gap:20px}
  table{width:100%;border-collapse:collapse;font-size:.9rem}
  th{text-align:left;color:var(--mut);font-weight:600;padding:11px 12px;border-bottom:2px solid var(--line);font-size:.76rem;text-transform:uppercase;letter-spacing:.5px}
  td{padding:12px;border-bottom:1px solid var(--line)}
  tr:hover td{background:#f8fafd}
  .bdg{font-size:.74rem;font-weight:700;padding:4px 11px;border-radius:999px}
  .bdg.g{background:#e6f6ec;color:#1b7a3d}.bdg.y{background:#fdf3da;color:#a9791a}.bdg.r{background:#fdecea;color:#b3261e}
  .up{border:2px dashed #c6d6ec;border-radius:14px;padding:30px;text-align:center;background:#f8fafd;transition:.2s}
  .up:hover{border-color:var(--navy);background:#f2f7fd}
  .up input[type=file]{width:100%;margin:14px 0;font-size:.88rem}
  .btn{background:var(--navy);color:#fff;font-weight:600;padding:12px 18px;border:0;border-radius:10px;cursor:pointer;width:100%}
  .btn:hover{background:var(--navy2)}
  .ok{background:#e6f6ec;color:#1b7a3d;border:1px solid #b7e2c5;border-radius:11px;padding:12px 15px;font-size:.92rem;margin-bottom:18px}
  .docitem{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--line);font-size:.92rem;color:var(--mut)}
  .docitem:last-child{border:0}.docitem .fi{width:34px;height:34px;border-radius:8px;background:#fdf2da;display:flex;align-items:center;justify-content:center;flex:none}
  .field{margin-bottom:16px;max-width:420px}.field label{display:block;font-size:.85rem;color:var(--mut);margin-bottom:6px}
  .field input{width:100%;border:1px solid var(--line);border-radius:10px;padding:11px;font-family:inherit}
  .tot{display:flex;gap:30px;margin-top:14px;color:var(--mut);font-size:.92rem}.tot b{color:var(--navy)}
  @media(max-width:880px){.side{display:none}.cards{grid-template-columns:1fr 1fr}.row2{grid-template-columns:1fr}}
</style>
</head>
<body>

  <aside class="side">
    <div class="brand"><div class="mk">A</div> Apex Gestoria</div>
    <nav class="nav">
      <a class="<?php echo $sec=='resum'?'active':''; ?>" href="?seccion=resum"><span class="em">📊</span> Resum</a>
      <a class="<?php echo $sec=='clients'?'active':''; ?>" href="?seccion=clients"><span class="em">👥</span> Clients</a>
      <a class="<?php echo $sec=='factures'?'active':''; ?>" href="?seccion=factures"><span class="em">🧾</span> Factures</a>
      <a class="<?php echo $sec=='documents'?'active':''; ?>" href="?seccion=documents"><span class="em">📁</span> Documents</a>
      <a class="<?php echo $sec=='config'?'active':''; ?>" href="?seccion=config"><span class="em">⚙️</span> Configuració</a>
    </nav>
    <div class="bottom">Apex Gestoria<br>Portal de clients v2.4<br>Sessió segura</div>
  </aside>

  <div class="main">
    <div class="top">
      <h2><?php echo htmlspecialchars($titol); ?></h2>
      <div class="user">
        <span class="badgeu">Hola, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b> · <?php echo htmlspecialchars($_SESSION['rol']); ?></span>
        <div class="avatar"><?php echo htmlspecialchars($inicial); ?></div>
        <a class="logout" href="login.php">Sortir</a>
      </div>
    </div>

    <div class="content">
    <?php if ($msg): ?><div class="ok">✅ <?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

    <?php if ($sec === 'resum'): ?>
      <div class="hello"><h1>Benvingut/da de nou 👋</h1><p>Aquí tens el resum de la teva gestió. Última connexió: avui.</p></div>
      <div class="cards">
        <div class="kpi"><div class="ic">👥</div><b><?php echo $nclients; ?></b><span>Clients a la cartera</span></div>
        <div class="kpi"><div class="ic">🧾</div><b><?php echo $nfact; ?></b><span>Factures emeses</span></div>
        <div class="kpi"><div class="ic">⏳</div><b><?php echo $pend; ?></b><span>Factures pendents</span></div>
        <div class="kpi"><div class="ic">💶</div><b><?php echo eur($facturat); ?></b><span>Total facturat</span></div>
      </div>
      <div class="row2">
        <div class="box">
          <h3>🧾 Últimes factures</h3>
          <table><tr><th>Número</th><th>Client</th><th>Data</th><th>Total</th><th>Estat</th></tr>
          <?php $r=mysqli_query($conn,"SELECT numero,cliente,fecha,total,estado FROM facturas ORDER BY fecha DESC LIMIT 5");
            while($r && $f=mysqli_fetch_assoc($r)){ echo '<tr><td>'.htmlspecialchars($f['numero']).'</td><td>'.htmlspecialchars($f['cliente']).'</td><td>'.htmlspecialchars($f['fecha']).'</td><td>'.eur($f['total']).'</td><td>'.badge($f['estado']).'</td></tr>'; } ?>
          </table>
        </div>
        <div class="box">
          <h3>📌 Tasques pendents</h3>
          <div class="docitem"><div class="fi">📅</div> Presentar Model 303 (IVA) · 20 abr.</div>
          <div class="docitem"><div class="fi">👥</div> Revisar nòmines del mes</div>
          <div class="docitem"><div class="fi">✉️</div> Enviar comptes anuals a 3 clients</div>
        </div>
      </div>

    <?php elseif ($sec === 'clients'): ?>
      <div class="box">
        <h3>📇 Cartera de clients (<?php echo $nclients; ?>)</h3>
        <table><tr><th>Nom</th><th>NIF</th><th>Email</th><th>IBAN</th></tr>
        <?php $r=mysqli_query($conn,"SELECT nombre,nif,email,iban FROM clientes");
          while($r && $c=mysqli_fetch_assoc($r)){ echo '<tr><td>'.htmlspecialchars($c['nombre']).'</td><td>'.htmlspecialchars($c['nif']).'</td><td>'.htmlspecialchars($c['email']).'</td><td>'.htmlspecialchars($c['iban']).'</td></tr>'; } ?>
        </table>
      </div>

    <?php elseif ($sec === 'factures'): ?>
      <div class="box">
        <h3>🧾 Factures emeses</h3>
        <table><tr><th>Número</th><th>Client</th><th>Data</th><th>Base</th><th>IVA</th><th>Total</th><th>Estat</th></tr>
        <?php $r=mysqli_query($conn,"SELECT numero,cliente,fecha,base,iva,total,estado FROM facturas ORDER BY fecha DESC");
          while($r && $f=mysqli_fetch_assoc($r)){ echo '<tr><td>'.htmlspecialchars($f['numero']).'</td><td>'.htmlspecialchars($f['cliente']).'</td><td>'.htmlspecialchars($f['fecha']).'</td><td>'.eur($f['base']).'</td><td>'.eur($f['iva']).'</td><td>'.eur($f['total']).'</td><td>'.badge($f['estado']).'</td></tr>'; } ?>
        </table>
        <div class="tot"><span>Factures: <b><?php echo $nfact; ?></b></span><span>Pendents: <b><?php echo $pend; ?></b></span><span>Total facturat: <b><?php echo eur($facturat); ?></b></span></div>
      </div>

    <?php elseif ($sec === 'documents'): ?>
      <div class="row2">
        <div class="box">
          <h3>📤 Pujar un document</h3>
          <form method="post" enctype="multipart/form-data" action="?seccion=documents">
            <div class="up">
              <div style="font-size:2.4rem">📄</div>
              <p style="color:var(--mut);font-size:.9rem;margin-top:8px">Selecciona un fitxer per enviar-lo de forma segura a la teva gestoria</p>
              <input type="file" name="archivo">
              <button class="btn" type="submit">Pujar a la gestoria</button>
            </div>
          </form>
        </div>
        <div class="box">
          <h3>🗂️ Documents recents</h3>
          <div class="docitem"><div class="fi">📄</div> Model_303_T1.pdf</div>
          <div class="docitem"><div class="fi">📄</div> Nòmines_gener.pdf</div>
          <div class="docitem"><div class="fi">📄</div> Factura_2026_014.pdf</div>
          <div class="docitem"><div class="fi">📄</div> Comptes_anuals_2025.pdf</div>
        </div>
      </div>

    <?php else: /* config */ ?>
      <div class="box">
        <h3>⚙️ Dades del compte</h3>
        <div class="field"><label>Usuari</label><input value="<?php echo htmlspecialchars($_SESSION['user']); ?>" readonly></div>
        <div class="field"><label>Rol</label><input value="<?php echo htmlspecialchars($_SESSION['rol']); ?>" readonly></div>
        <div class="field"><label>Email de contacte</label><input value="<?php echo htmlspecialchars($_SESSION['user']); ?>@apex-gestoria.cat"></div>
        <button class="btn" style="max-width:200px" onclick="return false">Desar canvis</button>
      </div>
      <div class="box">
        <h3>🔔 Preferències d'avisos</h3>
        <div class="docitem"><label style="display:flex;gap:10px;align-items:center"><input type="checkbox" checked> Avisos de venciment d'impostos</label></div>
        <div class="docitem"><label style="display:flex;gap:10px;align-items:center"><input type="checkbox" checked> Còpia de factures per email</label></div>
        <div class="docitem"><label style="display:flex;gap:10px;align-items:center"><input type="checkbox"> Newsletter mensual</label></div>
      </div>
    <?php endif; ?>
    </div>
  </div>
</body>
</html>
