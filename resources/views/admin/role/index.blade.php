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
@endsection
@section('custom_css')
<style>
.hiddenRow {
    padding: 0 !important;
}
</style>
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
                    Chức vụ </h5>
                <!--end::Page Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">
                            Admin </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">
                            Chức vụ </a>
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
                    Chức vụ
                    <div class="text-muted pt-2 font-size-sm">Chức vụ thành viên</div>
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
                        <!--end::Svg Icon--></span> Thêm chức vụ
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
                        <th>Tên chức vụ</th>
                        <th>Mã chức vụ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @if(!empty($data))
                    @foreach($data as $k => $v)
                    <tr>
                        <td>{{ $k + 1 }}</td>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->slug }}</td>
                        <td nowrap>
                            @if(auth('admin')->user()->hasRole(['super_admin']))
                                <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-edit" title="Edit details" data-id="{{ $v->id }}">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-remove" title="Delete" data-id="{{ $v->id }}">
                                    <i class="la la-trash"></i>
                                </a>
                            @endif
                            <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-report" title="Báo cáo" data-id="{{ $v->id }}">
                                <i class="la la-signal"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-report2" title="Báo cáo SLHĐ" data-id="{{ $v->id }}">
                                <i class="la la-chart-bar"></i>
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
    <div class="modal-dialog modal modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm chức vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.role.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên chức vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Tên chức vụ" name="name" required/>
                    </div>
                    <div class="form-group">
                        <label>Mã chức vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Mã chức vụ" name="slug" required/>
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
    <div class="modal-dialog modal modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa chức vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.role.create') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label>Tên chức vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Tên chức vụ" name="name" required/>
                    </div>
                    <div class="form-group">
                        <label>Mã chức vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Mã chức vụ" name="slug" required/>
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
                <h5 class="modal-title">Báo cáo <span class="username font-weight-bold text-primary"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="row text-nowrap mb-4">
                    {{--<div class="col-xl-3 d-flex align-items-center mb-2">--}}
                        {{--<div class="mr-2">Dự án: </div>--}}
                        {{--<select name="project_id" class="select-project form-control"></select>--}}
                    {{--</div>--}}
                    <div class="col-xl-3 d-flex align-items-center mb-2">
                        <div class="mr-2">Tài khoản: </div>
                        <select name="admin_id" class="select-admin form-control"></select>
                    </div>
                    <div class="col-xl-3 d-flex align-items-center mb-2">
                        <div class="mr-2">Ngày bắt đầu: </div>
                        <input type="date" class="form-control" name="start_time">
                    </div>
                    <div class="col-xl-3 d-flex align-items-center mb-2">
                        <div class="mr-2">Ngày kết thúc: </div>
                        <input type="date" class="form-control" name="end_time">
                    </div>
                    <div class="col-xl-3 ml-auto d-flex">
                        <a href="javascript:void(0)" class="btn btn-light-primary mb-2 ml-auto" onclick="reportRole()">Tìm kiếm</a>
                    </div>
                </div>
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tài khoản</th>
                        <th scope="col">Dự án</th>
                        <th scope="col">SL</th>
                        <th scope="col">Mới</th>
                        <th scope="col">Hết hạn</th>
                        <th scope="col">Hoàn thành</th>
                        <th scope="col">Hoàn thành đúng hạn</th>
                        <th scope="col">Hoàn thành trễ</th>
                        <th scope="col">Tỉ lệ đúng hạn</th>
                        <th scope="col">Tỉ lệ trễ</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReport2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Báo cáo SLHĐ <span class="username font-weight-bold text-primary"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="row text-nowrap mb-4">
                    <div class="col-xl-3 d-flex align-items-center mb-2">
                        <div class="mr-2">Tài khoản: </div>
                        <select name="admin_id" class="select-admin form-control"></select>
                    </div>
                    <div class="col-xl-3 d-flex align-items-center mb-2">
                        <div class="mr-2">Ngày bắt đầu: </div>
                        <input type="date" class="form-control" name="start_time">
                    </div>
                    <div class="col-xl-3 d-flex align-items-center mb-2">
                        <div class="mr-2">Ngày kết thúc: </div>
                        <input type="date" class="form-control" name="end_time">
                    </div>
                    <div class="col-xl-3 ml-auto d-flex">
                        <a href="javascript:void(0)" class="btn btn-light-primary mb-2 ml-auto" onclick="reportRole2()">Tìm kiếm</a>
                    </div>
                </div>
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tài khoản</th>
                        <th scope="col">DA Marketing</th>
                        <th scope="col">DA Branding</th>
                        <th scope="col">DA Video</th>
                        <th scope="col">SLHĐ</th>
                        <th scope="col">Hoàn thành</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
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

    var data = @json($data->keyBy('id'));
    $('.btn-edit').click(function(){
        let id = $(this).data('id');
        let role = data[id];
        $('#modalEdit input[name="name"]').val(role.name);
        $('#modalEdit input[name="slug"]').val(role.slug);
        $('#modalEdit input[name="id"]').val(role.id);
        $('#modalEdit').modal('show');
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
                        url: "{{ route('admin.role.remove') }}",
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
        let role = data[id];
        $('#modalReport .username').html(role.name);
        $('#modalReport input[name="id"]').val(id);
        reportRole();
        $('#modalReport').modal('show');
    });

    $('.btn-report2').click(function(){
        let id = $(this).data('id');
        let role = data[id];
        $('#modalReport2 .username').html(role.name);
        $('#modalReport2 input[name="id"]').val(id);
        $('#modalReport2 select[name="project_id"]').val(0).trigger('change');
        reportRole2();
        $('#modalReport2').modal('show');
    });

    function reportRole() {
        let id = $('#modalReport input[name="id"]').val();
        let start_time = $('#modalReport input[name="start_time"]').val();
        let end_time = $('#modalReport input[name="end_time"]').val();
        let project_id = $('#modalReport select[name="project_id"]').val() ?? 0;
        let admin_id = $('#modalReport select[name="admin_id"]').val() ?? 0;
        if(!init.conf.ajax_sending){
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.role.report') }}",
                data: {id, start_time, end_time, project_id, admin_id},
                beforeSend: function(){
                    init.conf.ajax_sending = true;
                },
                success: function(res){
                    let html = '', htmlSelect = '';
                    if(res.success){
                        if(res.data.length > 0){
                            res.data.forEach(function (admin, $ka) {
                                if(admin.projects.length> 0){
                                    admin.projects.forEach(function (project , $kp) {
                                        if(!!!$kp){
                                            html += `<tr class="bg-success text-white accordion-toggle" data-toggle="collapse" data-target="#demo${$ka}">
                                                    <td >${($ka+1)}</td>
                                                    <td >${admin.admin}</td>
                                                    <td>${admin.projects.length - 1}</td>
                                                    <td>${project.report.total}</td>
                                                    <td>${project.report.new}</td>
                                                    <td>${project.report.expired}</td>
                                                    <td>${project.report.done}</td>
                                                    <td>${project.report.done_on_time}</td>
                                                    <td>${project.report.done_out_time}</td>
                                                    <td>${project.report.percent_on_time} %</td>
                                                    <td>${project.report.percent_out_time} %</td>
                                                </tr>`;
                                        }else{
                                            if($kp == 1){
                                                html += `<tr>
                                                        <td colspan="11" class="hiddenRow">
                                                            <div class="accordian-body collapse" id="demo${$ka}"> 
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr class="info">
                                                                            <th scope="col">Dự án</th>
                                                                            <th scope="col">SL</th>
                                                                            <th scope="col">Mới</th>
                                                                            <th scope="col">Hết hạn</th>
                                                                            <th scope="col">Hoàn thành</th>
                                                                            <th scope="col">Hoàn thành đúng hạn</th>
                                                                            <th scope="col">Hoàn thành trễ</th>
                                                                            <th scope="col">Tỉ lệ đúng hạn</th>
                                                                            <th scope="col">Tỉ lệ trễ</th>
                                                                        </tr>
                                                                    </thead>	
                                                                    <tbody>`;
                                            }
                                            html += `<tr>
                                                    <td>${project.project}</td>
                                                    <td>${project.report.total}</td>
                                                    <td>${project.report.new}</td>
                                                    <td>${project.report.expired}</td>
                                                    <td>${project.report.done}</td>
                                                    <td>${project.report.done_on_time}</td>
                                                    <td>${project.report.done_out_time}</td>
                                                    <td>${project.report.percent_on_time} %</td>
                                                    <td>${project.report.percent_out_time} %</td>
                                                </tr>`;

                                            if($kp === admin.projects.length - 1){
                                                html += `</tbody>
                                                                </table>
                                                            </div> 
                                                        </td>
                                                    </tr>`;
                                            }
                                        }
                                    });
                                } else {
                                    html += `<tr class="bg-success text-white">
                                                    <td>${($ka + 1)}</td>
                                                    <td>${admin.admin}</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0%</td>
                                                    <td>0%</td>
                                                </tr>`;
                                }
                            });
                        }
                        $('#modalReport tbody').html(html);
                        if ($('#modalReport .select-project').hasClass("select2-hidden-accessible")) {
                            $('#modalReport .select-project').select2('destroy');
                        }
                        if(res.project.length > 0){
                            htmlSelect += `<option value="0" ${project_id == 0? 'selected' : ''}>Tất cả dự án</option>`
                            res.project.forEach(function ($project, $k) {
                                htmlSelect += `<option value="${$project.id}" ${project_id == $project.id? 'selected' : ''}>${$project.name}</option>`
                            });
                        }
                        $('#modalReport .select-project').html(htmlSelect);
                        $('#modalReport .select-project').select2({
                            placeholder: 'Chọn dự án',
                            minimumResultsForSearch:-1,
                        });

                        if ($('#modalReport .select-admin').hasClass("select2-hidden-accessible")) {
                            $('#modalReport .select-admin').select2('destroy');
                        }
                        htmlSelect = '';
                        if(res.admin.length > 0){
                            htmlSelect += `<option value="0" ${admin_id == 0? 'selected' : ''}>Tất cả tài khoản</option>`
                            res.admin.forEach(function ($admin, $k) {
                                htmlSelect += `<option value="${$admin.id}" ${admin_id == $admin.id? 'selected' : ''}>${$admin.username}</option>`
                            });
                            console.log(htmlSelect)
                        }
                        $('#modalReport .select-admin').html(htmlSelect);
                        $('#modalReport .select-admin').select2({
                            placeholder: 'Chọn tài khoản',
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

    function reportRole2() {
        let id = $('#modalReport2 input[name="id"]').val();
        let start_time = $('#modalReport2 input[name="start_time"]').val();
        let end_time = $('#modalReport2 input[name="end_time"]').val();
        let admin_id = $('#modalReport2 select[name="admin_id"]').val() ?? 0;
        if(!init.conf.ajax_sending){
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.role.report2') }}",
                data: {id, start_time, end_time, admin_id},
                beforeSend: function(){
                    init.conf.ajax_sending = true;
                },
                success: function(res){
                    let html = '', htmlSelect = '';
                    let type = @json($type);
                    if(res.success){
                        if(res.data.length > 0){
                            res.data.forEach(function (v, $ka) {
                                html += `<tr class="bg-success text-white accordion-toggle" data-toggle="collapse" data-target="#dome${$ka}">
                                                    <td >${($ka+1)}</td>
                                                    <td >${v.admin}</td>
                                                    <td>${v.total_mkt}</td>
                                                    <td>${v.total_branding}</td>
                                                    <td>${v.total_video}</td>
                                                    <td>${v.total}</td>
                                                    <td>${v.total_complete}</td>
                                                </tr>
                                                <tr>
                                                        <td colspan="7" class="hiddenRow">
                                                            <div class="accordian-body collapse" id="dome${$ka}"> 
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr class="info">
                                                                            <th scope="col">Dự án</th>
                                                                            <th scope="col">SLHĐ</th>
                                                                            <th scope="col">Hoàn thành</th>
                                                                        </tr>
                                                                    </thead>	
                                                                    <tbody>`;
                                if(v.projects.length> 0){
                                    v.projects.forEach(function (project , $kp) {
                                        if(!!!$kp){
                                                html += `<tr>
                                                        <td>${project.name}</td>
                                                        <td>${project.qty}</td>
                                                        <td>${project.complete}</td>
                                                    </tr>`;
                                        }else{
                                            html += `<tr>
                                                    <td>${project.name}</td>
                                                    <td>${project.qty}</td>
                                                    <td>${project.complete}</td>
                                                </tr>`;
                                        }
                                    });
                                } else {
                                    html += `<tr class="bg-success text-white">
                                                    <td>${($ka + 1)}</td>
                                                    <td>${v.admin}</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>`;
                                }
                                html += `</tbody>
                                                                </table>
                                                            </div> 
                                                        </td>
                                                    </tr>`;
                            });
                        }
                        $('#modalReport2 tbody').html(html);
                        if ($('#modalReport2 .select-admin').hasClass("select2-hidden-accessible")) {
                            $('#modalReport2 .select-admin').select2('destroy');
                        }
                        htmlSelect = '';
                        if(res.admin.length > 0){
                            htmlSelect += `<option value="0" ${admin_id == 0? 'selected' : ''}>Tất cả tài khoản</option>`
                            res.admin.forEach(function ($admin, $k) {
                                htmlSelect += `<option value="${$admin.id}" ${admin_id == $admin.id? 'selected' : ''}>${$admin.username}</option>`
                            });
                        }
                        $('#modalReport2 .select-admin').html(htmlSelect);
                        $('#modalReport2 .select-admin').select2({
                            placeholder: 'Chọn tài khoản',
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
