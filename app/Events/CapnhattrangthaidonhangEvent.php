<?php

namespace App\Events;

use App\Models\Donhang;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CapnhattrangthaidonhangEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Donhang $donhang
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('donhang-' . $this->donhang->ma_don_hang),
        ];
    }

    public function broadcastAs(): string
    {
        return 'cap-nhat-trang-thai';
    }

    public function broadcastWith(): array
    {
        return [
            'don_hang' => [
                'id' => $this->donhang->id,
                'ma_don_hang' => $this->donhang->ma_don_hang,
                'trang_thai_key' => $this->donhang->trang_thai_don_hang,
                'trang_thai_text' => $this->donhang->trangThaiDonHangText(),
                'trang_thai_class' => $this->donhang->trangThaiDonHangClass(),
                'cap_nhat_luc' => now()->format('d/m/Y H:i:s'),
                'timeline' => $this->taoTimeline($this->donhang->trang_thai_don_hang),
            ],
        ];
    }

    private function taoTimeline(string $trangThaiHienTai): array
    {
        if ($trangThaiHienTai === Donhang::TRANG_THAI_DA_HUY) {
            return [
                [
                    'key' => Donhang::TRANG_THAI_CHO_XAC_NHAN,
                    'label' => 'Chờ xác nhận',
                    'mo_ta' => 'Đơn hàng đã được tạo thành công.',
                    'active' => false,
                    'done' => true,
                ],
                [
                    'key' => Donhang::TRANG_THAI_DA_HUY,
                    'label' => 'Đã hủy',
                    'mo_ta' => 'Đơn hàng đã bị hủy.',
                    'active' => true,
                    'done' => true,
                ],
            ];
        }

        $cacTrangThai = [
            Donhang::TRANG_THAI_CHO_XAC_NHAN => [
                'label' => 'Chờ xác nhận',
                'mo_ta' => 'Cửa hàng đã nhận đơn và đang chờ xác nhận.',
            ],
            Donhang::TRANG_THAI_DA_XAC_NHAN => [
                'label' => 'Đã xác nhận',
                'mo_ta' => 'Đơn hàng đã được cửa hàng xác nhận.',
            ],
            Donhang::TRANG_THAI_DANG_GIAO_HANG => [
                'label' => 'Đang giao hàng',
                'mo_ta' => 'Đơn hàng đang được giao đến bạn.',
            ],
            Donhang::TRANG_THAI_HOAN_THANH => [
                'label' => 'Hoàn thành',
                'mo_ta' => 'Đơn hàng đã hoàn thành.',
            ],
        ];

        $keys = array_keys($cacTrangThai);
        $viTriHienTai = array_search($trangThaiHienTai, $keys);

        if ($viTriHienTai === false) {
            $viTriHienTai = 0;
        }

        $timeline = [];

        foreach ($cacTrangThai as $key => $item) {
            $viTri = array_search($key, $keys);

            $timeline[] = [
                'key' => $key,
                'label' => $item['label'],
                'mo_ta' => $item['mo_ta'],
                'active' => $viTri === $viTriHienTai,
                'done' => $viTri <= $viTriHienTai,
            ];
        }

        return $timeline;
    }
}
