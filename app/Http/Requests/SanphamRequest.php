<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SanphamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'gia_ban' => $this->chuyenTienThanhSo($this->gia_ban),
            'gia_khuyen_mai' => $this->gia_khuyen_mai ? $this->chuyenTienThanhSo($this->gia_khuyen_mai) : null,
        ]);
    }

    public function rules(): array
    {
        $sanphamId = $this->route('sanpham')?->id;

        return [
            'danhmuc_id' => [
                'required',
                'exists:danhmuc,id',
            ],

            'ten_san_pham' => [
                'required',
                'string',
                'max:200',
            ],

            'duong_dan' => [
                'nullable',
                'string',
                'max:220',
                Rule::unique('sanpham', 'duong_dan')->ignore($sanphamId),
            ],

            'ma_san_pham' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('sanpham', 'ma_san_pham')->ignore($sanphamId),
            ],

            'gia_ban' => [
                'required',
                'integer',
                'min:0',
            ],

            'gia_khuyen_mai' => [
                'nullable',
                'integer',
                'min:0',
                'lt:gia_ban',
            ],

            'so_luong_ton' => [
                'required',
                'integer',
                'min:0',
            ],

            'muc_canh_bao_ton' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'anh_dai_dien' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'mo_ta_ngan' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'mo_ta_chi_tiet' => [
                'nullable',
                'string',
            ],

            'trang_thai' => [
                'nullable',
                'boolean',
            ],

            'noi_bat' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'danhmuc_id.required' => 'Vui lòng chọn danh mục.',
            'danhmuc_id.exists' => 'Danh mục không tồn tại.',

            'ten_san_pham.required' => 'Vui lòng nhập tên sản phẩm.',
            'ten_san_pham.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',

            'duong_dan.unique' => 'Đường dẫn sản phẩm đã tồn tại.',

            'ma_san_pham.unique' => 'Mã sản phẩm đã tồn tại.',

            'gia_ban.required' => 'Vui lòng nhập giá bán.',
            'gia_ban.integer' => 'Giá bán phải là số.',
            'gia_ban.min' => 'Giá bán không được nhỏ hơn 0.',

            'gia_khuyen_mai.integer' => 'Giá khuyến mãi phải là số.',
            'gia_khuyen_mai.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán.',

            'so_luong_ton.required' => 'Vui lòng nhập số lượng tồn kho.',
            'so_luong_ton.integer' => 'Số lượng tồn kho phải là số.',
            'so_luong_ton.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',

            'anh_dai_dien.image' => 'File tải lên phải là hình ảnh.',
            'anh_dai_dien.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'anh_dai_dien.max' => 'Ảnh không được vượt quá 2MB.',
        ];
    }

    private function chuyenTienThanhSo($giaTri): int
    {
        return (int) preg_replace('/[^0-9]/', '', (string) $giaTri);
    }
}
