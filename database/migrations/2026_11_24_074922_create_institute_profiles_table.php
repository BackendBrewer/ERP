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
        Schema::create('institute_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('alternate_phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_profiles');
    }
};
