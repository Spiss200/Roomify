import json, pathlib, re, time, urllib.request, urllib.parse, urllib.error
from PIL import Image

IMG = pathlib.Path("../../public/images/landmarks")
UA = {"User-Agent":"RoomifyTubes/1.0 (https://github.com/Spiss200/Roomify; tugas kuliah web semantik) Python-urllib"}
W, Q, JEDA = 1600, 84, 5.0

TARGET = [
 ("lm-centrepoint","Centre Point Mall"),
 ("lm-sunplaza","Sun Plaza Medan"),
 ("lm-tirtanadi","Menara Air Tirtanadi"),
 ("lm-vihara","Maha Vihara Maitreya"),
 ("lm-zoo","Kebun Binatang Medan"),
]

def get(url, binary=False, tries=3):
    for k in range(tries):
        try:
            r = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=40)
            return r.read() if binary else json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            if e.code in (429,503) and k < tries-1:
                w = 45*(k+1); print(f"   ⏳ {e.code}, tunggu {w}s"); time.sleep(w); continue
            return None
        except Exception:
            if k < tries-1: time.sleep(6); continue
            return None

def foto_asli(p):
    try:
        im = Image.open(p).convert("RGB")
        c = im.getcolors(maxcolors=2_000_000)
        return im.width >= 1000 and c is not None and len(c) > 20000
    except Exception:
        return False

def commons(hint):
    q = urllib.parse.urlencode({"action":"query","list":"search","srsearch":hint,
                                "srnamespace":6,"srlimit":10,"format":"json"})
    d = get("https://commons.wikimedia.org/w/api.php?"+q); time.sleep(JEDA)
    if not d: return None
    for h in d.get("query",{}).get("search",[]):
        t = h["title"]
        if not re.search(r'\.(jpe?g|png)$', t, re.I): continue
        q2 = urllib.parse.urlencode({"action":"query","titles":t,"prop":"imageinfo",
                                     "iiprop":"url|size","iiurlwidth":W,"format":"json"})
        info = get("https://commons.wikimedia.org/w/api.php?"+q2); time.sleep(JEDA)
        if not info: continue
        for p in info.get("query",{}).get("pages",{}).values():
            ii = (p.get("imageinfo") or [{}])[0]
            u = ii.get("thumburl") or ii.get("url")
            if u and ii.get("width",0) >= 1000:
                return u, t
    return None

ok = 0
for lid, hint in TARGET:
    dst = IMG/(lid+".jpg")
    if dst.exists() and foto_asli(dst):
        print(f"⏭  {lid:16s} sudah foto asli"); ok += 1; continue
    print(f"🔎 {lid:16s} cari di Commons…")
    r = commons(hint)
    if not r:
        print("   ❌ tidak ketemu"); continue
    url, title = r
    b = get(url, True)
    if not b:
        print("   ⚠️ unduh gagal"); continue
    tmp = IMG/(lid+".src"); tmp.write_bytes(b)
    if not foto_asli(tmp):
        print(f"   ⚠️ ditolak (bukan foto / terlalu kecil): {title}"); tmp.unlink(missing_ok=True); continue
    im = Image.open(tmp).convert("RGB")
    if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
    im.save(dst, "JPEG", quality=Q, optimize=True, progressive=True)
    tmp.unlink(missing_ok=True)
    print(f"   ✅ {im.size} · {dst.stat().st_size//1024} KB ← {title}")
    ok += 1
    time.sleep(JEDA)

print(f"\n📦 {ok}/5 foto asli")
