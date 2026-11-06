import csv, json, re, glob, difflib, time, sys, pathlib, requests

FAC_MAP = {
    "kolam renang": "Kolam Renang", "spa": "Spa", "pusat kebugaran": "Gym",
    "wi-fi gratis": "WiFi", "wi-fi": "WiFi", "parkir gratis": "Parkir",
    "parkir": "Parkir", "ac": "AC", "sarapan gratis": "Sarapan",
    "sarapan": "Sarapan", "bak air panas": "Hot Tub",
}
JUNK = ("lebih murah dari biasanya", "malam pada", "google.com/travel/search",
        "anda belum lama ini", "tetapkan tanggal", "malam ini", "besok",
        "sebelumnya", "berikutnya", "kunjungi situs", "bebas biaya pembatalan")
RATING_RE  = re.compile(r'^\d,\d(/5)?$')
REVIEWS_RE = re.compile(r'^\(.*\)$')
STAR_RE    = re.compile(r'hotel bintang (\d)', re.I)
PRICE_RE   = re.compile(r'^rp\s?[\d.,]+\s?(rb|jt)?$', re.I)
COORD_RE   = re.compile(r'(?:!3d|@)(-?\d+\.\d+)[,!4d]*(-?\d+\.\d+)')
COORD_DM   = re.compile(r'!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)')
COORD_AT   = re.compile(r'@(-?\d+\.\d+),(-?\d+\.\d+)')

def norm(s): return re.sub(r'[^a-z0-9]', '', (s or '').lower())

def parse_price(s):
    t = s.lower().replace('rp', '').strip()
    unit = 'jt' if 'jt' in t else ('rb' if 'rb' in t else '')
    num = re.sub(r'[^\d.,]', '', t)
    if not num: return None
    if ',' in num and '.' in num: num = num.replace('.', '').replace(',', '.')
    elif ',' in num: num = num.replace(',', '.')
    elif '.' in num: num = num.replace('.', '')
    v = float(num)
    return int(v * (1000000 if unit == 'jt' else 1000 if unit == 'rb' else 1))

def parse_int(s):
    d = re.sub(r'[^\d]', '', s or '')
    return int(d) if d else None

def parse_row(row):
    r = {"name": None, "rating": None, "reviews": None, "star": None,
         "price": None, "lat": None, "lon": None, "facilities": [],
         "description": None, "sponsored": False}
    name_cands = []
    for cell in row:
        v = (cell or '').strip()
        if not v: continue
        low = v.lower()
        if any(j in low for j in JUNK) or low in ("promo", "promo terbaik"): continue
        m = COORD_DM.search(v) or COORD_AT.search(v)
        if m:
            r["lat"], r["lon"] = float(m.group(1)), float(m.group(2)); continue
        if RATING_RE.match(v):
            r["rating"] = float(v.replace("/5", "").replace(",", ".")); continue
        if REVIEWS_RE.match(v):
            r["reviews"] = parse_int(v); continue
        ms = STAR_RE.search(v)
        if ms: r["star"] = int(ms.group(1)); continue
        if PRICE_RE.match(low):
            r["price"] = parse_price(v); continue
        if low.startswith("oleh "):
            r["sponsored"] = True; continue
        if low in FAC_MAP:
            f = FAC_MAP[low]
            if f not in r["facilities"]: r["facilities"].append(f)
            continue
        if v.startswith("http"): continue
        if " dengan " in low or len(v) > 45:
            r["description"] = v; continue
        name_cands.append(v)
    if name_cands:
        r["name"] = max(name_cands, key=len) if len(name_cands) > 1 else name_cands[0]
    return r

def richness(r):
    return sum(1 for k in ("name","rating","reviews","star","price","lat","description") if r.get(k))

def merge_into(target, src):
    for k in ("rating","reviews","star","price","lat","lon","description"):
        if target.get(k) in (None, "") and src.get(k) not in (None, ""):
            target[k] = src[k]
    for f in src.get("facilities", []):
        if f not in target["facilities"]: target["facilities"].append(f)

# ---------- 1. Lahap semua CSV ----------
records, read, kept = {}, 0, 0
for path in sorted(glob.glob("google-*.csv")):
    with open(path, encoding='utf-8', errors='ignore') as f:
        for row in csv.reader(f):
            read += 1
            r = parse_row(row)
            if not r["name"] or 'closed' in r["name"].lower():
                continue
            kept += 1
            k = norm(r["name"])
            if k not in records:
                records[k] = r
            else:
                if richness(r) > richness(records[k]):
                    merge_into(r, records[k]); records[k] = r
                else:
                    merge_into(records[k], r)
print(f"Baris terbaca: {read} | Baris bernama: {kept} | Hotel unik: {len(records)}")

# ---------- 2. Rescue koordinat via Nominatim (opsional) ----------
if "--geocode" in sys.argv:
    missing = [r for r in records.values() if not r["lat"]]
    print(f"Hotel tanpa koordinat: {len(missing)} -> geocoding...")
    for r in missing:
        try:
            q = requests.get("https://nominatim.openstreetmap.org/search",
                             params={"format": "jsonv2", "q": f"{r['name']}, Medan"},
                             headers={"User-Agent": "RoomifyBot/1.0 (tugas kuliah)"}, timeout=15)
            hit = q.json()
            if hit:
                r["lat"], r["lon"] = float(hit[0]["lat"]), float(hit[0]["lon"])
                print(f"   ✅ {r['name']}")
            else:
                print(f"   ❌ {r['name']} tidak ketemu")
        except Exception as e:
            print(f"   ⚠️ {r['name']}: {e}")
        time.sleep(1.1)

# ---------- 3. Rapikan & simpan ----------
master = []
for i, r in enumerate(records.values(), 1):
    master.append({
        "id": f"gmaps-{i}", "name": r["name"], "city": "Medan",
        "address": "", "latitude": r["lat"], "longitude": r["lon"],
        "star": r["star"], "rating": r["rating"], "review_count": r["reviews"],
        "start_price": r["price"],
        "facilities": r["facilities"] or ["WiFi", "AC"],
        "description": r["description"] or f"{r['name']} adalah pilihan menginap di Medan.",
        "source": "google_maps",
    })
with open("hotels_master.json", "w", encoding='utf-8') as f:
    json.dump(master, f, indent=2, ensure_ascii=False)
no_coord = sum(1 for m in master if not m["latitude"])
no_price = sum(1 for m in master if not m["start_price"])
print(f"TOTAL MASTER: {len(master)} hotel -> hotels_master.json")
print(f"Tanpa koordinat: {no_coord} | Tanpa harga: {no_price}")
