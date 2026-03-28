<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->json('answers')->nullable(); // Lưu đáp án đã chọn dạng JSON {"question_id": "A"}
            $table->timestamp('started_at'); // Thời gian bắt đầu bấm nút làm bài
            $table->timestamp('submitted_at')->nullable(); // Thời gian nộp bài
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->float('score')->nullable(); // Điểm số
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
