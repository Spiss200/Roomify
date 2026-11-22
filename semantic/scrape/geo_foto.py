import json, pathlib, re, shutil, time, urllib.request, urllib.parse, urllib.error
from PIL import Image

IMG = pathlib.Path("../../public/images/landmarks")
UA = {"User-Agent":"RoomifyTubes/1.0 (https://github.com/Spiss200/Roomify; tugas kuliah web semantik) Python-urllib"}
W, Q = 1600, 84

TARGET = [
 ("lm-centrepoint", 3.5910, 98.6782, r"centre|point|mall", "centre-point.jpg"),
 ("lm-vihara",      3.5424, 98.7106, r"maitreya|vihara|cemara|buddh", "maha-vihara-maitreya.jpg"),
]
TOLAK = re.compile(r"(duta|batam|kinabalu|sabah|malaysia|riau|singapor|jakarta|bandung|surabaya|semarang|yogyakarta|palembang|pekanbaru|padang|bali|makassar)", re.I)

def get(url, binary=False, tries=2):
    for k in range(tries):
        try:
            r = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=45)
            return r.read() if binary else json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            if e.code in (429,503) and k < tries-1:
                print(f"   ⏳ {e.code}, tunggu 40s"); time.sleep(40); continue
            return None
        except Exception:
            if k < tries-1: time.sleep(5); continue
            return None

def commons_url(fn):
    return "https://commons.wikimedia.org/wiki/Special:FilePath/" + urllib.parse.quote(fn) + f"?width={W}"

def geo_commons(lat, lon, wajib):
    q = urllib.parse.urlencode({"action":"query","list":"geosearch","gscoord":f"{lat}|{lon}",
                                "gsradius":1000,"gslimit":40,"gsnamespace":6,"format":"json"})
    d = get("https://commons.wikimedia.org/w/api.php?"+q); time.sleep(3)
    if not d: return []
    out = []
    for x in d.get("query",{}).get("geosearch",[]):
        t = x["title"]
        if TOLAK.search(t) or not re.search(wajib, t, re.I): continue
        if not re.search(r"\.(jpe?g|png)$", t, re.I): continue
        out.append((commons_url(t[5:]), f"Commons geosearch · {t}"))
    return out

def geo_wikidata(lat, lon, wajib):
    sparql = f'''PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX wikibase: <http://wikiba.se/ontology#>
PREFIX bd: <http://www.bigdata.com/rdf#>
PREFIX geo: <http://www.opengis.net/ont/geosparql#>
SELECT ?itemLabel ?img ?dist WHERE {{
  SERVICE wikibase:around {{
    ?item wdt:P625 ?loc .
    bd:serviceParam wikibase:center "Point({lon} {lat})"^^geo:wktLiteral .
    bd:serviceParam wikibase:radius "1.5" .
    bd:serviceParam wikibase:distance ?dist .
  }}
  ?item wdt:P18 ?img .
  SERVICE wikibase:label {{ bd:serviceParam wikibase:language "id,en". }}
}} ORDER BY ?dist LIMIT 20'''
    d = get("https://query.wikidata.org/sparql?" + urllib.parse.urlencode({"query":sparql,"format":"json"}))
    time.sleep(3)
    if not d: return []
    out = []
    for b in d.get("results",{}).get("bindings",[]):
        lbl = b.get("itemLabel",{}).get("value","")
        if TOLAK.search(lbl) or not re.search(wajib, lbl, re.I): continue
        fn = b["img"]["value"].rsplit("/",1)[-1]
        out.append((commons_url(urllib.parse.unquote(fn)), f"Wikidata around · {lbl}"))
    return out

def foto_asli(p):
    try:
        im = Image.open(p).convert("RGB")
        c = im.getcolors(maxcolors=2_000_000)
        return im.width >= 900 and c is not None and len(c) > 20000
    except Exception: return False

for lid, lat, lon, wajib, ph in TARGET:
    dst = IMG/(lid+".jpg")
    print(f"🔎 {lid} @ {lat},{lon}")
    kandidat = geo_commons(lat, lon, wajib) or geo_wikidata(lat, lon, wajib)
    if not kandidat: print("   ❌ tidak ada foto ber-geotag di titik itu")
    ok = False
    for url, asal in kandidat:
        b = get(url, True)
        if not b: continue
        tmp = IMG/(lid+".src"); tmp.write_bytes(b)
        if not foto_asli(tmp):
            print(f"   🚫 ditolak: {asal}"); tmp.unlink(missing_ok=True); continue
        im = Image.open(tmp).convert("RGB")
        if im.width > W: im = im.resize((W, round(im.height*W/im.width)), Image.LANCZOS)
        im.save(dst,"JPEG",quality=Q,optimize=True,progressive=True)
        tmp.unlink(missing_ok=True)
        print(f"   ✅ {im.size} · {dst.stat().st_size//1024} KB ← {asal}")
        ok = True; break
    if not ok:
        s = IMG/ph
        if s.exists(): shutil.copyfile(s, dst)
        print("   ↩️  tetap placeholder")
    time.sleep(2)

