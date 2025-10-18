# Hướng dẫn chuẩn bị Google Sheets chi tiết

## 📋 Cấu trúc Google Sheets

### Ví dụ chính xác cấu trúc header và data:

```
Row 1 (Header - BẮT BUỘC):
+----------+--------+--------------+-----------+------------+---------+
| chu_de   | mo_ta  | khach_duyet  | san_pham  | deadline   | ghi_chu |
+----------+--------+--------------+-----------+------------+---------+

Row 2+ (Data):
+----------+--------+--------------+-----------+------------+---------+
| Banner   | Thiết  | Anh Nam      | PSD, PNG  | 25/12/2024 | Gấp     |
| quảng    | kế     |              |           |            |         |
| cáo      | banner |              |           |            |         |
+----------+--------+--------------+-----------+------------+---------+
```

## 🔧 Các bước setup Google Sheets từ đầu

### Bước 1: Tạo sheet mới hoặc sử dụng sheet hiện có

1. Vào Google Sheets: https://sheets.google.com
2. Tạo mới hoặc mở sheet có sẵn
3. Chọn sheet tab cần import (lưu ý gid trong URL)

### Bước 2: Setup Header (Dòng 1)

**QUAN TRỌNG:** Header phải viết chính xác như sau (không dấu, chữ thường):

- Cell A1: `chu_de`
- Cell B1: `mo_ta`
- Cell C1: `khach_duyet`
- Cell D1: `san_pham`
- Cell E1: `deadline`
- Cell F1: `ghi_chu`

**Lưu ý:**
- ✅ ĐÚNG: `chu_de` (chữ thường, gạch dưới)
- ❌ SAI: `Chủ đề`, `CHU_DE`, `chu de`, `Chu_De`

### Bước 3: Nhập dữ liệu (từ dòng 2 trở đi)

#### Cột A: chu_de (BẮT BUỘC - không được để trống)
- Tên công việc / Tiêu đề ticket
- Ví dụ: "Thiết kế banner quảng cáo"
- ❌ KHÔNG được để trống

#### Cột B: mo_ta (Tùy chọn)
- Mô tả chi tiết công việc
- Có thể nhiều dòng, nhiều ký tự
- Ví dụ: "Banner cho sản phẩm mới ra mắt tháng 12, kích thước 2000x1000px"

#### Cột C: khach_duyet (Tùy chọn)
- Người hoặc bộ phận duyệt công việc
- Ví dụ: "Anh Nam - Marketing", "Chị Hoa", "Ban giám đốc"

#### Cột D: san_pham (Tùy chọn)
- Sản phẩm cần bàn giao
- Ví dụ: "File PSD + PNG", "Video MP4", "Document Word"

#### Cột E: deadline (Tùy chọn)
- Ngày hết hạn
- **Định dạng chấp nhận:**
  - DD/MM/YYYY: `25/12/2024`
  - YYYY-MM-DD: `2024-12-25`
  - Hoặc dùng date picker của Google Sheets
- Có thể để trống nếu chưa có deadline

#### Cột F: ghi_chu (Tùy chọn)
- Ghi chú thêm
- Ví dụ: "Ưu tiên cao", "Cần phê duyệt trước"

### Bước 4: Chia sẻ công khai

1. Click nút "Share" (góc phải trên)
2. Trong phần "General access":
   - Click "Restricted" → Chọn "Anyone with the link"
   - Chọn role: "Viewer" (Người xem)
3. Click "Copy link" để lấy URL
4. Click "Done"

**Test:** Mở link trong chế độ Incognito để kiểm tra

### Bước 5: Copy URL đầy đủ

URL đúng format:
```
https://docs.google.com/spreadsheets/d/[SPREADSHEET_ID]/edit?gid=[SHEET_ID]#gid=[SHEET_ID]
```

Ví dụ:
```
https://docs.google.com/spreadsheets/d/1-2LO-6XKqAiwysFnsp-Y_B_qqfr8rZkSExn5wdBA3DY/edit?gid=965454359#gid=965454359
```

**Lưu ý:**
- Phải copy toàn bộ URL, bao gồm cả `gid=` parameter
- Nếu không có `gid=`, hệ thống sẽ lấy sheet đầu tiên (gid=0)

## 📊 Ví dụ hoàn chỉnh

### Sheet: "Tickets tháng 12"

| chu_de | mo_ta | khach_duyet | san_pham | deadline | ghi_chu |
|--------|-------|-------------|----------|----------|---------|
| Thiết kế banner | Banner quảng cáo sản phẩm mới | Anh Nam | File PSD, PNG | 25/12/2024 | Ưu tiên cao |
| Viết content | Bài viết giới thiệu tính năng | Chị Hoa | File Word | 30/12/2024 | 800-1000 từ |
| Chỉnh sửa video | Video review sản phẩm | Anh Tuấn | File MP4 | | Cần duyệt trước |
| Tạo mockup | Thiết kế UI đăng nhập | Chị Mai | Figma | 20/12/2024 | Theo design system |

## 🎯 Tips & Best Practices

### 1. Chuẩn bị dữ liệu
- ✅ Kiểm tra spelling trước khi import
- ✅ Format deadline đồng nhất
- ✅ Xóa các dòng trống
- ✅ Đảm bảo chu_de không trống

### 2. Tối ưu hóa
- Import nhiều tickets cùng lúc (bulk import)
- Không giới hạn số lượng dòng
- Hệ thống tự động chia nhỏ batch 1000 records

### 3. Sao lưu
- ✅ Tạo copy của sheet trước khi import
- ✅ Có thể import lại nhiều lần nếu cần
- ✅ Mỗi lần import tạo tickets mới (không update)

### 4. Kiểm tra sau import
- Số lượng tickets = Số dòng data (trừ header)
- Content khớp với Google Sheets
- Deadline hiển thị đúng
- Người tạo là user hiện tại

## 🚨 Các lỗi thường gặp

### 1. "URL không hợp lệ"
**Nguyên nhân:** URL không đúng format
**Giải pháp:** 
- Copy lại toàn bộ URL từ address bar
- Đảm bảo có `/spreadsheets/d/` trong URL

### 2. "Không thể tải file"
**Nguyên nhân:** Sheet chưa public
**Giải pháp:**
- Chia sẻ: Anyone with the link → Viewer
- Test bằng Incognito mode

### 3. "Import 0 tickets"
**Nguyên nhân:** Header không đúng hoặc data trống
**Giải pháp:**
- Kiểm tra header: chu_de, mo_ta, khach_duyet, san_pham, deadline, ghi_chu
- Kiểm tra có data từ dòng 2 không
- Cột chu_de không được trống

### 4. "Một số tickets không import"
**Nguyên nhân:** Một số dòng có chu_de trống
**Giải pháp:**
- Lọc và xóa dòng trống
- Đảm bảo mỗi dòng có chu_de

## 📞 Support

Nếu gặp vấn đề:
1. Kiểm tra checklist trong `IMPORT_CHECKLIST.md`
2. Xem logs: `storage/logs/laravel.log`
3. Chạy test: `php test_google_sheets_url.php`
4. Đọc hướng dẫn: `GOOGLE_SHEETS_IMPORT_GUIDE.md`
