<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: http://roomify.example.org/ontology#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomify - Pencarian Hotel</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #1e293b;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255,255,255,.97);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(15,23,42,.06);
        }

        header > div {
            width: 100%;
            min-height: 82px;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        header a {
            text-decoration: none;
        }

        header a:first-child {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        header a:first-child span:first-child {
            color: #2563eb;
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        header a:first-child span:last-child {
            color: #1d4ed8;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 999px;
            padding: 6px 13px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        header nav {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        header nav a {
            color: #475569;
            font-size: 15px;
            font-weight: 700;
            padding: 12px 16px;
            border-radius: 12px;
        }

        header nav a:last-child {
            color: white;
            background: #2563eb;
        }

        header nav a:last-child:hover {
            background: #1d4ed8;
        }

        .hero {
            padding: 58px 18px 64px;
            color: white;
            background:
                linear-gradient(180deg, rgba(10,18,45,.70) 0%, rgba(10,18,45,.45) 55%, rgba(10,18,45,.35) 100%),
                url('{{ asset('images/hero-room.webp') }}') center 45% / cover no-repeat,
                #1e40af;
        }

        .hero-inner {
            max-width: 900px;
            margin: auto;
            text-align: center;
        }

        .hero h1 {
            margin: 0 0 14px;
            font-size: clamp(30px,4vw,46px);
            line-height: 1.15;
            font-weight: 800;
        }

        .hero-intro {
            margin: 0;
            color: #dbeafe;
            font-size: 17px;
            line-height: 1.6;
        }

        .search-form {
            margin-top: 30px;
            padding: 24px;
            text-align: left;
            color: #1e293b;
            background: white;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(15,23,42,.18);
        }

        .field-label {
            display: block;
            margin-bottom: 7px;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .search-form input[type="text"],
        .search-form select {
            width: 100%;
            min-height: 46px;
            padding: 10px 13px;
            color: #0f172a;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
        }

        .search-form input:focus,
        .search-form select:focus {
            outline: 2px solid #93c5fd;
            border-color: #3b82f6;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: 16px;
            margin-top: 17px;
        }

        .facility-block {
            margin-top: 18px;
        }

        .facility-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .facility-options label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            font-size: 14px;
            cursor: pointer;
        }

        .facility-options input {
            accent-color: #2563eb;
        }

        .search-button {
            width: 100%;
            margin-top: 20px;
            padding: 14px 18px;
            color: white;
            background: #2563eb;
            border: 0;
            border-radius: 11px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
        }

        .search-button:hover {
            background: #1d4ed8;
        }

        .landmark-section {
            max-width: 1200px;
            margin: auto;
            padding: 58px 24px 64px;
        }

        .section-heading {
            margin-bottom: 34px;
            text-align: center;
        }

        .section-heading h2 {
            margin: 0 0 9px;
            color: #0f172a;
            font-size: clamp(25px,3vw,34px);
            font-weight: 800;
        }

        .section-heading p {
            margin: 0;
            color: #64748b;
            font-size: 15px;
        }

        .landmark-grid {
            display: grid;
            grid-template-columns: repeat(3,minmax(0,1fr));
            gap: 24px;
        }

        .landmark-card {
            display: block;
            overflow: hidden;
            padding: 16px;
            color: inherit;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 19px;
            box-shadow: 0 2px 5px rgba(15,23,42,.05);
            text-decoration: none;
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }

        .landmark-card:hover {
            transform: translateY(-4px);
            border-color: #93c5fd;
            box-shadow: 0 12px 25px rgba(15,23,42,.12);
        }

        .landmark-image-wrap {
            width: 100%;
            height: 205px;
            margin-bottom: 16px;
            overflow: hidden;
            background: #e2e8f0;
            border-radius: 12px;
        }

        .landmark-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .landmark-card:hover .landmark-image {
            transform: scale(1.04);
        }

        .landmark-card h3 {
            margin: 0;
            color: #0f172a;
            font-size: 20px;
            line-height: 1.35;
            font-weight: 800;
        }

        .landmark-card:hover h3 {
            color: #2563eb;
        }

        .landmark-card p {
            margin: 7px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .landmark-link {
            display: inline-block;
            margin-top: 16px;
            color: #2563eb;
            font-size: 14px;
            font-weight: 700;
        }

        footer {
            padding: 27px 16px;
            color: #64748b;
            background: white;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 800px) {
            .landmark-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }

            header > div {
                padding: 12px 18px;
            }
        }

        @media (max-width: 600px) {
            header > div {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            header a:first-child span:first-child {
                font-size: 30px;
            }

            header nav {
                width: 100%;
                justify-content: space-between;
                gap: 4px;
            }

            header nav a {
                padding: 9px 10px;
                font-size: 13px;
            }

            .hero {
                padding: 42px 15px;
            }

            .search-form {
                padding: 17px;
            }

            .filter-grid,
            .landmark-grid {
                grid-template-columns: 1fr;
            }

            .landmark-section {
                padding: 42px 16px;
            }

            .landmark-image-wrap {
                height: 210px;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen">

    <!-- NAVBAR -->
    <header>
        <div>
            <a href="/">
                <span>ROOMIFY</span>
                <span>Medan</span>
            </a>

            <nav>
                <a href="#landmark-section">
                    Landmark Medan
                </a>

                <a href="{{ url('/search') }}">
                    Cari Semua Hotel &rarr;
                </a>
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-inner">

            <h1>
                Temukan Hotel Ideal di Medan
            </h1>

            <p class="hero-intro">
                Jelajahi penginapan terbaik di sekitar landmark terkenal Medan.
                Gunakan filter untuk menemukan hotel sesuai kebutuhan Anda.
            </p>

            <!-- FORM PENCARIAN -->
            <form action="{{ url('/search') }}"
                  method="GET"
                  class="search-form">

                <div>
                    <label for="q" class="field-label">
                        Cari Hotel atau Alamat
                    </label>

                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Contoh: Cambridge, JW Marriott, atau Jalan Palang Merah..."
                    >
                </div>

                <div class="filter-grid">

                    <!-- FILTER LANDMARK -->
                    <div>
                        <label for="landmark" class="field-label">
                            Dekat Landmark Medan
                        </label>

                        <select id="landmark" name="landmark">
                            <option value="">-- Pilih Semua Landmark --</option>

                            <option value="Istana Maimun">
                                Istana Maimun
                            </option>

                            <option value="Tjong A Fie Mansion">
                                Tjong A Fie Mansion
                            </option>

                            <option value="Masjid Raya Al Mashun">
                                Masjid Raya Al Mashun
                            </option>

                            <option value="Lapangan Merdeka">
                                Lapangan Merdeka
                            </option>

                            <option value="Graha Maria Annai Velangkanni">
                                Graha Maria Annai Velangkanni
                            </option>
                        </select>
                    </div>

                    <!-- FILTER BINTANG HOTEL -->
                    <div>
                        <label for="rating" class="field-label">
                            Kelas Hotel (Bintang)
                        </label>

                        <select id="rating" name="rating">
                            <option value="">Semua Bintang</option>
                            <option value="5">Bintang 5</option>
                            <option value="4">Bintang 4 atau lebih</option>
                            <option value="3">Bintang 3 atau lebih</option>
                        </select>
                    </div>

                </div>

                <!-- FILTER FASILITAS -->
                <div class="facility-block">

                    <label class="field-label">
                        Fasilitas Diinginkan
                    </label>

                    <div class="facility-options">

                        <label>
                            <input type="checkbox"
                                   name="facilities[]"
                                   value="WiFi">
                            WiFi
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="facilities[]"
                                   value="Kolam Renang">
                            Kolam Renang
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="facilities[]"
                                   value="AC">
                            AC
                        </label>

                        <label>
                            <input type="checkbox"
                                   name="facilities[]"
                                   value="Sarapan">
                            Sarapan
                        </label>

                    </div>
                </div>

                <!-- TOMBOL PENCARIAN -->
                <button type="submit" class="search-button">
                    Cari Hotel Sekarang
                </button>

            </form>
        </div>
    </section>

    <!-- SECTION LANDMARK -->
    <section id="landmark-section" class="landmark-section">

        <div class="section-heading">

            <h2>
                Jelajahi Hotel Menurut Landmark
            </h2>

            <p>
                Pilih destinasi populer di Medan untuk menemukan penginapan terdekat
            </p>

        </div>

        <div class="landmark-grid">

            <!-- LANDMARK 1: ISTANA MAIMUN -->
            <a href="{{ url('/search?landmark=Istana+Maimun') }}"
               class="landmark-card">

                <div class="landmark-image-wrap">
                    <img
                        src="{{ asset('images/landmarks/istana-maimun.jpg') }}"
                        alt="Istana Maimun Medan"
                        loading="lazy"
                        class="landmark-image"
                    >
                </div>

                <h3 property="rdfs:label">
                    Istana Maimun
                </h3>

                <p>
                    Medan Maimun, Kota Medan
                </p>

                <span class="landmark-link">
                    Lihat Hotel Terdekat &rarr;
                </span>

            </a>

            <!-- LANDMARK 2: TJONG A FIE -->
            <a href="{{ url('/search?landmark=Tjong+A+Fie+Mansion') }}"
               class="landmark-card">

                <div class="landmark-image-wrap">
                    <img
                        src="{{ asset('images/landmarks/tjong-a-fie.jpg') }}"
                        alt="Tjong A Fie Mansion Medan"
                        loading="lazy"
                        class="landmark-image"
                    >
                </div>

                <h3 property="rdfs:label">
                    Tjong A Fie Mansion
                </h3>

                <p>
                    Kesawan, Kota Medan
                </p>

                <span class="landmark-link">
                    Lihat Hotel Terdekat &rarr;
                </span>

            </a>

            <!-- LANDMARK 3: MASJID RAYA -->
            <a href="{{ url('/search?landmark=Masjid+Raya+Al+Mashun') }}"
               class="landmark-card">

                <div class="landmark-image-wrap">
                    <img
                        src="{{ asset('images/landmarks/masjid-raya.jpg') }}"
                        alt="Masjid Raya Al Mashun Medan"
                        loading="lazy"
                        class="landmark-image"
                    >
                </div>

                <h3 property="rdfs:label">
                    Masjid Raya Al Mashun
                </h3>

                <p>
                    Kota Matsum, Kota Medan
                </p>

                <span class="landmark-link">
                    Lihat Hotel Terdekat &rarr;
                </span>

            </a>

            <!-- LANDMARK 4: LAPANGAN MERDEKA -->
            <a href="{{ url('/search?landmark=Lapangan+Merdeka') }}"
               class="landmark-card">

                <div class="landmark-image-wrap">
                    <img
                        src="{{ asset('images/landmarks/lapangan-merdeka.jpg') }}"
                        alt="Lapangan Merdeka Medan"
                        loading="lazy"
                        class="landmark-image"
                    >
                </div>

                <h3 property="rdfs:label">
                    Lapangan Merdeka
                </h3>

                <p>
                    Pusat Kota, Medan Barat
                </p>

                <span class="landmark-link">
                    Lihat Hotel Terdekat &rarr;
                </span>

            </a>

            <!-- LANDMARK 5: GRAHA MARIA -->
            <a href="{{ url('/search?landmark=Graha+Maria+Annai+Velangkanni') }}"
               class="landmark-card">

                <div class="landmark-image-wrap">
                    <img
                        src="{{ asset('images/landmarks/graha-maria.jpg') }}"
                        alt="Graha Maria Annai Velangkanni Medan"
                        loading="lazy"
                        class="landmark-image"
                    >
                </div>

                <h3 property="rdfs:label">
                    Graha Maria Annai Velangkanni
                </h3>

                <p>
                    Tuntungan, Kota Medan
                </p>

                <span class="landmark-link">
                    Lihat Hotel Terdekat &rarr;
                </span>

            </a>

        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>
            © 2026 Roomify – Knowledge Graph Hotel Ontology Medan.
        </p>
    </footer>

</body>
</html>
