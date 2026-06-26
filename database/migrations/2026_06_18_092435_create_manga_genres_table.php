<?php

// Kode ini diletakkan di database/migrations/xxxx_xx_xx_xxxxxx_create_manga_genres_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manga_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manga_id')->constrained('mangas')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manga_genres');
    }
};