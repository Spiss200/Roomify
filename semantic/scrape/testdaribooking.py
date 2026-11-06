import requests
from bs4 import BeautifulSoup

url = 'https://www.booking.com/searchresults.html?ss=Medan&dest_type=city&group_adults=2&no_rooms=1'
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'Accept-Language': 'en-US,en;q=0.9',
}

print("🕵️‍♂️ Lagi coba nembus pintu depan Booking.com...")
response = requests.get(url, headers=headers)

print(f"🚨 Status Code dari Server: {response.status_code}")

if response.status_code != 200:
    print("❌ WADUH! Kita diblokir / disuruh CAPTCHA sama Booking.com.")
    print("Isi HTML-nya bukan daftar hotel, tapi halaman blokir.")
else:
    soup = BeautifulSoup(response.text, 'html.parser')
    hotels = soup.findAll('div', {'data-testid': 'property-card'})
    
    if len(hotels) == 0:
        print("❌ Status 200 (OK), tapi list hotel KOSONG!")
        print("Ini karena datanya dimuat pakai JavaScript, dan requests nggak bisa baca JS.")
        print(f"Judul halaman yang kita dapat: {soup.title.text if soup.title else 'Tidak ada'}")
    else:
        print(f"✅ SUKSES! Dapat {len(hotels)} hotel.")
