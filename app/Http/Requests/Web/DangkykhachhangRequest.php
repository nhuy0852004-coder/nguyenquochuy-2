<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DangkykhachhangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'so_dien_thoai' => preg_replace('/\s+/', '', (string) $this->so_dien_thoai),
        ]);
    }

    public function rules(): array
    {
        return [
            'ho_ten' => [
                'required',
                'string',
                'max:150',
            ],
            'so_dien_thoai' => [
                'required',
                'regex:/^(0|\+84)[0-9]{9,10}$/',
                Rule::unique('khachhang', 'so_dien_thoai'),
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('khachhang', 'email')->whereNotNull('email'),
            ],
            'mat_khau' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',
            'so_dien_thoai.unique' => 'Số điện thoại này đã được đăng ký.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
