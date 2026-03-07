<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;

// Lấy danh sách
Route::get('/users', function () {
    return response()->json(User::select('id', 'name')->get(), 200, [], JSON_UNESCAPED_UNICODE);
});

Route::get('/users/{id}', function ($id) {
    $user = User::select('id', 'name')->find($id);
    if ($user) {
        return response()->json($user, 200, [], JSON_UNESCAPED_UNICODE);
    }
    return response()->json(['message' => 'Không tìm thấy!'], 404, [], JSON_UNESCAPED_UNICODE);
});

// Thêm sinh viên mới
Route::post('/users', function (Request $request) {
    if (!$request->id || !$request->name) {
        return response()->json(['message' => 'Vui lòng nhập đủ MSSV và Họ tên'], 400);
    }
    if (User::find($request->id)) {
        return response()->json(['message' => 'MSSV này đã tồn tại trên hệ thống!'], 400);
    }
    
    $user = User::create(['id' => $request->id, 'name' => $request->name]);
    return response()->json(['message' => 'Thêm sinh viên thành công!', 'user' => $user], 201);
});

// Cập nhật tên sinh viên
Route::put('/users/{id}', function (Request $request, $id) {
    $user = User::find($id);
    if (!$user) return response()->json(['message' => 'Không tìm thấy sinh viên!'], 404);

    $user->name = $request->name;
    $user->save();
    return response()->json(['message' => 'Cập nhật thành công!']);
});

// Xóa sinh viên
Route::delete('/users/{id}', function ($id) {
    $user = User::find($id);
    if (!$user) return response()->json(['message' => 'Không tìm thấy sinh viên!'], 404);

    $user->delete();
    return response()->json(['message' => 'Đã xóa sinh viên khỏi danh sách!']);
});