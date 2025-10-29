# RESTful API Documentation

## Base URL
```
/api
```

## Authentication
Tất cả các API endpoint yêu cầu authentication qua Laravel Sanctum.

### Headers
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

---

## Endpoints

### 1. Get Current User Information

**Endpoint:** `GET /api/user`

**Description:** Lấy thông tin của user đang đăng nhập

**Response Success (200):**
```json
{
  "id": 1,
  "username": "admin",
  "email": "admin@example.com",
  "phone": "0123456789",
  "address": "Ha Noi",
  "status": 1,
  "avatar": "/assets/images/avatar.jpg",
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-10-29T00:00:00.000000Z",
  "roles": [
    {
      "id": 1,
      "name": "Super Admin",
      "slug": "super_admin"
    }
  ]
}
```

**Response Error (401):**
```json
{
  "message": "Unauthenticated."
}
```

---

### 2. Get List of Projects

**Endpoint:** `GET /api/projects`

**Description:** Lấy danh sách dự án với các điều kiện lọc

**Query Parameters:**
- `limit` (integer, optional): Số lượng items trên mỗi trang (default: 20)
- `name` (string, optional): Tìm kiếm theo tên dự án
- `field` (string, optional): Lọc theo field
- `status` (integer, optional): Lọc theo trạng thái
  - `1`: Đang hoạt động và chưa hết hạn
  - `2`: Đã hết hạn
- `type` (integer, optional): Lọc theo loại dự án
  - `1`: Marketing
  - `2`: Branding
  - `3`: Video
- `order` (string, optional): Sắp xếp theo field (default: 'id')

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Project Name",
      "description": "Project description",
      "status": 1,
      "type": 1,
      "field": "field_name",
      "package": "package_info",
      "payment_month": "12",
      "fanpage": "https://facebook.com/...",
      "website": "https://example.com",
      "accept_time": 1698537600,
      "expired_time": 1730160000,
      "created_time": 1698537600,
      "planer": {
        "id": 1,
        "username": "planer_name"
      },
      "executive": {
        "id": 2,
        "username": "executive_name"
      },
      "admin": [
        {
          "id": 3,
          "username": "admin_name"
        }
      ],
      "ticket": []
    }
  ],
  "pagination": {
    "total": 100,
    "per_page": 20,
    "current_page": 1,
    "last_page": 5,
    "from": 1,
    "to": 20
  }
}
```

**Response Error (500):**
```json
{
  "success": false,
  "message": "Error fetching projects",
  "error": "Error details"
}
```

---

### 3. Get Groups by Project ID

**Endpoint:** `GET /api/projects/{projectId}/groups`

**Description:** Lấy danh sách nhóm công việc theo project_id

**URL Parameters:**
- `projectId` (integer, required): ID của dự án

**Query Parameters:**
- `phase_id` (integer, optional): ID của phase (nếu không truyền, sẽ lấy phase mới nhất)

**Response Success (200):**
```json
{
  "success": true,
  "project": {
    "id": 1,
    "name": "Project Name",
    "description": "Project description"
  },
  "phase_id": 1,
  "data": [
    {
      "id": 1,
      "name": "Group Name",
      "project_id": 1,
      "role_id": 1,
      "created_time": 1698537600,
      "role": {
        "id": 1,
        "name": "Role Name",
        "slug": "role-slug"
      }
    }
  ]
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Project not found"
}
```

**Response Error (403):**
```json
{
  "success": false,
  "message": "You do not have permission to access this project"
}
```

---

### 4. Get Tickets by Group ID

**Endpoint:** `GET /api/groups/{groupId}/tickets`

**Description:** Lấy danh sách ticket theo group_id

**URL Parameters:**
- `groupId` (integer, required): ID của nhóm

**Query Parameters:**
- `phase_id` (integer, optional): Lọc theo phase_id (nếu không truyền, sẽ lấy phase mới nhất của dự án)

**Response Success (200):**
```json
{
  "success": true,
  "group": {
    "id": 1,
    "name": "Group Name",
    "project_id": 1,
    "project_name": "Project Name"
  },
  "phase_id": 1,
  "data": [
    {
      "id": 1,
      "name": "Task Name",
      "description": "Task description",
      "note": "Task note",
      "input": "Input info",
      "output": "Output info",
      "qty": 1,
      "priority": 2,
      "deadline_time": 1698537600,
      "complete_time": null,
      "status": 0,
      "status_label": "New",
      "status_class": "success",
      "project_id": 1,
      "group_id": 1,
      "phase_id": 1,
      "admin_id_c": 1,
      "created_time": 1698537600,
      "admin": [
        {
          "id": 2,
          "username": "assigned_user"
        }
      ],
      "creator": {
        "id": 1,
        "username": "creator_name"
      },
      "child": null
    }
  ]
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Group not found"
}
```

**Response Error (403):**
```json
{
  "success": false,
  "message": "You do not have permission to access this group"
}
```

---

### 5. Get Ticket Detail

**Endpoint:** `GET /api/tickets/{ticketId}`

**Description:** Lấy chi tiết ticket theo ID

**URL Parameters:**
- `ticketId` (integer, required): ID của ticket

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Task Name",
    "description": "Task description",
    "note": "Task note",
    "input": "Input info",
    "output": "Output info",
    "qty": 1,
    "priority": 2,
    "deadline_time": 1698537600,
    "complete_time": null,
    "status": 0,
    "status_label": "New",
    "status_class": "success",
    "project_id": 1,
    "group_id": 1,
    "phase_id": 1,
    "admin_id_c": 1,
    "created_time": 1698537600,
    "admin": [
      {
        "id": 2,
        "username": "assigned_user",
        "email": "user@example.com"
      }
    ],
    "creator": {
      "id": 1,
      "username": "creator_name"
    },
    "project": {
      "id": 1,
      "name": "Project Name",
      "expired_time": 1730160000
    },
    "group": {
      "id": 1,
      "name": "Group Name"
    },
    "child": null
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Ticket not found"
}
```

**Response Error (403):**
```json
{
  "success": false,
  "message": "You do not have permission to access this ticket"
}
```

---

### 6. Update Ticket

**Endpoint:** `PUT /api/tickets/{ticketId}` hoặc `PATCH /api/tickets/{ticketId}`

**Description:** Cập nhật thông tin ticket

**URL Parameters:**
- `ticketId` (integer, required): ID của ticket

**Request Body:**
```json
{
  "name": "Updated Task Name",
  "description": "Updated description",
  "note": "Updated note",
  "input": "Updated input",
  "output": "Updated output",
  "qty": 2,
  "priority": 3,
  "deadline_time": "2024-11-30",
  "status": true,
  "admin": [2, 3, 4]
}
```

**Request Body Parameters:**
- `name` (string, optional): Tên task (max 255 ký tự)
- `description` (string, optional, nullable): Mô tả
- `note` (string, optional, nullable): Ghi chú
- `input` (string, optional, nullable): Khách duyệt
- `output` (string, optional, nullable): Sản phẩm
- `qty` (integer, optional): Khối lượng (min: 1)
- `priority` (integer, optional): Độ ưu tiên (1: Thấp, 2: Trung bình, 3: Cao)
- `deadline_time` (date, optional): Deadline (format: Y-m-d)
- `status` (boolean, optional): Trạng thái (true: Hoàn thành, false: Chưa làm)
- `admin` (array, optional): Mảng ID người xử lý (chỉ admin hoặc creator mới cập nhật được)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Ticket updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Task Name",
    "description": "Updated description",
    "note": "Updated note",
    "input": "Updated input",
    "output": "Updated output",
    "qty": 2,
    "priority": 3,
    "deadline_time": 1701302400,
    "complete_time": 1698537600,
    "status": 1,
    "project_id": 1,
    "group_id": 1,
    "phase_id": 1,
    "admin_id_c": 1,
    "created_time": 1698537600,
    "admin": [
      {
        "id": 2,
        "username": "user1"
      },
      {
        "id": 3,
        "username": "user2"
      }
    ],
    "creator": {
      "id": 1,
      "username": "creator_name"
    },
    "project": {
      "id": 1,
      "name": "Project Name",
      "expired_time": 1730160000
    },
    "group": {
      "id": 1,
      "name": "Group Name"
    }
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Ticket not found"
}
```

**Response Error (403):**
```json
{
  "success": false,
  "message": "You do not have permission to update this ticket"
}
```

**Response Error (422) - Validation Error:**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "name": ["The name field is required."],
    "priority": ["The priority must be between 1 and 3."]
  }
}
```

**Response Error (422) - Deadline Exceeds Project End Date:**
```json
{
  "success": false,
  "message": "Deadline cannot exceed project end date",
  "project_expired_time": "2024-12-31"
}
```

---

## Status Labels

Ticket có các status label được tính toán tự động:

- **New**: Task chưa hoàn thành và chưa quá deadline
- **Overdue**: Task chưa hoàn thành và đã quá deadline
- **Completed**: Task đã hoàn thành trước deadline
- **Completed Late**: Task đã hoàn thành sau deadline

---

## Priority Levels

- `1`: Thấp (Low)
- `2`: Trung bình (Medium)
- `3`: Cao (High)

---

## Project Types

- `1`: Marketing
- `2`: Branding
- `3`: Video

---

## Permission Rules

### Projects List
- **Super Admin / Account**: Xem tất cả dự án
- **User khác**: Chỉ xem dự án mà họ được phân công

### Groups List
- **Super Admin / Account**: Xem tất cả groups của dự án
- **User khác**: Chỉ xem groups của dự án mà họ được phân công

### Tickets List
- **Super Admin / Account**: Xem tất cả tickets của group
- **User khác**: Chỉ xem tickets của group trong dự án mà họ được phân công

### Ticket Detail
- **Super Admin / Account**: Xem tất cả tickets
- **User khác**: Chỉ xem tickets mà họ:
  - Được phân công (assigned)
  - Là người tạo (creator)

### Update Ticket
- **Super Admin / Account**: Cập nhật tất cả tickets và có thể thay đổi assigned users
- **Creator**: Cập nhật ticket mà họ tạo và có thể thay đổi assigned users
- **Assigned User**: Chỉ cập nhật thông tin, không thể thay đổi assigned users

---

## Error Codes

- `200`: Success
- `403`: Forbidden - Không có quyền truy cập
- `404`: Not Found - Không tìm thấy resource
- `422`: Unprocessable Entity - Lỗi validation
- `500`: Internal Server Error - Lỗi server

---

## Examples

### Example 1: Get Current User Information

**Request:**
```bash
curl -X GET "http://your-domain.com/api/user" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Example 2: Get Projects with Filters

**Request:**
```bash
curl -X GET "http://your-domain.com/api/projects?status=1&type=1&limit=10" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Example 3: Get Groups of Project

**Request:**
```bash
curl -X GET "http://your-domain.com/api/projects/1/groups" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Example 4: Get Tickets of Group

**Request:**
```bash
curl -X GET "http://your-domain.com/api/groups/1/tickets?phase_id=1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Example 5: Get Ticket Detail

**Request:**
```bash
curl -X GET "http://your-domain.com/api/tickets/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Example 6: Update Ticket

**Request:**
```bash
curl -X PUT "http://your-domain.com/api/tickets/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Task Name",
    "status": true,
    "priority": 3,
    "deadline_time": "2024-11-30",
    "admin": [2, 3]
  }'
```

---

## Notes

1. **Authentication**: Tất cả API đều yêu cầu Sanctum token trong header `Authorization: Bearer {token}`
2. **Date Format**: Tất cả date truyền vào sử dụng format `Y-m-d` (ví dụ: 2024-11-30)
3. **Timestamps**: API trả về timestamps dưới dạng Unix timestamp (số giây kể từ 1970-01-01)
4. **Deadline Validation**: Deadline không được vượt quá `expired_time` của dự án
5. **Status Auto-update**: Khi set `status = 1` (completed), hệ thống tự động set `complete_time = now()`
6. **Permissions**: User chỉ có thể truy cập và cập nhật resources mà họ có quyền
7. **Default Phase**: Nếu không truyền `phase_id`, hệ thống sẽ tự động lấy phase mới nhất của dự án (phase có ID lớn nhất)
