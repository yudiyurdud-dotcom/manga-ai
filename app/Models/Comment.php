<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Pastikan semua kolom ini tertulis di dalam array!
    protected $fillable = [
        'user_id', 
        'manga_id', 
        'chapter_id', 
        'parent_id', 
        'comment_text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // Relasi untuk mengambil balasan komentar
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}