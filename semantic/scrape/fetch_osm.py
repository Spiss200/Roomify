import json, pathlib, urllib.request, urllib.parse

QUERY = """
[out:json][timeout:90];
(
  node["tourism"~"^(hotel|guest_house|hostel|motel)$"](3.45,98.55,3.75,98.80);
  way["tourism"~"^(hotel|guest_house|hostel|motel)$"](3.45,98.55,3.75,98.80);
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
            with urllib.request.urlopen(req, timeout=120) as r:
                return json.loads(r.read().decode("utf-8"))
        except Exception as e:
            print(f"⚠️  {url} gagal: {e}")
    raise SystemExit("❌ Semua endpoint Overpass gagal. Coba lagi 10 menit nanti.")

def to_int(v):
    try: return int(float(v))
    except: return None

def main():
    raw = fetch()
    out = []
    for el in raw.get("elements", []):
        t = el.get("tags", {})
        name = (t.get("name") or "").strip()
        if not name: continue
        lat = el.get("lat") or el.get("center", {}).get("lat")
        lon = el.get("lon") or el.get("center", {}).get("lon")
        if lat is None or lon is None: continue
        parts = [t.get("addr:street"), t.get("addr:housenumber"),
                 t.get("addr:suburb") or t.get("addr:city")]
        address = ", ".join(p for p in parts if p) or "Kota Medan"
        out.append({
            "name": name, "address": address,
            "latitude": float(lat), "longitude": float(lon),
            "star": to_int(t.get("stars")),
            "wifi": t.get("internet_access"), "pool": t.get("swimming_pool"),
            "osm_id": f"{el['type']}/{el['id']}", "source": "osm",
        })
    pathlib.Path("osm_hotels.json").write_text(
        json.dumps(out, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"✅ {len(out)} penginapan OSM → osm_hotels.json")

if __name__ == "__main__":
    main()
