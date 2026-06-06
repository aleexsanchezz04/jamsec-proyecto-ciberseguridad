# ============================================================================
#  TEST DE CONECTIVIDAD — ejecútalo en el PC desde el que harás la demo.
#  Comprueba si llegas directamente a los servicios del clúster (sin túnel).
#  Funciona en cualquier PC de la red del aula (172.20.17.0/24), sea cual sea su IP.
# ============================================================================
Write-Host ""
Write-Host "  Tu(s) IP(s) en este PC:" -ForegroundColor Cyan
(Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue | Where-Object {$_.IPAddress -notlike '127.*'}).IPAddress | ForEach-Object { Write-Host "    $_" }
Write-Host ""

$svcs = @(
  @{n='Web JAMSEC'; h='172.20.17.240'; p=80;   u='http://172.20.17.240'},
  @{n='Web Apex';   h='172.20.17.230'; p=80;   u='http://172.20.17.230'},
  @{n='Wazuh SIEM'; h='172.20.17.231'; p=443;  u='https://172.20.17.231'},
  @{n='Proxmox';    h='172.20.17.202'; p=8006; u='https://172.20.17.202:8006'}
)
Write-Host "  Comprobando servicios..." -ForegroundColor Cyan
Write-Host ""
$ok=0
foreach($s in $svcs){
  $t = Test-NetConnection -ComputerName $s.h -Port $s.p -WarningAction SilentlyContinue
  if($t.TcpTestSucceeded){
    Write-Host ("  [ OK ]  {0,-12}  ->  {1}" -f $s.n, $s.u) -ForegroundColor Green; $ok++
  } else {
    Write-Host ("  [FALLA] {0,-12}  ->  {1}" -f $s.n, $s.u) -ForegroundColor Red
  }
}
Write-Host ""
if($ok -eq $svcs.Count){
  Write-Host "  TODO ACCESIBLE. Abre esas URLs en el navegador para la demo." -ForegroundColor Green
  Write-Host "  (Wazuh y Proxmox avisaran de 'certificado no seguro' -> Avanzado -> Continuar)" -ForegroundColor DarkGray
} elseif($ok -gt 0){
  Write-Host "  Acceso parcial. Lo que diga [OK] funciona por IP directa." -ForegroundColor Yellow
} else {
  Write-Host "  Este PC NO llega a la red del cluster (172.20.17.0/24)." -ForegroundColor Red
  Write-Host "  Plan B: usa tu portatil con Tailscale y el script abrir-accesos.ps1," -ForegroundColor Yellow
  Write-Host "          o el video de respaldo de la demo." -ForegroundColor Yellow
}
Write-Host ""
