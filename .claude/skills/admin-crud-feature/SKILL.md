---
name: admin-crud-feature
description: Xây dựng tính năng CRUD mới trong khu vực admin của dự án Biss, áp dụng đúng theme Metronic (public/assets/admin/themes) và các quy ước Controller/Repo/View/Route đã có sẵn trong dự án. Dùng khi được yêu cầu thêm module/tính năng quản trị mới (danh sách + thêm/sửa/xoá) trong resources/views/admin.
---

# Admin CRUD Feature (Biss)

Skill này mô tả cách xây một tính năng CRUD mới trong khu vực `/admin` của dự án,
đúng theo khuôn mẫu đã dùng cho các module hiện có (Project, Ticket, Group, Customer...).
Mục tiêu: tính năng mới trông và hoạt động giống hệt các tính năng cũ — không tạo
kiến trúc mới, không tự chế theme.

## Nguyên tắc chung

- **Không copy trực tiếp từ `public/assets/admin/themes/crud/`, `custom/`, `features/`,
  `layout/`, `index.html`** — đó là các trang demo gốc của theme Metronic (mua sẵn),
  chỉ dùng để tham khảo markup/class khi cần. Code thật của app chỉ dùng thư mục
  `public/assets/admin/themes/assets/` (css/js đã build sẵn) + các blade layout đã
  wire sẵn trong `resources/views/admin/layout/`.
- **Không tạo trang create/edit riêng.** Toàn bộ module hiện có dùng pattern:
  1 file `index.blade.php` = bảng danh sách + modal "Thêm mới" + modal "Sửa", cả
  hai modal cùng submit về **1 route** (`create`), phân biệt update/insert bằng
  có/không có `id` trong request.
- **Không dùng Laravel Policy/Gate/`@can`.** Phân quyền kiểm tra thủ công bằng
  `auth('admin')->user()->hasRole([...])` trong controller, và bằng
  `{{ $isAdmin ? '' : 'd-none' }}` trong blade.
- **Không tạo Service trừ khi có tích hợp bên ngoài (API, v.v).** CRUD dữ liệu chỉ
  cần Repo. `app/Services/` chỉ dành cho logic kiểu `OpenAiService`.

## 1. Route (`routes/admin.php`)

Thêm vào bên trong group `Route::namespace('Admin')->name('admin.')->prefix('admin')
->middleware(['auth:admin'])`:

```php
Route::get('<feature>', 'Admin<Feature>Controller@index')->name('<feature>.index');
Route::post('<feature>/create', 'Admin<Feature>Controller@create')->name('<feature>.create');
Route::post('<feature>/remove', 'Admin<Feature>Controller@remove')->name('<feature>.remove');
```

- `<feature>` số ít, thường thái. Đặt tên route theo mẫu `admin.<feature>.<action>`.
- Dùng cú pháp string `'Controller@method'` (style cũ, khớp toàn bộ route file hiện tại),
  không dùng invokable/array callable.
- Nếu cần thêm hành động riêng (vd AJAX lấy dữ liệu, xuất báo cáo...) đặt tên động từ
  rõ nghĩa như các module khác đã làm: `createAjax`, `bulkRemove`, `createNote`...

## 2. Model (`app/Models/<Feature>.php`)

Eloquent model bình thường, số ít, không có gì đặc biệt so với convention Laravel.

## 3. Repo (`app/Repo/<Feature>Repo.php`)

Theo đúng shape của `ProjectRepo` (không cần kế thừa `BaseRepo` — chỉ `CustomerRepo`
dùng, phần còn lại đều độc lập):

```php
<?php
namespace App\Repo;

use App\Models\<Feature>;
use Exception;

class <Feature>Repo
{
    private $repo;
    public function __construct(<Feature> $repo)
    {
        $this->repo = $repo;
    }

    public function create($data) { /* new <Feature>(), gán field, save() */ }
    public function update($repo, $data) { /* gán field, save() */ }
    public function remove($id) { $this->repo->destroy($id); return true; }
    public function first($condition = [], $order = [], $with = []) { ... }
    public function get($condition = [], $order = [], $with = []) { ... }
    public function paginate($condition = [], $limit = 20, $order = [], $with = []) { ... }
    // các hàm search/filter riêng của feature nếu cần, ví dụ:
    // public function search($condition, $limit, $adminId, $orderBy) {...}
}
```

Đọc `app/Repo/ProjectRepo.php` để copy chính xác cách xử lý `$condition` (hỗ trợ cả
`where($key, $value)` lẫn `whereIn` khi `$value` là mảng, và điều kiện dạng
`[$field, $operator, $value]` khi key là số).

## 4. Controller (`app/Http/Controllers/Admin/Admin<Feature>Controller.php`)

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\<Feature>Repo;
use Illuminate\Http\Request;

class Admin<Feature>Controller extends Controller
{
    private $repo;

    public function __construct(<Feature>Repo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $request->flash(); // giữ lại filter khi F5 / quay lại
        $limit = $request->get('limit', 20);
        // ...đọc các filter khác từ request, build $condition...
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        $data = $this->repo->paginate($condition, $limit, ['id' => 'DESC'], [/* eager load */]);
        return view('admin.<feature>.index', compact('data', 'isAdmin', /* filter vars for old() */));
    }

    public function create(Request $request)
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(['super_admin'])) {
            return back()->with('error_message', 'Bạn không có quyền thực hiện');
        }
        $id = $request->get('id');
        $data = $request->only([...]); // các field cho phép nhập
        if ($id) {
            $item = $this->repo->first(['id' => $id]);
            $this->repo->update($item, $data);
        } else {
            $this->repo->create($data);
        }
        return back()->with('success_message', 'Cập nhật thành công');
    }

    public function remove(Request $request)
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(['super_admin'])) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền thực hiện']);
        }
        $this->repo->remove($request->get('id'));
        return response()->json(['success' => 1]);
    }
}
```

Ghi chú:
- `create()` xử lý cả thêm mới lẫn cập nhật (không tách `store`/`update`/`edit`).
- `remove()` trả JSON `{success: 0|1}` — được gọi bằng AJAX từ nút xoá trong danh sách,
  không redirect.
- Flash message dùng key `success_message` / `error_message` (KHÔNG dùng `session('status')`
  mặc định của Laravel) — `resources/views/admin/layout/main.blade.php` đọc 2 key này
  và `public/js/init.js` tự hiển thị SweetAlert2 toast khi có.

## 5. View (`resources/views/admin/<feature>/index.blade.php`)

Một file duy nhất. Khung sườn — copy cấu trúc này rồi chỉnh nội dung, đừng bịa layout mới:

```blade
@extends('admin.layout.main')

@section('title')
Danh sách <Tên tính năng>
@endsection

@section('lib_css')
{{-- link CSS plugin riêng nếu cần, ví dụ datatables --}}
@endsection

@section('lib_js')
{{-- script JS plugin riêng nếu cần --}}
@endsection

@section('custom_css')
<style>
    /* CSS riêng cho trang này */
</style>
@endsection

@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6" id="kt_subheader">
    <div class="w-100 d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <h5 class="text-dark font-weight-bold my-1 mr-5"><Tên tính năng></h5>
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.<feature>.index') }}" class="text-muted">Danh sách</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">
    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-label">Danh sách <Tên tính năng></h3>
            </div>
            <div class="card-toolbar">
                {{-- nút "Thêm mới" mở modal, chỉ hiện nếu $isAdmin --}}
                <button type="button" class="btn btn-primary {{ $isAdmin ? '' : 'd-none' }}"
                    data-toggle="modal" data-target="#modalCreate">
                    Thêm mới
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="form-search" method="get">
                {{-- input/select filter, dùng old('field') để giữ giá trị --}}
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        {{-- các cột --}}
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        {{-- các cột --}}
                        <td>
                            <button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit"
                                data-id="{{ $item->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-remove {{ $isAdmin ? '' : 'd-none' }}"
                                data-id="{{ $item->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $data->withQueryString()->links('admin.layout.paginate') }}
        </div>
    </div>
    <!--end::Card-->
</div>

@include('admin.<feature>.modal-create')
@include('admin.<feature>.modal-edit')
@endsection

@section('custom_js')
<script>
    var data = @json($data->keyBy('id'));

    $('.select2').select2();

    $(document).on('click', '.btn-edit', function () {
        var item = data[$(this).data('id')];
        $('#modalEdit input[name="id"]').val(item.id);
        // ...gán các field khác vào form trong modal Sửa...
        $('#modalEdit').modal('show');
    });

    $(document).on('click', '.btn-remove', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: 'Hành động này không thể hoàn tác',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ'
        }).then(function (result) {
            if (result.value) {
                if (init.conf.ajax_sending) return;
                init.conf.ajax_sending = true;
                $.ajax({
                    url: '{{ route("admin.<feature>.remove") }}',
                    type: 'POST',
                    data: { id: id },
                    success: function (res) {
                        init.conf.ajax_sending = false;
                        if (res.success) {
                            init.showNoty('Xoá thành công', 'success');
                            location.reload();
                        } else {
                            init.showNoty(res.message || 'Có lỗi xảy ra', 'error');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
```

Ghi chú quan trọng khi viết view:
- Đường dẫn asset dùng **path tuyệt đối trực tiếp**, không dùng `asset()`:
  `/assets/admin/themes/assets/plugins/custom/.../xxx.bundle.css`.
- Form control dùng class Bootstrap 4 + Metronic: `.form-control`, `.form-group.row`,
  `.select2` (init bằng jQuery `select2()`), switch boolean dùng
  `<input data-switch="true" type="checkbox">` (Metronic bootstrap-switch).
- Icon: theme có 2 nguồn — Font Awesome class (`<i class="fas fa-...">`) dùng cho nút
  thao tác nhỏ, và SVG inline copy từ `public/assets/admin/themes/assets/media/svg/icons/`
  cho icon lớn hơn trong card/menu.
- Xác nhận xoá luôn dùng SweetAlert2 (`Swal.fire`), không dùng `confirm()` JS thường.
- Toast thông báo dùng `init.showNoty(text, type)` (định nghĩa trong `public/js/init.js`),
  không tự viết toast riêng.
- Chống double-submit AJAX bằng cờ toàn cục `init.conf.ajax_sending`.
- Nếu danh sách cần nhiều dữ liệu ẩn để JS dùng lại (vd cho modal edit), dump thẳng
  `@json($data->keyBy('id'))` ra JS thay vì gọi AJAX lấy lại — đúng pattern cũ, tránh
  round-trip thừa.

## 6. Sidebar menu (`resources/views/admin/layout/header.blade.php`)

Thêm menu item mới vào đúng khối `menu-item menu-item-submenu`/`menu-item` hiện có,
active state theo route hiện tại kiểu `{{ Route::is('admin.<feature>.*') ? 'menu-item-active' : '' }}`,
href trỏ `{{ route('admin.<feature>.index') }}`.

## 7. File tham khảo bắt buộc đọc trước khi code

- `app/Http/Controllers/Admin/AdminProjectController.php`
- `app/Repo/ProjectRepo.php`
- `resources/views/admin/project/index.blade.php`
- `resources/views/admin/layout/main.blade.php`, `css.blade.php`, `js.blade.php`, `header.blade.php`
- `public/js/init.js`
- `routes/admin.php`

Luôn mở các file trên trước khi tạo module mới để bám sát 100% style hiện tại
(tên biến, cấu trúc điều kiện, class CSS) thay vì suy diễn.
