# Hệ Thống Bán Hàng Laravel

Dự án hệ thống bán hàng gồm:

- Website bán hàng cho khách
- Trang Admin quản lý
- Giỏ hàng
- Thanh toán
- Quản lý đơn hàng
- Theo dõi đơn hàng
- Thông báo Admin
- Realtime bằng Laravel Reverb
- Báo cáo doanh thu
- Quản lý khách hàng
- Cài đặt cửa hàng

## Công nghệ sử dụng

- Laravel
- Blade Template
- MySQL
- XAMPP/Laragon
- Bootstrap 5
- JavaScript thuần
- Laravel Reverb
- Laravel Echo
- Pusher JS

## Cài đặt project

Clone project:

```bash
git clone link-repo
cd ten-project

Cài package PHP:

composer install

Cài package frontend:

npm install

Tạo file môi trường:

copy .env.example .env

Tạo app key:

php artisan key:generate

Tạo database:

hethongbanhang

Chạy migration:

php artisan migrate

Tạo storage link:

php artisan storage:link

Chạy Laravel:

php artisan serve

Chạy Vite:

npm run dev

Chạy Reverb nếu cần realtime:

php artisan reverb:start --host=127.0.0.1 --port=8080
Đường dẫn chính

Website:

http://127.0.0.1:8000

Admin:

http://127.0.0.1:8000/admin

Sản phẩm:

http://127.0.0.1:8000/san-pham

Giỏ hàng:

http://127.0.0.1:8000/gio-hang

Theo dõi đơn hàng:

http://127.0.0.1:8000/theo-doi-don-hang
Lưu ý

Không push các thư mục/file sau lên GitHub:

.env
vendor
node_modules
public/storage

---

# 5. Kiểm tra cấu hình Laravel tiếng Việt

Mở:

```txt
config/app.php

Đảm bảo:

'timezone' => 'Asia/Ho_Chi_Minh',

Và locale:

'locale' => env('APP_LOCALE', 'vi'),

Trong .env:

APP_LOCALE=vi
APP_FALLBACK_LOCALE=vi
APP_FAKER_LOCALE=vi_VN


. Kiểm tra storage upload ảnh

Chạy:

php artisan storage:link

Nếu báo đã tồn tại thì bỏ qua.

Kiểm tra các thư mục:

storage/app/public/sanpham
storage/app/public/caidatcuahang

Nếu chưa có thì tự tạo:

mkdir storage\app\public\sanpham
mkdir storage\app\public\caidatcuahang

Test lại:

/admin/sanpham
/admin/caidatcuahang

Upload ảnh sản phẩm và logo xem có hiển thị không.

7. Dọn cache Laravel

Chạy:

php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

Sau đó chạy lại:

php artisan serve
8. Kiểm tra route toàn hệ thống

Chạy:

php artisan route:list

Bạn cần thấy các nhóm chính:

admin.bangdieukhien
admin.danhmuc.*
admin.sanpham.*
admin.donhang.*
admin.khachhang.*
admin.thongbao.*
admin.baocao.*
admin.caidatcuahang.*

web.trangchu
web.sanpham.*
web.giohang.*
web.thanhtoan.*
web.theodoi.*

Nếu route nào lỗi, kiểm tra lại routes/web.php.

9. Kiểm tra Reverb trước khi commit

Nếu dùng realtime, mở 3 terminal:

php artisan serve
npm run dev
php artisan reverb:start --host=127.0.0.1 --port=8080

Test:

Khách đặt hàng
→ Admin nhận realtime

Admin đổi trạng thái đơn
→ Khách theo dõi đơn nhận realtime

Nếu Reverb bị lỗi mà bạn vẫn muốn hệ thống chạy bình thường, trong .env đổi tạm:

BROADCAST_CONNECTION=log

Sau đó:

php artisan optimize:clear

Khi cần realtime thì đổi lại:

BROADCAST_CONNECTION=reverb
10. Backup database local

Vào phpMyAdmin:

http://localhost/phpmyadmin

Chọn database:

hethongbanhang

Bấm:

Export → Quick → SQL → Export

Lưu file ví dụ:

backup-hethongbanhang-2026-05-14.sql

Nên tạo thư mục riêng ngoài project:

C:\huy\backup_database

Không nên để file .sql lớn trong project rồi push lên GitHub.

Nếu muốn backup bằng lệnh:

mysqldump -u root hethongbanhang > backup-hethongbanhang.sql

Nếu MySQL có mật khẩu:

mysqldump -u root -p hethongbanhang > backup-hethongbanhang.sql
11. Kiểm tra code debug

Tìm trong project các đoạn debug:

dd(
dump(
var_dump(
die(
console.log(

Các file cần kiểm tra nhiều:

app/Http/Controllers
app/Services
resources/views
public/js

Nếu còn dd() trong code, phải xóa hết trước khi commit.

12. Kiểm tra form bảo mật cơ bản

Đảm bảo các form POST/PUT/PATCH/DELETE đều có:

@csrf

Với PUT/PATCH/DELETE cần có:

@method('PUT')

hoặc:

@method('PATCH')

hoặc:

@method('DELETE')

Các form quan trọng:

Thêm/sửa danh mục
Thêm/sửa sản phẩm
Xóa sản phẩm
Cập nhật trạng thái đơn hàng
Thanh toán
Cập nhật giỏ hàng
Cài đặt cửa hàng
13. Kiểm tra validate

Đảm bảo các Request chính còn hoạt động:

DanhmucRequest
SanphamRequest
ThanhtoanRequest
CaidatcuahangRequest

Test nhanh:

Thêm danh mục không nhập tên → báo lỗi tiếng Việt
Thêm sản phẩm không nhập giá → báo lỗi tiếng Việt
Thanh toán sai số điện thoại → báo lỗi tiếng Việt
Upload ảnh quá 2MB → báo lỗi tiếng Việt
14. Kiểm tra production checklist

Tạo file:

docs/kiemtrachuandeploy.md

Nếu chưa có thư mục docs, tạo:

mkdir docs

Dán nội dung:

# Checklist chuẩn bị deploy

## Cấu hình môi trường

- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] APP_URL đúng domain thật
- [ ] DB_DATABASE đúng database server
- [ ] DB_USERNAME đúng
- [ ] DB_PASSWORD đúng
- [ ] BROADCAST_CONNECTION cấu hình đúng
- [ ] REVERB_HOST đúng domain/server
- [ ] REVERB_PORT đúng port server

## Laravel

- [ ] composer install --optimize-autoloader --no-dev
- [ ] php artisan key:generate nếu server mới
- [ ] php artisan migrate --force
- [ ] php artisan storage:link
- [ ] php artisan config:cache
- [ ] php artisan route:cache
- [ ] php artisan view:cache

## Frontend

- [ ] npm install
- [ ] npm run build
- [ ] public/build tồn tại

## File quyền

- [ ] storage có quyền ghi
- [ ] bootstrap/cache có quyền ghi
- [ ] public/storage hoạt động

## Bảo mật

- [ ] Không upload .env lên GitHub
- [ ] APP_DEBUG=false
- [ ] Không còn dd(), dump()
- [ ] Không lộ key/token
- [ ] Admin cần có auth trước khi public

## Database

- [ ] Backup database trước khi migrate
- [ ] Kiểm tra bảng đầy đủ
- [ ] Kiểm tra dữ liệu mẫu nếu cần

## Realtime

- [ ] Reverb chạy bằng service/process manager
- [ ] Port Reverb mở đúng
- [ ] Echo kết nối được