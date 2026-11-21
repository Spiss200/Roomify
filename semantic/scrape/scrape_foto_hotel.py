import json, pathlib, re, time, urllib.request, urllib.parse

IMG = pathlib.Path("../../public/images/hotels/_wiki"); IMG.mkdir(parents=True, exist_ok=True)
UA = {"User-Agent":"Roomify-Tubes/1.0 (proyek kuliah)"}
data = json.loads(pathlib.Path("hotels_complete.json").read_text(encoding="utf-8"))
data = [{(k.strip() if isinstance(k,str) else k):v for k,v in h.items()} for h in data]

def get(u, binary=False):
    r = urllib.request.urlopen(urllib.request.Request(u, headers=UA), timeout=20)
    return r.read() if binary else json.loads(r.read().decode())

def bersih(s): return re.sub(r'[^a-z0-9]','',str(s).lower())

def cari_foto(nama):
    try:
        q = urllib.parse.urlencode({"action":"query","list":"search","srsearch":nama+" Medan",
                                    "format":"json","srlimit":3})
        hits = get("https://id.wikipedia.org/w/api.php?"+q).get("query",{}).get("search",[])
        for h in hits:
            t = h["title"]
            if bersih(nama)[:12] not in bersih(t) and bersih(t)[:12] not in bersih(nama): continue
            d = get("https://id.wikipedia.org/api/rest_v1/page/summary/"+urllib.parse.quote(t.replace(" ","_")))
            img = (d.get("originalimage") or d.get("thumbnail") or {}).get("source")
            if img: return img.replace("/330px-","/1200px-")
    except Exception: pass
    return None

target = [h for h in data if not h.get("image_url")]
print(f"🎯 {len(target)} hotel tanpa gambar. Mencoba Wikipedia…\n")
ok = 0
for i, h in enumerate(target, 1):
    img = cari_foto(h["name"])
    if img:
        try:
            dest = IMG / (h["id"] + ".jpg"); dest.write_bytes(get(img, True))
            h["image_url"] = "/images/hotels/_wiki/" + dest.name
            h["image_source"] = "wikipedia"; ok += 1
            print(f"✅ [{i}/{len(target)}] {h['name']}")
        except Exception: pass
    if i % 25 == 0: print(f"… {i}/{len(target)} diproses, {ok} ketemu")
    time.sleep(.35)

pathlib.Path("hotels_complete.json").write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
print(f"\n📦 {ok}/{len(target)} dapat foto. Sisanya ({len(target)-ok}) pakai placeholder.")
