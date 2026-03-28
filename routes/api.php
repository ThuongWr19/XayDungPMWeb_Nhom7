<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentExamController;

// --- CÁC API KHÔNG CẦN ĐĂNG NHẬP ---
Route::post('/login', [AuthController::class, 'login']);


// --- CÁC API YÊU CẦU PHẢI ĐĂNG NHẬP (Gửi kèm Token) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // apiResource tự động tạo ra 4 routes:
    // GET /users (Lấy danh sách)
    // POST /users (Thêm mới)
    // PUT /users/{id} (Cập nhật)
    // DELETE /users/{id} (Xóa)
    Route::post('/users/import', [UserController::class, 'import']);
    Route::apiResource('users', UserController::class); 

    Route::post('/questions/import', [QuestionController::class, 'import']);
    Route::apiResource('questions', QuestionController::class);

    Route::post('/exams/{id}/toggle-status', [ExamController::class, 'toggleStatus']);
    Route::post('/exams/{id}/generate-questions', [ExamController::class, 'generateQuestions']);
    Route::apiResource('exams', ExamController::class);

    Route::get('/student/exams/{id}/do', [StudentExamController::class, 'getExam']);
    Route::post('/student/exams/{id}/save-progress', [StudentExamController::class, 'saveProgress']);
    Route::get('/student/exams', [StudentExamController::class, 'getAvailableExams']);
    Route::post('/student/exams/{id}/check-password', [StudentExamController::class, 'checkPassword']);
    Route::get('/student/exams/history', [StudentExamController::class, 'getHistory']);


    Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit']);
    Route::get('/exams/{exam}/statistics', [ExamController::class, 'statistics']);
    Route::get('/exams/{exam}/export', [ExamController::class, 'export']);
});