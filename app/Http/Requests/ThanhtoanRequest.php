<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThanhtoanRequest extends FormRequest
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
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
            ],
            'dia_chi' => [
                'required',
                'string',
                'max:500',
            ],
            'ghi_chu' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'phuong_thuc_thanh_toan' => [
                'required',
                'in:cod,chuyen_khoan',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Vui lòng nhập họ tên người nhận.',
            'ho_ten.max' => 'Họ tên không được vượt quá 150 ký tự.',

            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',

            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 150 ký tự.',

            'dia_chi.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 500 ký tự.',

            'ghi_chu.max' => 'Ghi chú không được vượt quá 1000 ký tự.',

            'phuong_thuc_thanh_toan.required' => 'Vui lòng chọn phương thức thanh toán.',
            'phuong_thuc_thanh_toan.in' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
