<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $blueprint->string('file_path');
            $blueprint->string('file_name');
            $blueprint->text('note')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
