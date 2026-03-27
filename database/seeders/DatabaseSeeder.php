<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker; // Thêm dòng này để dùng Faker chuẩn

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Khởi tạo Faker để tránh lỗi undefined function fake()
        $faker = Faker::create();

        // 1. Tạo 1 tài khoản Admin xịn xò cố định
        // Dùng updateOrCreate để tránh lỗi trùng lặp nếu bạn chạy lệnh seed nhiều lần
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản trị viên',
                'password' => Hash::make('123456'), 
                'role' => 1, 
                'phone' => '0999888777',
                'class' => null,
            ]
        );

        // 2. Dùng vòng lặp chạy 10 lần
        for ($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'password' => Hash::make('123456'),
                'role' => 0, 
                // Sử dụng biến $faker đã khởi tạo ở trên thay vì hàm fake()
                'class' => $faker->randomElement(['12A1', 'CNTT1', 'KTPM2', 'ĐTVT1']), 
            ]);
        }
    }
}
