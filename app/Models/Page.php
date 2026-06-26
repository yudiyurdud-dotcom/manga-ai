<?php

// Kode ini diletakkan di app/Models/Page.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = ['id'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}