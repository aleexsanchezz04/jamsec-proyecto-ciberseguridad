#!/bin/bash
for f in apex-fw apex-web apex-db apex-srv; do
  printf '%s: ' "$f"
  python3 - "$f" <<'PY'
import sys, yaml
f = sys.argv[1]
try:
    yaml.safe_load(open("/var/lib/vz/snippets/%s.yaml" % f))
    print("YAML_OK")
except Exception as e:
    print("YAML_ERROR:", str(e)[:200])
PY
done
