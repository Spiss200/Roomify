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
def upscale(u):
    # Ganti ukuran ke 1200x800 (HD) atau 1920x1080 (Full HD)
    return re.sub(r'=w\d+-h\d+(-k)?-no$', '=w1200-h800-k-no', u)

# 1) kumpulkan semua gambar per hotel dari CSV
hotel_images = {}  # norm(name) -> list of urls
for path in sorted(glob.glob('google-*.csv')):
    with open(path, encoding='utf-8', errors='ignore') as f:
        for row in csv.reader(f):
            name = None
            imgs = []
            for cell in row:
                v = (cell or '').strip()
                if not v: continue
                low = v.lower()
                if IMG_RE.match(v):
                    if 'branding/icon' not in v and v not in imgs:
                        imgs.append(v)
                    continue
                if v.startswith('http'): continue
                if RATING_RE.match(v) or REVIEWS_RE.match(v) or STAR_RE.search(v) or PRICE_RE.match(low): continue
                if any(j in low for j in JUNK): continue
                if ' dengan ' in low or len(v) > 45: continue
                if name is None and len(v) > 2: name = v
            if name and imgs:
                k = norm(name)
                if k not in hotel_images:
                    hotel_images[k] = imgs
                else:
                    # gabung kalau ada gambar baru
                    for img in imgs:
                        if img not in hotel_images[k]:
                            hotel_images[k].append(img)

print(f'Hotel dengan gambar ditemukan: {len(hotel_images)}')

# 2) download semua gambar ke folder per hotel
base = pathlib.Path('../../public/images/hotels')
base.mkdir(parents=True, exist_ok=True)
local_galleries = {}

for k, urls in hotel_images.items():
    folder = base / slug(k)
    folder.mkdir(exist_ok=True)
    gallery = []
    for i, url in enumerate(urls[:8], 1):  # max 8 foto per hotel biar nggak kebanyakan
        target = folder / f'{i}.jpg'
        if target.exists():
            gallery.append(f'/images/hotels/{folder.name}/{target.name}')
            continue
        try:
            r = requests.get(upscale(url), headers=UA, timeout=20)
            r.raise_for_status()
            if len(r.content) < 2000: raise ValueError('terlalu kecil')
            target.write_bytes(r.content)
            gallery.append(f'/images/hotels/{folder.name}/{target.name}')
        except Exception as e:
            print(f'  ❌ {k} foto {i}: {e}')
        time.sleep(0.3)
    if gallery:
        local_galleries[k] = gallery
        print(f'  ✅ {k}: {len(gallery)} foto')

# 3) update JSON dengan field gallery
data = json.loads(pathlib.Path('hotels_ready.json').read_text(encoding='utf-8'))
clean, got = [], 0
for h in data:
    hh = {k.strip(): (v.strip() if isinstance(v, str) else v) for k, v in h.items()}
    if isinstance(hh.get('rooms'), list):
        hh['rooms'] = [{k.strip(): (v.strip() if isinstance(v, str) else v) for k, v in r.items()} for r in hh['rooms']]
    key = norm(hh.get('name', ''))
    hh['image_url'] = local_galleries.get(key, ['/images/placeholder.svg'])[0] if local_galleries.get(key) else '/images/placeholder.svg'
    hh['gallery'] = local_galleries.get(key, ['/images/placeholder.svg'])
    got += key in local_galleries
    clean.append(hh)

pathlib.Path('hotels_complete.json').write_text(json.dumps(clean, indent=2, ensure_ascii=False), encoding='utf-8')
print(f'Hotel dengan galeri: {got} / {len(clean)}')
print('➡️  File gabungan final: hotels_complete.json')

