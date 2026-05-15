<?php

namespace App\Services;

use App\Models\Donhang;

class VnpayService
{
    public function taoUrlThanhToan(Donhang $donhang): string
    {
        $vnpUrl = config('thanhtoan.vnpay.url');
        $vnpTmnCode = config('thanhtoan.vnpay.tmn_code');
        $vnpHashSecret = config('thanhtoan.vnpay.hash_secret');
        $vnpReturnUrl = config('thanhtoan.vnpay.return_url');

        $vnpTxnRef = $donhang->ma_don_hang;
        $vnpOrderInfo = 'Thanh toan don hang ' . $donhang->ma_don_hang;
        $vnpOrderType = 'billpayment';
        $vnpAmount = $donhang->tong_tien * 100;
        $vnpLocale = 'vn';
        $vnpIpAddr = request()->ip();

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnpTmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnpIpAddr,
            'vnp_Locale' => $vnpLocale,
            'vnp_OrderInfo' => $vnpOrderInfo,
            'vnp_OrderType' => $vnpOrderType,
            'vnp_ReturnUrl' => $vnpReturnUrl,
            'vnp_TxnRef' => $vnpTxnRef,
        ];

        ksort($inputData);

        $hashData = [];
        $query = [];

        foreach ($inputData as $key => $value) {
            $hashData[] = urlencode($key) . '=' . urlencode($value);
            $query[] = urlencode($key) . '=' . urlencode($value);
        }

        $hashData = implode('&', $hashData);
        $query = implode('&', $query);

        $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        return $vnpUrl . '?' . $query . '&vnp_SecureHash=' . $secureHash;
    }

    public function xacThucPhanHoi(array $duLieu): bool
    {
        if (!isset($duLieu['vnp_SecureHash'])) {
            return false;
        }

        $secureHash = $duLieu['vnp_SecureHash'];

        unset($duLieu['vnp_SecureHash'], $duLieu['vnp_SecureHashType']);

        ksort($duLieu);

        $hashData = [];

        foreach ($duLieu as $key => $value) {
            $hashData[] = urlencode($key) . '=' . urlencode($value);
        }

        $hashData = implode('&', $hashData);

        $hashSecret = config('thanhtoan.vnpay.hash_secret');

        $myHash = hash_hmac('sha512', $hashData, $hashSecret);

        return hash_equals($myHash, $secureHash);
    }

    public function thanhCong(array $duLieu): bool
    {
        return ($duLieu['vnp_ResponseCode'] ?? null) === '00'
            && ($duLieu['vnp_TransactionStatus'] ?? null) === '00';
    }
}
