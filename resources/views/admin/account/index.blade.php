@extends('admin.layout.main')
@section('title')
Biss
@endsection
@section('lib_css')
<link href="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/admin/themes/assets/js/pages/crud/forms/widgets/bootstrap-switch.js"></script>
@endsection
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 " id="kt_subheader">
    <div class=" w-100  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">

            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    Tài khoản </h5>
                <!--end::Page Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">
                            Admin </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">
                            Tài khoản </a>
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->
    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">

    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-label">
                    Tài khoản
                    <div class="text-muted pt-2 font-size-sm">Tài khoản thành viên</div>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="javascript:void(0);" class="btn btn-primary font-weight-bolder" data-toggle="modal"
                    data-target="#modalCreate">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:/assets/admin/themes/assets/media/svg/icons/Design/Flatten.svg--><svg
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path
                                    d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                    fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon--></span> Thêm thành viên
                </a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-separate table-head-custom table-checkable" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tài khoản</th>
                        <th>MNS</th>
                        <th>Họ tên</th>
                        <th>Thông tin cá nhân</th>
                        <th>Chức vụ</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @if(!empty($data))
                    @foreach($data as $v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->username }}</td>
                        <td>{{ $v->mns }}</td>
                        <td>{{ $v->name }}</td>
                        <td>
                            SĐT: {{ $v->phone }} <br>
                            Email: {{ $v->email }} <br>
                            Ngày sinh: {{ $v->birthday ? date('d/m/Y', $v->birthday) : '' }} <br>
                            Địa chỉ: {{ $v->address }}
                        </td>
                        <td>{{ !empty($v->roles) ? implode(', ', $v->roles->pluck('name')->toArray()) : '' }}</td>
                        <td>
                            <span class="label label-lg font-weight-bold label-light-{{ $v->status ? 'success' : 'danger' }} label-inline">{{ $v->status ? 'Hoạt động' : 'khóa' }}</span>
                        </td>
                        <td nowrap>
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-edit" title="Chỉnh sửa" data-id="{{ $v->id }}">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-pass" title="Đổi mật khẩu" data-id="{{ $v->id }}">
                                <i class="la la-lock"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-remove" title="Xóa thành viên" data-id="{{ $v->id }}">
                                <i class="la la-trash"></i>
                            </a>
                            {{--<a href="{{route('admin.account.report',['id' => $v->id])}}" class="btn btn-sm btn-clean btn-icon" title="Báo cáo thành viên" data-id="{{ $v->id }}">--}}
                                {{--<i class="la la-signal"></i>--}}
                            {{--</a>--}}
                            <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-report" title="Báo cáo thành viên" data-id="{{ $v->id }}">
                                <i class="la la-signal"></i>
                            </a>
                        </td>
                        @endforeach
                        @endif
                    </tr>
                </tbody>

            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
</div>
<!--end::Content-->
<!-- Modal Create-->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm chức vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.account.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Tài khoản</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="la la-user"></i></span></div>
                                <input type="text" class="form-control" name="username" placeholder="Tài khoản đăng nhập" required/>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>Mật khẩu</label>
                            <input type="text" class="form-control" name="password" placeholder="Mật khẩu" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Mã nhân sự</label>
                            <input type="text" class="form-control" name="mns" placeholder="Mã nhân sự"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Họ tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Họ và tên"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Email:</label>
                            <div class="input-group">
                                <input type="email" class="form-control" name="email" placeholder="Email"/>
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-mail-bulk"></i></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>SĐT:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="phone" placeholder="SĐT"/>
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-mobile"></i></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Sinh nhật:</label>
                            <input type="date" class="form-control" name="birthday" placeholder="Sinh nhật"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Địa chỉ:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="address" placeholder="Địa chỉ"/>
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-map-marker"></i></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" checked="checked" data-on-text="Hoạt động" data-off-text="Khóa" data-on-color="primary"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Chức vụ:</label>
                        <div class="col-lg-12">
                            <select class="form-control select2" name="roles[]" multiple="multiple" style="width: 100%">
                                @if(!empty($roles))
                                @foreach($roles as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!-- Modal Edit-->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa chức vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.account.create') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Tài khoản</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="la la-user"></i></span></div>
                                <input type="text" class="form-control" name="username" placeholder="Tài khoản đăng nhập" required/>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>Mã nhân sự</label>
                            <input type="text" class="form-control" name="mns" placeholder="Mã nhân sự"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Họ tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Họ và tên"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Email:</label>
                            <div class="input-group">
                                <input type="email" class="form-control" name="email" placeholder="Email"/>
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-mail-bulk"></i></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>SĐT:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="phone" placeholder="SĐT"/>
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-mobile"></i></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Sinh nhật:</label>
                            <input type="date" class="form-control" name="birthday" placeholder="Sinh nhật"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Địa chỉ:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="address" placeholder="Địa chỉ"/>
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-map-marker"></i></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" data-on-text="Hoạt động" data-off-text="Khóa" data-on-color="primary"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Chức vụ:</label>
                        <div class="col-lg-12">
                            <select class="form-control select2" name="roles[]" multiple="multiple" style="width: 100%">
                                @if(!empty($roles))
                                @foreach($roles as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!-- Modal Password-->
<div class="modal fade" id="modalPass" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.account.changePass') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label>Tài khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Tên chức vụ" name="username" readonly/>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Mật khẩu mới" name="password" required/>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>

<div class="modal fade" id="modalReport" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Báo cáo tài khoản <span class="username font-weight-bold text-primary"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="row text-nowrap mb-4">
                        <div class="col-md-3 d-flex align-items-center mb-2">
                            <div class="mr-2">Dự án: </div>
                            <select name="project_id" class="select-project form-control"></select>
                        </div>
                        <div class="col-md-3 d-flex align-items-center mb-2">
                            <div class="mr-2">Ngày bắt đầu: </div>
                            <input type="date" class="form-control" name="start_time">
                        </div>
                        <div class="col-md-3 d-flex align-items-center mb-2">
                            <div class="mr-2">Ngày kết thúc: </div>
                            <input type="date" class="form-control" name="end_time">
                        </div>
                        <div class="col-md-3">
                            <a href="javascript:void(0)" class="btn btn-light-primary mb-2" onclick="reportMember()">Tìm kiếm</a>
                        </div>
                </div>
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Dự án</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Mới</th>
                        <th scope="col">Hết hạn</th>
                        <th scope="col">Hoàn thành</th>
                        <th scope="col">Hoàn thành đúng hạn</th>
                        <th scope="col">Hoàn thành trễ</th>
                        <th scope="col">Tiến độ</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_js')
<script>
    $('#kt_datatable').DataTable({
        responsive: true,
        paging: true,
    });

    $('.select2').select2({
        placeholder: 'Chọn chức vụ',
    });

    var data = @json($data->keyBy('id'));
    $('.btn-edit').click(function(){
        let id = $(this).data('id');
        let admin = data[id];
        $('#modalEdit input[name="username"]').val(admin.username);
        $('#modalEdit input[name="mns"]').val(admin.mns);
        $('#modalEdit input[name="name"]').val(admin.name);
        $('#modalEdit input[name="email"]').val(admin.email);
        $('#modalEdit input[name="phone"]').val(admin.phone);
        $('#modalEdit input[name="address"]').val(admin.address);
        let timestamp = admin.birthday ? admin.birthday*1000 : null;
        if(timestamp){
            let tzoffset = (new Date()).getTimezoneOffset() * 60000;
            let date = new Date(timestamp - tzoffset).toISOString().split('T')[0];
            $('#modalEdit input[name="birthday"]').val(date);
        } else {
            $('#modalEdit input[name="birthday"]').val('');
        }
        $('#modalEdit input[name="id"]').val(admin.id);
        let roles = []
        if(admin.roles.length > 0){
            $.each(admin.roles, function(i, v){
                roles.push(v.id);
            });
        }
        $('#modalEdit select').val(roles).change();
        $('#modalEdit input[name="status"]').prop('checked', admin.status == 1 ? true : false).change();
        $('#modalEdit').modal('show');
    });

    $('.btn-pass').click(function(){
        let id = $(this).data('id');
        let admin = data[id];
        $('#modalPass input[name="username"]').val(admin.username);
        $('#modalPass input[name="password"]').val('');
        $('#modalPass input[name="id"]').val(admin.id);
        $('#modalPass').modal('show');
    });

    $('.btn-remove').click(function(){
        let id = $(this).data('id');
        Swal.fire({
            title: "Bạn chắc chắn muốn xóa?",
            text: "Sau khi xóa sẽ không thể khôi phục",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        }).then(function(result) {
            if (result.value) {
                if(!init.conf.ajax_sending){
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.account.remove') }}",
                        data: {id},
                        beforeSend: function(){
                            init.conf.ajax_sending = true;
                        },
                        success: function(res){
                            if(res.success){
                                init.showNoty('Xóa thành công!', 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            } else {
                                init.showNoty('Có lỗi xảy ra!', 'error');
                            }
                        },
                        complete: function(){
                            init.conf.ajax_sending = false;
                        }
                    })
                }
                
            }
        });
    });

    $('.btn-report').click(function(){
        let id = $(this).data('id');
        let admin = data[id];
        $('#modalReport .username').html(admin.username);
        $('#modalReport input[name="id"]').val(id);
        $('#modalReport select[name="project_id"]').val(0).trigger('change');
        reportMember();
        $('#modalReport').modal('show');
    });

    function reportMember() {
        let id = $('#modalReport input[name="id"]').val();
        let start_time = $('#modalReport input[name="start_time"]').val();
        let end_time = $('#modalReport input[name="end_time"]').val();
        let project_id = $('#modalReport select[name="project_id"]').val() ?? 0;
        if(!init.conf.ajax_sending){
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.account.report') }}",
                data: {id, start_time, end_time, project_id},
                beforeSend: function(){
                    init.conf.ajax_sending = true;
                },
                success: function(res){
                    console.log(project_id);
                    let html = '', htmlSelect = '';
                    if(res.success){
                        if(res.data.length > 0){
                            res.data.forEach(function ($project, $k) {
                                html += `<tr>
                                            <td scope="row">${($k+1)}</td>
                                            <td>${$project.project}</td>
                                            <td>${$project.report.total}</td>
                                            <td>${$project.report.new}</td>
                                            <td>${$project.report.expired}</td>
                                            <td>${$project.report.done}</td>
                                            <td>${$project.report.done_on_time}</td>
                                            <td>${$project.report.done_out_time}</td>
                                            <td>${$project.report.percent} %</td>
                                        </tr>`
                            });
                        }
                        $('#modalReport tbody').html(html);

                        if ($('.select-project').hasClass("select2-hidden-accessible")) {
                            $('.select-project').select2('destroy');
                        }
                        if(res.project.length > 0){
                            htmlSelect += `<option value="0" ${project_id == 0? 'selected' : ''}>Tất cả dự án</option>`
                            res.project.forEach(function ($project, $k) {
                                htmlSelect += `<option value="${$project.id}" ${project_id == $project.id? 'selected' : ''}>${$project.name}</option>`
                            });
                        }
                        $('.select-project').html(htmlSelect);
                        $('.select-project').select2({
                            placeholder: 'Chọn dự án',
                            minimumResultsForSearch:-1,
                        });
                    } else {
                        init.showNoty('Có lỗi xảy ra!', 'error');
                    }
                },
                complete: function(){
                    init.conf.ajax_sending = false;
                }
            })
        }
    }
</script>
@endsection
