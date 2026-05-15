<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Services\PayosService;
use Illuminate\Http\Request;

class PayosWebhookController extends Controller
{
    public function __invoke(Request $request, PayosService $payosService)
    {
        $body = $request->all();

        if (!$payosService->xacThucWebhook($body)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 400);
        }

        $data = $body['data'] ?? [];

        $orderCode = $data['orderCode'] ?? null;

        $donhang = Donhang::query()
            ->where('ma_thanh_toan_payos', $orderCode)
            ->first();

        if (!$donhang) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $code = $body['code'] ?? null;
        $success = (bool) ($body['success'] ?? false);
        $desc = $body['desc'] ?? null;

        if ($code === '00' && $success) {
            $donhang->update([
                'trang_thai_thanh_toan' => 'da_thanh_toan',
                'trang_thai_don_hang' => \App\Models\Donhang::TRANG_THAI_DA_XAC_NHAN,
                'ma_phan_hoi_thanh_toan' => $code,
                'cong_thanh_toan' => 'payos',
                'thanh_toan_luc' => now(),
            ]);

            cache()->forget('dashboard_thong_ke');
        } elseif ($code !== null) {
            $donhang->update([
                'trang_thai_thanh_toan' => 'thanh_toan_that_bai',
                'ma_phan_hoi_thanh_toan' => $code,
                'cong_thanh_toan' => 'payos',
            ]);
        } elseif ($desc) {
            $donhang->update([
                'trang_thai_thanh_toan' => 'thanh_toan_that_bai',
                'ma_phan_hoi_thanh_toan' => $desc,
                'cong_thanh_toan' => 'payos',
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
