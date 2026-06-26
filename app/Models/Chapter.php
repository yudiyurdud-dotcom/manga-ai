<?php

// Kode ini diletakkan di app/Models/Chapter.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke tabel Manga (Chapter ini milik Manga apa)
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    // Relasi ke tabel Page (1 Chapter punya banyak Gambar Halaman)
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // Relasi ke tabel Comment (1 Chapter punya banyak Komentar)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}