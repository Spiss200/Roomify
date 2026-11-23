import json, pathlib, re

SRC = pathlib.Path("hotels_master_final.json")
IMG_DIR = pathlib.Path("../../public/images/hotels")

# Baca data hotel
data = json.loads(SRC.read_text(encoding="utf-8"))
hotels = data.get("hotels", data) if isinstance(data, dict) else data

slug = lambda s: re.sub(r"[^a-z0-9]+", "-", str(s).lower()).strip("-")

# 1. Kumpulin semua "jodoh" (foto) yang ada di folder
stems = {}
if IMG_DIR.exists():
    for f in IMG_DIR.glob("*.*"):
        if f.suffix.lower() in (".jpg", ".jpeg", ".png", ".webp"):
            stems[slug(f.stem)] = f"/images/hotels/{f.name}"

print(f"📸 Total foto lokal siap jodoh: {len(stems)}")

# 2. Jodohkan!
matched = 0
for h in hotels:
    name = h.get("name") or h.get("nama") or ""
    hid = str(h.get("id", ""))
    
    # Cari jodohnya berdasarkan nama atau ID
    path = stems.get(slug(name)) or stems.get(hid)
    
    if path:
        # Suntik ke semua kemungkinan nama field foto biar Laravel & RDF pasti kebaca
        h["image_url"] = path
        h["foto"] = path
        h["photo"] = path
        h["photoUrl"] = path
        matched += 1

# 3. Simpan hasil pernikahan
if isinstance(data, dict) and "hotels" in data:
    data["hotels"] = hotels
    SRC.write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
else:
    SRC.write_text(json.dumps(hotels, ensure_ascii=False, indent=2), encoding="utf-8")

print(f"💍 BERHASIL DIKAWINKAN: {matched} hotel!")
print("Sisanya yang jomblo tinggal dikit, nanti kita urusin belakangan.")
