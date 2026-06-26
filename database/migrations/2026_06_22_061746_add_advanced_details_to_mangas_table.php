<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->string('artist')->nullable()->after('author');
            $table->string('theme')->nullable()->after('status');
            $table->string('demographic')->nullable()->after('theme');
            $table->text('alternative_titles')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn(['artist', 'theme', 'demographic', 'alternative_titles']);
        });
    }
};