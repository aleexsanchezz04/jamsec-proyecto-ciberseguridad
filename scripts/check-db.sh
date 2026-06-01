#!/bin/bash
echo "=== bases de datos (root/toor) ==="
mysql -u root -ptoor -N -e "SHOW DATABASES;" 2>&1
echo "=== usuarios remotos ==="
mysql -u root -ptoor -N -e "SELECT CONCAT(user,'@',host) FROM mysql.user;" 2>&1
echo "=== tablas en apexgestoria ==="
mysql -u root -ptoor -N -e "SHOW TABLES IN apexgestoria;" 2>&1
echo "=== prueba login apexweb ==="
mysql -u apexweb -papexweb -N -e "SELECT COUNT(*) FROM apexgestoria.usuarios;" 2>&1
