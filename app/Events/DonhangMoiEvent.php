<?php

namespace App\Events;

use App\Models\Donhang;
use App\Models\Thongbao;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonhangMoiEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Donhang $donhang,
        public Thongbao $thongbao
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-thongbao'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'donhang-moi';
    }

    public function broadcastWith(): array
    {
        return [
            'don_hang' => [
                'id' => $this->donhang->id,
                'ma_don_hang' => $this->donhang->ma_don_hang,
                'khach_hang' => $this->donhang->ho_ten_nguoi_nhan,
                'so_dien_thoai' => $this->donhang->so_dien_thoai_nguoi_nhan,
                'tong_tien' => number_format($this->donhang->tong_tien, 0, ',', '.') . ' ₫',
                'trang_thai' => $this->donhang->trangThaiDonHangText(),
                'duong_dan' => route('admin.donhang.chitiet', $this->donhang),
            ],
            'thong_bao' => [
                'id' => $this->thongbao->id,
                'tieu_de' => $this->thongbao->tieu_de,
                'noi_dung' => $this->thongbao->noi_dung,
                'loai' => $this->thongbao->loai,
                'duong_dan' => $this->thongbao->duong_dan,
                'created_at' => $this->thongbao->created_at->format('d/m/Y H:i'),
            ],
        ];
    }
}
