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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('block_id')->nullable()->constrained('blocks')->nullOnDelete();
            $table->string('name');
            $table->text('address');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->unsignedInteger('total_floors')->default(0);
            $table->unsignedInteger('total_flats')->default(0);
            $table->unsignedInteger('flats_per_floor')->default(0);
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index('site_id');
            $table->index('block_id');
            // Aynı blok içinde isim benzersiz olsun
            $table->unique(['block_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
