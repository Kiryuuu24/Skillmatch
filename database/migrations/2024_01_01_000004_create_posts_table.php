<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ketua_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_lomba');
            $table->string('kategori_lomba');
            $table->unsignedInteger('jumlah_dibutuhkan');
            $table->unsignedInteger('jumlah_terisi')->default(0);
            $table->text('deskripsi_syarat')->nullable();
            $table->date('deadline_daftar');
            $table->string('link_sosmed');
            $table->enum('status_postingan', ['aktif', 'penuh', 'ditutup', 'disembunyikan'])->default('aktif');
            $table->timestamps();

            $table->index('kategori_lomba');
            $table->index('status_postingan');
            $table->index('deadline_daftar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
