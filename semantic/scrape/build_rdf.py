import json, re, math, pathlib

INPUT_JSON, OUTPUT_TTL = "hotels_complete.json", "../rdf/roomify.ttl"

def slug(s): return re.sub(r'[^a-z0-9]+', '-', str(s).lower()).strip('-')
def esc(s):
    if s is None: return ""
    return str(s).replace('\\','\\\\').replace('"','\\"').replace('\n',' ').replace('\r','')
def dist(a,b,c,d): return math.sqrt((a-c)**2 + (b-d)**2)
def block(subject, preds): return subject + ' ;\n    ' + ' ;\n    '.join(preds) + ' .'

LANDMARKS = [
    ("lm-maimun","Istana Maimun",3.5793,98.6820),
    ("lm-tjong","Tjong A Fie Mansion",3.5920,98.6755),
    ("lm-mashun","Masjid Raya Al Mashun",3.5786,98.6812),
    ("lm-merdeka","Lapangan Merdeka",3.5928,98.6748),
    ("lm-velangkanni","Graha Maria Annai Velangkanni",3.5536,98.6229),
    ("lm-sunplaza","Sun Plaza",3.5857,98.6722),
    ("lm-rahmat","Museum Rahmat",3.5775,98.6623),
    ("lm-maitreya","Maha Vihara Maitreya",3.6264,98.7028),
    ("lm-merdekawalk","Merdeka Walk",3.5932,98.6758),
    ("lm-menaraair","Menara Air Tirtanadi",3.5802,98.6836),
    ("lm-centrepoint","Centre Point Mall",3.5915,98.6789),
    ("lm-medanzoo","Kebun Binatang Medan",3.5042,98.6644),
    ("lm-shrimariamman","Kuil Shri Mariamman",3.5859,98.6717),
]

ONTOLOGY_HEADER = """@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .
@prefix schema: <https://schema.org/> .
@prefix geo: <http://www.w3.org/2003/01/geo/wgs84_pos#> .
@prefix roomify: <https://roomify.example/ontology#> .

roomify: a owl:Ontology ; rdfs:label "Roomify Hotel Ontology" .

roomify:Hotel a owl:Class ; rdfs:subClassOf schema:Hotel .
roomify:Room a owl:Class ; rdfs:subClassOf schema:HotelRoom .
roomify:Facility a owl:Class ; rdfs:subClassOf schema:LocationFeatureSpecification .
roomify:City a owl:Class ; rdfs:subClassOf schema:City .
roomify:Review a owl:Class ; rdfs:subClassOf schema:Review .
roomify:Photo a owl:Class ; rdfs:subClassOf schema:ImageObject .
roomify:Landmark a owl:Class ; rdfs:subClassOf schema:TouristAttraction .

roomify:hasRoom a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Room .
roomify:roomOf a owl:ObjectProperty ; owl:inverseOf roomify:hasRoom .
roomify:hasFacility a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Facility .
roomify:facilityOf a owl:ObjectProperty ; owl:inverseOf roomify:hasFacility .
roomify:hasFeature a owl:ObjectProperty ; rdfs:domain roomify:Room ; rdfs:range roomify:Facility .
roomify:locatedInCity a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:City .
roomify:cityHasHotel a owl:ObjectProperty ; owl:inverseOf roomify:locatedInCity .
roomify:hasReview a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Review .
roomify:reviewAbout a owl:ObjectProperty ; owl:inverseOf roomify:hasReview .
roomify:hasPhoto a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Photo .
roomify:photoOf a owl:ObjectProperty ; owl:inverseOf roomify:hasPhoto .
roomify:nearLandmark a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Landmark .
roomify:landmarkInCity a owl:ObjectProperty ; rdfs:domain roomify:Landmark ; rdfs:range roomify:City .

roomify:name a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:address a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:description a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:starRating a owl:DatatypeProperty ; rdfs:range xsd:integer .
roomify:rating a owl:DatatypeProperty ; rdfs:range xsd:decimal .
roomify:reviewCount a owl:DatatypeProperty ; rdfs:range xsd:integer .
roomify:startPrice a owl:DatatypeProperty ; rdfs:range xsd:integer .
roomify:pricePerNight a owl:DatatypeProperty ; rdfs:range xsd:integer .
roomify:roomType a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:maxGuests a owl:DatatypeProperty ; rdfs:range xsd:integer .
roomify:latitude a owl:DatatypeProperty ; rdfs:subPropertyOf geo:lat .
roomify:longitude a owl:DatatypeProperty ; rdfs:subPropertyOf geo:long .
roomify:reviewText a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:reviewerName a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:reviewRating a owl:DatatypeProperty ; rdfs:range xsd:decimal .
roomify:reviewDate a owl:DatatypeProperty ; rdfs:range xsd:date .
roomify:photoUrl a owl:DatatypeProperty ; rdfs:range xsd:anyURI .
roomify:photoCaption a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:dataSource a owl:DatatypeProperty ; rdfs:range xsd:string .
"""

def main():
    hotels = json.loads(pathlib.Path(INPUT_JSON).read_text(encoding='utf-8'))
    hotels = [{(k.strip() if isinstance(k,str) else k): v for k,v in h.items()} for h in hotels]
    out = [ONTOLOGY_HEADER]

    out.append(block('roomify:city-medan a roomify:City, schema:City', ['roomify:name "Medan"']))
    for lid, lname, lat, lon in LANDMARKS:
        out.append(block(f'roomify:{lid} a roomify:Landmark, schema:TouristAttraction',
            [f'roomify:name "{esc(lname)}"', f'roomify:latitude {lat}',
             f'roomify:longitude {lon}', 'roomify:landmarkInCity roomify:city-medan']))

    facs = set()
    for h in hotels:
        facs.update(h.get('facilities') or [])
        for r in h.get('rooms') or []: facs.update(r.get('features') or [])
    for f in sorted(facs):
        out.append(block(f'roomify:fac-{slug(f)} a roomify:Facility', [f'roomify:name "{esc(f)}"']))

    n_osm = 0
    for h in hotels:
        hid  = slug(h.get('id') or h['name'])
        name = (h.get('name') or 'Unknown').strip()
        lat, lon = h.get('latitude'), h.get('longitude')
        near = min(LANDMARKS, key=lambda l: dist(lat, lon, l[2], l[3])) if lat and lon else LANDMARKS[0]
        src  = h.get('source') or 'google_maps'
        if src == 'osm': n_osm += 1

        p = [f'roomify:name "{esc(name)}"']
        if h.get('address'):     p.append(f'roomify:address "{esc(h["address"])}"')
        if h.get('description'): p.append(f'roomify:description "{esc(h["description"])}"')
        if h.get('star'):        p.append(f'roomify:starRating {int(h["star"])}')
        if h.get('rating'):      p.append(f'roomify:rating {float(h["rating"])}')
        if h.get('review_count'):p.append(f'roomify:reviewCount {int(h["review_count"])}')
        if h.get('start_price'): p.append(f'roomify:startPrice {int(h["start_price"])}')
        if lat and lon:
            p.append(f'roomify:latitude {lat}')
            p.append(f'roomify:longitude {lon}')
        p.append(f'roomify:dataSource "{esc(src)}"')
        p.append('roomify:locatedInCity roomify:city-medan')
        p.append(f'roomify:nearLandmark roomify:{near[0]}')
        p += [f'roomify:hasFacility roomify:fac-{slug(f)}' for f in (h.get('facilities') or [])]
        rooms = h.get('rooms') or []
        p += [f'roomify:hasRoom roomify:{hid}-room{i+1}' for i in range(len(rooms))]

        ada_review = h.get('rating') is not None
        ada_foto   = bool(h.get('image_url'))
        if ada_review: p.append(f'roomify:hasReview roomify:{hid}-rev1')
        if ada_foto:   p.append(f'roomify:hasPhoto roomify:{hid}-photo1')
        out.append(block(f'roomify:{hid} a roomify:Hotel, schema:Hotel', p))

        for i, r in enumerate(rooms):
            rp = [f'roomify:roomType "{esc(r.get("room_type") or "Standard")}"']
            if r.get('price'):      rp.append(f'roomify:pricePerNight {int(r["price"])}')
            if r.get('max_guests'): rp.append(f'roomify:maxGuests {int(r["max_guests"])}')
            rp.append(f'roomify:roomOf roomify:{hid}')
            rp += [f'roomify:hasFeature roomify:fac-{slug(f)}' for f in (r.get('features') or [])]
            out.append(block(f'roomify:{hid}-room{i+1} a roomify:Room, schema:HotelRoom', rp))

        if ada_review:
            out.append(block(f'roomify:{hid}-rev1 a roomify:Review, schema:Review', [
                'roomify:reviewerName "Tamu Roomify"',
                f'roomify:reviewRating {float(h["rating"])}',
                'roomify:reviewDate "2026-11-01"^^xsd:date',
                f'roomify:reviewText "{esc(f"Penginapan nyaman di {name}, dekat {near[1]}.")}"',
                f'roomify:reviewAbout roomify:{hid}']))

        if ada_foto:
            out.append(block(f'roomify:{hid}-photo1 a roomify:Photo, schema:ImageObject', [
                f'roomify:photoUrl "{esc(h["image_url"])}"',
                f'roomify:photoCaption "Foto {esc(name)}"',
                f'roomify:photoOf roomify:{hid}']))

    pathlib.Path(OUTPUT_TTL).parent.mkdir(parents=True, exist_ok=True)
    pathlib.Path(OUTPUT_TTL).write_text('\n\n'.join(out) + '\n', encoding='utf-8')
    print(f"✅ {OUTPUT_TTL} ditulis. Total {len(hotels)} hotel ({n_osm} dari OSM)")

if __name__ == "__main__":
    main()
