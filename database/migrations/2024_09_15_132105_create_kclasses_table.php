<?php

use App\Traits\HasEntityDescriptiveFields;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('klasses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            HasEntityDescriptiveFields::addTitleAndDescription($table);
            $table->foreignId('main_teacher_id')->nullable()->constrained('users'); // Add foreign key for main teacher
            $table->foreignId('course_id')->nullable()->constrained('courses'); // Add foreign key for course
        });

        // Create pivot table for many-to-many relation between Kclass and User (students)
        Schema::create('kclass_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kclass_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kclass_student');
        Schema::dropIfExists('klasses');
    }
};
