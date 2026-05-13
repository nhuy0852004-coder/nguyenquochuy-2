@extends('web.layouts.app')

@section('tieude', 'Trang chủ')

@section('noidung')
    <section class="py-5">
        <div class="container">
            <div class="p-5 bg-white border rounded-4">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="fw-bold mb-3">Website bán hàng đang được xây dựng</h1>
                        <p class="text-muted mb-4">
                            Đây là phần website khách hàng. Các chức năng sản phẩm, giỏ hàng,
                            thanh toán và theo dõi đơn hàng sẽ được xây dựng ở các ngày tiếp theo.
                        </p>

                        <a href="{{ route('admin.bangdieukhien') }}" class="btn btn-primary">
                            Vào trang Admin
                        </a>
                    </div>

                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="bg-light rounded-4 p-4 border">
                            <h5 class="fw-bold">Module sắp làm</h5>
                            <ul class="mb-0 text-muted">
                                <li>Danh sách sản phẩm</li>
                                <li>Chi tiết sản phẩm</li>
                                <li>Giỏ hàng</li>
                                <li>Thanh toán</li>
                                <li>Theo dõi đơn hàng realtime</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
