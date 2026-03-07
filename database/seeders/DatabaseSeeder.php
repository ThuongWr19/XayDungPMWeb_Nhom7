<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::insert([
            ['id' => '1', 'name' => 'Nguyễn Văn Thưởng'],
            ['id' => '2', 'name' => 'Hồ Tuấn Khải'],
            ['id' => '3', 'name' => 'Hoàng Hà Thiện Nhân'],
            ['id' => '4', 'name' => 'Huỳnh Ngọc Quân'],
            ['id' => '5', 'name' => 'Ngô Minh Nhật'],
            ['id' => '6', 'name' => 'Đặng Phước Lộc'],
        ]);
    }
}
