<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaidatcuahangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'so_dien_thoai' => $this->so_dien_thoai
                ? preg_replace('/\s+/', '', (string) $this->so_dien_thoai)
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'ten_cua_hang' => [
                'required',
                'string',
                'max:200',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'so_dien_thoai' => [
                'nullable',
                'regex:/^(0|\+84)[0-9]{9,10}$/',
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
            ],
            'dia_chi' => [
                'nullable',
                'string',
                'max:500',
            ],
            'chinh_sach_van_chuyen' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'chinh_sach_doi_tra' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'facebook' => [
                'nullable',
                'url',
                'max:255',
            ],
            'zalo' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_cua_hang.required' => 'Vui lòng nhập tên cửa hàng.',
            'ten_cua_hang.max' => 'Tên cửa hàng không được vượt quá 200 ký tự.',

            'logo.image' => 'Logo phải là hình ảnh.',
            'logo.mimes' => 'Logo phải có định dạng jpg, jpeg, png hoặc webp.',
            'logo.max' => 'Logo không được vượt quá 2MB.',

            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',

            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 150 ký tự.',

            'dia_chi.max' => 'Địa chỉ không được vượt quá 500 ký tự.',

            'chinh_sach_van_chuyen.max' => 'Chính sách vận chuyển không được vượt quá 3000 ký tự.',
            'chinh_sach_doi_tra.max' => 'Chính sách đổi trả không được vượt quá 3000 ký tự.',

            'facebook.url' => 'Link Facebook không đúng định dạng URL.',
        ];
    }
}
