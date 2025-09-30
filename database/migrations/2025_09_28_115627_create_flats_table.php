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
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');
            $table->integer('flat_number');
            $table->enum('flat_type', ['1+0', '1+1', '2+1', '3+1', '4+1', '5+1']);
            $table->decimal('area', 8, 2)->nullable();
            $table->enum('status', ['empty', 'occupied', 'maintenance', 'renovation'])->default('empty');
            $table->decimal('monthly_dues', 10, 2)->nullable();
            $table->decimal('net_area', 8, 2)->nullable();
            $table->decimal('gross_area', 8, 2)->nullable();
            $table->boolean('has_balcony')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index('floor_id');
            $table->unique(['floor_id', 'flat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
