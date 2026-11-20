import json, pathlib, urllib.request, urllib.parse

BBOX = "3.40,98.50,3.80,98.95"   # Medan + Belawan + Kualanamu

QUERY = f"""
[out:json][timeout:120];
(
  node["tourism"~"^(hotel|guest_house|hostel|motel|apartment|chalet|resort)$"]({BBOX});
  way["tourism"~"^(hotel|guest_house|hostel|motel|apartment|chalet|resort)$"]({BBOX});
  node["building"="hotel"]({BBOX});
  way["building"="hotel"]({BBOX});
  node["leisure"="resort"]({BBOX});
  way["leisure"="resort"]({BBOX});
);
out center tags;
"""

ENDPOINTS = [
    "https://overpass-api.de/api/interpreter",
    "https://overpass.kumi.systems/api/interpreter",
]

def fetch():
    for url in ENDPOINTS:
        try:
            body = urllib.parse.urlencode({"data": QUERY}).encode()
            req = urllib.request.Request(url, data=body,
                  headers={"User-Agent": "Roomify-Tubes/1.0 (proyek kuliah)"})
            with urllib.request.urlopen(req, timeout=150) as r:
                return json.loads(r.read().decode("utf-8"))
        except Exception as e:
            print(f"⚠️  {url} gagal: {e}")
    raise SystemExit("❌ Semua endpoint gagal. Coba 10 menit lagi.")

def yes(v, ok=("yes",)):
    return str(v or "").lower() in ok

def to_int(v):
    try: return int(float(v))
    except: return None

def facilities(t):
    f = []
    if yes(t.get("internet_access"), ("yes","wlan","free","wifi")): f.append("WiFi")
    if yes(t.get("swimming_pool"), ("yes","outdoor","indoor")):     f.append("Kolam Renang")
    if yes(t.get("parking"), ("yes","surface","multi-storey","underground")): f.append("Parkir")
    if yes(t.get("breakfast"), ("yes","included","buffet")):        f.append("Sarapan")
    if yes(t.get("restaurant")):                                   f.append("Restoran")
    if yes(t.get("air_conditioning")):                             f.append("AC")
    if yes(t.get("fitness_centre")):                               f.append("Gym")
    if yes(t.get("spa")):                                          f.append("Spa")
    return f

def alamat(t):
    parts = []
    if t.get("addr:street"):
        s = t["addr:street"]
        if t.get("addr:housenumber"): s += " No. " + t["addr:housenumber"]
        parts.append(s)
    for k in ("addr:suburb", "addr:district", "addr:city"):
        if t.get(k): parts.append(t[k])
    a = ", ".join(parts) if parts else "Kota Medan"
    return a if "Medan" in a else a + ", Medan"

def main():
    raw = fetch()
    out, seen = [], set()
    for el in raw.get("elements", []):
        t = el.get("tags", {})
        name = (t.get("name") or "").strip()
        if not name: continue
        lat = el.get("lat") or el.get("center", {}).get("lat")
        lon = el.get("lon") or el.get("center", {}).get("lon")
        if lat is None or lon is None: continue
        key = (name.lower(), round(float(lat), 4), round(float(lon), 4))
        if key in seen: continue
        seen.add(key)
        out.append({
            "name": name, "address": alamat(t),
            "latitude": float(lat), "longitude": float(lon),
            "star": to_int(t.get("stars")),
            "rooms": to_int(t.get("rooms")), "beds": to_int(t.get("beds")),
            "phone": t.get("phone") or t.get("contact:phone"),
            "website": t.get("website") or t.get("contact:website"),
            "facilities": facilities(t),
            "osm_id": f"{el['type']}/{el['id']}", "source": "osm",
        })
    pathlib.Path("osm_hotels.json").write_text(
        json.dumps(out, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"✅ {len(out)} penginapan OSM (bbox diperluas) → osm_hotels.json")

if __name__ == "__main__":
    main()

