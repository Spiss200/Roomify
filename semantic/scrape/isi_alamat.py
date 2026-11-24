import json, pathlib, sys, time, urllib.request, urllib.parse
APPLY = "--apply" in sys.argv
SRC = pathlib.Path("hotels_complete.json")
data = json.loads(SRC.read_text(encoding="utf-8"))
H = data if isinstance(data, list) else data.get("hotels", [])
GEN = {"", "kota medan", "medan"}
UA = {"User-Agent": "RoomifyTubes/1.0 (tugas kuliah web semantik)"}
def reverse(lat, lon):
    q = urllib.parse.urlencode({"format":"jsonv2","lat":lat,"lon":lon,"zoom":18,"addressdetails":1})
    d = json.load(urllib.request.urlopen(urllib.request.Request("https://nominatim.openstreetmap.org/reverse?"+q, headers=UA), timeout=20))
    a = d.get("address", {})
    parts = [a.get("road"), a.get("suburb") or a.get("neighbourhood"), a.get("city") or a.get("town") or "Medan"]
    return ", ".join(p for p in parts if p) or d.get("display_name","").split(",")[0]
ubah = 0
for h in H:
    if str(h.get("address") or h.get("alamat") or "").strip().lower() not in GEN: continue
    lat, lon = h.get("latitude") or h.get("lat"), h.get("longitude") or h.get("lon")
    if not lat or not lon: continue
    try: baru = reverse(lat, lon)
    except Exception as e: print("⚠️", h.get("name"), e); continue
    print(f"{'APPLY' if APPLY else 'DRY'}: {h.get('name'):34s} -> {baru}")
    if APPLY: h["address"] = baru; h["address_source"] = "osm_reverse"; ubah += 1
    time.sleep(1.1)
if APPLY:
    if isinstance(data, dict): data["hotels"] = H
    SRC.write_text(json.dumps(data if isinstance(data,dict) else H, ensure_ascii=False, indent=2), encoding="utf-8")
print(f"✅ alamat diperbarui: {ubah}" if APPLY else "DRY RUN — tambah --apply untuk menyimpan")
