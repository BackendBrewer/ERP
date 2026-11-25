<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institute_user', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('user_id')
                ->constrained('institute_roles')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('institute_user', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
