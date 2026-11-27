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
        Schema::create('subject_group_subject', function (Blueprint $table) {
            $table->foreignId('subject_group_id')->constrained()->cascadeOnDelete();

            $table->foreignId('subject_id')->constrained()->restrictOnDelete();

            $table->primary(['subject_group_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_group_subject');
    }
};
