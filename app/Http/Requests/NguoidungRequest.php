<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NguoidungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_ten' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'vai_tro' => ['required', 'in:admin,nhan_vien'],
            'mat_khau' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'vai_tro.required' => 'Vui lòng chọn vai trò.',
            'vai_tro.in' => 'Vai trò không hợp lệ.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
