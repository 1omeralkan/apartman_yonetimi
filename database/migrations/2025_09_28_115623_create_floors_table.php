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
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->integer('floor_number');
            $table->string('floor_name');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->unsignedInteger('total_flats')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('apartment_id');
            $table->unique(['apartment_id', 'floor_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
