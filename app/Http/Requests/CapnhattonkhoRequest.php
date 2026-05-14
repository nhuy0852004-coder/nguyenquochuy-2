<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapnhattonkhoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'so_luong_ton' => [
                'required',
                'integer',
                'min:0',
                'max:999999',
            ],
            'muc_canh_bao_ton' => [
                'required',
                'integer',
                'min:0',
                'max:999999',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'so_luong_ton.required' => 'Vui lòng nhập số lượng tồn kho.',
            'so_luong_ton.integer' => 'Số lượng tồn kho phải là số.',
            'so_luong_ton.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'so_luong_ton.max' => 'Số lượng tồn kho quá lớn.',

            'muc_canh_bao_ton.required' => 'Vui lòng nhập mức cảnh báo tồn kho.',
            'muc_canh_bao_ton.integer' => 'Mức cảnh báo tồn kho phải là số.',
            'muc_canh_bao_ton.min' => 'Mức cảnh báo tồn kho không được nhỏ hơn 0.',
            'muc_canh_bao_ton.max' => 'Mức cảnh báo tồn kho quá lớn.',
        ];
    }
}