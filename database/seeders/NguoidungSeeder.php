<?php

namespace Database\Seeders;

use App\Models\Nguoidung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NguoidungSeeder extends Seeder
{
    public function run(): void
    {
        Nguoidung::updateOrCreate(
            [
                'email' => 'admin@cuahang.vn',
            ],
            [
                'ho_ten' => 'Quản trị viên',
                'mat_khau' => Hash::make('12345678'),
                'vai_tro' => 'admin',
                'trang_thai' => true,
            ]
        );
    }
}
