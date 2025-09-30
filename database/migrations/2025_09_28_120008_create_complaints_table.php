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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->foreignId('flat_id')->nullable()->constrained('flats')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // şikayeti oluşturan
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low','medium','high'])->default('low');
            $table->enum('status', ['new','in_progress','resolved','rejected'])->default('new');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('apartment_id');
            $table->index('flat_id');
            $table->index('user_id');
            $table->index('priority');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
