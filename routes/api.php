<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentExamController;
// THÊM DÒNG NÀY ĐỂ IMPORT CONTROLLER THÔNG BÁO
use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SystemSettingController;


// --- CÁC API KHÔNG CẦN ĐĂNG NHẬP ---
Route::middleware('throttle:30,1')->post('/login', [AuthController::class, 'login']);

// API lấy danh sách thông báo hiển thị cho sinh viên
Route::get('/notifications', [NotificationController::class, 'getActiveNotifications']); 

// --- CÁC API YÊU CẦU PHẢI ĐĂNG NHẬP (Gửi kèm Token) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // --- CÁC API DÀNH RIÊNG CHO CHỨC NĂNG MỚI THÊM (ADMIN/GIÁM THỊ) ---
    Route::prefix('admin')->group(function () {
        // API Quản lý thông báo (CRUD)
        Route::apiResource('notifications', NotificationController::class);
        
        // API Giám sát phòng thi
        Route::get('/exams/{id}/active-attempts', [ExamController::class, 'getActiveAttempts']);
        Route::post('/exam-attempts/{id}/force-submit', [ExamController::class, 'forceSubmit']);

        Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics']);
        Route::get('/settings', [SystemSettingController::class, 'getSettings']);
        Route::post('/settings', [SystemSettingController::class, 'updateSettings']);
    });
    // ------------------------------------------------------------------

    // Các API cũ của bạn giữ nguyên bên dưới:
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

    // Thay thế dòng log-violation cũ bằng dòng này:
    Route::post('/exams/{id}/log-violation', [StudentExamController::class, 'logViolation']);
    Route::middleware('throttle:60,1')->post('/exams/{exam}/submit', [StudentExamController::class, 'submit']);
    Route::get('/exams/{exam}/statistics', [ExamController::class, 'statistics']);
    Route::get('/exams/{exam}/export', [ExamController::class, 'export']);
});