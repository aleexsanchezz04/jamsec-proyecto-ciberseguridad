import zipfile, re, sys
z = zipfile.ZipFile("JAMSEC_Presentacion.pptx")
names = sorted([n for n in z.namelist() if re.match(r"ppt/slides/slide\d+\.xml$", n)],
               key=lambda n: int(re.search(r"(\d+)", n).group(1)))
for n in names:
    xml = z.read(n).decode("utf-8")
    texts = re.findall(r"<a:t>(.*?)</a:t>", xml, re.S)
    num = re.search(r"(\d+)", n).group(1)
    joined = " | ".join(t.strip() for t in texts if t.strip())
    print(f"--- Slide {num} ---")
    print(joined[:600])
    print()
