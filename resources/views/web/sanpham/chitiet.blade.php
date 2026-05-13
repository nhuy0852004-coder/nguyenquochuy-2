@extends('web.layouts.app')

@section('tieude', $sanpham->ten_san_pham)

@section('mota', $sanpham->mo_ta_ngan ?: 'Chi tiết sản phẩm tại Bán Hàng Việt.')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.sanpham.index') }}">Sản phẩm</a>
            <span class="mx-2">/</span>
            <span>{{ $sanpham->ten_san_pham }}</span>
        </div>

        <section class="section-block">
            <div class="product-detail-box sanpham-card-bay">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="product-detail-image">
                            @if ($sanpham->anh_dai_dien)
                                <img src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}" alt="{{ $sanpham->ten_san_pham }}" class="anh-bay-gio">
                            @else
                                <div class="product-image-empty">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="product-detail-info">
                            <div class="detail-meta">
                                {{ $sanpham->danhmuc?->ten_danh_muc ?? 'Sản phẩm' }}
                                @if ($sanpham->ma_san_pham)
                                    · Mã: {{ $sanpham->ma_san_pham }}
                                @endif
                            </div>

                            <h1>{{ $sanpham->ten_san_pham }}</h1>

                            <div class="detail-price">
                                @if ($sanpham->gia_khuyen_mai)
                                    <span class="price-sale">
                                        {{ number_format($sanpham->gia_khuyen_mai, 0, ',', '.') }} ₫
                                    </span>
                                    <span class="price-old">
                                        {{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫
                                    </span>
                                @else
                                    <span class="price-normal">
                                        {{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫
                                    </span>
                                @endif
                            </div>

                            @if ($sanpham->so_luong_ton > 0)
                                <span class="stock-badge stock-ok">
                                    <i class="bi bi-check-circle"></i>
                                    Còn hàng - {{ $sanpham->so_luong_ton }} sản phẩm
                                </span>
                            @else
                                <span class="stock-badge stock-out">
                                    <i class="bi bi-x-circle"></i>
                                    Hết hàng
                                </span>
                            @endif

                            @if ($sanpham->mo_ta_ngan)
                                <p class="text-muted mt-3 mb-0">
                                    {{ $sanpham->mo_ta_ngan }}
                                </p>
                            @endif

                            <div class="quantity-box">
                                <span class="fw-bold">Số lượng:</span>

                                <div class="quantity-control">
                                    <button type="button" onclick="giamSoLuong()">-</button>
                                    <input type="text" id="soLuong" value="1" readonly>
                                    <button type="button" onclick="tangSoLuong({{ $sanpham->so_luong_ton }})">+</button>
                                </div>
                            </div>

                            <form action="{{ route('web.giohang.them', $sanpham) }}" method="POST" class="d-flex flex-wrap gap-2 form-them-gio">
                                @csrf

                                <input type="hidden" name="so_luong" id="soLuongSubmit" value="1">

                                <button type="submit" class="btn-web-primary">
                                    <i class="bi bi-bag-plus"></i>
                                    Thêm vào giỏ hàng
                                </button>

                                <button type="submit" class="btn-web-light" name="mua_ngay" value="1">
                                    Mua ngay
                                </button>
                            </form>

                            <div class="mt-4 text-muted small">
                                <div><i class="bi bi-truck me-2"></i>Giao hàng toàn quốc</div>
                                <div><i class="bi bi-cash-coin me-2"></i>Thanh toán khi nhận hàng hoặc chuyển khoản</div>
                                <div><i class="bi bi-arrow-repeat me-2"></i>Hỗ trợ đổi trả theo chính sách cửa hàng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-description">
                <h4 class="fw-bold mb-3">Mô tả sản phẩm</h4>

                @if ($sanpham->mo_ta_chi_tiet)
                    {!! nl2br(e($sanpham->mo_ta_chi_tiet)) !!}
                @else
                    <p class="text-muted mb-0">
                        Sản phẩm chưa có mô tả chi tiết.
                    </p>
                @endif
            </div>
        </section>

        @if ($sanphamlienquan->count())
            <section class="section-block">
                <div class="section-head">
                    <div>
                        <h2>Sản phẩm liên quan</h2>
                        <p>Các sản phẩm cùng danh mục có thể bạn sẽ thích.</p>
                    </div>
                </div>

                <div class="product-grid">
                    @foreach ($sanphamlienquan as $sanphamLienQuan)
                        @include('web.components.the-san-pham', ['sanpham' => $sanphamLienQuan])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function capNhatSoLuongSubmit() {
            const hienThi = document.getElementById('soLuong');
            const submit = document.getElementById('soLuongSubmit');

            if (hienThi && submit) {
                submit.value = hienThi.value;
            }
        }

        function giamSoLuong() {
            const input = document.getElementById('soLuong');
            let soLuong = parseInt(input.value);

            if (soLuong > 1) {
                input.value = soLuong - 1;
                capNhatSoLuongSubmit();
            }
        }

        function tangSoLuong(tonKho) {
            const input = document.getElementById('soLuong');
            let soLuong = parseInt(input.value);

            if (soLuong < tonKho) {
                input.value = soLuong + 1;
                capNhatSoLuongSubmit();
            }
        }
    </script>
@endpush
