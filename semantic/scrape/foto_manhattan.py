import json, pathlib, shutil, urllib.parse
from PIL import Image

DL  = pathlib.Path.home()/"Downloads"
IMG = pathlib.Path("../../public/images/landmarks"); IMG.mkdir(parents=True, exist_ok=True)
W, Q = 1600, 84

PASANGAN = [
 (["Mall centre point medan.jpeg","Mall%20centre%20point%20medan.jpeg"], "lm-centrepoint"),
 (["kota medan.jpeg","kota%20medan.jpeg"], "manhattan"),
]

def cari(names):
    for n in names:
        for v in (n, urllib.parse.unquote(n)):
            p = DL/v
            if p.exists(): return p
    return None

def simpan(src, lid):
    dst = IMG/(lid+".jpg")
    im = Image.open(src).convert("RGB")
    if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
    im.save(dst,"JPEG",quality=Q,optimize=True,progressive=True)
    print(f"✅ {lid:16s} {im.size} · {dst.stat().st_size//1024} KB ← {src.name}")
    return f"/images/landmarks/{lid}.jpg"

hasil = {}
for names, lid in PASANGAN:
    p = cari(names)
    if not p: print(f"❌ {lid}: file tidak ditemukan di ~/Downloads"); continue
    hasil[lid] = simpan(p, lid)

JS = pathlib.Path("landmarks.json")
data = json.loads(JS.read_text(encoding="utf-8"))
ada = {d["id"] for d in data}

if "lm-centrepoint" in hasil:
    for d in data:
        if d["id"]=="lm-centrepoint": d["image"]=hasil["lm-centrepoint"]

# ⚠️ GANTI koordinat ini dengan hasil klik-kanan Google Maps
if "manhattan" in hasil and "lm-manhattan" not in ada:
    data.append(dict(id="lm-manhattan", name="Manhattan Times Square",
        lat=3.5905, lon=98.6440, category="Mall & Lifestyle",
        address="Jl. Gatot Subroto No. 217, Sei Sikambing B, Medan Sunggal",
        image=hasil["manhattan"], rating=None, reviews=None, ticket=None,
        source="foto_tim"))
    print("➕ lm-manhattan ditambahkan")

JS.write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")

HEAD = ("@prefix roomify: <https://roomify.example/ontology#> .\n"
        "@prefix schema: <https://schema.org/> .\n"
        "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .\n\n")
rdf=[]
for d in data:
    p=[f'roomify:name "{d["name"]}"', f'roomify:latitude {d["lat"]}',
       f'roomify:longitude {d["lon"]}', f'roomify:category "{d["category"]}"',
       'roomify:landmarkInCity roomify:city-medan']
    if d.get("address"): p.append(f'roomify:address "{d["address"]}"')
    if d.get("rating"):  p.append(f'roomify:rating {d["rating"]}')
    if d.get("reviews"): p.append(f'roomify:reviewCount {d["reviews"]}')
    if d.get("image"):   p.append(f'roomify:photoUrl "{d["image"]}"')
    rdf.append(f'roomify:{d["id"]} a roomify:Landmark, schema:TouristAttraction ;\n    '+" ;\n    ".join(p)+" .")
pathlib.Path("../rdf/landmarks.ttl").write_text(HEAD+"\n\n".join(rdf)+"\n", encoding="utf-8")
print(f"\n📦 {len(data)} landmark siap")
