import json, pathlib, re, time, urllib.request, urllib.parse, urllib.error
from PIL import Image

IMG = pathlib.Path("../../public/images/landmarks"); IMG.mkdir(parents=True, exist_ok=True)
W, MIN_W, Q = 1600, 1100, 84
UA = {"User-Agent":"RoomifyTubes/1.0 (https://github.com/Spiss200/Roomify; tugas kuliah web semantik) Python-urllib"}
JEDA = 4.0

LM = [
 ("lm-maimun","Istana Maimun","istana-maimun.jpg","Istana Maimun"),
 ("lm-tjong","Tjong A Fie Mansion","tjong-a-fie.jpg","Tjong A Fie"),
 ("lm-mashun","Masjid Raya Al-Mashun","masjid-raya.jpg","Masjid Raya Al-Mashun"),
 ("lm-merdeka","Lapangan Merdeka","lapangan-merdeka.jpg","Lapangan Merdeka Medan"),
 ("lm-velangkanni","Graha Maria Annai Velangkanni","graha-maria.jpg","Annai Velangkanni"),
 ("lm-sunplaza","Sun Plaza Medan","sun-plaza.jpg","Sun Plaza Medan"),
 ("lm-vihara","Maha Vihara Maitreya","maha-vihara-maitreya.jpg","Vihara Maitreya"),
 ("lm-museum","Museum Rahmat","museum-rahmat.jpg","Museum Sumatera Utara"),
 ("lm-merdekawalk","Merdeka Walk","merdeka-walk.jpg","Merdeka Walk"),
 ("lm-tirtanadi","Menara Air Tirtanadi","menara-air.jpg","Menara Air Tirtanadi"),
 ("lm-centrepoint","Centre Point Mall","centre-point.jpg","Centre Point Medan"),
 ("lm-zoo","Medan Zoo","kebun-binatang.jpg","Kebun Binatang Medan"),
 ("lm-mariamman","Kuil Shri Mariamman","shri-mariamman.jpg","Kuil Shri Mariamman Medan"),
]

def get(url, binary=False, tries=4):
    for k in range(tries):
        try:
            r = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=45)
            return r.read() if binary else json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            if e.code in (429,503) and k < tries-1:
                w = 45*(k+1); print(f"   ⏳ {e.code} — tunggu {w}s"); time.sleep(w); continue
            return None
        except Exception:
            if k < tries-1: time.sleep(8); continue
            return None

def hd_url(hint):
    for lang in ("id","en"):
        q = urllib.parse.urlencode({"action":"query","list":"search","srsearch":hint,
                                    "format":"json","srlimit":5,"srnamespace":0})
        d = get(f"https://{lang}.wikipedia.org/w/api.php?"+q); time.sleep(JEDA)
        if not d: continue
        for h in d.get("query",{}).get("search",[]):
            s = get(f"https://{lang}.wikipedia.org/api/rest_v1/page/summary/"+urllib.parse.quote(h["title"].replace(" ","_")))
            time.sleep(JEDA)
            if not s: continue
            t = (s.get("thumbnail") or {}).get("source")
            if t and re.search(r'/\d+px-', t):
                return re.sub(r'/\d+px-', f'/{W}px-', t), h["title"]
            o = (s.get("originalimage") or {}).get("source")
            if o: return o, h["title"]
    return None, None

def kompres(src, dest):
    im = Image.open(src).convert("RGB")
    if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
    im.save(dest, "JPEG", quality=Q, optimize=True, progressive=True)
    return im.size

laporan = []
for lid, nama, tim, hint in LM:
    dst = IMG / (lid + ".jpg")
    kandidat = [p for p in (dst, IMG/tim) if p.exists()]
    terbaik, lebar = None, 0
    for p in kandidat:
        try:
            with Image.open(p) as im:
                if im.width > lebar: terbaik, lebar = p, im.width
        except Exception: pass

    if lebar >= MIN_W:
        ukuran = kompres(terbaik, dst)
        print(f"✅ {nama:30s} {ukuran[0]}×{ukuran[1]} · {dst.stat().st_size//1024} KB (lokal)")
        laporan.append((lid, nama, ukuran[0], dst.stat().st_size//1024, "lokal")); continue

    print(f"🔎 {nama:30s} lokal cuma {lebar}px → cari HD…")
    url, judul = hd_url(hint)
    if not url:
        print(f"   ❌ tidak ketemu di Wikipedia"); laporan.append((lid,nama,lebar,0,"GAGAL")); continue
    b = get(url, True)
    if not b:
        print(f"   ⚠️ unduh gagal"); laporan.append((lid,nama,lebar,0,"GAGAL")); continue
    tmp = IMG / (lid + ".src")
    tmp.write_bytes(b)
    try:
        ukuran = kompres(tmp, dst)
        print(f"   ✅ {ukuran[0]}×{ukuran[1]} · {dst.stat().st_size//1024} KB ← {judul}")
        laporan.append((lid, nama, ukuran[0], dst.stat().st_size//1024, "wikipedia"))
    except Exception as e:
        print("   ⚠️", e); laporan.append((lid,nama,lebar,0,"GAGAL"))
    tmp.unlink(missing_ok=True)
    time.sleep(JEDA)

print("\n═══ LAPORAN ═══")
for lid, nama, w, kb, src in laporan:
    flag = "HD ✅" if w >= MIN_W else "BURIK ⚠️"
    print(f"{flag}  {nama:30s} {w:>5}px  {kb:>4} KB  {src}")
hd = sum(1 for x in laporan if x[2] >= MIN_W)
tot = sum(x[3] for x in laporan)
print(f"\n{hd}/13 HD · total {tot} KB ({tot/1024:.1f} MB)")
