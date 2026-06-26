<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingHistory extends Model
{
    use HasFactory;

    // Mengizinkan Laravel mengisi kolom ini secara otomatis
    protected $fillable = ['user_id', 'manga_id', 'chapter_id'];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Manga
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    // Relasi ke tabel Chapter
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}