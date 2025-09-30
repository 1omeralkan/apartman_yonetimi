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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('name');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->unsignedInteger('total_apartments')->default(0);
            $table->unsignedInteger('total_floors')->default(0);
            $table->unsignedInteger('flats_per_floor')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('site_id');
            $table->unique(['site_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
