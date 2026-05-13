<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaidatcuahangRequest;
use App\Models\Caidatcuahang;
use Illuminate\Support\Facades\Storage;

class CaidatcuahangController extends Controller
{
    public function index()
    {
        $caidat = Caidatcuahang::hienTai();

        return view('admin.caidatcuahang.index', compact('caidat'));
    }

    public function capnhat(CaidatcuahangRequest $request)
    {
        $caidat = Caidatcuahang::hienTai();

        $dulieu = $request->validated();

        if ($request->hasFile('logo')) {
            if ($caidat->logo) {
                Storage::disk('public')->delete($caidat->logo);
            }

            $dulieu['logo'] = $request->file('logo')->store('caidatcuahang', 'public');
        }

        $caidat->update($dulieu);

        return redirect()
            ->route('admin.caidatcuahang.index')
            ->with('thanhcong', 'Cập nhật cài đặt cửa hàng thành công.');
    }

    public function xoalogo()
    {
        $caidat = Caidatcuahang::hienTai();

        if ($caidat->logo) {
            Storage::disk('public')->delete($caidat->logo);

            $caidat->update([
                'logo' => null,
            ]);
        }

        return redirect()
            ->route('admin.caidatcuahang.index')
            ->with('thanhcong', 'Đã xóa logo cửa hàng.');
    }
}
