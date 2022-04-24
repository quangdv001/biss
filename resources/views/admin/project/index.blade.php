@extends('admin.layout.main')
@section('title')
    Danh sách dự án
@endsection
@section('lib_css')
    {{--<link href="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"--}}
    {{--type="text/css" />--}}
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
                        Dự án </h5>
                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.project.index')}}" class="text-muted">
                                Danh sách </a>
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
                        Danh sách dự án
                        {{--<div class="text-muted pt-2 font-size-sm">Tài khoản thành viên</div>--}}
                    </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="javascript:void(0);" class="btn btn-primary font-weight-bolder" data-toggle="modal"
                       data-target="#modalCreate">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:/assets/admin/themes/assets/media/svg/icons/Design/Flatten.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" cx="9" cy="15" r="6"/>
                                <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                        <!--end::Svg Icon--></span> Thêm dự án
                    </a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="kt_datatable_length">
                            <label  class="d-inline-flex align-items-center">Show <select id="select-limit" class="custom-select custom-select-sm form-control form-control-sm mx-2 d-block">
                                    <option value="10" {{ request('limit') == 10? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('limit') == 25? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('limit') == 50? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('limit') == 100? 'selected' : '' }}>100</option>
                                </select> entries</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <form id="form_filter" class="dataTables_filte text-right">
                            <label class="d-inline-flex align-items-center">Search:<input id="input-name" name="name" type="search" class="form-control form-control-sm mx-2 d-block" value="{{request('name')}}"></label>
                        </form>
                    </div>
                </div>
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom table-checkable" id="kt_datatable">
                    <thead>
                    <tr class="text-nowrap">
                        <th>ID</th>
                        <th>Tên dự án</th>
                        <th>Mô tả</th>
                        <th>Ghi chú</th>
                        <th>Account</th>
                        <th>Phụ trách</th>
                        <th>Gói</th>
                        <th>Số tháng đã thanh toán</th>
                        <th>Fanpage</th>
                        <th>Website</th>
                        <th>Ngày tạo</th>
                        <th>Ngày nhận</th>
                        <th>Ngày hết hạn</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if(!empty($data))
                        @foreach($data as $v)
                            <tr>
                                <th>{{$v->id}}</th>
                                <th><a href="{{route('admin.group.index',['project_id'=>$v->id])}}">{{$v->name}}</a></th>
                                <th title="{{$v->description}}"><span><i class="fab fa-stack-exchange"></i></span></th>
                                <th title="{{$v->note}}"><span><i class="fab fa-stack-exchange"></i></span></th>
                                <th>{{$v->planer->username ?? ''}}</th>
                                <th>{{$v->executive->username ?? ''}}</th>
                                <th>{{$v->package}}</th>
                                <th>{{$v->payment_month}}</th>
                                <th>{{$v->fanpage}}</th>
                                <th>{{$v->website}}</th>
                                <th class="text-nowrap">{{!empty($v->created_time)?date('d/m/Y',$v->created_time):''}}</th>
                                <th class="text-nowrap">{{!empty($v->accept_time)?date('d/m/Y',$v->accept_time):''}}</th>
                                <th class="text-nowrap">{{!empty($v->expired_time)?date('d/m/Y',$v->expired_time):''}}</th>
                                <th>{{$v->status}}</th>
                                <th class="text-nowrap">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-edit" title="Chỉnh sửa" data-id="{{$v->id}}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-remove" title="Xóa dự án" data-id="{{$v->id}}">
                                        <i class="la la-trash"></i>
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>

                </table>
                <!--end: Datatable-->

                {{ $data->appends(request()->query())->links('admin.layout.paginate',['data'=>$data]) }}
            </div>

        </div>
        <!--end::Card-->
    </div>
    <!--end::Content-->
    <!-- Modal Create-->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm dự án</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <!--begin::Form-->
                <form method="post" action="{{ route('admin.project.create') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Tên dự án</label>
                                <input type="text" class="form-control" name="name" placeholder="Tên dự án"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Mô tả</label>
                                <textarea class="form-control" name="description" rows="1"
                                          placeholder="Mô tả"></textarea>
                            </div>
                            <div class="col-lg-4">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="note" rows="1" placeholder="Ghi chú"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Account:</label>
                                <select class="form-control select2" name="planer_id" style="width: 100%">
                                    @if(!empty($admins))
                                        @foreach($admins as $v)
                                            <option value="{{ $v->id }}">{{ $v->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Phụ trách:</label>
                                <select class="form-control select2" name="executive_id" style="width: 100%">
                                    @if(!empty($admins))
                                        @foreach($admins as $v)
                                            <option value="{{ $v->id }}">{{ $v->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Nhân sự:</label>
                                <select class="form-control select2" name="admin_project[]" multiple="multiple"
                                        style="width: 100%">
                                    @if(!empty($admins))
                                        @foreach($admins as $v)
                                            <option value="{{ $v->id }}">{{ $v->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Ngày nhận</label>
                                <input type="date" class="form-control" name="accept_time" placeholder="Ngày nhận"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Ngày hết hạn</label>
                                <input type="date" class="form-control" name="expired_time" placeholder="Ngày hết hạn"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Số tháng đã thanh toán</label>
                                <input type="text" class="form-control" name="payment_month"
                                       placeholder="Số tháng đã thanh toán"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Gói</label>
                                <input type="text" class="form-control" name="package" placeholder="Gói"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Fanpage</label>
                                <input type="text" class="form-control" name="fanpage" placeholder="Fanpage"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Website</label>
                                <input type="text" class="form-control" name="website" placeholder="Website"/>
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
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa dự án</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <!--begin::Form-->
                <form method="post" action="{{ route('admin.project.create') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Tên dự án</label>
                                <input type="text" class="form-control" name="name" placeholder="Tên dự án"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Mô tả</label>
                                <textarea class="form-control" name="description" rows="1"
                                          placeholder="Mô tả"></textarea>
                            </div>
                            <div class="col-lg-4">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="note" rows="1" placeholder="Ghi chú"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Account:</label>
                                <select class="form-control select2" name="planer_id" style="width: 100%">
                                    @if(!empty($admins))
                                        @foreach($admins as $v)
                                            <option value="{{ $v->id }}">{{ $v->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Phụ trách:</label>
                                <select class="form-control select2" name="executive_id" style="width: 100%">
                                    @if(!empty($admins))
                                        @foreach($admins as $v)
                                            <option value="{{ $v->id }}">{{ $v->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Nhân sự:</label>
                                <select class="form-control select2" name="admin_project[]" multiple="multiple"
                                        style="width: 100%">
                                    @if(!empty($admins))
                                        @foreach($admins as $v)
                                            <option value="{{ $v->id }}">{{ $v->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Ngày nhận</label>
                                <input type="date" class="form-control" name="accept_time" placeholder="Ngày nhận"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Ngày hết hạn</label>
                                <input type="date" class="form-control" name="expired_time" placeholder="Ngày hết hạn"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Số tháng đã thanh toán</label>
                                <input type="text" class="form-control" name="payment_month"
                                       placeholder="Số tháng đã thanh toán"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Gói</label>
                                <input type="text" class="form-control" name="package" placeholder="Gói"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Fanpage</label>
                                <input type="text" class="form-control" name="fanpage" placeholder="Fanpage"/>
                            </div>
                            <div class="col-lg-4">
                                <label>Website</label>
                                <input type="text" class="form-control" name="website" placeholder="Website"/>
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

@endsection
@section('custom_js')
    <script>
        var data = @json($data->keyBy('id'));

        $('#kt_datatable').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            bInfo: false,
            scrollX: true
        });

        $('.select2').select2({
            placeholder: 'Chọn',
        });

        $('.btn-edit').click(function () {
            let id = $(this).data('id');
            let project = data[id];
            $('#modalEdit input[name="id"]').val(project.id);
            $('#modalEdit input[name="name"]').val(project.name);
            $('#modalEdit input[name="package"]').val(project.package);
            $('#modalEdit input[name="payment_month"]').val(project.payment_month);
            $('#modalEdit input[name="website"]').val(project.website);
            $('#modalEdit input[name="fanpage"]').val(project.fanpage);
            $('#modalEdit input[name="address"]').val(project.address);
            $('#modalEdit textarea[name="description"]').val(project.description);
            $('#modalEdit textarea[name="note"]').val(project.note);
            let timestampA = project.accept_time ? project.accept_time * 1000 : null;
            let tzoffset = (new Date()).getTimezoneOffset() * 60000;
            if (timestampA) {
                let dateA = new Date(timestampA - tzoffset).toISOString().split('T')[0];
                $('#modalEdit input[name="accept_time"]').val(dateA);
            } else {
                $('#modalEdit input[name="accept_time"]').val('');
            }
            let timestampE = project.expired_time ? project.expired_time * 1000 : null;
            if (timestampE) {
                let dateE = new Date(timestampE - tzoffset).toISOString().split('T')[0];
                $('#modalEdit input[name="expired_time"]').val(dateE);
            } else {
                $('#modalEdit input[name="expired_time"]').val('');
            }
            let admin = [];
            if (project.admin.length > 0) {
                $.each(project.admin, function (i, v) {
                    admin.push(v.id);
                });
            }
            $('#modalEdit select[name="admin_project[]"]').val(admin).trigger('change');
            // $('#modalEdit input[name="status"]').prop('checked', admin.status == 1 ? true : false).change();
            $('#modalEdit').modal('show');
        });

        $('.btn-remove').click(function () {
            let id = $(this).data('id');
            Swal.fire({
                title: "Bạn chắc chắn muốn xóa?",
                text: "Sau khi xóa sẽ không thể khôi phục",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy",
            }).then(function (result) {
                if (result.value) {
                    if (!init.conf.ajax_sending) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.project.remove') }}",
                            data: {id},
                            beforeSend: function () {
                                init.conf.ajax_sending = true;
                            },
                            success: function (res) {
                                if (res.success) {
                                    init.showNoty('Xóa thành công!', 'success');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 500);
                                } else {
                                    init.showNoty('Có lỗi xảy ra!', 'error');
                                }
                            },
                            complete: function () {
                                init.conf.ajax_sending = false;
                            }
                        })
                    }

                }
            });
        });

        $('#select-limit').change(function () {
            let limit = $(this).val();
            let url = new URL("{{route('admin.project.index')}}");
            url.searchParams.set("limit", limit);
            window.location.href = url.href;
        })
    </script>
@endsection
