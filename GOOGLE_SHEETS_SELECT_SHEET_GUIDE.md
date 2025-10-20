# Hướng dẫn chọn Sheet khi Import từ Google Sheets

## Tính năng mới

Đã thêm chức năng chọn sheet cụ thể khi import dữ liệu từ Google Sheets vào hệ thống quản lý ticket.

## Các thay đổi

### 1. Giao diện (View)
**File:** `resources/views/admin/ticket/index2.blade.php`

- Thêm nút **"Tải danh sách sheets"** bên cạnh ô nhập URL Google Sheets
- Thêm dropdown **"Chọn Sheet"** để hiển thị danh sách các sheets có trong file Google Sheets
- Dropdown sẽ ẩn ban đầu và chỉ hiển thị sau khi tải thành công danh sách sheets

### 2. JavaScript
**File:** `resources/views/admin/ticket/index2.blade.php` (phần script)

#### Chức năng "Tải danh sách sheets"
```javascript
$("#btnLoadSheets").click(function() {
    // Gửi request để lấy danh sách sheets từ Google Sheets URL
    // Hiển thị danh sách sheets trong dropdown
    // Hiện dropdown chọn sheet
});
```

**Luồng hoạt động:**
1. Người dùng nhập URL Google Sheets
2. Click nút "Tải danh sách sheets"
3. Hệ thống tải file Excel và đọc danh sách tất cả các sheets
4. Hiển thị danh sách sheets trong dropdown
5. Người dùng chọn sheet muốn import
6. Click "Import dữ liệu"

### 3. Route
**File:** `routes/admin.php`

Thêm route mới:
```php
Route::post('ticket/get-google-sheets', 'AdminTicketController@getGoogleSheets')
    ->name('ticket.getGoogleSheets');
```

### 4. Controller
**File:** `app/Http/Controllers/Admin/AdminTicketController.php`

#### Method mới: `getGoogleSheets()`
- **Mục đích:** Lấy danh sách tất cả các sheets trong Google Sheets
- **Cách hoạt động:**
  1. Nhận URL Google Sheets từ request
  2. Trích xuất Spreadsheet ID từ URL
  3. Download file Excel từ Google Sheets
  4. Sử dụng PhpSpreadsheet để đọc tất cả sheets
  5. Trả về danh sách sheets với: title, gid, index

#### Method cập nhật: `importFromGoogleSheet()`
- Thêm parameter `sheet_gid` để nhận sheet được chọn
- Truyền `sheetIndex` vào class `TicketImport`
- Import chỉ sheet được chọn thay vì tất cả sheets

### 5. Import Class
**File:** `app/Imports/Ticket/TicketImport.php`

**Thay đổi:**
- Implement thêm interface `WithMultipleSheets`
- Thêm property `$sheetIndex`
- Thêm method `sheets()` để chỉ định sheet nào được import
- Cập nhật constructor để nhận `$sheetIndex`

**Cách hoạt động:**
- Nếu `$sheetIndex` được chỉ định → Import chỉ sheet đó
- Nếu `$sheetIndex` là null → Import sheet đầu tiên (index 0)

## Cách sử dụng

### Bước 1: Mở modal Import Google Sheets
Click vào nút **"Import Google Sheet"**

### Bước 2: Nhập URL Google Sheets
Dán URL của Google Sheets vào ô **"URL Google Sheets"**

Ví dụ:
```
https://docs.google.com/spreadsheets/d/1ABC123xyz.../edit#gid=0
```

### Bước 3: Tải danh sách sheets
Click nút **"Tải danh sách sheets"**

Hệ thống sẽ:
- Tải file từ Google Sheets
- Hiển thị tất cả các sheets có trong file
- Hiện dropdown để chọn sheet

### Bước 4: Chọn sheet để import
Trong dropdown **"Chọn Sheet"**, chọn sheet bạn muốn import dữ liệu

### Bước 5: Import dữ liệu
Click nút **"Import dữ liệu"**

## Lưu ý quan trọng

1. **Google Sheets phải được chia sẻ công khai**
   - Anyone with the link can view
   - Nếu không, hệ thống không thể tải file

2. **Cấu trúc dữ liệu**
   - Hàng 1: Có thể để tiêu đề hoặc ghi chú
   - Hàng 2: Tiêu đề cột (chu_de, mo_ta, khach_duyet, san_pham, deadline, ghi_chu)
   - Hàng 3 trở đi: Dữ liệu cần import

3. **Định dạng các cột**
   - `chu_de` (Chủ đề): Bắt buộc
   - `mo_ta` (Mô tả): Tùy chọn
   - `khach_duyet` (Khách duyệt): Tùy chọn
   - `san_pham` (Sản phẩm): Tùy chọn
   - `deadline` (Deadline): Tùy chọn, có thể là số serial Excel hoặc chuỗi ngày tháng
   - `ghi_chu` (Ghi chú): Tùy chọn

4. **Nếu không chọn sheet**
   - Hệ thống sẽ tự động import từ sheet đầu tiên

## Ví dụ minh họa

### Trường hợp 1: File có nhiều sheets
```
Sheet 1: "Dữ liệu tháng 1"
Sheet 2: "Dữ liệu tháng 2"  
Sheet 3: "Dữ liệu tháng 3"
```

→ Người dùng có thể chọn chính xác sheet nào muốn import

### Trường hợp 2: File có 1 sheet
```
Sheet 1: "Sheet1"
```

→ Người dùng vẫn có thể xem và chọn sheet, hoặc bỏ qua bước này

## Xử lý lỗi

Hệ thống sẽ hiển thị thông báo lỗi nếu:
- URL Google Sheets không hợp lệ
- Không thể tải file từ Google Sheets (vấn đề quyền truy cập)
- Không tìm thấy sheet nào trong file
- Có lỗi khi đọc dữ liệu từ sheet

## Kỹ thuật

### Công nghệ sử dụng
- **PhpSpreadsheet**: Đọc file Excel và lấy thông tin các sheets
- **Guzzle HTTP Client**: Download file từ Google Sheets
- **Maatwebsite Excel**: Xử lý import dữ liệu vào Laravel
- **jQuery AJAX**: Giao tiếp giữa frontend và backend

### Flow dữ liệu
```
User Input URL 
→ Click "Tải danh sách sheets"
→ AJAX Request to getGoogleSheets()
→ Download Excel file
→ Parse sheets using PhpSpreadsheet
→ Return sheets list
→ Display in dropdown
→ User selects sheet
→ Submit form with sheet_gid
→ importFromGoogleSheet() with sheetIndex
→ Import only selected sheet
→ Success!
```
