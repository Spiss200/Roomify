import json
import re
import math
import pathlib

# --- KONFIGURASI ---
INPUT_JSON = "hotels_complete.json"
OUTPUT_TTL = "../rdf/roomify.ttl"

# --- HELPERS ---
def slug(s):
    return re.sub(r'[^a-z0-9]+', '-', str(s).lower()).strip('-')

def esc(s):
    if s is None: return ""
    return str(s).replace('\\', '\\\\').replace('"', '\\"').replace('\n', ' ')

def dist(lat1, lon1, lat2, lon2):
    return math.sqrt((lat1-lat2)**2 + (lon1-lon2)**2)

# --- DATA STATIK (LANDMARK & CITY) ---
LANDMARKS = [
    ("lm-maimun", "Istana Maimun", 3.5793, 98.6820),
    ("lm-tjong", "Tjong A Fie Mansion", 3.5920, 98.6755),
    ("lm-mashun", "Masjid Raya Al Mashun", 3.5786, 98.6812),
    ("lm-merdeka", "Lapangan Merdeka", 3.5928, 98.6748),
    ("lm-velangkanni", "Graha Maria Annai Velangkanni", 3.5536, 98.6229)
]

# --- ONTOLOGI (SCHEMA) ---
ONTOLOGY_HEADER = """
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .
@prefix schema: <https://schema.org/> .
@prefix geo: <http://www.w3.org/2003/01/geo/wgs84_pos#> .
@prefix roomify: <https://roomify.example/ontology#> .

roomify: a owl:Ontology ;
    rdfs:label "Roomify Hotel Ontology" ;
    rdfs:comment "Ontology for Semantic Hotel Search in Medan" .

# --- CLASSES (7) ---
roomify:Hotel a owl:Class ; rdfs:subClassOf schema:Hotel ; rdfs:label "Hotel" .
roomify:Room a owl:Class ; rdfs:subClassOf schema:HotelRoom ; rdfs:label "Room" .
roomify:Facility a owl:Class ; rdfs:subClassOf schema:LocationFeatureSpecification ; rdfs:label "Facility" .
roomify:City a owl:Class ; rdfs:subClassOf schema:City ; rdfs:label "City" .
roomify:Review a owl:Class ; rdfs:subClassOf schema:Review ; rdfs:label "Review" .
roomify:Photo a owl:Class ; rdfs:subClassOf schema:ImageObject ; rdfs:label "Photo" .
roomify:Landmark a owl:Class ; rdfs:subClassOf schema:TouristAttraction ; rdfs:label "Landmark" .

# --- OBJECT PROPERTIES (13) ---
roomify:hasRoom a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Room .
roomify:roomOf a owl:ObjectProperty ; rdfs:domain roomify:Room ; rdfs:range roomify:Hotel ; owl:inverseOf roomify:hasRoom .
roomify:hasFacility a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Facility .
roomify:facilityOf a owl:ObjectProperty ; rdfs:domain roomify:Facility ; rdfs:range roomify:Hotel ; owl:inverseOf roomify:hasFacility .
roomify:hasFeature a owl:ObjectProperty ; rdfs:domain roomify:Room ; rdfs:range roomify:Facility .
roomify:locatedInCity a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:City .
roomify:cityHasHotel a owl:ObjectProperty ; rdfs:domain roomify:City ; rdfs:range roomify:Hotel ; owl:inverseOf roomify:locatedInCity .
roomify:hasReview a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Review .
roomify:reviewAbout a owl:ObjectProperty ; rdfs:domain roomify:Review ; rdfs:range roomify:Hotel ; owl:inverseOf roomify:hasReview .
roomify:hasPhoto a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Photo .
roomify:photoOf a owl:ObjectProperty ; rdfs:domain roomify:Photo ; rdfs:range roomify:Hotel ; owl:inverseOf roomify:hasPhoto .
roomify:nearLandmark a owl:ObjectProperty ; rdfs:domain roomify:Hotel ; rdfs:range roomify:Landmark .
roomify:landmarkInCity a owl:ObjectProperty ; rdfs:domain roomify:Landmark ; rdfs:range roomify:City .

# --- DATA PROPERTIES (18) ---
roomify:name a owl:DatatypeProperty ; rdfs:range xsd:string .
roomify:address a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:string .
roomify:description a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:string .
roomify:starRating a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:integer .
roomify:rating a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:decimal .
roomify:reviewCount a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:integer .
roomify:startPrice a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:integer .
roomify:pricePerNight a owl:DatatypeProperty ; rdfs:domain roomify:Room ; rdfs:range xsd:integer .
roomify:roomType a owl:DatatypeProperty ; rdfs:domain roomify:Room ; rdfs:range xsd:string .
roomify:maxGuests a owl:DatatypeProperty ; rdfs:domain roomify:Room ; rdfs:range xsd:integer .
roomify:latitude a owl:DatatypeProperty ; rdfs:range xsd:decimal ; rdfs:subPropertyOf geo:lat .
roomify:longitude a owl:DatatypeProperty ; rdfs:range xsd:decimal ; rdfs:subPropertyOf geo:long .
roomify:reviewText a owl:DatatypeProperty ; rdfs:domain roomify:Review ; rdfs:range xsd:string .
roomify:reviewerName a owl:DatatypeProperty ; rdfs:domain roomify:Review ; rdfs:range xsd:string .
roomify:reviewRating a owl:DatatypeProperty ; rdfs:domain roomify:Review ; rdfs:range xsd:decimal .
roomify:reviewDate a owl:DatatypeProperty ; rdfs:domain roomify:Review ; rdfs:range xsd:date .
roomify:photoUrl a owl:DatatypeProperty ; rdfs:domain roomify:Photo ; rdfs:range xsd:anyURI .
roomify:photoCaption a owl:DatatypeProperty ; rdfs:domain roomify:Photo ; rdfs:range xsd:string .
roomify:dataSource a owl:DatatypeProperty ; rdfs:domain roomify:Hotel ; rdfs:range xsd:string .
"""

def main():
    # Load Data
    with open(INPUT_JSON, 'r', encoding='utf-8') as f:
        hotels = json.load(f)

    out = []
    out.append(ONTOLOGY_HEADER)

    # 1. Static Resources (City & Landmarks)
    out.append('\n# --- STATIC RESOURCES ---')
    out.append('roomify:city-medan a roomify:City, schema:City ; roomify:name "Medan" .')
    
    for lid, lname, lat, lon in LANDMARKS:
        out.append(f'roomify:{lid} a roomify:Landmark, schema:TouristAttraction ;')
        out.append(f'    roomify:name "{lname}" ;')
        out.append(f'    roomify:latitude {lat} ;')
        out.append(f'    roomify:longitude {lon} ;')
        out.append(f'    roomify:landmarkInCity roomify:city-medan .')

    # 2. Collect Unique Facilities
    all_facs = set()
    for h in hotels:
        all_facs.update(h.get('facilities', []))
        for r in h.get('rooms', []):
            all_facs.update(r.get('features', []))
    
    out.append('\n# --- FACILITIES ---')
    for fac in sorted(all_facs):
        fid = slug(fac)
        out.append(f'roomify:fac-{fid} a roomify:Facility ; roomify:name "{esc(fac)}" .')

    # 3. Generate Hotels & Related Data
    out.append('\n# --- HOTELS & INSTANCES ---')
    for h in hotels:
        hid = slug(h.get('id', h['name']))
        name = h.get('name', 'Unknown')
        
        # Find Nearest Landmark
        h_lat, h_lon = h.get('latitude'), h.get('longitude')
        nearest_lm = LANDMARKS[0]
        if h_lat and h_lon:
            nearest_lm = min(LANDMARKS, key=lambda lm: dist(h_lat, h_lon, lm[2], lm[3]))
        
        # Hotel Triples
        out.append(f'\nroomify:{hid} a roomify:Hotel, schema:Hotel ;')
        out.append(f'    roomify:name "{esc(name)}" ;')
        if h.get('address'): out.append(f'    roomify:address "{esc(h["address"])}" ;')
        if h.get('description'): out.append(f'    roomify:description "{esc(h["description"])}" ;')
        if h.get('star'): out.append(f'    roomify:starRating {int(h["star"])} ;')
        if h.get('rating'): out.append(f'    roomify:rating {float(h["rating"])} ;')
        if h.get('review_count'): out.append(f'    roomify:reviewCount {int(h["review_count"])} ;')
        if h.get('start_price'): out.append(f'    roomify:startPrice {int(h["start_price"])} ;')
        if h_lat and h_lon:
            out.append(f'    roomify:latitude {h_lat} ;')
            out.append(f'    roomify:longitude {h_lon} ;')
        
        out.append(f'    roomify:dataSource "{esc(h.get("source", "google_maps"))}" ;')
        out.append(f'    roomify:locatedInCity roomify:city-medan ;')
        out.append(f'    roomify:nearLandmark roomify:{nearest_lm[0]} ;')
        
        # Link Facilities
        facs = h.get('facilities', [])
        for fac in facs:
            out.append(f'    roomify:hasFacility roomify:fac-{slug(fac)} ;')
            
        # Link Rooms, Reviews, Photos (Prepare URIs)
        rooms = h.get('rooms', [])
        for i in range(len(rooms)):
            out.append(f'    roomify:hasRoom roomify:{hid}-room{i+1} ;')
        out.append(f'    roomify:hasReview roomify:{hid}-rev1 ;')
        out.append(f'    roomify:hasPhoto roomify:{hid}-photo1 .')

        # Generate Rooms
        for i, r in enumerate(rooms):
            rid = f"{hid}-room{i+1}"
            out.append(f'\nroomify:{rid} a roomify:Room, schema:HotelRoom ;')
            out.append(f'    roomify:roomType "{esc(r.get("room_type", "Standard"))}" ;')
            if r.get('price'): out.append(f'    roomify:pricePerNight {int(r["price"])} ;')
            if r.get('max_guests'): out.append(f'    roomify:maxGuests {int(r["max_guests"])} ;')
            out.append(f'    roomify:roomOf roomify:{hid} ;')
            feats = r.get('features', [])
            for j, feat in enumerate(feats):
                prefix = " ;" if j < len(feats)-1 else " ."
                out.append(f'    roomify:hasFeature roomify:fac-{slug(feat)}{prefix}')
            if not feats:
                # Remove trailing semicolon from previous line if no features
                pass

        # Generate 1 Synthetic Review
        rev_rating = h.get('rating') or 4.0
        rev_text = f"Pengalaman menginap yang menyenangkan di {name}. Lokasi strategis dekat {nearest_lm[1]}."
        out.append(f'\nroomify:{hid}-rev1 a roomify:Review, schema:Review ;')
        out.append(f'    roomify:reviewerName "Tamu Roomify" ;')
        out.append(f'    roomify:reviewRating {float(rev_rating)} ;')
        out.append(f'    roomify:reviewDate "2026-11-01"^^xsd:date ;')
        out.append(f'    roomify:reviewText "{esc(rev_text)}" ;')
        out.append(f'    roomify:reviewAbout roomify:{hid} .')

        # Generate Photo
        img_url = h.get('image_url', '/images/placeholder.svg')
        out.append(f'\nroomify:{hid}-photo1 a roomify:Photo, schema:ImageObject ;')
        out.append(f'    roomify:photoUrl "{esc(img_url)}" ;')
        out.append(f'    roomify:photoCaption "Foto {esc(name)}" ;')
        out.append(f'    roomify:photoOf roomify:{hid} .')

    # Write File
    pathlib.Path(OUTPUT_TTL).parent.mkdir(parents=True, exist_ok=True)
    with open(OUTPUT_TTL, 'w', encoding='utf-8') as f:
        f.write('\n'.join(out))
        
    print(f"✅ SUKSES! File RDF tercipta: {OUTPUT_TTL}")
    print(f"   Total Hotel: {len(hotels)}")
    print(f"   Total Baris: {len(out)}")

if __name__ == "__main__":
    main()
