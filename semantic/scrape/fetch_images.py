import csv, json, re, glob, time, pathlib, requests

UA = {"User-Agent": "RoomifyBot/1.0 (tugas kuliah web semantik)"}
IMG_RE = re.compile(r'^https://(encrypted-tbn\d\.gstatic\.com|lh\d\.googleusercontent\.com)/')
RATING_RE = re.compile(r'^\d,\d(/5)?$')
REVIEWS_RE = re.compile(r'^\(.*\)$')
STAR_RE = re.compile(r'hotel bintang \d', re.I)
PRICE_RE = re.compile(r'^rp\s?[\d.,]+\s?(rb|jt)?$', re.I)
JUNK = ("lebih murah","malam pada","travel/search","anda belum lama","tetapkan tanggal",
        "malam ini","besok","kunjungi situs","bebas biaya","promo","lihat harga","lihat detail")

def norm(s): return re.sub(r'[^a-z0-9]', '', (s or '').lower())
def slug(s): return re.sub(r'[^a-z0-9]+', '-', s.lower()).strip('-')[:60]
def upscale(u): return re.sub(r'=w\d+-h\d+(-k)?-no$', '=w800-h600-k-no', u)

# 1) kumpulkan pasangan nama -> gambar dari semua CSV di folder INI
img_map = {}
for path in sorted(glob.glob('google-*.csv')):
    with open(path, encoding='utf-8', errors='ignore') as f:
        for row in csv.reader(f):
            name = img = None
            for cell in row:
                v = (cell or '').strip()
                if not v: continue
                low = v.lower()
                if IMG_RE.match(v):
                    if img is None and 'branding/icon' not in v: img = v
                    continue
                if v.startswith('http'): continue
                if RATING_RE.match(v) or REVIEWS_RE.match(v) or STAR_RE.search(v) or PRICE_RE.match(low): continue
                if any(j in low for j in JUNK): continue
                if ' dengan ' in low or len(v) > 45: continue
                if name is None and len(v) > 2: name = v
            if name and img:
                img_map.setdefault(norm(name), (name, img))
print(f'Pasangan nama+gambar ditemukan: {len(img_map)}')

# 2) download ke public/images/hotels (Path Laravel yang bener)
out = pathlib.Path('../../public/images/hotels')
out.mkdir(parents=True, exist_ok=True)
local = {}
for k, (name, url) in img_map.items():
    target = out / f'{slug(name)}.jpg'
    if target.exists():
        local[k] = f'/images/hotels/{target.name}'; continue
    try:
        r = requests.get(upscale(url), headers=UA, timeout=20)
        r.raise_for_status()
        if len(r.content) < 2000: raise ValueError('terlalu kecil')
        target.write_bytes(r.content)
        local[k] = f'/images/hotels/{target.name}'
        print(f'  ✅ {name}')
    except Exception as e:
        print(f'  ❌ {name}: {e}')
    time.sleep(0.4)

# 3) suntikkan ke data gabungan -> hotels_complete.json
data = json.loads(pathlib.Path('hotels_ready.json').read_text(encoding='utf-8'))
clean, got = [], 0
for h in data:
    hh = {k.strip(): (v.strip() if isinstance(v, str) else v) for k, v in h.items()}
    if isinstance(hh.get('rooms'), list):
        hh['rooms'] = [{k.strip(): (v.strip() if isinstance(v, str) else v) for k, v in r.items()} for r in hh['rooms']]
    key = norm(hh.get('name', ''))
    hh['image_url'] = local.get(key, '/images/placeholder.svg')
    got += key in local
    clean.append(hh)
pathlib.Path('hotels_complete.json').write_text(json.dumps(clean, indent=2, ensure_ascii=False), encoding='utf-8')
print(f'Hotel berfoto asli: {got} / {len(clean)} (sisanya placeholder.svg)')
print('➡️  File gabungan final: hotels_complete.json')
