<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blueprint->string('full_name');
            $blueprint->integer('age')->nullable();
            $blueprint->date('birthday')->nullable();
            $blueprint->string('school')->nullable();
            $blueprint->string('address')->nullable();
            $blueprint->string('parent_name')->nullable();
            $blueprint->string('parent_nic')->nullable();
            $blueprint->string('parent_contact')->nullable();
            $blueprint->string('profile_picture_path')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
