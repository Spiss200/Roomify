import csv
import json
import random
import pathlib

# ===============================
# KONFIGURASI ROOMIFY
# ===============================
INPUT_FILE = "hotels_medan.csv"
OUTPUT_FILE = "hotels_enriched.json"

# Daftar fasilitas yang mungkin ada
ALL_FACILITIES = ["WiFi", "AC", "TV", "Kolam Renang", "Gym", "Parkir", "Restoran", "Laundry", "Spa"]

# Keyword penentu harga & kelas
LUXURY_KEYWORDS = ["Grand", "Marriott", "Sheraton", "Aston", "Radisson", "International", "Swiss-Bel", "Aryaduta", "Hermes"]
BUDGET_KEYWORDS = ["OYO", "RedDoorz", "Hostel", "Guest House", "Homestay", "Inn", "Losmen"]

def generate_price_and_tier(name, stars):
    name_lower = name.lower()
    
    # Cek bintang dulu (kalau ada di OSM)
    if stars and str(stars).isdigit():
        s = int(stars)
        if s >= 4: return random.randint(800000, 1500000), "Luxury"
        if s == 3: return random.randint(450000, 750000), "Mid-Range"
        return random.randint(250000, 400000), "Budget"

    # Cek kata kunci nama
    if any(kw.lower() in name_lower for kw in LUXURY_KEYWORDS):
        return random.randint(900000, 1800000), "Luxury"
    if any(kw.lower() in name_lower for kw in BUDGET_KEYWORDS):
        return random.randint(150000, 350000), "Budget"
        
    # Default (Mid-Range)
    return random.randint(400000, 700000), "Mid-Range"

def generate_facilities(tier, tags):
    facs = ["WiFi", "AC"] # Wajib ada
    if tier == "Luxury":
        facs.extend(random.sample(["Kolam Renang", "Gym", "Spa", "Restoran", "Parkir"], 3))
    elif tier == "Mid-Range":
        facs.extend(random.sample(["Parkir", "Restoran", "Laundry", "TV"], 2))
    else:
        facs.extend(random.sample(["TV", "Laundry"], 1))
    
    # Cek tag OSM (misal internet_access)
    if tags.get("internet_access") in ["wlan", "yes"]:
        if "WiFi" not in facs: facs.append("WiFi")
    return list(set(facs)) # Hapus duplikat

def main():
    print(f"🎨 Roomify Makeover: Lagi mempercantik data hotel...")
    
    enriched_data = []
    
    with open(INPUT_FILE, mode='r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        for row in reader:
            # Parse tags JSON dari OSM
            try:
                tags = json.loads(row['tags_json']) if row['tags_json'] else {}
            except:
                tags = {}

            name = row['name']
            stars = row.get('stars')
            
            # 1. Generate Harga & Tier
            base_price, tier = generate_price_and_tier(name, stars)
            
            # 2. Generate Rating (Luxury cenderung lebih tinggi)
            if tier == "Luxury": rating = round(random.uniform(4.5, 4.9), 1)
            elif tier == "Mid-Range": rating = round(random.uniform(3.8, 4.4), 1)
            else: rating = round(random.uniform(3.0, 3.9), 1)

            # 3. Generate Fasilitas
            facilities = generate_facilities(tier, tags)

            # 4. Generate Kamar (Dummy)
            rooms = [
                {"type": "Standard", "price": base_price, "capacity": 2},
                {"type": "Deluxe", "price": int(base_price * 1.3), "capacity": 2},
            ]
            if tier == "Luxury":
                rooms.append({"type": "Suite", "price": int(base_price * 2.0), "capacity": 4})
            elif tier == "Budget":
                rooms.append({"type": "Family", "price": int(base_price * 1.5), "capacity": 4})

            # 5. Deskripsi Dummy
            desc = f"{name} adalah pilihan tepat untuk menginap di {row['city']}. Menawarkan fasilitas lengkap seperti {', '.join(facilities[:3])} dengan pelayanan terbaik."

            # Simpan ke objek baru
            enriched_data.append({
                "id": row['id'],
                "name": name,
                "city": row['city'],
                "address": row['address'] or f"Jl. {name} No. {random.randint(1,99)}, Medan", # Fallback alamat
                "latitude": float(row['latitude']),
                "longitude": float(row['longitude']),
                "tier": tier,
                "rating": rating,
                "start_price": base_price,
                "facilities": facilities,
                "rooms": rooms,
                "description": desc,
                "image_url": f"https://source.unsplash.com/800x600/?hotel,room,interior&sig={row['id']}" # Gambar random unik
            })

    # Simpan ke JSON
    with open(OUTPUT_FILE, 'w', encoding='utf-8') as f:
        json.dump(enriched_data, f, indent=4, ensure_ascii=False)

    print("-" * 40)
    print(f"✅ Sukses! {len(enriched_data)} hotel telah 'dimakeover'.")
    print(f"📁 File baru: {OUTPUT_FILE}")
    print("💡 Sekarang datanya udah kayak Traveloka beneran!")

if __name__ == "__main__":
    main()
