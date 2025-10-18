# Checklist để sử dụng tính năng Import Google Sheets

## ✅ Checklist chuẩn bị Google Sheets

### 1. Cấu trúc Header (Dòng đầu tiên)
- [ ] Có cột `chu_de` (Chủ đề) - **BẮT BUỘC**
- [ ] Có cột `mo_ta` (Mô tả)
- [ ] Có cột `khach_duyet` (Khách duyệt)
- [ ] Có cột `san_pham` (Sản phẩm)
- [ ] Có cột `deadline` (Deadline)
- [ ] Có cột `ghi_chu` (Ghi chú)

**Lưu ý:** Tên cột phải viết chính xác như trên (không dấu, chữ thường, gạch dưới)

### 2. Quyền chia sẻ
- [ ] Google Sheets được set là "Anyone with the link"
- [ ] Quyền tối thiểu: "Viewer" (Người xem)
- [ ] Test bằng cách mở link ở chế độ ẩn danh (Incognito)

### 3. Dữ liệu
- [ ] Cột `chu_de` không có dòng nào trống
- [ ] Cột `deadline` (nếu có) đúng định dạng ngày tháng
- [ ] Không có dữ liệu lỗi hoặc ký tự đặc biệt gây lỗi

## ✅ Checklist hệ thống

### 1. Dependencies
- [ ] Package `maatwebsite/excel` đã được cài đặt
- [ ] Package `guzzlehttp/guzzle` đã được cài đặt
- [ ] Laravel framework version >= 10.x

### 2. Files đã tạo
- [ ] `app/Imports/Ticket/TicketImport.php`
- [ ] Route đã thêm vào `routes/admin.php`
- [ ] Controller method `importFromGoogleSheet` đã thêm
- [ ] View có button và modal import
- [ ] JavaScript xử lý AJAX đã thêm

### 3. Database & Permissions
- [ ] Table `ticket` tồn tại
- [ ] Table `project`, `group`, `phase` tồn tại
- [ ] User đã đăng nhập
- [ ] User có quyền tạo ticket trong project/group

## ✅ Checklist test tính năng

### 1. Trước khi import
- [ ] Đăng nhập vào hệ thống
- [ ] Vào đúng project/group/phase cần import
- [ ] URL Google Sheets đã copy chính xác

### 2. Trong quá trình import
- [ ] Click button "Import Google Sheet"
- [ ] Paste URL vào ô input
- [ ] Click "Import dữ liệu"
- [ ] Thấy loading indicator
- [ ] Không có lỗi JavaScript trong Console

### 3. Sau khi import
- [ ] Thấy thông báo thành công
- [ ] Trang tự động reload
- [ ] Dữ liệu hiển thị trong danh sách ticket
- [ ] Số lượng ticket khớp với số dòng trong Google Sheets
- [ ] Kiểm tra nội dung một vài ticket để đảm bảo đúng

## ✅ Troubleshooting Checklist

### Nếu gặp lỗi "URL không hợp lệ"
- [ ] URL có chứa `/spreadsheets/d/`?
- [ ] URL có copy đầy đủ không?
- [ ] URL có ký tự đặc biệt không mã hóa?

### Nếu gặp lỗi "Không thể tải file"
- [ ] Google Sheets đã public?
- [ ] Test mở link ở chế độ ẩn danh
- [ ] Kiểm tra internet connection
- [ ] Thử export URL: `https://docs.google.com/spreadsheets/d/{ID}/export?format=xlsx&gid={GID}`

### Nếu import thành công nhưng không có dữ liệu
- [ ] Kiểm tra header có đúng tên không?
- [ ] Có dữ liệu ở dòng 2 trở đi không?
- [ ] Cột `chu_de` có dữ liệu không?
- [ ] Kiểm tra logs: `storage/logs/laravel.log`

### Nếu một số tickets không import
- [ ] Kiểm tra cột `chu_de` có trống không?
- [ ] Kiểm tra định dạng deadline
- [ ] Xem chi tiết lỗi trong logs

## ✅ Quick Test với dữ liệu mẫu

### Bước 1: Tạo Google Sheets test
1. Tạo Google Sheets mới
2. Copy nội dung từ `storage/app/ticket_import_template.csv`
3. Paste vào Google Sheets
4. Chia sẻ công khai

### Bước 2: Test import
1. Vào trang ticket của project/group bất kỳ
2. Click "Import Google Sheet"
3. Paste URL của sheet test
4. Click import
5. Verify kết quả

## ✅ Export URL Test

URL của bạn:
```
https://docs.google.com/spreadsheets/d/1-2LO-6XKqAiwysFnsp-Y_B_qqfr8rZkSExn5wdBA3DY/edit?gid=965454359#gid=965454359
```

Export URL tương ứng:
```
https://docs.google.com/spreadsheets/d/1-2LO-6XKqAiwysFnsp-Y_B_qqfr8rZkSExn5wdBA3DY/export?format=xlsx&gid=965454359
```

Test steps:
- [ ] Mở export URL trong browser
- [ ] File .xlsx tự động download
- [ ] Mở file và kiểm tra dữ liệu

## 📝 Notes

- Import có thể mất vài giây tùy số lượng dòng
- Hệ thống xử lý 1000 records mỗi batch
- Temp files tự động được xóa sau khi import
- Nếu import lỗi, không có dữ liệu nào được thêm vào database
