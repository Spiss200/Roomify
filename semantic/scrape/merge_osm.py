import json, math, pathlib, re, shutil
from difflib import SequenceMatcher

BASE, OSM = "hotels_complete.json", "osm_hotels.json"
STOP = r'\b(oyo|reddoorz|capital|collection|super|urbanview|dancenter|airy|hotel|wisma|penginapan|guest|house|homestay|syariah|medan|o)\b'

def clean_keys(rows):
    return [{(k.strip() if isinstance(k, str) else k): v for k, v in r.items()} for r in rows]

def norm(s):
    s = re.sub(STOP, ' ', (s or '').lower())
    return re.sub(r'[^a-z0-9]', '', s)

def meters(a, b, c, d):
    R = 6371000
    x = math.radians(d - b) * math.cos(math.radians((a + c) / 2))
    y = math.radians(c - a)
    return R * math.hypot(x, y)

def sim(a, b): return SequenceMatcher(None, a, b).ratio()

def main():
    base = clean_keys(json.loads(pathlib.Path(BASE).read_text(encoding="utf-8")))
    osm  = clean_keys(json.loads(pathlib.Path(OSM).read_text(encoding="utf-8")))
    shutil.copy(BASE, "hotels_complete.backup.json")
    print(f"🛟 Backup dibuat. Hotel lama: {len(base)}")

    idx = [(norm(h.get("name")), h.get("latitude"), h.get("longitude")) for h in base]
    added = skipped = 0
    no = len(base) + 1

    for o in osm:
        n = norm(o["name"])
        if not n: skipped += 1; continue
        dup = False
        for bn, blat, blon in idx:
            if bn and (bn == n or sim(bn, n) >= 0.85): dup = True; break
            if blat and blon and meters(o["latitude"], o["longitude"], blat, blon) < 100 and sim(bn, n) >= 0.5:
                dup = True; break
        if dup: skipped += 1; continue

        facs = []
        if str(o.get("wifi", "")).lower() in ("yes", "wlan", "free"): facs.append("WiFi")
        if str(o.get("pool", "")).lower() == "yes": facs.append("Kolam Renang")

        base.append({
            "id": f"hotel-{no:03d}", "name": o["name"], "address": o["address"],
            "description": None, "star": o.get("star"), "rating": None,
            "review_count": None, "start_price": None,
            "latitude": o["latitude"], "longitude": o["longitude"],
            "facilities": facs, "rooms": [], "image_url": None, "source": "osm",
        })
        idx.append((n, o["latitude"], o["longitude"]))
        no += 1; added += 1

    pathlib.Path(BASE).write_text(json.dumps(base, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"✅ Ditambah: {added} | Dilewati (duplikat): {skipped}")
    print(f"📦 Total: {len(base)} hotel")

if __name__ == "__main__":
    main()
