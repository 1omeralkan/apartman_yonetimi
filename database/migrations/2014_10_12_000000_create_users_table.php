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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Kimlik bilgileri
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // İletişim ve kimlik
            $table->string('phone')->unique()->nullable();
            $table->string('national_id')->unique()->nullable();

            // Demografik
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();

            // Adres ve acil durum
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            // Profil
            $table->string('profile_photo')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            // Durum ve tercih
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('notification_email')->default(true);
            $table->boolean('notification_sms')->default(false);

            // Jetstream/Sanctum
            $table->rememberToken();

            // Zaman damgaları ve soft delete
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
