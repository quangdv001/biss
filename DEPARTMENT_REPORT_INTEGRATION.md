# Tích hợp Báo cáo Phòng ban vào Dashboard

## Tổng quan
Đã tích hợp đầy đủ 3 loại báo cáo từ `AdminRoleController.php` vào phần **Báo cáo Phòng ban** ở Dashboard.

## Các function đã tích hợp

### 1. **report() - Báo cáo theo Task**
**Mục đích**: Thống kê chi tiết các task theo nhân sự trong phòng ban

**Dữ liệu trả về**:
- Tổng số task
- Task mới (chưa làm, chưa quá hạn)
- Task trễ hạn
- Task hoàn thành
- Task hoàn thành đúng hạn
- Task hoàn thành trễ hạn
- Tỷ lệ % hoàn thành đúng hạn

**Cách hoạt động**:
1. Lấy tất cả nhân sự trong phòng ban được chọn
2. Lấy tất cả ticket của các nhân sự đó trong khoảng thời gian
3. Nhóm theo admin_id và project_id
4. Thống kê các chỉ số cho từng dự án của từng nhân sự
5. Tính tổng cho "Tất cả dự án" của mỗi nhân sự

**Hiển thị**: Tab "Báo cáo theo Task"

---

### 2. **report2() - Báo cáo theo Dự án (KL)**
**Mục đích**: Thống kê khối lượng công việc cần làm và đã hoàn thành theo dự án

**Dữ liệu trả về**:
- Tên dự án
- Loại dự án (Marketing, Branding, Video)
- KL (Khối lượng) cần làm
- Đã hoàn thành
- Còn lại
- Tiến độ (%)

**Cách hoạt động**:
1. Lấy các dự án trong khoảng thời gian
2. Tính KL cần làm dựa trên:
   - `qty` từ PhaseGroup
   - `payment_month` của dự án
   - Số lượng admin được phân công
3. Đếm số task đã hoàn thành (status = 1)
4. Thống kê theo loại dự án

**Công thức tính KL**:
```
KL = (qty) / (payment_month) / (số admin được phân công)
```

**Hiển thị**: Tab "Báo cáo theo Dự án"

---

### 3. **report3() - Dự án sắp hết hạn**
**Mục đích**: Cảnh báo các dự án sắp hết hạn trong 30 ngày

**Dữ liệu trả về**:
- Tên dự án
- Ngày hết hạn (expired_time)
- Số lượng dự án sắp hết hạn của mỗi nhân sự

**Cách hoạt động**:
1. Lấy các dự án có `expired_time` trong khoảng:
   - Từ: `start_time` (mặc định: 30 ngày trước)
   - Đến: hiện tại
2. Lọc theo admin được phân công trong dự án
3. Nhóm theo admin_id

**Hiển thị**: Tab "Dự án sắp hết hạn"

---

## Các file đã chỉnh sửa

### 1. `dashboard.blade.php`
**Thay đổi**:
- Thêm function `loadDepartmentReport()` để load cả 3 loại báo cáo
- Thêm function `loadDepartmentTaskReport()` (gọi report)
- Thêm function `loadDepartmentProjectReport()` (gọi report2)
- Thêm function `loadDepartmentExpiredReport()` (gọi report3)
- Thêm function `renderDepartmentProjectReport()` - hiển thị báo cáo KL
- Thêm function `renderDepartmentExpiredReport()` - hiển thị dự án sắp hết hạn
- Thêm event handler để load danh sách admin khi chọn phòng ban
- Thêm event handler để load các tab khi chuyển đổi

### 2. `department_report.blade.php`
**Thay đổi**:
- Xóa các function JavaScript trùng lặp
- Giữ lại cấu trúc HTML với 3 tabs:
  - Tab 1: Báo cáo theo Task
  - Tab 2: Báo cáo theo Dự án
  - Tab 3: Dự án sắp hết hạn

---

## Cách sử dụng

### Bước 1: Chọn phòng ban
- Chọn phòng ban từ dropdown "Phòng ban"
- Hệ thống sẽ tự động load danh sách nhân sự của phòng ban đó

### Bước 2: Lọc dữ liệu (tùy chọn)
- **Nhân sự**: Chọn nhân sự cụ thể hoặc để "Tất cả"
- **Từ ngày**: Ngày bắt đầu (mặc định: 30 ngày trước)
- **Đến ngày**: Ngày kết thúc (mặc định: hôm nay)

### Bước 3: Xem báo cáo
- Nhấn nút "Xem báo cáo"
- Hệ thống sẽ load đồng thời cả 3 loại báo cáo

### Bước 4: Chuyển đổi giữa các tab
- **Tab 1 - Báo cáo theo Task**: Xem chi tiết task theo dự án
- **Tab 2 - Báo cáo theo Dự án**: Xem khối lượng công việc theo dự án
- **Tab 3 - Dự án sắp hết hạn**: Xem danh sách dự án sắp hết hạn

---

## Các route được sử dụng

```php
// routes/admin.php
Route::get('role/report', 'AdminRoleController@report')->name('role.report');
Route::get('role/report2', 'AdminRoleController@report2')->name('role.report2');
Route::get('role/report3', 'AdminRoleController@report3')->name('role.report3');
```

---

## Giao diện

### Tab 1: Báo cáo theo Task
- Hiển thị dạng accordion (có thể mở/đóng) theo nhân sự
- Mỗi nhân sự có bảng chi tiết các dự án với:
  - Tổng task, Mới, Trễ hạn, Hoàn thành
  - Đúng hạn, Trễ hạn, % Đúng hạn

### Tab 2: Báo cáo theo Dự án
- Hiển thị dạng accordion theo nhân sự
- Header hiển thị tổng hợp:
  - Tổng KL, Hoàn thành, Số dự án Branding/Marketing/Video
- Bảng chi tiết với progress bar hiển thị tiến độ

### Tab 3: Dự án sắp hết hạn
- Hiển thị dạng accordion theo nhân sự
- Header hiển thị số lượng dự án sắp hết hạn
- Bảng liệt kê các dự án với ngày hết hạn

---

## Lưu ý kỹ thuật

1. **Load song song**: Khi nhấn "Xem báo cáo", cả 3 API được gọi song song để tăng tốc độ

2. **Lazy loading**: Tab "Báo cáo theo Dự án" và "Dự án sắp hết hạn" chỉ reload khi chuyển tab (nếu đã load lần đầu)

3. **Caching**: Sử dụng `window.departmentReportLoaded` để tránh load lại không cần thiết

4. **Responsive**: Tất cả bảng đều có class `table-responsive` để hiển thị tốt trên mobile

5. **Visual feedback**: Có loading spinner khi đang tải dữ liệu

---

## Điểm khác biệt so với báo cáo cũ

**Trước đây**: 
- Chỉ có báo cáo theo Task (report)
- Phải vào màn hình riêng để xem báo cáo khác

**Bây giờ**:
- Tích hợp đầy đủ 3 loại báo cáo trong 1 màn hình
- Dễ dàng chuyển đổi giữa các loại báo cáo
- Hiển thị trực quan hơn với progress bar, badge, label màu sắc
- Load nhanh hơn với AJAX song song
