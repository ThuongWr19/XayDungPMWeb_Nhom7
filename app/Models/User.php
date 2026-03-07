<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 1. Báo cho Laravel biết ID là chuỗi (MSSV), không phải số tự tăng
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // 2. Cho phép thêm dữ liệu vào 2 cột này
    protected $fillable = [
        'id',
        'name',
    ];
}