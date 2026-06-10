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
  :root{--navy:#0b3b6f;--navy2:#0e4a89;--gold:#c8941f;--ink:#1c2533;--mut:#6b7a90;--bg:#f0f4fa;--line:#e2e8f2;--serif:Georgia,'Times New Roman',serif}
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,Arial,sans-serif;color:var(--ink);background:var(--bg);display:flex;min-height:100vh}
  a{text-decoration:none;color:inherit}
  .side{width:260px;background:linear-gradient(180deg,#0b3b6f,#071f3a);color:#cfdcec;flex:none;display:flex;flex-direction:column;padding:0;position:sticky;top:0;height:100vh;overflow:hidden}
  .side-top{padding:22px 20px 0}
  .side .brand{display:flex;align-items:center;gap:11px;font-family:var(--serif);font-weight:700;font-size:1.22rem;color:#fff;padding:0 0 20px;border-bottom:1px solid rgba(255,255,255,.08)}
  .side .mk{width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-weight:700;position:relative}
  .side .mk::after{content:"";position:absolute;right:-3px;bottom:-3px;width:12px;height:12px;background:var(--gold);border-radius:3px}
  .nav-section{padding:16px 12px 6px;font-size:.67rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.35);font-weight:600}
  .nav a{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:10px;margin:2px 8px;font-size:.94rem;color:#b8ccdf;transition:.18s}
  .nav a:hover{background:rgba(255,255,255,.08);color:#fff}
  .nav a.active{background:linear-gradient(135deg,rgba(255,255,255,.18),rgba(255,255,255,.1));color:#fff;font-weight:600;box-shadow:inset 0 0 0 1px rgba(255,255,255,.12)}
  .nav a .em{font-size:1rem;width:20px;text-align:center}
  .side-bottom{margin-top:auto;padding:16px 20px;border-top:1px solid rgba(255,255,255,.08)}
  .side-user{display:flex;align-items:center;gap:11px}
  .side-av{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy2),var(--gold));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex:none}
  .side-info{flex:1;min-width:0}
  .side-info b{display:block;color:#fff;font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .side-info span{color:rgba(255,255,255,.45);font-size:.76rem}
  .main{flex:1;display:flex;flex-direction:column;min-width:0}
  .top{background:#fff;border-bottom:1px solid var(--line);height:66px;display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:10;box-shadow:0 1px 6px rgba(11,59,111,.05)}
  .top h2{font-family:var(--serif);font-size:1.3rem;color:var(--navy)}
  .top-right{display:flex;align-items:center;gap:14px}
  .notif{width:36px;height:36px;border-radius:10px;background:var(--bg);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;position:relative;cursor:pointer;transition:.2s}
  .notif:hover{background:#e8eef8}
  .notif .dot{position:absolute;top:7px;right:7px;width:8px;height:8px;border-radius:50%;background:#ef4444;border:2px solid #fff}
  .badgeu{color:var(--mut);font-size:.88rem}
  .avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--gold));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.95rem}
  .logout{font-size:.84rem;color:var(--mut);border:1px solid var(--line);padding:8px 14px;border-radius:9px;transition:.2s}
  .logout:hover{color:#b3261e;border-color:#f0c4bf;background:#fef7f7}
  .content{padding:28px;overflow:auto;flex:1}
  .hello{margin-bottom:26px}
  .hello h1{font-family:var(--serif);font-size:1.7rem;color:var(--navy);margin-bottom:4px}
  .hello p{color:var(--mut);font-size:.92rem}
  .cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
  .kpi{background:#fff;border:1px solid var(--line);border-radius:16px;padding:20px;position:relative;overflow:hidden;animation:up .5s both;transition:.3s}
  .kpi:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(11,59,111,.1)}
  .kpi:nth-child(2){animation-delay:.06s}.kpi:nth-child(3){animation-delay:.12s}.kpi:nth-child(4){animation-delay:.18s}
  @keyframes up{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
  .kpi::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--navy),var(--navy2))}
  .kpi:nth-child(2)::before{background:linear-gradient(90deg,var(--gold),#e0ad3a)}
  .kpi:nth-child(3)::before{background:linear-gradient(90deg,#0e4a89,#22a0dd)}
  .kpi:nth-child(4)::before{background:linear-gradient(90deg,#1b7a3d,#4ade80)}
  .kpi .ic{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#eaf1fa,#fdf2da);display:flex;align-items:center;justify-content:center;margin-bottom:14px;font-size:1.2rem}
  .kpi b{font-size:1.75rem;display:block;color:var(--navy);font-family:var(--serif)}
  .kpi span{color:var(--mut);font-size:.82rem;margin-top:2px;display:block}
  .kpi .trend{position:absolute;top:18px;right:18px;font-size:.76rem;font-weight:600;padding:3px 8px;border-radius:20px}
  .trend-up{background:#e6f6ec;color:#1b7a3d}
  .trend-dn{background:#fdecea;color:#b3261e}
  .box{background:#fff;border:1px solid var(--line);border-radius:16px;padding:24px;margin-bottom:20px;animation:up .5s both}
  .box h3{font-family:var(--serif);font-size:1.12rem;color:var(--navy);margin-bottom:16px;display:flex;align-items:center;gap:9px;padding-bottom:12px;border-bottom:1px solid var(--line)}
  .row2{display:grid;grid-template-columns:1.6fr 1fr;gap:18px}
  table{width:100%;border-collapse:collapse;font-size:.89rem}
  th{text-align:left;color:var(--mut);font-weight:600;padding:10px 12px;border-bottom:2px solid var(--line);font-size:.74rem;text-transform:uppercase;letter-spacing:.5px}
  td{padding:12px;border-bottom:1px solid var(--line)}
  tr:last-child td{border-bottom:0}
  tr:hover td{background:#f7faff}
  .bdg{font-size:.73rem;font-weight:700;padding:4px 11px;border-radius:999px}
  .bdg.g{background:#e6f6ec;color:#1b7a3d}.bdg.y{background:#fdf3da;color:#a9791a}.bdg.r{background:#fdecea;color:#b3261e}
  .up-zone{border:2px dashed #c6d6ec;border-radius:14px;padding:32px;text-align:center;background:#f7fbff;transition:.25s;cursor:pointer}
  .up-zone:hover{border-color:var(--navy);background:#eef4fc}
  .up-zone .up-ico{font-size:2.6rem;margin-bottom:10px}
  .up-zone p{color:var(--mut);font-size:.88rem;margin-bottom:14px}
  .up-zone input[type=file]{width:100%;font-size:.88rem;color:var(--mut);margin-bottom:14px;padding:8px 0}
  .btn{background:linear-gradient(135deg,var(--navy),var(--navy2));color:#fff;font-weight:600;padding:12px 18px;border:0;border-radius:10px;cursor:pointer;width:100%;font-family:inherit;transition:.2s;font-size:.94rem}
  .btn:hover{box-shadow:0 8px 20px rgba(11,59,111,.22);transform:translateY(-1px)}
  .ok{background:linear-gradient(135deg,#e6f6ec,#dcf5e5);color:#1b7a3d;border:1px solid #b7e2c5;border-radius:12px;padding:13px 16px;font-size:.91rem;margin-bottom:18px;display:flex;align-items:center;gap:9px}
  .docitem{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--line);font-size:.91rem;color:var(--mut);transition:.2s}
  .docitem:hover{color:var(--navy)}
  .docitem:last-child{border:0}
  .docitem .fi{width:34px;height:34px;border-radius:8px;background:#fdf2da;display:flex;align-items:center;justify-content:center;flex:none}
  .field{margin-bottom:16px;max-width:420px}
  .field label{display:block;font-size:.84rem;color:var(--mut);margin-bottom:6px;font-weight:500}
  .field input{width:100%;border:1px solid var(--line);border-radius:10px;padding:11px;font-family:inherit;transition:.2s;font-size:.95rem}
  .field input:focus{outline:0;border-color:var(--navy);box-shadow:0 0 0 3px rgba(11,59,111,.07)}
  .tot{display:flex;gap:28px;margin-top:16px;color:var(--mut);font-size:.9rem;padding-top:14px;border-top:1px solid var(--line)}
  .tot b{color:var(--navy)}
  @media(max-width:900px){.side{display:none}.cards{grid-template-columns:1fr 1fr}.row2{grid-template-columns:1fr}}
</style>
</head>
<body>

  <aside class="side">
    <div class="side-top">
      <div class="brand"><div class="mk">A</div> Apex Gestoria</div>
      <div class="nav-section">Navegació</div>
      <nav class="nav">
        <a class="<?php echo $sec=='resum'?'active':''; ?>" href="?seccion=resum"><span class="em">📊</span> Resum</a>
        <a class="<?php echo $sec=='clients'?'active':''; ?>" href="?seccion=clients"><span class="em">👥</span> Clients</a>
        <a class="<?php echo $sec=='factures'?'active':''; ?>" href="?seccion=factures"><span class="em">🧾</span> Factures</a>
        <a class="<?php echo $sec=='documents'?'active':''; ?>" href="?seccion=documents"><span class="em">📁</span> Documents</a>
      </nav>
      <div class="nav-section">Compte</div>
      <nav class="nav">
        <a class="<?php echo $sec=='config'?'active':''; ?>" href="?seccion=config"><span class="em">⚙️</span> Configuració</a>
      </nav>
    </div>
    <div class="side-bottom">
      <div class="side-user">
        <div class="side-av"><?php echo htmlspecialchars($inicial); ?></div>
        <div class="side-info">
          <b><?php echo htmlspecialchars($_SESSION['user']); ?></b>
          <span><?php echo htmlspecialchars($_SESSION['rol']); ?></span>
        </div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="top">
      <h2><?php echo htmlspecialchars($titol); ?></h2>
      <div class="top-right">
        <div class="notif"><svg width="16" height="16" fill="none" stroke="#6b7a90" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><div class="dot"></div></div>
        <span class="badgeu">Hola, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b></span>
        <div class="avatar"><?php echo htmlspecialchars($inicial); ?></div>
        <a class="logout" href="login.php">Sortir</a>
      </div>
    </div>

    <div class="content">
    <?php if ($msg): ?><div class="ok">✅ <?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

    <?php if ($sec === 'resum'): ?>
      <div class="hello"><h1>Benvingut/da de nou 👋</h1><p>Resum de la teva gestió. Última connexió: avui.</p></div>
      <div class="cards">
        <div class="kpi"><div class="ic">👥</div><b><?php echo $nclients; ?></b><span>Clients a la cartera</span><div class="trend trend-up">+3%</div></div>
        <div class="kpi"><div class="ic">🧾</div><b><?php echo $nfact; ?></b><span>Factures emeses</span><div class="trend trend-up">+8%</div></div>
        <div class="kpi"><div class="ic">⏳</div><b><?php echo $pend; ?></b><span>Factures pendents</span><?php if($pend>0):?><div class="trend trend-dn">Pendent</div><?php endif;?></div>
        <div class="kpi"><div class="ic">💶</div><b><?php echo eur($facturat); ?></b><span>Total facturat</span><div class="trend trend-up">+12%</div></div>
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
          <div class="docitem"><div class="fi">📅</div><div><b style="color:var(--ink);font-size:.9rem">Model 303 (IVA)</b><br><small>Venciment: 20 abr.</small></div></div>
          <div class="docitem"><div class="fi">👥</div><div><b style="color:var(--ink);font-size:.9rem">Revisar nòmines</b><br><small>Mes en curs</small></div></div>
          <div class="docitem"><div class="fi">✉️</div><div><b style="color:var(--ink);font-size:.9rem">Comptes anuals</b><br><small>3 clients pendents</small></div></div>
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
            <div class="up-zone">
              <div class="up-ico">📄</div>
              <p>Selecciona un fitxer per enviar-lo de forma segura a la teva gestoria</p>
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

    <?php else: ?>
      <div class="box">
        <h3>⚙️ Dades del compte</h3>
        <div class="field"><label>Usuari</label><input value="<?php echo htmlspecialchars($_SESSION['user']); ?>" readonly></div>
        <div class="field"><label>Rol</label><input value="<?php echo htmlspecialchars($_SESSION['rol']); ?>" readonly></div>
        <div class="field"><label>Email de contacte</label><input value="<?php echo htmlspecialchars($_SESSION['user']); ?>@apex-gestoria.cat"></div>
        <button class="btn" style="max-width:200px" onclick="return false">Desar canvis</button>
      </div>
      <div class="box">
        <h3>🔔 Preferències d'avisos</h3>
        <div class="docitem"><label style="display:flex;gap:10px;align-items:center;cursor:pointer"><input type="checkbox" checked> Avisos de venciment d'impostos</label></div>
        <div class="docitem"><label style="display:flex;gap:10px;align-items:center;cursor:pointer"><input type="checkbox" checked> Còpia de factures per email</label></div>
        <div class="docitem"><label style="display:flex;gap:10px;align-items:center;cursor:pointer"><input type="checkbox"> Newsletter mensual</label></div>
      </div>
    <?php endif; ?>
    </div>
  </div>
</body>
</html>
