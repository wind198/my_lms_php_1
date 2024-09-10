<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', User::$MAX_FIRST_NAME_LENGTH);
            $table->string('last_name', User::$MAX_LAST_NAME_LENGTH);
            $table->string('full_name', User::$MAX_FULL_NAME_LENGTH);
            $table->string('email', User::$MAX_EMAIL_LENGTH)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('dob')->nullable();
            $table->string('password');
            $table->enum('gender', [User::$GENDER_FEMALE, User::$GENDER_MALE])->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('phone', User::$MAX_PHONE_LENGTH)->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->enum('user_type', [
                User::$SUPER_ADMIN_ROLE,
                User::$STUDENT_ROLE,
                User::$TEACHER_ROLE

            ]);
            $table->enum('education_background', [User::$EDUCATION_HIGHSCHOOL, User::$EDUCATION_BACHELOR, User::$EDUCATION_MASTER]);
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
