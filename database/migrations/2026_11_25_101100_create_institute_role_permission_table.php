<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institute_role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_role_id')->constrained('institute_roles')->restrictOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['institute_role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institute_role_permission');
    }
};
