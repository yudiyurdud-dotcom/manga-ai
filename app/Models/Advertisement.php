<?php

// Kode ini diletakkan di app/Models/Advertisement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    // Kolom-kolom yang diizinkan untuk diisi
    protected $fillable = [
        'title', 
        'image_path', 
        'link_url', 
        'position', 
        'is_active'
    ];
}