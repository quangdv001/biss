# Tính năng Import Ticket từ Google Sheets - Tóm tắt

## Các file đã tạo/chỉnh sửa

### 1. Import Class
**File:** `app/Imports/Ticket/TicketImport.php`
- Class xử lý import dữ liệu từ Google Sheets
- Implements `ToCollection` và `WithHeadingRow` từ maatwebsite/excel
- Nhận parameters: projectId, groupId, phaseId, adminIdC
- Xử lý mapping dữ liệu từ sheet vào database

### 2. Controller
**File:** `app/Http/Controllers/Admin/AdminTicketController.php`
- Thêm method `importFromGoogleSheet(Request $request)`
- Validate URL và parameters
- Download file từ Google Sheets
- Gọi Import class để xử lý dữ liệu
- Trả về JSON response

### 3. Repository
**File:** `app/Repo/TicketRepo.php`
- Thêm method `insert($data)` để bulk insert dữ liệu

### 4. Routes
**File:** `routes/admin.php`
- Thêm route: `POST /admin/ticket/import-google-sheet`
- Named route: `admin.ticket.importGoogleSheet`

### 5. View
**File:** `resources/views/admin/ticket/index2.blade.php`
- Thêm button "Import Google Sheet"
- Thêm modal `#modalImportGoogleSheet`
- Thêm JavaScript xử lý form submit với AJAX
- Hiển thị loading và thông báo kết quả

### 6. Documentation
**File:** `GOOGLE_SHEETS_IMPORT_GUIDE.md`
- Hướng dẫn chi tiết cách sử dụng
- Các lưu ý quan trọng
- Ví dụ cụ thể

### 7. Template
**File:** `storage/app/ticket_import_template.csv`
- File mẫu có thể import vào Google Sheets
- Chứa dữ liệu ví dụ

## Cách sử dụng

### Bước 1: Chuẩn bị Google Sheets
1. Tạo Google Sheets với cấu trúc:
   ```
   chu_de | mo_ta | khach_duyet | san_pham | deadline | ghi_chu
   ```
2. Chia sẻ công khai (Anyone with the link - Viewer)
3. Nhập dữ liệu vào các dòng tiếp theo

### Bước 2: Import vào hệ thống
1. Vào trang danh sách Ticket
2. Click "Import Google Sheet"
3. Paste link Google Sheets
4. Click "Import dữ liệu"
5. Đợi xử lý và kiểm tra kết quả

## Quy trình xử lý

```
User nhập URL
    ↓
Extract Spreadsheet ID & GID
    ↓
Convert to Export URL (.xlsx)
    ↓
Download file về temp folder
    ↓
Parse Excel file (maatwebsite/excel)
    ↓
Map data to database format
    ↓
Bulk insert (chunks of 1000)
    ↓
Delete temp file
    ↓
Return success/error response
```

## Mapping dữ liệu

| Google Sheets Column | Database Column | Note |
|---------------------|-----------------|------|
| chu_de | name | Required |
| mo_ta | description | Optional |
| khach_duyet | input | Optional |
| san_pham | output | Optional |
| deadline | deadline_time | Optional, auto convert |
| ghi_chu | note | Optional |
| - | status | Default: 0 |
| - | qty | Default: 1 |
| - | priority | Default: 2 |
| - | created_time | Auto: time() |
| - | admin_id_c | Auto: current user |
| - | project_id | From parameter |
| - | group_id | From parameter |
| - | phase_id | From parameter |
| - | parent_id | Default: 0 |

## Features

✅ **Bulk Import**: Import nhiều tickets cùng lúc
✅ **Auto Date Parsing**: Tự động chuyển đổi deadline
✅ **Validation**: Kiểm tra URL và dữ liệu
✅ **Error Handling**: Xử lý lỗi chi tiết
✅ **Loading Indicator**: Hiển thị trạng thái xử lý
✅ **Success Notification**: Thông báo kết quả
✅ **Auto Redirect**: Tự động reload sau khi import
✅ **Public Sheet Support**: Hỗ trợ Google Sheets public

## Lưu ý kỹ thuật

### Dependencies
- `maatwebsite/excel`: ^3.1
- `guzzlehttp/guzzle`: ^7.2
- Laravel Framework: ^10.10

### Permissions
- Google Sheets phải được chia sẻ công khai
- User phải có quyền tạo ticket trong project/group

### Performance
- Batch insert 1000 records mỗi lần
- Sử dụng temp file để tránh memory issues
- Auto cleanup temp files

### Security
- Validate URL format
- Validate project/group/phase existence
- CSRF protection
- Authentication required

## Testing với link của bạn

URL của bạn:
```
https://docs.google.com/spreadsheets/d/1-2LO-6XKqAiwysFnsp-Y_B_qqfr8rZkSExn5wdBA3DY/edit?gid=965454359#gid=965454359
```

Để test:
1. Đảm bảo sheet có header row với tên cột: chu_de, mo_ta, khach_duyet, san_pham, deadline, ghi_chu
2. Chia sẻ công khai
3. Sử dụng tính năng import trên UI
4. Kiểm tra dữ liệu đã import vào database

## Troubleshooting

### Lỗi "URL không hợp lệ"
- Kiểm tra format URL
- Đảm bảo có /spreadsheets/d/ trong URL

### Lỗi "Không thể tải file"
- Kiểm tra sheet đã public chưa
- Kiểm tra internet connection

### Dữ liệu không import
- Kiểm tra tên cột header
- Kiểm tra cột chu_de không trống
- Xem logs trong storage/logs/laravel.log

### Deadline không đúng
- Sử dụng format DD/MM/YYYY
- Hoặc để Google Sheets format as Date
