@extends('admin.layout.main')
@section('title')
Danh sách dự án
@endsection
@section('lib_css')
{{-- <link href="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
type="text/css" /> --}}
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/admin/themes/assets/js/pages/crud/forms/widgets/bootstrap-switch.js"></script>
@endsection
@section('custom_css')
<style>
    .hiddenRow {
        padding: 0 !important;
    }

    .dtr-details{
        list-style: none;
        margin-left: 0;
        padding-left: 0;
    }

    .dtr-details .dtr-title{
        min-width: 70px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-block;
    }
    .dtr-details .dtr-data{

    }

    th, td{
        text-align: center;
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
                <a href="javascript:void(0);" class="btn btn-primary font-weight-bolder {{$isAdmin?'':'d-none'}}" data-toggle="modal"
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
                        <!--end::Svg Icon--></span> Thêm dự án
                </a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin::Search Form-->
            <div class="mb-7">
                <form id="form-search" action="">
                <div class="row align-items-center">

                    
                    <div class="col-lg-9 col-xl-10">
                        <div class="row align-items-center">
                            <div class="col-md-2 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-2 mb-0 d-none d-md-block"></label>
                                    <select class="form-control" name="limit" id="select-limit">
                                        <option value="20" @if(old('limit') == 20) selected @endif>20</option>
                                        <option value="30" @if(old('limit') == 30) selected @endif>30</option>
                                        <option value="50" @if(old('limit') == 50) selected @endif>50</option>
                                        <option value="100" @if(old('limit') == 100) selected @endif>100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block"></label>
                                    <select class="form-control" name="status">
                                        <option value="1" @if(old('status') == 1) selected @endif>Hoạt động</option>
                                        <option value="2" @if(old('status') == 2) selected @endif>Hoàn thành</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" class="form-control" name="name" placeholder="Tên dự án" value="{{ old('name') }}"/>
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" class="form-control" name="field" placeholder="Lĩnh vực" value="{{ old('field') }}"/>
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-2 mt-5 mt-lg-0">
                        <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">Tìm kiếm</button>
                    </div>
                </div>
                </form>
            </div>
            <!--end::Search Form-->

            @if(!empty($data))
            <!--begin: Datatable-->
            <div class="row">
                <div class="col-12">
                    <table class="table table-responsive-md table-separate">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" style="width: 20%">Tên</th>
                                <th scope="col">Lĩnh vực</th>
                                <th scope="col">Gói</th>
                                <th scope="col">Thanh toán</th>
                                <th scope="col">Ngày nhận</th>
                                <th scope="col">Ngày hết hạn</th>
                                <th scope="col">Trạng thái</th>
                                @if($isAdmin)
                                <th scope="col">Ticket trễ hạn</th>
                                @endif
                                <th class="{{$isAdmin?'':'d-none'}}">Hành động</th>
                                {{-- <th scope="col">Account</th>
                                <th scope="col">Phụ trách</th>
                                <th scope="col">Nhân sự</th>
                                <th scope="col">Fanpage</th>
                                <th scope="col">Website</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Ghi chú</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $time = time();
                            @endphp
                            @foreach($data as $k => $v)
                            <tr>
                                <th scope="row" nowrap><a href="javascript:void(0);" data-toggle="collapse"  class="accordion-toggle" data-target="#collapse{{ $k }}"><i class="la la-angle-down text-success mr-1"></i> {{ ($data->currentpage()-1) * $data->perpage() + $loop->index + 1 }}</a> </th>
                                <td class="text-left"><a href="{{ route('admin.group.index', $v->id) }}">{{ $v->name }}</a></td>
                                <td>{{ $v->field }}</td>
                                <td>{{ $v->package }}</td>
                                <td>{{ $v->payment_month }}</td>
                                <td>{{ date('d/m/Y', $v->accept_time) }}</td>
                                <td>{{ date('d/m/Y', $v->expired_time) }}</td>
                                <td nowrap>
                                    <span class="label label-lg font-weight-bold label-light-{{ $v->status == 1 ? ($time > $v->expired_time ? 'danger' : ($time < $v->expired_time - 604800 ? 'success' : 'warning')) : 'success' }} label-inline">{{ $v->status == 1 ? 'Hoạt động' : 'Hoàn thành' }}</span>
                                </td>
                                @if($isAdmin)
                                    <td nowrap>
                                        <span class="label label-lg font-weight-bold label-light-{{ $v->has_late ? 'danger' : 'success' }} label-inline">{{ $v->has_late ? 'Có' : 'Không' }}</span>
                                    </td>
                                @endif
                                <td nowrap class="{{$isAdmin?'':'d-none'}}">
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-edit" title="Chỉnh sửa" data-id="{{ $v->id }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-remove" title="Xóa" data-id="{{ $v->id }}">
                                        <i class="la la-trash"></i>
                                    </a>
                                </td>
                                {{-- <td>{{ $v->planer ? $v->planer->username : '' }}</td>
                                <td>{{ $v->executive ? $v->executive->username : '' }}</td>
                                <td>{{ implode(', ', $v->admin->pluck('username')->toArray()) }}</td>
                                <td>@if($v->fanpage) <a href="{{ $v->fanpage }}" target="_blank">Xem</a> @endif</td>
                                <td>@if($v->website) <a href="{{ $v->website }}" target="_blank">Xem</a> @endif</td>
                                <td>{{ $v->description }}</td>
                                <td>{{ $v->note }}</td> --}}
                                
                                
                            </tr>
                            <tr>
                                <td colspan="9" class="hiddenRow text-left">
                                    <div class="accordian-body collapse" id="collapse{{ $k }}">
                                        <ul class="dtr-details pt-4">
                                            <li>
                                                <span class="dtr-title">Account:</span>
                                                <span class="dtr-data">{{ @$v->planer->username}}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Phụ trách:</span>
                                                <span class="dtr-data">{{ @$v->executive->username }}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Nhân sự:</span>
                                                <span class="dtr-data">{{ implode(', ', $v->admin->pluck('username')->toArray()) }}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Fanpage:</span>
                                                <span class="dtr-data">@if($v->fanpage) <a href="{{ $v->fanpage }}" target="_blank">Xem</a> @endif</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Website:</span>
                                                <span class="dtr-data">@if($v->website) <a href="{{ $v->website }}" target="_blank">Xem</a> @endif</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Mô tả:</span>
                                                <span class="dtr-data">{{ $v->description }}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Ghi chú:</span>
                                                <span class="dtr-data">{{ $v->note }}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Tài liệu:</span>
                                                @if(!empty($v->extra_link))
                                                    <a href="{{ $v->extra_link }}" target="_blank" class="dtr-data">Link</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <!--end: Datatable-->
            <div class="d-flex justify-content-end">
                {{ $data->withQueryString()->links() }}
            </div>
            @endif
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
                            <input type="text" class="form-control" name="name" placeholder="Tên dự án" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Mô tả</label>
                            <textarea class="form-control" name="description" rows="1" placeholder="Mô tả"></textarea>
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
                                <option value="{{ $v->id }}" @if($v->id == auth('admin')->user()->id) check @endif>{{ $v->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Phụ trách:</label>
                            <select class="form-control select2" name="executive_id" style="width: 100%">
                                @if(!empty($admins))
                                @foreach($admins as $v)
                                <option value="{{ $v->id }}" @if($v->id == auth('admin')->user()->id) check @endif>{{ $v->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Lĩnh vực</label>
                            <input type="text" class="form-control" name="field" placeholder="Lĩnh vực"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Ngày nhận</label>
                            <input type="date" class="form-control" name="accept_time" placeholder="Ngày nhận" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Ngày hết hạn</label>
                            <input type="date" class="form-control" name="expired_time" placeholder="Ngày hết hạn" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Số tháng đã thanh toán</label>
                            <input type="text" class="form-control" name="payment_month"
                                placeholder="Số tháng đã thanh toán" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Gói</label>
                            <input type="text" class="form-control" name="package" placeholder="Gói" />
                        </div>
                        <div class="col-lg-4">
                            <label>Fanpage</label>
                            <input type="text" class="form-control" name="fanpage" placeholder="Fanpage" />
                        </div>
                        <div class="col-lg-4">
                            <label>Website</label>
                            <input type="text" class="form-control" name="website" placeholder="Website" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Tài liệu</label>
                            <input type="text" class="form-control" name="extra_link" placeholder="Tài liệu" />
                        </div>
                        <div class="col-lg-4">
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" checked="checked" data-on-text="Hoạt động" data-off-text="Hoàn thành" data-on-color="primary"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
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
                            <input type="text" class="form-control" name="name" placeholder="Tên dự án" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Mô tả</label>
                            <textarea class="form-control" name="description" rows="1" placeholder="Mô tả"></textarea>
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
                            <label>Lĩnh vực</label>
                            <input type="text" class="form-control" name="field" placeholder="Lĩnh vực"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Ngày nhận</label>
                            <input type="date" class="form-control" name="accept_time" placeholder="Ngày nhận" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Ngày hết hạn</label>
                            <input type="date" class="form-control" name="expired_time" placeholder="Ngày hết hạn" required/>
                        </div>
                        <div class="col-lg-4">
                            <label>Số tháng đã thanh toán</label>
                            <input type="text" class="form-control" name="payment_month"
                                placeholder="Số tháng đã thanh toán" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Gói</label>
                            <input type="text" class="form-control" name="package" placeholder="Gói" />
                        </div>
                        <div class="col-lg-4">
                            <label>Fanpage</label>
                            <input type="text" class="form-control" name="fanpage" placeholder="Fanpage" />
                        </div>
                        <div class="col-lg-4">
                            <label>Website</label>
                            <input type="text" class="form-control" name="website" placeholder="Website" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Tài liệu</label>
                            <input type="text" class="form-control" name="extra_link" placeholder="Tài liệu" />
                        </div>
                        <div class="col-lg-4">
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" checked="checked" data-on-text="Hoạt động" data-off-text="Hoàn thành" data-on-color="primary"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
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

    $('.select2').select2({
        placeholder: 'Chọn',
    });

    $('.btn-edit').click(function () {
        let id = $(this).data('id');
        let project = data[id];
        $('#modalEdit input[name="id"]').val(project.id);
        $('#modalEdit input[name="name"]').val(project.name);
        $('#modalEdit input[name="field"]').val(project.field);
        $('#modalEdit input[name="package"]').val(project.package);
        $('#modalEdit input[name="payment_month"]').val(project.payment_month);
        $('#modalEdit input[name="website"]').val(project.website);
        $('#modalEdit input[name="extra_link"]').val(project.extra_link);
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
        $('#modalEdit select[name="planer_id"]').val(project.planer_id).trigger('change');
        $('#modalEdit select[name="executive_id"]').val(project.executive_id).trigger('change');
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
                        data: {
                            id
                        },
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
        let name = $('#form-search input[name="name"]').val();
        let limit = $(this).val();
        let url = new URL("{{route('admin.project.index')}}");
        url.searchParams.set("limit", limit);
        url.searchParams.set("name", name);
        window.location.href = url.href;
    })

</script>
@endsection
