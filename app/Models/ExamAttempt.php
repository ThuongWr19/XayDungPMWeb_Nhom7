<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'exam_id', 'answers', 'started_at', 'submitted_at', 'status', 'score'];
    
    // Tự động ép kiểu chuỗi JSON trong CSDL thành Array trong PHP
    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
    ];
}
