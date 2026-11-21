import json, pathlib, shutil
IMG = pathlib.Path("../../public/images/landmarks")

LM = [
 ("lm-maimun","Istana Maimun",3.5793,98.6820,"Ikon Utama","istana-maimun.jpg","Jl. Brigjend Katamso, Medan Maimun","Istana Kesultanan Deli berlapis kuning emas, dibangun 1888."),
 ("lm-tjong","Tjong A Fie Mansion",3.5920,98.6755,"Cagar Budaya","tjong-a-fie.jpg","Jl. Ahmad Yani, Kesawan","Rumah saudagar Tjong A Fie bergaya Tionghoa-Eropa di kawasan Kesawan."),
 ("lm-mashun","Masjid Raya Al-Mashun",3.5786,98.6812,"Wisata Religi","masjid-raya.jpg","Jl. Mahkamah, Medan Kota","Masjid 1906 berkubah hitam, perpaduan arsitektur Maroko, Persia, dan India."),
 ("lm-merdeka","Lapangan Merdeka",3.5928,98.6748,"KM 0 Medan","lapangan-merdeka.jpg","Jl. Balai Kota, Kesawan","Alun-alun titik nol Kota Medan, dikelilingi bangunan kolonial."),
 ("lm-velangkanni","Graha Maria Annai Velangkanni",3.5536,98.6229,"Indo-Mogul","graha-maria.jpg","Jl. Sakura III, Tanjung Selamat","Gereja Katolik berarsitektur Indo-Mogul tujuh tingkat."),
 ("lm-sunplaza","Sun Plaza Medan",3.5836,98.6684,"Mall & Lifestyle","sun-plaza.jpg","Jl. Zainul Arifin, Madras Hulu","Mall premium dengan akses langsung Stasiun KA Bandara."),
 ("lm-vihara","Maha Vihara Maitreya",3.5424,98.7106,"Vihara Megah","maha-vihara-maitreya.jpg","Cemara Asri, Medan Estate","Salah satu vihara Buddha terbesar di Indonesia."),
 ("lm-museum","Museum Rahmat",3.5787,98.6874,"Galeri Satwa","museum-rahmat.jpg","Jl. H.M. Joni, Teladan","Museum satwa dan sejarah Sumatera Utara."),
 ("lm-merdekawalk","Merdeka Walk Kesawan",3.5924,98.6762,"Kuliner Heritage","merdeka-walk.jpg","Jl. Pos No. 1, Medan Barat","Kawasan kuliner legendaris bernuansa heritage."),
 ("lm-tirtanadi","Menara Air Tirtanadi",3.5889,98.6788,"Kolonial 1908","menara-air.jpg","Jl. S.M. Raja No. 1, Medan Kota","Menara air peninggalan Belanda 1908, simbol Kota Medan."),
 ("lm-centrepoint","Centre Point Mall",3.5910,98.6782,"Railink KA","centre-point.jpg","Jl. Jawa No. 8, Gang Buntu","Mall terpadu dengan akses Kereta Api Bandara Kualanamu."),
 ("lm-zoo","Medan Zoo",3.5290,98.7220,"Wisata Alam","kebun-binatang.jpg","Simalingkar, Medan Tuntungan","Kebun binatang 30 hektar, koleksi Harimau Sumatera."),
 ("lm-mariamman","Kuil Shri Mariamman",3.5896,98.6784,"Kampung Madras","shri-mariamman.jpg","Jl. Teuku Umar, Kampung Madras","Kuil Hindu tertua di Medan (1884), gopuram warna-warni."),
]

out, rdf, ok = [], [], 0
for lid, nama, lat, lon, cat, src, addr, desc in LM:
    s = IMG / src
    path = None
    if s.exists():
        dst = IMG / (lid + ".jpg")
        if s.resolve() != dst.resolve(): shutil.copyfile(s, dst)
        path = "/images/landmarks/" + dst.name; ok += 1
        print(f"✅ {nama:32s} ← {src} ({s.stat().st_size//1024} KB)")
    else:
        print(f"❌ {nama:32s} — {src} tidak ada")
    out.append(dict(id=lid,name=nama,lat=lat,lon=lon,category=cat,image=path,address=addr,desc=desc))
    p = [f'roomify:name "{nama}"', f'roomify:latitude {lat}', f'roomify:longitude {lon}',
         f'roomify:address "{addr}"', f'roomify:description "{desc}"',
         'roomify:landmarkInCity roomify:city-medan']
    if path: p.append(f'roomify:photoUrl "{path}"')
    rdf.append(f"roomify:{lid} a roomify:Landmark, schema:TouristAttraction ;\n    "+" ;\n    ".join(p)+" .")

pathlib.Path("landmarks.json").write_text(json.dumps(out,ensure_ascii=False,indent=2),encoding="utf-8")
pathlib.Path("../rdf/landmarks.ttl").write_text("\n\n".join(rdf)+"\n",encoding="utf-8")
print(f"\n📦 {ok}/13 siap · landmarks.json + semantic/rdf/landmarks.ttl")
