<?php

namespace App\Services;

use App\Models\Donhang;
use Illuminate\Support\Facades\Http;

class PayosService
{
    public function taoLinkThanhToan(Donhang $donhang): array
    {
        $clientId = config('thanhtoan.payos.client_id');
        $apiKey = config('thanhtoan.payos.api_key');
        $checksumKey = config('thanhtoan.payos.checksum_key');
        $apiUrl = rtrim(config('thanhtoan.payos.api_url'), '/');

        if (!$clientId || !$apiKey || !$checksumKey) {
            throw new \Exception('Chưa cấu hình PAYOS_CLIENT_ID, PAYOS_API_KEY hoặc PAYOS_CHECKSUM_KEY.');
        }

        $orderCode = $this->taoOrderCode($donhang);

        $items = $donhang->chitietdonhang->map(function ($chitiet) {
            return [
                'name' => mb_substr($chitiet->ten_san_pham, 0, 255),
                'quantity' => (int) $chitiet->so_luong,
                'price' => (int) $chitiet->don_gia,
            ];
        })->values()->toArray();

        $payload = [
            'orderCode' => $orderCode,
            'amount' => (int) $donhang->tong_tien,
            'description' => 'DH' . $donhang->id,
            'buyerName' => $donhang->ho_ten_nguoi_nhan,
            'buyerEmail' => $donhang->email_nguoi_nhan,
            'buyerPhone' => $donhang->so_dien_thoai_nguoi_nhan,
            'buyerAddress' => $donhang->dia_chi_giao_hang,
            'items' => $items,
            'cancelUrl' => config('thanhtoan.payos.cancel_url'),
            'returnUrl' => config('thanhtoan.payos.return_url'),
        ];

        $payload['signature'] = $this->taoChuKyTaoLink($payload);

        $response = Http::withHeaders([
            'x-client-id' => $clientId,
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl . '/v2/payment-requests', $payload);

        if (!$response->successful()) {
            throw new \Exception('Không thể tạo link thanh toán payOS. Mã lỗi HTTP: ' . $response->status());
        }

        $json = $response->json();

        if (($json['code'] ?? null) !== '00') {
            throw new \Exception('payOS trả về lỗi: ' . ($json['desc'] ?? 'Không rõ lỗi'));
        }

        return [
            'order_code' => $orderCode,
            'checkout_url' => $json['data']['checkoutUrl'] ?? null,
            'payment_link_id' => $json['data']['paymentLinkId'] ?? null,
            'raw' => $json,
        ];
    }

    public function xacThucWebhook(array $body): bool
    {
        if (!isset($body['data'], $body['signature'])) {
            return false;
        }

        $data = $body['data'];
        $signature = $body['signature'];

        $mySignature = $this->taoChuKyWebhook($data);

        return hash_equals($mySignature, $signature);
    }

    private function taoOrderCode(Donhang $donhang): int
    {
        return (int) now()->format('ymdHis') . str_pad($donhang->id, 3, '0', STR_PAD_LEFT);
    }

    private function taoChuKyTaoLink(array $data): string
    {
        $checksumKey = config('thanhtoan.payos.checksum_key');

        $dataForSignature = [
            'amount' => (int) ($data['amount'] ?? 0),
            'cancelUrl' => (string) ($data['cancelUrl'] ?? ''),
            'description' => (string) ($data['description'] ?? ''),
            'orderCode' => (string) ($data['orderCode'] ?? ''),
            'returnUrl' => (string) ($data['returnUrl'] ?? ''),
        ];

        ksort($dataForSignature);

        $rawData = collect($dataForSignature)
            ->map(fn ($value, $key) => $key . '=' . $value)
            ->implode('&');

        return hash_hmac('sha256', $rawData, $checksumKey);
    }

    private function taoChuKyWebhook(array $data): string
    {
        $checksumKey = config('thanhtoan.payos.checksum_key');

        $dataForSignature = $data;
        unset($dataForSignature['signature']);

        ksort($dataForSignature);

        $parts = [];

        foreach ($dataForSignature as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            if ($value === null) {
                $value = '';
            }

            $parts[] = $key . '=' . $value;
        }

        $rawData = implode('&', $parts);

        return hash_hmac('sha256', $rawData, $checksumKey);
    }
}
