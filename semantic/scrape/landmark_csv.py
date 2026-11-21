import csv, glob, json, pathlib, re, time, urllib.request
from PIL import Image

IMG = pathlib.Path("../../public/images/landmarks"); IMG.mkdir(parents=True, exist_ok=True)
UA = {"User-Agent": "Mozilla/5.0 (X11; Linux x86_64) RoomifyTubes/1.0"}
W, Q = 1600, 84

TARGET = [
 ("lm-maimun","Istana Maimun",3.5793,98.6820,"Ikon Utama","Jl. Brigjend Katamso, Medan Maimun","istana maimun"),
 ("lm-mashun","Masjid Raya Al-Mashun",3.5786,98.6812,"Wisata Religi","Jl. Sisingamangaraja, Medan Kota","al-mashun"),
 ("lm-tjong","Tjong A Fie Mansion",3.5920,98.6755,"Cagar Budaya","Jl. Ahmad Yani No. 105, Kesawan","tjong a fie"),
 ("lm-merdeka","Lapangan Merdeka",3.5928,98.6748,"KM 0 Medan","Jl. Balai Kota, Kesawan",None),
 ("lm-merdekawalk","Merdeka Walk",3.5924,98.6762,"Kuliner Heritage","Jl. Pos No. 1, Medan Barat","merdeka walk"),
 ("lm-tirtanadi","Menara Air Tirtanadi",3.5889,98.6788,"Kolonial 1908","Jl. S.M. Raja No. 1, Medan Kota",None),
 ("lm-mariamman","Kuil Shri Mariamman",3.5896,98.6784,"Kampung Madras","Jl. Teuku Umar, Kampung Madras","shri mariamman"),
 ("lm-velangkanni","Graha Maria Annai Velangkanni",3.5536,98.6229,"Indo-Mogul","Jl. Sakura III, Tanjung Selamat","velangkanni"),
 ("lm-vihara","Maha Vihara Maitreya",3.5424,98.7106,"Vihara Megah","Cemara Asri, Medan Estate",None),
 ("lm-sunplaza","Sun Plaza Medan",3.5836,98.6684,"Mall & Lifestyle","Jl. Zainul Arifin, Madras Hulu",None),
 ("lm-centrepoint","Centre Point Mall",3.5910,98.6782,"Railink KA","Jl. Jawa No. 8, Gang Buntu",None),
 ("lm-zoo","Medan Zoo",3.5290,98.7220,"Wisata Alam","Simalingkar, Medan Tuntungan",None),
 ("lm-rahmat","Rahmat Wildlife Museum",3.5872,98.6650,"Galeri Satwa","Jl. S. Parman No. 409, Medan","rahmat"),
 ("lm-museumsumut","Museum Negeri Sumatera Utara",3.5787,98.6874,"Museum","Jl. H.M. Joni, Teladan","museum negeri"),
 ("lm-gunungtimur","Vihara Gunung Timur",3.5878,98.6798,"Wisata Religi","Jl. Hang Tuah, Madras Hulu","gunung timur"),
 ("lm-meriampungtung","Meriam Puntung",3.5794,98.6819,"Museum Pusaka","Kompleks Istana Maimun","meriam puntung"),
 ("lm-sib","Bundaran SIB / Majestik",3.5905,98.6772,"Tengara Budaya","Jl. Balai Kota, Medan Barat","bundaran sib"),
 ("lm-cadika","Taman Cadika",3.5661,98.6633,"Ruang Hijau","Jl. Karya Wisata, Pangkalan Masyhur","cadika"),
 ("lm-alosmani","Masjid Raya Al-Osmani",3.6607,98.6743,"Wisata Religi","Medan Labuhan","al-osmani"),
 ("lm-musperin","Museum Perkebunan Indonesia",3.5921,98.6776,"Museum Sejarah","Jl. Ahmad Yani, Kesawan","perkebunan"),
 ("lm-posbloc","Pos Bloc Medan",3.5926,98.6767,"Ruang Kreatif","Jl. Pos No. 1, Kesawan","pos bloc"),
 ("lm-buaya","Penangkaran Buaya Asam Kumbang",3.5397,98.6386,"Wisata Alam","Asam Kumbang, Medan Selayang","buaya"),
 ("lm-pubbarama","Pubbarama Buddhist Centre",3.5289,98.6103,"Wisata Religi","Simalingkar, Medan Tuntungan","pubbarama"),
 ("lm-titi","Titi Gantung Kesawan",3.5928,98.6779,"Heritage","Jl. Stasiun, Medan Barat","titi gantung"),
]

def angka(s):
    s = str(s or "").strip().strip("()")
    m = re.match(r"([\d.,]+)\s*(rb|jt)?", s)
    if not m: return None
    v = float(m.group(1).replace(".", "").replace(",", "."))
    return int(v * {"rb": 1000, "jt": 1000000}.get(m.group(2), 1))

# gabung SEMUA csv google (batch hotel ikut terbaca tapi tidak akan cocok TARGET)
kandidat = sorted(set(
    glob.glob("google*.csv")
    + glob.glob(str(pathlib.Path.home()/"Downloads"/"google*.csv"))
    + glob.glob(str(pathlib.Path.home()/"Downloads"/"**"/"google*.csv"), recursive=True)
))
if not kandidat: raise SystemExit("❌ Tidak ada google*.csv.")
rows = []
print("📂 CSV dibaca:")
for f in kandidat:
    n = 0
    try:
        for r in list(csv.reader(open(f, encoding="utf-8-sig", errors="ignore")))[1:]:
            if len(r) < 2 or not r[0].strip().startswith("http"): continue
            r = [c.strip() for c in (r + [""]*9)[:9]]
            rows.append({"img": r[0], "nama": r[1], "rating": r[2], "ulasan": r[3],
                         "status": r[4], "kat": r[6], "tiket": r[7], "jarak": r[8]})
            n += 1
    except Exception as e:
        print(f"   ⚠️ {pathlib.Path(f).name}: {e}"); continue
    print(f"   {pathlib.Path(f).name}: {n} baris")
if not rows: raise SystemExit("❌ Semua CSV nol baris valid.")
print(f"\n📄 total {len(rows)} baris gabungan\n")

def cari(kunci):
    if not kunci: return None
    k = [r for r in rows if kunci in r["nama"].lower()]
    if not k: return None
    def skor(r):
        n = r["nama"].lower()
        tepat = 2 if n == kunci else (1 if n.startswith(kunci) else 0)
        return (tepat, angka(r["ulasan"]) or 0)
    return max(k, key=skor)

def unduh(url, dest):
    hd = re.sub(r"=s\d+.*$", "=s" + str(W), url)
    try:
        b = urllib.request.urlopen(urllib.request.Request(hd, headers=UA), timeout=30).read()
        tmp = dest.with_suffix(".src"); tmp.write_bytes(b)
        im = Image.open(tmp).convert("RGB")
        if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
        im.save(dest, "JPEG", quality=Q, optimize=True, progressive=True)
        tmp.unlink(missing_ok=True)
        return im.width
    except Exception as e:
        print("   ⚠️ unduh gagal:", e); return 0

out, rdf, layak, gagal = [], [], 0, []
for lid, nama, lat, lon, kat, addr, kunci in TARGET:
    dst = IMG/(lid+".jpg"); r = cari(kunci)
    rating = ulasan = tiket = None; kategori = kat
    if r:
        rating = float((r["rating"] or "0").replace(",", ".") or 0) or None
        ulasan = angka(r["ulasan"]); kategori = r["kat"] or kat
        tiket = r["tiket"] or None
    lebar = 0
    if dst.exists():
        try:
            with Image.open(dst) as im: lebar = im.width
        except Exception: lebar = 0
    if lebar >= 1100:
        print(f"⏭  {nama:34s} {lebar}px sudah HD")
    elif r:
        baru = unduh(r["img"], dst)
        if baru:
            lebar = baru
            print(f"✅ {nama:34s} {lebar}px · {dst.stat().st_size//1024} KB")
        else:
            gagal.append(nama); print(f"⚠️  {nama:34s} unduh gagal")
    else:
        print(f"➖ {nama:34s} {lebar}px (tak ada di CSV)")
    if lebar >= 700: layak += 1
    path = f"/images/landmarks/{lid}.jpg" if dst.exists() else None
    out.append(dict(id=lid, name=nama, lat=lat, lon=lon, category=kategori, address=addr,
                    image=path, rating=rating, reviews=ulasan, ticket=tiket, source="google_maps"))
    p = [f'roomify:name "{nama}"', f'roomify:latitude {lat}', f'roomify:longitude {lon}',
         f'roomify:address "{addr}"', f'roomify:category "{kategori}"',
         'roomify:landmarkInCity roomify:city-medan']
    if rating: p.append(f'roomify:rating {rating}')
    if ulasan: p.append(f'roomify:reviewCount {ulasan}')
    if path: p.append(f'roomify:photoUrl "{path}"')
    rdf.append(f"roomify:{lid} a roomify:Landmark, schema:TouristAttraction ;\n    " + " ;\n    ".join(p) + " .")
    time.sleep(.4)

pathlib.Path("landmarks.json").write_text(json.dumps(out, ensure_ascii=False, indent=2), encoding="utf-8")
pathlib.Path("../rdf/landmarks.ttl").write_text("\n\n".join(rdf) + "\n", encoding="utf-8")
print(f"\n📦 {len(out)} landmark · {layak} foto layak")
if gagal: print("❌ gagal unduh:", ", ".join(gagal))
