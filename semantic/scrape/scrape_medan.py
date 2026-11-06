import requests
import csv
import json
import pathlib

# ===============================
# KONFIGURASI ROOMIFY
# ===============================
OVERPASS_URL = "https://lz4.overpass-api.de/api/interpreter"
HEADERS = {"User-Agent": "Roomify-SemanticBot/1.0 (Tugas Kuliah)"}
OUTPUT_FILE = "hotels_medan.csv"

# Query Overpass khusus Medan (Pakai Regex biar nangkep "Medan" & "Kota Medan")
QUERY = """
[out:json][timeout:180];
area["name"~"^(Medan|Kota Medan)$"]->.a;
(
  node["tourism"~"hotel|guest_house|hostel|motel"](area.a);
  way["tourism"~"hotel|guest_house|hostel|motel"](area.a);
  relation["tourism"~"hotel|guest_house|hostel|motel"](area.a);
);
out tags center;
"""

FIELDS = [
    "id", "name", "latitude", "longitude", "city", "address", 
    "phone", "website", "stars", "internet_access", "breakfast", "tags_json"
]

def get_lat_lon(element):
    if element.get("lat") and element.get("lon"):
        return element["lat"], element["lon"]
    center = element.get("center", {})
    return center.get("lat"), center.get("lon")

def build_address(tags):
    if tags.get("addr:full"): return tags["addr:full"]
    parts = [tags.get("addr:street"), tags.get("addr:city")]
    return ", ".join([p for p in parts if p])

def main():
    print("🤖 Roomify Bot: Lagi nyari hotel di Medan nih, tunggu bentar ya...")
    try:
        response = requests.post(OVERPASS_URL, data={"data": QUERY}, headers=HEADERS, timeout=240)
        response.raise_for_status()
    except Exception as e:
        print(f"❌ Error koneksi ke Overpass: {e}")
        return

    elements = response.json().get("elements", [])
    hotels = []

    for el in elements:
        tags = el.get("tags", {})
        name = tags.get("name") or tags.get("name:id") or tags.get("name:en")
        if not name: continue

        lat, lon = get_lat_lon(el)
        if not lat or not lon: continue

        hotels.append({
            "id": f"osm-{el.get('type')}-{el.get('id')}",
            "name": name,
            "latitude": lat,
            "longitude": lon,
            "city": "Medan",
            "address": build_address(tags),
            "phone": tags.get("phone") or tags.get("contact:phone"),
            "website": tags.get("website") or tags.get("contact:website"),
            "stars": tags.get("stars"),
            "internet_access": tags.get("internet_access"),
            "breakfast": tags.get("breakfast"),
            "tags_json": json.dumps(tags, ensure_ascii=False)
        })

    with open(OUTPUT_FILE, "w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=FIELDS)
        writer.writeheader()
        writer.writerows(hotels)

    print("-" * 40)
    print(f"✅ Sukses! Dapat {len(hotels)} hotel di Medan.")
    print(f"📁 Data disimpan di: {pathlib.Path(OUTPUT_FILE).resolve()}")

if __name__ == "__main__":
    main()
