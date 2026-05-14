<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoimatkhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mat_khau' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'mat_khau.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
