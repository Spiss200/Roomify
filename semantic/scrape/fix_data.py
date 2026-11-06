import json
import requests
import time

INPUT_FILE = "hotels_enriched.json"
OUTPUT_FILE = "hotels_final.json"

# 🕵️‍♂️ KAMUS RAHASIA: Harga Asli dari Tiket.com/Traveloka (Buat Hotel Terkenal)
# Tambahkan di sini kalau ada hotel terkenal lain yang mau kamu benerin harganya
REAL_PRICES = {
    "grand sakura": {"Standard": 390000, "Deluxe": 450000, "Suite": 650000},
    "jw marriott": {"Standard": 1200000, "Deluxe": 1500000, "Suite": 2500000},
    "danau toba": {"Standard": 600000, "Deluxe": 750000, "Suite": 1100000},
    "aryaduta": {"Standard": 750000, "Deluxe": 950000, "Suite": 1400000},
    "grand aston": {"Standard": 650000, "Deluxe": 850000, "Suite": 1300000},
    "radisson": {"Standard": 800000, "Deluxe": 1000000, "Suite": 1500000},
    "soechi": {"Standard": 550000, "Deluxe": 700000, "Suite": 1000000},
    "marriott": {"Standard": 1200000, "Deluxe": 1500000, "Suite": 2500000},
    "swiss-belinn": {"Standard": 450000, "Deluxe": 550000, "Suite": 800000},
    "ibis styles": {"Standard": 350000, "Deluxe": 450000, "Suite": 600000}
}

def get_real_address_from_coords(lat, lon):
    """Tanya ke server OpenStreetMap (Nominatim) alamat asli dari koordinat"""
    try:
        url = f"https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={lat}&lon={lon}&addressdetails=1"
        headers = {"User-Agent": "RoomifyBot/1.0 (Tugas Kuliah Universitas)"}
        res = requests.get(url, headers=headers, timeout=10)
        if res.status_code == 200:
            data = res.json()
            addr = data.get("address", {})
            road = addr.get("road", "")
            house_number = addr.get("house_number", "")
            
            if road:
                # Format jadi "Jl. Prof. H. M. Yamin No. 41, Medan"
                return f"{road} {house_number}, Medan".strip()
    except Exception as e:
        print(f"   ⚠️ Gagal fetch alamat: {e}")
    return None

def main():
    print("🕵️‍♂️ Roomify Data Cleaner: Lagi beresin alamat & harga...")
    
    with open(INPUT_FILE, "r", encoding="utf-8") as f:
        hotels = json.load(f)

    for h in hotels:
        name_lower = h['name'].lower()
        
        # 1. DETEKSI ALAMAT PALSU (Dummy)
        # Kalau di kolom address ada nama hotelnya, atau ada kata "No." (dari random.randint)
        if name_lower in h['address'].lower() or f"Jl. {h['name']}" in h['address']:
            print(f"🔍 Alamat palsu ketemu: {h['name']}. Lagi cari alamat asli di OSM...")
            real_addr = get_real_address_from_coords(h['latitude'], h['longitude'])
            if real_addr:
                h['address'] = real_addr
                print(f"   ✅ Berubah jadi: {real_addr}")
            
            # Server Nominatim minta jeda 1 detik per request biar gak di-banned
            time.sleep(1.2) 

        # 2. SUNTIK HARGA ASLI (Buat Hotel Terkenal)
        for keyword, prices in REAL_PRICES.items():
            if keyword in name_lower:
                for room in h['rooms']:
                    if room['type'] in prices:
                        room['price'] = prices[room['type']]
                
                # Update harga terendah buat tampilan di list pencarian
                h['start_price'] = min(prices.values())
                print(f"   💰 Harga {h['name']} disesuaikan dengan Tiket.com!")
                break

    # Simpan ke file baru yang udah bersih
    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        json.dump(hotels, f, indent=4, ensure_ascii=False)

    print("-" * 40)
    print(f"🎉 Selesai! Data yang udah akurat disimpan di: {OUTPUT_FILE}")
    print("💡 File inilah yang nanti kita pakai buat masukin ke RDF & Fuseki!")

if __name__ == "__main__":
    main()
