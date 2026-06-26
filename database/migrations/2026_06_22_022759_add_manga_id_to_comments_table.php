<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Menambahkan kolom manga_id. 
            // Kita set nullable() agar komentar lama yang sudah ada di chapter tidak error.
            $table->foreignId('manga_id')->nullable()->after('user_id')->constrained('mangas')->onDelete('cascade');
            
            // Opsional: Pastikan chapter_id juga nullable jika sebelumnya belum nullable
            // $table->foreignId('chapter_id')->nullable()->change(); 
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['manga_id']);
            $table->dropColumn('manga_id');
        });
    }
};