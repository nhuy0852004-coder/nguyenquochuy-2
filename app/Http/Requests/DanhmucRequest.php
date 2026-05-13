<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DanhmucRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $danhmucId = $this->route('danhmuc')?->id;

        return [
            'ten_danh_muc' => [
                'required',
                'string',
                'max:150',
            ],
            'duong_dan' => [
                'nullable',
                'string',
                'max:180',
                Rule::unique('danhmuc', 'duong_dan')->ignore($danhmucId),
            ],
            'mo_ta' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'thu_tu' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],
            'trang_thai' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_danh_muc.required' => 'Vui lòng nhập tên danh mục.',
            'ten_danh_muc.max' => 'Tên danh mục không được vượt quá 150 ký tự.',

            'duong_dan.unique' => 'Đường dẫn này đã tồn tại.',
            'duong_dan.max' => 'Đường dẫn không được vượt quá 180 ký tự.',

            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự.',

            'thu_tu.integer' => 'Thứ tự phải là số.',
            'thu_tu.min' => 'Thứ tự không được nhỏ hơn 0.',
            'thu_tu.max' => 'Thứ tự không được vượt quá 9999.',
        ];
    }
}
