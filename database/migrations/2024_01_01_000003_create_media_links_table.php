<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_links', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $blueprint->enum('type', ['photo', 'video']);
            $blueprint->string('drive_url');
            $blueprint->date('week_date');
            $blueprint->text('note')->nullable();
            $blueprint->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_links');
    }
};
