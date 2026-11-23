import json, pathlib, re, shutil, time, urllib.request, urllib.parse, urllib.error
from PIL import Image

IMG = pathlib.Path("../../public/images/landmarks")
UA = {"User-Agent":"RoomifyTubes/1.0 (https://github.com/Spiss200/Roomify; tugas kuliah web semantik) Python-urllib"}
W, Q = 1600, 84

TARGET = [
 ("lm-centrepoint","Centre Point","Centre Point Medan","centre-point.jpg"),
 ("lm-vihara","Maitreya","Maha Vihara Maitreya Medan","maha-vihara-maitreya.jpg"),
 ("lm-zoo","Zoo|Kebun Binatang","Medan Zoo","kebun-binatang.jpg"),
]

def get(url, binary=False, tries=2):
    for k in range(tries):
        try:
            r = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=45)
            return r.read() if binary else json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            if e.code in (429,503) and k < tries-1:
                print(f"   ⏳ {e.code}, tunggu 40s"); time.sleep(40); continue
            return None
        except Exception:
            if k < tries-1: time.sleep(5); continue
            return None

def commons_file(fn):
    return "https://commons.wikimedia.org/wiki/Special:FilePath/" + urllib.parse.quote(fn) + f"?width={W}"

def dari_osm(pola):
    q = f'[out:json][timeout:60];nwr["name"~"{pola}",i](3.45,98.55,3.75,98.90);out center tags;'
    for ep in ("https://overpass-api.de/api/interpreter",
               "https://overpass.kumi.systems/api/interpreter"):
        d = get(ep + "?" + urllib.parse.urlencode({"data": q}))
        if d: break
    if not d: return []
    hasil = []
    for el in d.get("elements", []):
        t = el.get("tags", {})
        nama = t.get("name","?")
        if t.get("image"):
            hasil.append((t["image"], f"OSM image · {nama}"))
        wc = t.get("wikimedia_commons","")
        if wc.lower().startswith("file:"):
            hasil.append((commons_file(wc[5:]), f"OSM wikimedia_commons · {nama}"))
        if t.get("wikidata"):
            u = p18(t["wikidata"])
            if u: hasil.append((u, f"OSM→Wikidata {t['wikidata']} · {nama}"))
    return hasil

def p18(qid):
    c = get(f"https://www.wikidata.org/w/api.php?action=wbgetclaims&entity={qid}&property=P18&format=json")
    if not c: return None
    cl = c.get("claims",{}).get("P18",[])
    if not cl: return None
    try: return commons_file(cl[0]["mainsnak"]["datavalue"]["value"])
    except Exception: return None

def dari_wikidata(nama):
    for lang in ("id","en"):
        q = urllib.parse.urlencode({"action":"wbsearchentities","search":nama,
                                    "language":lang,"uselang":lang,"format":"json","limit":5})
        d = get("https://www.wikidata.org/w/api.php?"+q)
        if not d: continue
        for r in d.get("search",[]):
            u = p18(r["id"])
            if u: return [(u, f"Wikidata {r['id']} · {r.get('label','')}")]
        time.sleep(2)
    return []

def foto_asli(p):
    try:
        im = Image.open(p).convert("RGB")
        c = im.getcolors(maxcolors=2_000_000)
        return im.width >= 900 and c is not None and len(c) > 20000
    except Exception:
        return False

for lid, pola, nama, ph in TARGET:
    dst = IMG/(lid+".jpg")
    print(f"🔎 {lid}")
    kandidat = dari_osm(pola)
    if not kandidat:
        print("   OSM: tidak ada tag foto → coba Wikidata")
        kandidat = dari_wikidata(nama)
    if not kandidat:
        print("   ❌ Wikidata juga kosong")
    berhasil = False
    for url, asal in kandidat:
        b = get(url, True)
        if not b: continue
        tmp = IMG/(lid+".src"); tmp.write_bytes(b)
        if not foto_asli(tmp):
            print(f"   🚫 ditolak (bukan foto/kecil): {asal}"); tmp.unlink(missing_ok=True); continue
        im = Image.open(tmp).convert("RGB")
        if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
        im.save(dst,"JPEG",quality=Q,optimize=True,progressive=True)
        tmp.unlink(missing_ok=True)
        print(f"   ✅ {im.size} · {dst.stat().st_size//1024} KB ← {asal}")
        berhasil = True; break
    if not berhasil:
        s = IMG/ph
        if s.exists(): shutil.copyfile(s, dst)
        print(f"   ↩️  tetap placeholder tim")
    time.sleep(2)

