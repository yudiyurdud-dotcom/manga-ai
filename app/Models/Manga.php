<?php

// Kode ini diletakkan di app/Models/Manga.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    // Tambahkan artist, theme, demographic, dan alternative_titles ke dalam array ini
    protected $fillable = [
        'title', 'slug', 'alternative_titles', 'author', 'artist', 
        'synopsis', 'status', 'theme', 'demographic', 'cover_image'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'manga_genres');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}