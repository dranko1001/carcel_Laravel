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
Schema::create('prisoners', function (Blueprint $table) {
    $table->id();
    $table->string('full_name');
    $table->date('birth_date');
    $table->date('entry_date');
    $table->string('crime');
    $table->string('assigned_cell');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prisoners');
    }
};
