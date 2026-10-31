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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('npm')->nullable();
            $table->string('prodi')->nullable();
            $table->string('angkatan')->nullable();
            $table->text('pengalaman_lomba')->nullable();
            $table->text('minat_bidang_lomba')->nullable();
            $table->string('link_portofolio')->nullable();
            $table->string('cv')->nullable();
            $table->string('link_sosmed')->nullable();
            $table->boolean('is_open_to_join')->default(false);
            $table->boolean('is_portfolio_public')->default(true);
            $table->boolean('is_sosmed_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
