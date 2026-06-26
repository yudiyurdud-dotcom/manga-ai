<?php

// Kode ini diletakkan di database/migrations/xxxx_xx_xx_xxxxxx_create_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('report_type', ['image_issue', 'comment_issue']);
            $table->unsignedBigInteger('target_id'); // ID dari chapter atau komentar
            $table->text('reason');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};