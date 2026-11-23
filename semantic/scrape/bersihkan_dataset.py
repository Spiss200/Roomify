#!/usr/bin/env python3
# bersihkan_dataset.py - jalankan dari folder semantic/scrape
# 1) isi 3 hotel dari Booking  2) buang hotel yang benar-benar kosong dari hotels_complete.json
# Default = DRY RUN. Simpan: --apply | lewati unduh foto: --tanpa-foto
import json, pathlib, shutil, sys, urllib.request
APPLY = "--apply" in sys.argv; TANPA_FOTO = "--tanpa-foto" in sys.argv
F_JSON = pathlib.Path("hotels_complete.json"); IMGD = pathlib.Path("../../public/images/hotels/_csv")
UA = {"User-Agent": "Mozilla/5.0 (X11; Linux x86_64) RoomifyTubes/1.0"}
NAMA = ["Aero Hotel", "Airy Hotel Medan Petisah Waringin", "Amelia Guest House", "Blue Hostel Pandau Hulu I", "Bua Guesthouse", "Bukit Toedjoeh Residence", "D'Prima Hotel Stasiun Kereta Api Medan", "Dazhong Backpacker's hostel", "Deal Guest House", "Depari Hotel Sei Sikambing B", "Garuda Citra Hotel", "GM House", "Guest House Michael & Agnesh Babura", "Hotel Alam Indah", "Hotel Anita", "Hotel Binjai", "Hotel Cempaka Medan", "Hotel Cherry Garden", "Hotel Delta", "hotel garuda plaza", "Hotel Gintas Tanjung Sari", "Hotel Hawaii", "Hotel Istana", "Hotel Lonari Medan", "Hotel Maharani", "Hotel Melati", "Hotel Mulia Tiza", "Hotel Petisah", "Hotel Sehati", "Hotel Semarak", "Hotel Soechi International", "Hotel Suani", "Hotel Sumatera", "Hotel Syariah Al Jayri,", "Hotel Transit Syariah", "Hotel Wisma Yuli", "Jabutta Guest House", "K77 Guest House", "Kanasha Hotel", "KNO hotel", "Lewi House", "Medan City Homestay", "Medan Hostel", "Mind Hostel", "Moumou Hostel", "My studio Hotel", "Novena Home Stay", "OYO 1009 Hotel Bumi Malaya", "OYO 1030 Hotel Buni Asih", "OYO 1183 Papakoel", "OYO 1371 Carofin Guest House", "OYO 1377 Os Residence", "OYO 1457 Tmj Guest House", "OYO 1940 Cendana Homestay Syariah", "OYO 2345 HOMESTAY JAYA", "OYO 359 The Executive Inn", "OYO 436 Raz Residence", "OYO 447 Hutasimo Residence", "OYO 623 MMTC Guest House", "OYO 687 Residence Hotel Syariah", "OYO 692 Hotel Graha Buana", "OYO 723 Penginapan Nia Mandiri Syariah", "OYO 741 Hotel Labuhan Raya", "OYO 839 Royal Guest House", "OYO Festive Inn", "OYO WBS Residence", "Pardede Hotel", "Prime Inn", "RedDoorz De Natio Cottage", "Reddoorz Near Jalan Gatot Subroto Medan No.29A", "Reddoorz Plus near Kualanamu Airport", "Reddoorz Premium De Natio", "RedDoorz Sumarsono Inn Helvetia", "Reddoorz Tritura Inn Suka Maju", "Royal Perintis Hotel", "Serena Anggrek Syariah Hotel Sei Sikambing B", "Siba Island Resort", "Sinurat Residence - Premium Kost", "T HOTEL KUALANAMU", "The Green Alam Indah Hotel", "The Heroes Hotel", "Tiara Hotel & Convention Centre", "Vivo Hotel", "Wing Hotel", "Wisma Syailendra", "Wisma Tiga Raja", "Zen hostel", "Amaliun Hotel", "Dhaksina Hotel", "Grand Nusantara Hotel", "Grand Sirao Hotel", "Hanlis Hotel", "Hotel Zakia", "Wisma Sudirman", "Amir Hamza 123", "Anara Sky Kualanamu", "Aresidencia Sei Putih Baru", "Bpk. Dodi", "Cambridge Condominium", "Citi International Palang Merah", "Danau Toba International Cottage Belawan", "Dharma Deli", "Gereja Katolik Santo Mikael Medan Tembung", "Grand Sri Intan", "Grand Swiss-Bel", "King Palace Medan", "Merak Bestari", "Miyana", "Perumahan Pertamina no 6H", "Prudential", "PURI STREET", "Red Doorz STM Medan Sitirejo II", "Romeah 28"]
ISI = [{"nama": "Dazhong Backpacker's hostel", "harga": 315000, "rating10": 7.9, "ulasan": 59, "tipe": "Kamar Double Besar", "foto": "https://cf.bstatic.com/xdata/images/hotel/square600/907774156.webp?k=30390e39da1431e28d44157d7327de7652a1efe18aa8a184626f6f95a254bcd1&o="}, {"nama": "OYO Festive Inn", "harga": 152700, "rating10": 3.5, "ulasan": 28, "tipe": "1 double besar", "foto": "https://cf.bstatic.com/xdata/images/hotel/square600/246912422.webp?k=3ec32408b72afb9066a42ec441bd1321a3d616c87e812768cd32cdbd14d4abd7&o="}, {"nama": "Reddoorz Plus near Kualanamu Airport", "harga": 281600, "rating10": 4.5, "ulasan": 55, "tipe": "1 double besar", "foto": "https://cf.bstatic.com/xdata/images/hotel/square600/222598228.webp?k=df85cec6e527981f0ded650f2a0ac9dfdef87334511e5e7fc2b469bc024670e2&o="}]
if not F_JSON.exists(): sys.exit("File hotels_complete.json tidak ada di folder ini")
hotels = [{(k.strip() if isinstance(k, str) else k): v for k, v in h.items()} for h in json.loads(F_JSON.read_text(encoding="utf-8"))]
awal = len(hotels); key = lambda s: " ".join(str(s or "").lower().split())
target = {key(n) for n in NAMA}; by_name = {}
for h in hotels: by_name.setdefault(key(h.get("name")), []).append(h)
kosong = lambda h: not (h.get("start_price") or h.get("image_url") or h.get("rooms") or h.get("images"))
ada = [h["rating"] for h in hotels if isinstance(h.get("rating"), (int, float))]
skala5 = bool(ada) and max(ada) <= 5.0
print(f"{awal} hotel dibaca | skala rating dataset: {'0-5 (rating Booking dibagi 2)' if skala5 else '0-10'}")
def unduh(url, hid):
    if TANPA_FOTO or not url: return None
    try:
        from PIL import Image
        IMGD.mkdir(parents=True, exist_ok=True)
        b = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=30).read()
        tmp, dst = IMGD / (hid + ".src"), IMGD / (hid + ".jpg"); tmp.write_bytes(b)
        Image.open(tmp).convert("RGB").save(dst, "JPEG", quality=84, optimize=True, progressive=True); tmp.unlink(missing_ok=True)
        return "/images/hotels/_csv/" + dst.name
    except Exception as e:
        print(f"   foto gagal diunduh ({e})"); return None
print("\n== ISI dari Booking ==")
diisi = 0
for r in ISI:
    c = by_name.get(key(r["nama"]), [])
    if len(c) != 1: print(f"!! {r['nama']}: ditemukan {len(c)}x di JSON, dilewati"); continue
    h = c[0]; h["start_price"] = r["harga"]
    if r["rating10"] is not None: h["rating"] = round(r["rating10"] / 2, 1) if skala5 else r["rating10"]
    if r["ulasan"] is not None: h["review_count"] = r["ulasan"]
    h["rooms"] = [{"room_type": r["tipe"], "price": r["harga"], "max_guests": 2, "features": []}]
    if not h.get("image_url"):
        u = unduh(r["foto"], str(h.get("id") or key(h["name"]).replace(" ", "-")))
        if u: h["image_url"] = u
    h["source"] = "osm+booking"; diisi += 1
    print(f"OK {h['name'][:38]:40s} Rp{r['harga']:>9,}  rating {h.get('rating')}  foto {'ya' if h.get('image_url') else 'belum'}")
print("\n== BUANG hotel yang tetap kosong ==")
buang = [h for h in hotels if key(h.get("name")) in target and kosong(h)]; ib = {id(h) for h in buang}
for h in buang[:8]: print("buang:", h.get("name"))
if len(buang) > 8: print(f"   ... dan {len(buang) - 8} lainnya")
hasil = [h for h in hotels if id(h) not in ib]
print(f"\nawal {awal} -> diisi {diisi} -> dibuang {len(buang)} -> sisa {len(hasil)} hotel  (harus: diisi 3, dibuang 103, sisa 209)")
if APPLY:
    shutil.copy(F_JSON, "hotels_complete.SEBELUM_BERSIH.json")
    F_JSON.write_text(json.dumps(hasil, ensure_ascii=False, indent=2), encoding="utf-8")
    print("Tersimpan. Cadangan: hotels_complete.SEBELUM_BERSIH.json -> lanjut: python3 build_rdf.py")
else:
    print("DRY RUN - belum disimpan. Kalau angkanya cocok: python3 bersihkan_dataset.py --apply")
