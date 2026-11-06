import json, re, time, requests, difflib, shutil, pathlib

SRC = pathlib.Path("hotels_master.json")
OUT = pathlib.Path("hotels_master_clean.json")
BAK = pathlib.Path("hotels_master_backup.json")
UA  = {"User-Agent": "RoomifyBot/1.0 (tugas kuliah web semantik)"}

# ---------- Pattern "penyusup" (teks UI Google, bukan nama hotel) ----------
JUNK_RE = re.compile(
    r"(akhir pekan|minggu bisnis|malam ini|^besok$|tidak menyediakan|"
    r"^rumah \d+ kamar|^apartemen \d+|kamar mandi$|tempat tidur$|"
    r"tetapkan tanggal|^sebelumnya$|^berikutnya$|properti bebas rokok|"
    r"^ber-ac$|rental akomodasi|^dapur$|^wi-fi|^parkir gratis$|^sarapan|"
    r"^fasilitas|lebih murah dari|^kapasitas|^\d+ orang$|^\d+ m²$|"
    r"^tidak ada |^cocok untuk|^bebas asap|^elevator$|^balkon$|^gym$|"
    r"^promo|^kunjungi|belum lama|^hotel bintang \d+$)",
    re.I)

def is_junk(name):
    n = (name or "").strip()
    return len(n) < 3 or bool(JUNK_RE.search(n))

def norm(s): return re.sub(r"[^a-z0-9]", "", (s or "").lower())

def same_hotel(a, b):
    na, nb = norm(a["name"]), norm(b["name"])
    if na == nb: return True
    short, long_ = sorted([na, nb], key=len)
    name_ok = (short in long_ and len(long_) - len(short) <= 6) or \
              difflib.SequenceMatcher(None, na, nb).ratio() >= 0.9
    if not name_ok or not a.get("latitude") or not b.get("latitude"):
        return False
    return (abs(a["latitude"] - b["latitude"]) < 0.002 and
            abs(a["longitude"] - b["longitude"]) < 0.002)

def reverse_geocode(lat, lon):
    """Pin -> nama jalan (tanya OSM)"""
    try:
        r = requests.get("https://nominatim.openstreetmap.org/reverse",
            params={"format": "jsonv2", "lat": lat, "lon": lon,
                    "addressdetails": 1, "zoom": 18}, headers=UA, timeout=15)
        a = r.json().get("address", {})
        road = a.get("road") or a.get("pedestrian") or a.get("residential") or ""
        hn   = a.get("house_number", "")
        sub  = a.get("suburb") or a.get("neighbourhood") or a.get("city_district") or ""
        parts = [f"{road} No. {hn}" if hn and road else road, sub, "Medan"]
        out = ", ".join(p for p in parts if p)
        return out if out != "Medan" else None
    except Exception:
        return None

def forward_geocode(name):
    """Nama -> pin (tanya OSM)"""
    try:
        r = requests.get("https://nominatim.openstreetmap.org/search",
            params={"format": "jsonv2", "q": f"{name}, Medan, Indonesia",
                    "limit": 1}, headers=UA, timeout=15)
        hit = r.json()
        if hit: return float(hit[0]["lat"]), float(hit[0]["lon"])
    except Exception:
        pass
    return None, None

def main():
    data = json.loads(SRC.read_text(encoding="utf-8"))
    shutil.copy(SRC, BAK)                      # backup dulu, aman!

    # ---- Tahap 1: buang penyusup ----
    valid, junk = [], []
    for h in data:
        (junk if is_junk(h.get("name")) else valid).append(h)

    # ---- Tahap 1b: dedupe ----
    clean = []
    for h in valid:
        if not any(same_hotel(h, e) for e in clean):
            clean.append(h)

    print(f"Penyusup dibuang : {len(junk)}")
    print(f"Duplikat dibuang : {len(valid) - len(clean)}")
    print(f"Hotel valid      : {len(clean)}")
    print(f"Perlu koordinat  : {sum(1 for h in clean if not h.get('latitude'))}")
    print("Mulai geocoding... (jangan di-Ctrl+C, tinggal ngopi dulu ☕)")

    # ---- Tahap 2: rescue koordinat ----
    for h in clean:
        if not h.get("latitude"):
            lat, lon = forward_geocode(h["name"])
            if lat:
                h["latitude"], h["longitude"] = lat, lon
                print(f"   📍 {h['name']}")
            time.sleep(1.1)

    # ---- Tahap 3: isi alamat ----
    for h in clean:
        if h.get("latitude") and not (h.get("address") or "").strip():
            addr = reverse_geocode(h["latitude"], h["longitude"])
            if addr:
                h["address"] = addr
                print(f"   🏠 {h['name']} -> {addr}")
            time.sleep(1.1)

    # ---- Tahap 4: rapikan & simpan ----
    for h in clean:
        for k, v in h.items():
            if isinstance(v, str): h[k] = v.strip()
    for i, h in enumerate(clean, 1):
        h["id"] = f"hotel-{i:03d}"

    OUT.write_text(json.dumps(clean, indent=2, ensure_ascii=False), encoding="utf-8")
    no_coord = sum(1 for h in clean if not h.get("latitude"))
    no_addr  = sum(1 for h in clean if not (h.get("address") or "").strip())
    print("-" * 50)
    print(f"TOTAL BERSIH : {len(clean)} hotel -> {OUT}")
    print(f"Sisa tanpa koordinat : {no_coord}")
    print(f"Sisa tanpa alamat    : {no_addr}")
    print(f"Backup file kotor    : {BAK}")

if __name__ == "__main__":
    main()
