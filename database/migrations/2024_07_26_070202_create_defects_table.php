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
            $table->integer('qtyok')->default(0); // Menambahkan kolom qtyok dengan nilai default
            $table->integer('qtynok')->default(0);
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