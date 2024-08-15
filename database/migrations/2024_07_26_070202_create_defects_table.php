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
        Schema::create('defects', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('cell');
            $table->json('idpass')->nullable();
            $table->json('qtyok')->nullable();
            $table->json('qtynok')->nullable();
            $table->text('defect')->nullable(); // Ubah menjadi array
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defects');
    }
};