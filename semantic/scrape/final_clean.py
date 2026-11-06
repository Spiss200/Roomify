import json, re, difflib, pathlib, random

SRC = pathlib.Path("hotels_master_cleaned.json")
OUT = pathlib.Path("hotels_ready.json")
random.seed(7)

# ---- GERBANG 1: daftar hitam frase UI / fasilitas / tombol ----
BLACK_RE = re.compile("|".join([
    r"tidak menyediakan", r"tidak ada ", r"dapur", r"tempat parkir",
    r"jemputan bandara", r"penatu", r"room service", r"cocok untuk",
    r"boleh bawa", r"ramah hewan", r"ramah lingkungan", r"ramah difabel",
    r"properti bebas", r"bebas asap", r"bebas rokok", r"sertifikasi",
    r"akhir pekan", r"minggu bisnis", r"malam ini", r"^besok$",
    r"lihat harga", r"lihat detail", r"lihat semua", r"^sebelumnya$",
    r"^berikutnya$", r"rumah \d+ kamar", r"apartemen \d+", r"apartemen ramah",
    r"\d+ orang", r"\d+ m²", r"kamar tidur", r"kamar mandi", r"tempat tidur",
    r"^elevator$", r"^balkon$", r"papan setrika", r"mesin cuci", r"tv kabel",
    r"ber-ac", r"^wi-fi", r"^sarapan", r"^parkir", r"^ac$", r"^gym$",
    r"^spa$", r"^restoran$", r"^bar$", r"^kafe$", r"kolam renang",
    r"layanan lengkap", r"hewan peliharaan", r"^hallo", r"nomor wa",
    r"tetapkan tanggal", r"kunjungi situs", r"lebih murah", r"promo",
]), re.I)

# ---- sampah class CSS / token acak (dicek hanya jika tanpa kata kunci) ----
CLASS_RE = re.compile(r"google-|\bsrc\b|symbols|[A-Za-z0-9]{4,}-[A-Za-z0-9]{4,}")
CAMEL_RE = re.compile(r"[a-z][A-Z]")

# ---- GERBANG 2a: kata kunci penginapan / brand ----
KEY_RE = re.compile(r"(hotel|motel|inn|guest|house|homestay|hostel|wisma|kost|"
                    r"residence|residance|resort|villa|apartment|apartemen|suite|"
                    r"condotel|penginapan|oyo|doorz|redpartner|fave|cordela|swiss|"
                    r"marriott|santika|harper|grand|sapadia|bobotel|airy|sans|prime|"
                    r"urbanview|collection|capital|express|super|premium|near|@)", re.I)

SMALL = {"di","yang","dan","untuk","dari","ke","@","near","by","the","of",
         "in","at","x","&","-","de","and"}

def title_case_ok(name):
    toks = [t for t in name.split() if t and t.lower() not in SMALL]
    if not toks: return False
    ok = sum(1 for t in toks if t[0].isupper() or t[0].isdigit())
    return ok >= len(toks) - 1

def is_valid_name(name):
    n = (name or "").strip()
    if len(n) < 3 or len(n.split()) < 2: return False   # "" dan "Hotel" mati di sini
    if BLACK_RE.search(n): return False                  # Gerbang 1
    has_key = bool(KEY_RE.search(n))
    if not has_key and (CLASS_RE.search(n) or CAMEL_RE.search(n)): return False
    if has_key: return True                              # Gerbang 2a
    return title_case_ok(n)                              # Gerbang 2b

def norm(s): return re.sub(r"[^a-z0-9]", "", (s or "").lower())

def same_hotel(a, b):
    na, nb = norm(a["name"]), norm(b["name"])
    if na == nb: return True
    short, long_ = sorted([na, nb], key=len)
    name_ok = (short in long_ and len(long_) - len(short) <= 6) or \
              difflib.SequenceMatcher(None, na, nb).ratio() >= 0.9
    if not name_ok or not a.get("latitude") or not b.get("latitude"): return False
    return (abs(a["latitude"]-b["latitude"]) < 0.002 and
            abs(a["longitude"]-b["longitude"]) < 0.002)

def make_rooms(h):
    star = h.get("star") or 3
    price = h.get("start_price")
    if not price:
        base = {5:1200000, 4:600000, 3:300000, 2:150000, 1:100000}[star]
        price = int(base * random.uniform(0.85, 1.15))
        h["start_price"] = price
        h["price_source"] = "estimated"
    rooms = [{"room_type": "Standard", "price": price, "max_guests": 2,
              "features": ["AC", "WiFi"]},
             {"room_type": "Deluxe", "price": int(price*1.3), "max_guests": 2,
              "features": ["AC", "WiFi", "TV"]}]
    if star >= 4:
        rooms.append({"room_type": "Suite", "price": int(price*2.0),
                      "max_guests": 4, "features": ["AC", "WiFi", "TV", "Sarapan"]})
    else:
        rooms.append({"room_type": "Family", "price": int(price*1.5),
                      "max_guests": 4, "features": ["AC", "WiFi"]})
    return rooms

def main():
    data = json.loads(SRC.read_text(encoding="utf-8"))
    killed, valid = [], []
    for h in data:
        if is_valid_name(h.get("name")): valid.append(h)
        else: killed.append((h.get("name") or "(kosong)").strip())

    clean = []
    for h in valid:
        if not any(same_hotel(h, e) for e in clean):
            clean.append(h)

    for h in clean:
        for k, v in h.items():
            if isinstance(v, str): h[k] = v.strip()
        h["rooms"] = make_rooms(h)
    for i, h in enumerate(clean, 1):
        h["id"] = f"hotel-{i:03d}"

    OUT.write_text(json.dumps(clean, indent=2, ensure_ascii=False), encoding="utf-8")
    print(f"Masuk      : {len(data)}")
    print(f"Dibunuh    : {len(killed)}  -> {killed}")
    print(f"Duplikat   : {len(valid) - len(clean)}")
    print(f"SIAP PAKAI : {len(clean)} hotel (dengan kamar) -> {OUT}")

if __name__ == "__main__":
    main()
