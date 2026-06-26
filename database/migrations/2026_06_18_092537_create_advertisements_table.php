<?php

// Kode ini diletakkan di database/migrations/2026_06_18_092537_create_advertisements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path');
            $table->string('link_url'); // Diubah agar sinkron dengan Controller
            $table->enum('position', ['header', 'footer', 'sidebar']); // Kolom penentu letak iklan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};