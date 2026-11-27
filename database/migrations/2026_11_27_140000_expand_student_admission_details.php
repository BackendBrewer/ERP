<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('admission_number', 40)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('student_phone', 25)->nullable();
            $table->string('guardian_phone', 25)->nullable();
            $table->string('whatsapp_phone', 25)->nullable();
            $table->unique(['academy_id', 'admission_number']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('roll_number', 40)->nullable();
            $table->date('attendance_starts_on')->nullable();
            $table->index(['student_id', 'admitted_on', 'id'], 'enrollments_student_date_id_index');
        });

        Schema::create('enrollment_subject', function (Blueprint $table) {
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->primary(['enrollment_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_subject');

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_student_date_id_index');
            $table->dropColumn(['roll_number', 'attendance_starts_on']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['academy_id', 'admission_number']);
            $table->dropColumn([
                'admission_number', 'date_of_birth', 'gender', 'address',
                'email', 'student_phone', 'guardian_phone', 'whatsapp_phone',
            ]);
        });
    }
};
