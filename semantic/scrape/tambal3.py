import json, pathlib, re, shutil, time, urllib.request, urllib.parse, urllib.error
from PIL import Image

IMG = pathlib.Path("../../public/images/landmarks")
UA = {"User-Agent":"RoomifyTubes/1.0 (https://github.com/Spiss200/Roomify; tugas kuliah web semantik) Python-urllib"}
W, Q, JEDA = 1600, 84, 5.0

# (id, kata cari, regex wajib cocok, wajib ada "medan", placeholder tim)
TARGET = [
 ("lm-centrepoint","Centre Point Medan", r"centre[\s\-]?point", True,  "centre-point.jpg"),
 ("lm-vihara","Maha Vihara Maitreya Medan Cemara Asri", r"maitreya", True, "maha-vihara-maitreya.jpg"),
 ("lm-zoo","Medan Zoo kebun binatang", r"(medan\s*zoo|kebun\s*binatang\s*medan)", True, "kebun-binatang.jpg"),
]

# tolak kota/negara lain — ini yang kemarin lolos
TOLAK = re.compile(r"(kinabalu|sabah|sarawak|malaysia|batam|riau|singapor|singapur|jakarta|bandung|surabaya|semarang|yogyakarta|jogja|palembang|pekanbaru|padang|aceh|bali|denpasar|makassar|manado|balikpapan|samarinda|pontianak|banjarmasin|jambi|bengkulu|lampung|duta)", re.I)

def get(url, binary=False, tries=3):
    for k in range(tries):
        try:
            r = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=40)
            return r.read() if binary else json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            if e.code in (429,503) and k < tries-1:
                w=45*(k+1); print(f"   ⏳ {e.code}, tunggu {w}s"); time.sleep(w); continue
            return None
        except Exception:
            if k < tries-1: time.sleep(6); continue
            return None

def commons(hint, wajib, harus_medan):
    q = urllib.parse.urlencode({"action":"query","list":"search","srsearch":hint,
                                "srnamespace":6,"srlimit":15,"format":"json"})
    d = get("https://commons.wikimedia.org/w/api.php?"+q); time.sleep(JEDA)
    if not d: return None
    for h in d.get("query",{}).get("search",[]):
        t = h["title"]
        if not re.search(r'\.(jpe?g|png)$', t, re.I): continue
        if TOLAK.search(t):
            print(f"   🚫 ditolak (lokasi lain): {t}"); continue
        if not re.search(wajib, t, re.I):
            print(f"   🚫 ditolak (nama tak cocok): {t}"); continue
        if harus_medan and "medan" not in t.lower():
            print(f"   🚫 ditolak (tanpa 'Medan'): {t}"); continue
        q2 = urllib.parse.urlencode({"action":"query","titles":t,"prop":"imageinfo",
                                     "iiprop":"url|size","iiurlwidth":W,"format":"json"})
        info = get("https://commons.wikimedia.org/w/api.php?"+q2); time.sleep(JEDA)
        if not info: continue
        for p in info.get("query",{}).get("pages",{}).values():
            ii = (p.get("imageinfo") or [{}])[0]
            u = ii.get("thumburl") or ii.get("url")
            if u and ii.get("width",0) >= 900:
                return u, t
    return None

for lid, hint, wajib, hm, ph in TARGET:
    dst = IMG/(lid+".jpg")
    print(f"🔎 {lid}")
    r = commons(hint, wajib, hm)
    if r:
        url, title = r
        b = get(url, True)
        if b:
            tmp = IMG/(lid+".src"); tmp.write_bytes(b)
            try:
                im = Image.open(tmp).convert("RGB")
                if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
                im.save(dst,"JPEG",quality=Q,optimize=True,progressive=True)
                print(f"   ✅ {im.size} · {dst.stat().st_size//1024} KB ← {title}")
            except Exception as e: print("   ⚠️", e)
            tmp.unlink(missing_ok=True)
            time.sleep(JEDA); continue
    # gagal → balik ke placeholder tim (jujur: belum ada foto)
    src = IMG/ph
    if src.exists():
        shutil.copyfile(src, dst)
        print(f"   ↩️  balik ke placeholder tim ({ph}) — lebih baik kosong daripada salah tempat")
    else:
        print("   ❌ tidak ketemu & placeholder tim tidak ada")
