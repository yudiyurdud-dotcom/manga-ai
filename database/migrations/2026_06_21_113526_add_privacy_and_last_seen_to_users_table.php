<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom penanda privasi (default: false/publik)
            $table->boolean('is_private')->default(false)->after('role');
            // Kolom pencatat waktu terakhir aktif
            $table->timestamp('last_seen')->nullable()->after('is_private');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_private', 'last_seen']);
        });
    }
};
