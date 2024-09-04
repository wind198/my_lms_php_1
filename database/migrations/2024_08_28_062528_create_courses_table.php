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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            HasEntityDescriptiveFields::addTitleAndDescription($table);
            $table->foreignId('major_id')->nullable(); // Use nullable if you need to handle existing records that might not have a value
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
