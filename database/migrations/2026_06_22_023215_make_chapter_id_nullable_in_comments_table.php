<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Mengubah kolom chapter_id agar boleh menerima nilai kosong (nullable)
            $table->unsignedBigInteger('chapter_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Mengembalikan ke aturan semula (jika suatu saat di-rollback)
            $table->unsignedBigInteger('chapter_id')->nullable(false)->change();
        });
    }
};