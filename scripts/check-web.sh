#!/bin/bash
echo "=== conectividad TCP a la BBDD 10.30.10.10:3306 ==="
nc -z -w3 10.30.10.10 3306 && echo "  3306 ALCANZABLE" || echo "  3306 BLOQUEADO"
echo "=== modulo mysqli en PHP ==="
php -m | grep -i mysqli || echo "  mysqli NO cargado"
echo "=== prueba de conexion PHP a la BBDD ==="
php -r '$c=@mysqli_connect("10.30.10.10","apexweb","apexweb","apexgestoria"); if($c){echo "  CONEXION OK, usuarios=".mysqli_fetch_row(mysqli_query($c,"SELECT COUNT(*) FROM usuarios"))[0]."\n";}else{echo "  ERROR: ".mysqli_connect_error()."\n";}'
echo "=== error log apache (ultimas lineas) ==="
tail -5 /var/log/apache2/error.log 2>/dev/null || echo "  sin log"
