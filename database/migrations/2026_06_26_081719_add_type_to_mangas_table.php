<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mangas', function (Blueprint $table) {
            // Menambahkan kolom type, defaultnya adalah Manga
            $table->enum('type', ['Manga', 'Manhwa', 'Manhua', 'Comic', 'OEL'])->default('Manga')->after('title');
        });
    }

    public function down()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};