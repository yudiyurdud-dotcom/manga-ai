import requests

# Ini ibarat Laravel yang memanggil API AI kita
url = "http://127.0.0.1:5000/api/search"

# Kita berikan query dan 3 contoh sinopsis komik
data = {
    "query": "Anak laki-laki memakai topi jerami yang ingin jadi raja bajak laut",
    "synopsis_list": [
        "Seorang ninja muda yang dijauhi desanya berjuang untuk menjadi pemimpin desa bernama Hokage.",
        "Siswa SMA jenius yang menemukan buku catatan kematian milik dewa pencabut nyawa.",
        "Kisah petualangan seru seorang remaja berkekuatan karet yang mengumpulkan kru untuk mencari harta karun legendaris.",
    ],
}

print("Mengirim pertanyaan ke AI...")
response = requests.post(url, json=data)

print("\nHasil dari AI:")
print(response.json())
