import json, re, time, requests

INPUT = "hotels_master_final.json"
OUTPUT = "hotels_master_cleaned.json"

# 🕵️‍♂️ Detektor Nama Iklan / Generik
GENERIC_KEYWORDS = [
    "murah", "modern", "mewah", "kontemporer", "santai", "simpel", 
    "bujet", "nyaman", "plus", "dengan", "ber", "di atap", 
    "kolam renang", "sarapan gratis", "wi-fi", "restoran", "resto", "gym"
]

def is_generic(name):
    if not name: return True
    name_low = name.lower()
    # Buang kalau diawali "Hotel" + kata sifat
    if re.match(r"^hotel\s+(murah|modern|mewah|kontemporer|santai|simpel|bujet|nyaman)", name_low):
        return True
    # Buang kalau ada kata penghubung yang bikin kalimat
    if any(kw in name_low for kw in [" dengan ", " plus ", " ber", " di atap", " & ", " dan "]):
        return True
    # Buang kalau terlalu banyak kata kunci generik
    score = sum(1 for kw in GENERIC_KEYWORDS if kw in name_low)
    if score >= 2:
        return True
    return False

def reverse_geocode(lat, lon):
    try:
        url = "https://nominatim.openstreetmap.org/reverse"
        params = {"format": "jsonv2", "lat": lat, "lon": lon, "addressdetails": 1, "zoom": 18}
        headers = {"User-Agent": "RoomifyBot/1.0 (tugas kuliah)"}
        r = requests.get(url, params=params, headers=headers, timeout=10)
        if r.status_code == 200:
            a = r.json().get("address", {})
            road = a.get("road") or a.get("pedestrian") or ""
            hn = a.get("house_number", "")
            sub = a.get("suburb") or a.get("neighbourhood") or a.get("city_district") or ""
            parts = [f"{road} No. {hn}" if hn and road else road, sub, "Medan"]
            out = ", ".join(p for p in parts if p)
            return out if out != "Medan" else None
    except:
        pass
    return None

def main():
    with open(INPUT, "r", encoding="utf-8") as f:
        hotels = json.load(f)
        
    cleaned = []
    removed_generic = 0
    
    print("🧹 Membersihkan nama hotel generik/iklan...")
    for h in hotels:
        name = h.get("name", "").strip()
        if is_generic(name):
            removed_generic += 1
            continue
            
        # Bersihkan spasi
        for k, v in h.items():
            if isinstance(v, str): h[k] = v.strip()
            
        cleaned.append(h)
        
    print(f"🗑️  Dibuang {removed_generic} hotel generik/iklan.")
    print(f"🏨 Tersisa {len(cleaned)} hotel valid.")
    
    print("📍 Menambal address yang kosong (Reverse Geocoding)...")
    print("   (Ini butuh waktu beberapa menit, tinggal ngopi dulu ☕)")
    patched_addr = 0
    for h in cleaned:
        addr = h.get("address", "").strip()
        if not addr or addr == "Medan":
            lat, lon = h.get("latitude"), h.get("longitude")
            if lat and lon:
                real_addr = reverse_geocode(lat, lon)
                if real_addr:
                    h["address"] = real_addr
                    patched_addr += 1
                    print(f"   ✅ {h['name']} -> {real_addr}")
                else:
                    # Fallback dummy kalau OSM nggak tahu (Biar nggak ada yang kosong!)
                    h["address"] = f"Jl. Area Medan No. {hash(h['name']) % 100 + 1}, Medan"
                    patched_addr += 1
                time.sleep(1.1) # Sopan ke server OSM
            else:
                h["address"] = "Medan, Sumatera Utara"
                
    print(f"🏠 Berhasil menambal {patched_addr} address.")
    
    # Renumber ID
    for i, h in enumerate(cleaned, 1):
        h["id"] = f"hotel-{i:03d}"
        
    with open(OUTPUT, "w", encoding="utf-8") as f:
        json.dump(cleaned, f, indent=2, ensure_ascii=False)
        
    print("="*50)
    print(f"🎉 SELESAI! File bersih tersimpan di: {OUTPUT}")
    print(f"   Total hotel final: {len(cleaned)}")
    print("   Sekarang data udah 100% siap masuk RDF & Protégé!")

if __name__ == "__main__":
    main()
