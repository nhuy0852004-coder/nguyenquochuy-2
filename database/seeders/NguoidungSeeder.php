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
                'email' => 'nhuy08052004@gmail.com',
            ],
            [
                'ho_ten' => 'Admin',
                'mat_khau' => Hash::make('12345678'),
                'vai_tro' => 'admin',
                'trang_thai' => true,
            ]
        );
    }
}
