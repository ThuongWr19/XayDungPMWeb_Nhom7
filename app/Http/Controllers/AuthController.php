<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. API Đăng nhập
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra user có tồn tại và mật khẩu có đúng không
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không chính xác.'
            ], 401);
        }

        // Tạo Token cho user (cấp thẻ từ)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // 2. API Đăng xuất
    public function logout(Request $request)
    {
        // Thu hồi toàn bộ thẻ từ (token) của user này
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Đã đăng xuất thành công'
        ]);
    }

    // 3. API Lấy thông tin tài khoản đang đăng nhập
    public function profile(Request $request)
    {
        // Trả về thông tin của user gắn với token hiện tại
        return response()->json($request->user());
    }

    // 4. API Đổi mật khẩu
    public function changePassword(Request $request)
    {
        // Backend cũng cần validate lại để đảm bảo an toàn tuyệt đối
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required'
        ], [
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.same' => 'Mật khẩu xác nhận không khớp.'
        ]);

        $user = $request->user();

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu hiện tại không đúng!'
            ], 400); // 400 Bad Request
        }

        // Nếu đúng thì cập nhật mật khẩu mới (đã mã hóa)
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Đổi mật khẩu thành công!'
        ]);
    }

    // 5. API Cập nhật thông tin cá nhân
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validate dữ liệu (Email không cho phép đổi để tránh lỗi đăng nhập)
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'class' => 'nullable|string|max:50',
        ]);

        // Cập nhật thông tin
        $user->name = $request->name;
        $user->phone = $request->phone;
        
        // Nếu là Sinh viên (role = 0) thì mới cập nhật lớp học
        if ($user->role == 0) {
            $user->class = $request->class;
        }
        
        $user->save();

        return response()->json([
            'message' => 'Cập nhật thông tin thành công!',
            'user' => $user
        ]);
    }
}