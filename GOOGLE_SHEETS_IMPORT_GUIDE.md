# Google Sheets Import for Tickets

## Hướng dẫn sử dụng tính năng Import từ Google Sheets

### Chuẩn bị Google Sheets

1. **Tạo hoặc mở Google Sheets** với link: https://docs.google.com/spreadsheets/d/1-2LO-6XKqAiwysFnsp-Y_B_qqfr8rZkSExn5wdBA3DY/edit?gid=965454359#gid=965454359

2. **Đảm bảo cột đầu tiên có các tiêu đề sau** (chính xác tên này):
   - `chu_de` - Chủ đề/Tên công việc (BẮT BUỘC)
   - `mo_ta` - Mô tả chi tiết
   - `khach_duyet` - Thông tin khách duyệt
   - `san_pham` - Sản phẩm
   - `deadline` - Ngày hết hạn (có thể để trống)
   - `ghi_chu` - Ghi chú thêm
   - `nguoi_xu_ly` - Người xử lý/phụ trách (username, có thể nhiều người cách nhau bởi dấu phẩy hoặc chấm phẩy)

3. **Chia sẻ Google Sheets công khai**:
   - Click nút "Share" (Chia sẻ)
   - Chọn "Anyone with the link" (Bất kỳ ai có link)
   - Đảm bảo quyền là "Viewer" (Người xem)

### Định dạng dữ liệu

#### Cột Deadline
- Có thể để trống
- Hoặc nhập ngày theo các định dạng:
  - DD/MM/YYYY (ví dụ: 25/12/2024)
  - YYYY-MM-DD (ví dụ: 2024-12-25)
  - Hoặc sử dụng định dạng ngày của Google Sheets

#### Các cột văn bản
- Nhập tự do, không giới hạn độ dài
- Có thể để trống (trừ cột `chu_de`)

#### Cột Người xử lý (nguoi_xu_ly)
- Nhập username của người xử lý trong hệ thống
- Có thể để trống (ticket sẽ không có người xử lý)
- Hỗ trợ nhiều người xử lý, cách nhau bởi dấu phẩy (,) hoặc chấm phẩy (;)
- Ví dụ: `admin1, admin2` hoặc `admin1; admin2`
- Username không phân biệt chữ hoa/chữ thường
- Nếu username không tồn tại trong hệ thống, sẽ bị bỏ qua

### Sử dụng tính năng Import

1. Vào trang danh sách Ticket của nhóm/dự án
2. Click nút **"Import Google Sheet"**
3. Dán link Google Sheets vào ô input
4. Click **"Import dữ liệu"**
5. Đợi hệ thống xử lý (có thông báo loading)
6. Kiểm tra kết quả import

### Lưu ý quan trọng

- ✅ Dòng đầu tiên PHẢI là tiêu đề cột
- ✅ Google Sheets PHẢI được chia sẻ công khai
- ✅ Cột `chu_de` là bắt buộc, không được để trống
- ✅ Các tickets được import sẽ có:
  - Trạng thái: Chưa hoàn thành (status = 0)
  - Khối lượng: 1
  - Độ ưu tiên: 2 (Trung bình)
  - Người tạo: User hiện tại
  - Project/Group/Phase: Theo context hiện tại

### Xử lý lỗi

Nếu gặp lỗi:
1. Kiểm tra Google Sheets đã public chưa
2. Kiểm tra tên các cột tiêu đề có đúng không
3. Kiểm tra link có hợp lệ không
4. Đảm bảo cột `chu_de` không có dòng nào trống

### Ví dụ cấu trúc Google Sheets

| chu_de | mo_ta | khach_duyet | san_pham | deadline | ghi_chu | nguoi_xu_ly |
|--------|-------|-------------|----------|----------|---------|-------------|
| Thiết kế banner | Banner quảng cáo sản phẩm mới | Anh Nam | File PSD, PNG | 25/12/2024 | Ưu tiên cao | admin1, admin2 |
| Viết content | Bài viết giới thiệu | Chị Hoa | File Word | 30/12/2024 | | admin3 |
| Chỉnh sửa video | Video review sản phẩm | Anh Tuấn | File MP4 | | Cần phê duyệt trước | admin1; admin3 |
| Làm mockup | Mockup website | | | 28/12/2024 | | |

## Thông tin kỹ thuật

### Files liên quan
- Import Class: `app/Imports/Ticket/TicketImport.php`
- Controller: `app/Http/Controllers/Admin/AdminTicketController.php`
- Route: `routes/admin.php` - `POST /ticket/import-google-sheet`
- View: `resources/views/admin/ticket/index2.blade.php`

### Packages sử dụng
- `maatwebsite/excel` - Xử lý file Excel/Google Sheets
- `guzzlehttp/guzzle` - Download file từ Google Sheets

### Quy trình xử lý
1. User nhập URL Google Sheets
2. Hệ thống extract Spreadsheet ID và Sheet ID (gid)
3. Convert URL sang export URL (format xlsx)
4. Download file về thư mục tạm
5. Parse file Excel sử dụng maatwebsite/excel
6. Import dữ liệu vào database
7. Xóa file tạm
8. Trả về kết quả
