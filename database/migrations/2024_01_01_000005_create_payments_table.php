<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $blueprint->decimal('amount', 10, 2);
            $blueprint->enum('status', ['paid', 'unpaid', 'pending'])->default('unpaid');
            $blueprint->string('period_month'); // e.g. YYYY-MM
            $blueprint->date('paid_on')->nullable();
            $blueprint->text('note')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
