import csv, glob, json, pathlib, re, shutil, sys, time, urllib.request
from difflib import SequenceMatcher
from PIL import Image

IMGD = pathlib.Path("../../public/images/hotels/_csv"); IMGD.mkdir(parents=True, exist_ok=True)
UA = {"User-Agent":"Mozilla/5.0 (X11; Linux x86_64) RoomifyTubes/1.0"}
W, Q = 1600, 84
APPLY = "--apply" in sys.argv

RE_IMG   = re.compile(r"^https?://\S*(googleusercontent|ggpht|gstatic)\S*", re.I)
RE_URL   = re.compile(r"^https?://", re.I)
RE_RATE  = re.compile(r"^\d[.,]\d$")
RE_ULAS  = re.compile(r"^\(\s*[\d.,]+\s*(rb|jt)?\s*\)$", re.I)
RE_HARGA = re.compile(r"(rp\s*[\d.,]+|gratis)", re.I)
RE_STAR  = re.compile(r"bintang\s*(\d)|(\d)\s*bintang|hotel\s*(\d)\s*bintang", re.I)

STOP = r'\b(oyo|reddoorz|capital|collection|super|urbanview|dancenter|airy|hotel|wisma|penginapan|guest|house|homestay|syariah|medan|o|plus|front|one|near|di|by|at)\b'
def norm(s):
    s = re.sub(STOP, ' ', str(s or '').lower())
    return re.sub(r'[^a-z0-9]', '', s)

def angka_ulasan(s):
    m = re.match(r"\(\s*([\d.,]+)\s*(rb|jt)?\s*\)", str(s or ''), re.I)
    if not m: return None
    v = float(m.group(1).replace('.', '').replace(',', '.'))
    return int(v * {'rb':1000,'jt':1000000}.get((m.group(2) or '').lower(), 1))

def angka_harga(s):
    s = str(s or '').strip()
    if not s: return None
    if 'gratis' in s.lower(): return 0
    m = re.search(r'([\d.,]+)\s*(jt|rb)?', s.replace('Rp','').replace('rp','').strip())
    if not m: return None
    v, u = m.group(1), (m.group(2) or '').lower()
    try:
        if u == 'jt': return int(float(v.replace(',', '.')) * 1_000_000)
        if u == 'rb': return int(float(v.replace(',', '.')) * 1000)
        d = re.sub(r'[^\d]', '', v)
        return int(d) if d else None
    except Exception:
        return None

def parse_baris(r):
    """Deteksi kolom dari ISI, bukan posisi. Kebal CSV hotel yang susunannya beda."""
    img = next((c for c in r if RE_IMG.match(c)), None)
    if not img: return None
    nama = None
    for c in r:
        if c is img or RE_URL.match(c): continue
        if len(c) < 3: continue
        if RE_RATE.match(c) or RE_ULAS.match(c) or RE_HARGA.search(c): continue
        if c in ('·','•','-','–'): continue
        if re.fullmatch(r'[\d.,\s]+', c): continue
        nama = c; break
    if not nama: return None
    rate = next((c for c in r if RE_RATE.match(c)), None)
    ulas = next((c for c in r if RE_ULAS.match(c)), None)
    hrg  = next((c for c in r if RE_HARGA.search(c) and c is not nama), None)
    kat  = next((c for c in r if RE_STAR.search(c)), None)
    return {"img":img, "nama":nama,
            "rating": float(rate.replace(',', '.')) if rate else None,
            "ulasan": angka_ulasan(ulas),
            "harga": angka_harga(hrg),
            "bintang": (lambda m: int(next(g for g in m.groups() if g)) if m else None)(RE_STAR.search(kat or ''))}

rows, dibuang = [], 0
for f in sorted(set(glob.glob("google*.csv"))):
    try:
        for r in list(csv.reader(open(f, encoding="utf-8-sig", errors="ignore")))[1:]:
            r = [c.strip() for c in r]
            if len(r) < 2: continue
            p = parse_baris(r)
            if p: rows.append(p)
            else: dibuang += 1
    except Exception as e:
        print(f"⚠️ {f}: {e}")

print(f"📄 {len(rows)} baris valid · {dibuang} baris dibuang (bukan data tempat)")
punya_harga = sum(1 for r in rows if r["harga"])
print(f"💰 {punya_harga} baris punya harga\n")

hotels = json.loads(pathlib.Path("hotels_complete.json").read_text(encoding="utf-8"))
hotels = [{(k.strip() if isinstance(k,str) else k): v for k,v in h.items()} for h in hotels]
shutil.copy("hotels_complete.json", "hotels_complete.SEBELUM_ISI.json")

def cari(nama):
    n = norm(nama)
    if not n: return None, 0
    best, skor = None, 0
    for r in rows:
        c = norm(r["nama"])
        if not c: continue
        s = 1.0 if c == n else SequenceMatcher(None, n, c).ratio()
        if s > skor: best, skor = r, s
    return (best, skor) if skor >= 0.88 else (None, skor)

def unduh(url, hid):
    hd = re.sub(r"=s\d+.*$", "=s"+str(W), url)
    hd = re.sub(r"=w\d+-h\d+[^=]*$", "=w"+str(W), hd)
    try:
        b = urllib.request.urlopen(urllib.request.Request(hd, headers=UA), timeout=30).read()
        dst = IMGD/(hid+".jpg"); tmp = IMGD/(hid+".src"); tmp.write_bytes(b)
        im = Image.open(tmp).convert("RGB")
        if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
        im.save(dst, "JPEG", quality=Q, optimize=True, progressive=True)
        tmp.unlink(missing_ok=True)
        return "/images/hotels/_csv/"+dst.name
    except Exception:
        return None

target = [h for h in hotels if not h.get("start_price") or not h.get("image_url")]
print(f"🎯 {len(target)} hotel kurang data\n")
cocok = 0; ada_harga = 0; gagal = []

for h in target:
    r, skor = cari(h["name"])
    if not r:
        gagal.append(h["name"]); continue
    cocok += 1
    if r["harga"]: ada_harga += 1
    tanda = "✅" if r["harga"] else "◐ "
    print(f"{tanda} {h['name'][:40]:42s} ← {r['nama'][:32]:34s} Rp{r['harga'] or '-':>9} ★{r['bintang'] or '-'} ({skor:.2f})")
    if not APPLY: continue
    if r["harga"]:
        h["start_price"] = r["harga"]
        if not h.get("rooms"):
            h["rooms"] = [{"room_type":"Standard","price":r["harga"],"max_guests":2,
                           "features":[f for f in (h.get("facilities") or []) if f in ("AC","WiFi","TV")]}]
    if r["bintang"]: h["star"] = r["bintang"]
    if r["rating"]:  h["rating"] = r["rating"]
    if r["ulasan"]:  h["review_count"] = r["ulasan"]
    if not h.get("image_url"):
        u = unduh(r["img"], h["id"])
        if u: h["image_url"] = u
    h["source"] = "osm+google_maps"
    time.sleep(.25)

print(f"\n📊 cocok {cocok}/{len(target)} · yang dapat harga: {ada_harga}")
if gagal:
    print(f"❌ tidak ketemu ({len(gagal)}): " + ", ".join(gagal[:6]) + " …")
if APPLY:
    pathlib.Path("hotels_complete.json").write_text(json.dumps(hotels, ensure_ascii=False, indent=2), encoding="utf-8")
    print("💾 tersimpan → jalankan: python3 build_rdf.py")
else:
    print("🔍 DRY RUN — belum disimpan. Cek pasangan nama di atas, kalau masuk akal:")
    print("   python3 isi_dari_csv.py --apply")
