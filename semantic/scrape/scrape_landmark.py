import json, pathlib, re, time, urllib.request, urllib.parse, urllib.error

IMG = pathlib.Path("../../public/images/landmarks"); IMG.mkdir(parents=True, exist_ok=True)
UA = {"User-Agent":"RoomifyTubes/1.0 (https://github.com/Spiss200/Roomify; tugas kuliah web semantik) Python-urllib"}
JEDA = 3.0

LM = [
 ("lm-maimun","Istana Maimun",3.5793,98.6820,"Ikon Utama","Istana Maimun"),
 ("lm-tjong","Tjong A Fie Mansion",3.5920,98.6755,"Cagar Budaya","Tjong A Fie"),
 ("lm-mashun","Masjid Raya Al-Mashun",3.5786,98.6812,"Wisata Religi","Masjid Raya Al-Mashun"),
 ("lm-merdeka","Lapangan Merdeka",3.5928,98.6748,"KM 0 Medan","Lapangan Merdeka Medan"),
 ("lm-velangkanni","Graha Maria Annai Velangkanni",3.5536,98.6229,"Indo-Mogul","Annai Velangkanni"),
 ("lm-sunplaza","Sun Plaza Medan",3.5836,98.6684,"Mall & Lifestyle","Sun Plaza Medan"),
 ("lm-vihara","Maha Vihara Maitreya",3.5424,98.7106,"Vihara Megah","Vihara Maitreya"),
 ("lm-museum","Museum Negeri Sumut",3.5787,98.6874,"Galeri Satwa","Museum Sumatera Utara"),
 ("lm-merdekawalk","Merdeka Walk Kesawan",3.5924,98.6762,"Kuliner Heritage","Merdeka Walk"),
 ("lm-tirtanadi","Menara Air Tirtanadi",3.5889,98.6788,"Kolonial 1908","Menara Air Tirtanadi"),
 ("lm-centrepoint","Centre Point Mall",3.5910,98.6782,"Railink KA","Centre Point Medan"),
 ("lm-zoo","Medan Zoo",3.5290,98.7220,"Wisata Alam","Kebun Binatang Medan"),
 ("lm-stasiun","Stasiun Medan (KM 0)",3.5934,98.6784,"Transit Utama","Stasiun Medan"),
]

def get(url, binary=False, tries=3):
    for k in range(tries):
        try:
            r = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=35)
            return r.read() if binary else json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            if e.code in (429,503) and k < tries-1:
                w = 40*(k+1); print(f"   ⏳ {e.code}, tunggu {w}s…"); time.sleep(w); continue
            return None
        except Exception:
            if k < tries-1: time.sleep(6); continue
            return None

def cari(hint):
    for lang in ("id","en"):
        q = urllib.parse.urlencode({"action":"query","list":"search","srsearch":hint,
                                    "format":"json","srlimit":5,"srnamespace":0})
        d = get(f"https://{lang}.wikipedia.org/w/api.php?"+q); time.sleep(JEDA)
        if not d: continue
        for h in d.get("query",{}).get("search",[]):
            t = h["title"]
            s = get(f"https://{lang}.wikipedia.org/api/rest_v1/page/summary/"+urllib.parse.quote(t.replace(" ","_")))
            time.sleep(JEDA)
            if not s: continue
            img = (s.get("thumbnail") or {}).get("source")
            if img:
                return re.sub(r'/\d+px-','/900px-',img), s.get("extract","")[:220], t
    return None,"",None

out, rdf, ok = [], [], 0
for lid, nama, lat, lon, cat, hint in LM:
    ada = next(IMG.glob(lid+".*"), None)
    if ada:
        print(f"⏭  {nama} sudah ada → {ada.name}")
        img_path, desc, canon = "/images/landmarks/"+ada.name, "", None
        ok += 1
    else:
        img, desc, canon = cari(hint)
        img_path = None
        if img:
            b = get(img, True)
            if b:
                ext = ".png" if ".png" in img.lower() else ".jpg"
                (IMG/(lid+ext)).write_bytes(b)
                img_path = "/images/landmarks/"+lid+ext; ok += 1
                print(f"✅ {nama} ← {canon}")
            else: print(f"⚠️  {nama} gagal unduh")
        else: print(f"❌ {nama} tidak ketemu")
        time.sleep(JEDA)
    out.append(dict(id=lid,name=nama,lat=lat,lon=lon,category=cat,image=img_path,desc=desc,source=canon))
    p = [f'roomify:name "{nama}"', f'roomify:latitude {lat}', f'roomify:longitude {lon}',
         'roomify:landmarkInCity roomify:city-medan']
    if img_path: p.append(f'roomify:photoUrl "{img_path}"')
    if desc: p.append(f'roomify:description "{desc.replace(chr(34),chr(39))}"')
    rdf.append(f"roomify:{lid} a roomify:Landmark, schema:TouristAttraction ;\n    "+" ;\n    ".join(p)+" .")

pathlib.Path("landmarks.json").write_text(json.dumps(out,ensure_ascii=False,indent=2),encoding="utf-8")
pathlib.Path("../rdf/landmarks.ttl").write_text("\n\n".join(rdf)+"\n",encoding="utf-8")
print(f"\n📦 {ok}/13 foto siap")
