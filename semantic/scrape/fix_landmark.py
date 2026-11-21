import pathlib
from PIL import Image
IMG = pathlib.Path("../../public/images/landmarks")
MAX, CUKUP = 1600, 700

PAIR = {
 "lm-maimun":"istana-maimun.jpg", "lm-tjong":"tjong-a-fie.jpg",
 "lm-mashun":"masjid-raya.jpg", "lm-merdeka":"lapangan-merdeka.jpg",
 "lm-velangkanni":"graha-maria.jpg", "lm-sunplaza":"sun-plaza.jpg",
 "lm-vihara":"maha-vihara-maitreya.jpg", "lm-museum":"museum-rahmat.jpg",
 "lm-merdekawalk":"merdeka-walk.jpg", "lm-tirtanadi":"menara-air.jpg",
 "lm-centrepoint":"centre-point.jpg", "lm-zoo":"kebun-binatang.jpg",
 "lm-mariamman":"shri-mariamman.jpg",
}
def lebar(p):
    try:
        with Image.open(p) as im: return im.width
    except Exception: return 0

for lid, tim in PAIR.items():
    dst = IMG/(lid+".jpg")
    cand = [p for p in (dst, IMG/tim) if p.exists()]
    if not cand: print(f"❌ {lid:18s} tidak ada file"); continue
    best = max(cand, key=lebar); w = lebar(best)
    im = Image.open(best).convert("RGB")
    if im.width > MAX: im = im.resize((MAX, round(im.height*MAX/im.width)), Image.LANCZOS)
    im.save(dst, "JPEG", quality=84, optimize=True, progressive=True)
    print(f"{'✅' if w>=CUKUP else '⚠️ '} {lid:18s} {w:>5}px → {dst.stat().st_size//1024:>4} KB  ← {best.name}")
