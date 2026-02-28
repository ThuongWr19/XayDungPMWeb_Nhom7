<?php

use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    return response()->json([
        [
            'id' => '2201605', 
            'name' => 'Nguyen Van Thuong', 
            'email' => 'thuong@sv.edu.vn', 
            'phone' => '0901234567'
        ],
        [
            'id' => '2201606', 
            'name' => 'Nguyen van A', 
            'email' => 'AA@sv.edu.vn', 
            'phone' => '0987654321'
        ],
        [
            'id' => '2201607', 
            'name' => 'Khai', 
            'email' => 'khai@sv.edu.vn', 
            'phone' => '0911222333'
        ],
    ]);
});