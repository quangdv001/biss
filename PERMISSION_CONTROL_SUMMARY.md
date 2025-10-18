# Quyền hạn tạo Ticket theo Group

## Tổng quan thay đổi

Đã thêm logic kiểm soát quyền tạo ticket dựa trên role của group:

### 1. **Design & Design2**
- ❌ **Chỉ Admin (super_admin) được tạo ticket**
- ❌ User khác KHÔNG được tạo ticket
- ⚠️ Deadline phải ít nhất là ngày mai

### 2. **Content_SEO** (Mới thêm)
- ✅ **Admin (super_admin) được tạo ticket**
- ✅ **User có role SEO được tạo ticket**
- ❌ User khác KHÔNG được tạo ticket

### 3. **Các group khác**
- ✅ Tất cả user đã đăng nhập đều có thể tạo ticket

## Chi tiết triển khai

### File thay đổi
`app/Http/Controllers/Admin/AdminTicketController.php`

### Các method được cập nhật

#### 1. Method `createAjax` (Tạo ticket qua AJAX)
```php
if ($currentGroup) {
    $role = $this->role->first(['id' => $currentGroup->role_id]);
    
    // Kiểm tra Design và Design2
    if ($role && in_array(@$role->slug, ['Design', 'Design2'])) {
        if(!$user->hasRole(['super_admin'])){
            return response()->json([
                'success' => 0,
                'mess' => 'Bạn không có quyền tạo ticket group Thiết kế!'
            ]);
        }
        // Kiểm tra deadline phải từ ngày mai
    }
    
    // Kiểm tra Content_SEO
    if ($role && in_array(@$role->slug, ['content_seo'])) {
        $isSuperAdmin = $user->hasRole(['super_admin']);
        $isSeoUser = $user->hasRole(['seo']);
        
        if (!$isSuperAdmin && !$isSeoUser) {
            return response()->json([
                'success' => 0,
                'mess' => 'Bạn không có quyền tạo ticket group Content SEO!'
            ]);
        }
    }
}
```

#### 2. Method `importFromGoogleSheet` (Import từ Google Sheets)
```php
// Kiểm tra quyền import theo group
$currentGroup = $this->groupRepo->first(['id' => $groupId]);
if ($currentGroup) {
    $role = $this->role->first(['id' => $currentGroup->role_id]);
    
    // Kiểm tra quyền cho Design và Design2
    if ($role && in_array(@$role->slug, ['Design', 'Design2'])) {
        if(!$user->hasRole(['super_admin'])){
            return response()->json([
                'success' => 0,
                'mess' => 'Bạn không có quyền import ticket cho group Thiết kế!'
            ]);
        }
    }
    
    // Kiểm tra quyền cho Content_SEO
    if ($role && in_array(@$role->slug, ['content_seo'])) {
        $isSuperAdmin = $user->hasRole(['super_admin']);
        $isSeoUser = $user->hasRole(['seo']);
        
        if (!$isSuperAdmin && !$isSeoUser) {
            return response()->json([
                'success' => 0,
                'mess' => 'Bạn không có quyền import ticket cho group Content SEO!'
            ]);
        }
    }
}
```

## Role Slugs

### Các role hiện tại:
- `super_admin` - Admin tối cao
- `account` - Account manager
- `guest` - Khách hàng
- `seo` - SEO specialist
- `Design` - Designer
- `Design2` - Designer level 2
- `content_seo` - Content SEO (cần thêm vào database nếu chưa có)

## Thông báo lỗi

### Design/Design2:
```
"Bạn không có quyền tạo ticket group Thiết kế!"
"Bạn không có quyền import ticket cho group Thiết kế!"
```

### Content_SEO:
```
"Bạn không có quyền tạo ticket group Content SEO!"
"Bạn không có quyền import ticket cho group Content SEO!"
```

## Testing

### Test case 1: User không phải admin tạo ticket Design
```
User: normal_user (không có role super_admin)
Group: Design/Design2
Expected: ❌ Thông báo lỗi "Bạn không có quyền..."
```

### Test case 2: Admin tạo ticket Design
```
User: admin (có role super_admin)
Group: Design/Design2
Expected: ✅ Tạo thành công
```

### Test case 3: User SEO tạo ticket Content_SEO
```
User: seo_user (có role seo)
Group: Content_SEO
Expected: ✅ Tạo thành công
```

### Test case 4: User thường tạo ticket Content_SEO
```
User: normal_user (không có role super_admin hoặc seo)
Group: Content_SEO
Expected: ❌ Thông báo lỗi "Bạn không có quyền..."
```

### Test case 5: Import Google Sheets cho group Design
```
User: admin (có role super_admin)
Group: Design
Action: Import từ Google Sheets
Expected: ✅ Import thành công
```

### Test case 6: Import Google Sheets cho group Content_SEO
```
User: seo_user (có role seo)
Group: Content_SEO
Action: Import từ Google Sheets
Expected: ✅ Import thành công
```

## Lưu ý quan trọng

### 1. Cần thêm role Content_SEO vào database
Nếu chưa có, cần chạy migration hoặc insert thủ công:
```sql
INSERT INTO role (name, slug) VALUES ('Content SEO', 'content_seo');
```

### 2. Gán role cho users
Đảm bảo users được gán đúng role trong bảng `admin_role`:
```sql
-- Gán role SEO cho user
INSERT INTO admin_role (admin_id, role_id) 
VALUES (user_id, (SELECT id FROM role WHERE slug = 'seo'));
```

### 3. Logic áp dụng cho:
- ✅ Tạo ticket mới (method `createAjax`)
- ✅ Import từ Google Sheets (method `importFromGoogleSheet`)
- ⚠️ Chưa áp dụng cho method `create` (form submission thông thường)

### 4. Deadline check
Chỉ áp dụng cho Design/Design2:
- Deadline phải từ ngày mai trở đi
- Content_SEO không có ràng buộc deadline

## Migration cần thiết (nếu chưa có)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddContentSeoRole extends Migration
{
    public function up()
    {
        DB::table('role')->insert([
            'name' => 'Content SEO',
            'slug' => 'content_seo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('role')->where('slug', 'content_seo')->delete();
    }
}
```

## Rollback

Nếu cần rollback các thay đổi, xóa các đoạn code kiểm tra Content_SEO trong:
- Line ~268-274 trong method `createAjax`
- Line ~476-485 trong method `importFromGoogleSheet`

---

**Ngày cập nhật:** October 18, 2025  
**Phiên bản:** 1.0  
**Status:** ✅ Đã triển khai
