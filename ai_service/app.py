from flask import Flask, request, jsonify
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity

app = Flask(__name__)

# Memuat model AI (Menggunakan model multilingual agar paham bahasa Indonesia)
print("Sedang memuat model AI... (Proses ini butuh waktu saat pertama kali dijalankan)")
model = SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')
print("Model AI berhasil dimuat!")

@app.route('/api/search', methods=['POST'])
def semantic_search():
    try:
        # Menerima data dari Laravel (teks pencarian & daftar sinopsis komik)
        data = request.json
        query = data.get('query')
        synopsis_list = data.get('synopsis_list')

        if not query or not synopsis_list:
            return jsonify({"error": "Query dan daftar sinopsis tidak boleh kosong"}), 400

        # Mengubah teks menjadi angka vektor (Embedding)
        query_embedding = model.encode([query])
        synopsis_embeddings = model.encode(synopsis_list)

        # Menghitung kemiripan (Cosine Similarity)
        similarities = cosine_similarity(query_embedding, synopsis_embeddings)[0]

        # Menyusun hasil dari yang paling mirip ke yang tidak mirip
        results = []
        for index, score in enumerate(similarities):
            results.append({
                "index_komik": index,
                "skor_kemiripan": float(score) # Semakin mendekati 1.0, semakin mirip
            })

        # Urutkan berdasarkan skor tertinggi
        results = sorted(results, key=lambda x: x['skor_kemiripan'], reverse=True)

        return jsonify({"status": "success", "data": results})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    # Menjalankan server Python di port 5000
    app.run(host='127.0.0.1', port=5000, debug=True)