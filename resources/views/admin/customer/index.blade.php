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
                    Danh sách Khách hàng
                    {{--<div class="text-muted pt-2 font-size-sm">Tài khoản thành viên</div>--}}
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <label href="javascript:void(0);" class="btn btn-light-info font-weight-bolder mr-3 mt-2" for="inp-import">
                    <input type="file" id="inp-import" class="d-none" onchange="importFile(this.files)">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg--><svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span> Import
                </label>
                <!--end::Button-->
                <!--begin::Button-->
                <a href="javascript:void(0);" class="btn btn-light-primary font-weight-bolder mr-3 btn-export">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg--><svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span> Export
                </a>
                <!--end::Button-->
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
                        <!--end::Svg Icon-->
                    </span> Thêm khách hàng
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
                            {{-- <div class="col-md-2 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-2 mb-0 d-none d-md-block"></label>
                                    <select class="form-control" name="limit" id="select-limit">
                                        <option value="20" @if(old('limit') == 20) selected @endif>20</option>
                                        <option value="30" @if(old('limit') == 30) selected @endif>30</option>
                                        <option value="50" @if(old('limit') == 50) selected @endif>50</option>
                                        <option value="100" @if(old('limit') == 100) selected @endif>100</option>
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-2 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block"></label>
                                    <select class="form-control" name="status">
                                        <option value="0" @if(old('status') == 0) selected @endif>Trạng thái</option>
                                        <option value="1" @if(old('status') == 1) selected @endif>Mới</option>
                                        <option value="2" @if(old('status') == 2) selected @endif>Đã xử lý</option>
                                    </select>
                                </div>
                            </div>
                            @if ($isSuperAdmin)
                            <div class="col-md-2 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block"></label>
                                    <select class="form-control" name="admin_id">
                                        <option value="0" @if(old('admin_id') == 0) selected @endif>Phụ trách</option>
                                        @if ($admins->count())
                                        @foreach ($admins as $v)
                                        <option value="{{ $v->id }}" @if(old('admin_id') == $v->id) selected @endif>{{ $v->username }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            {{-- <div class="col-md-2 my-2 my-md-0">
                                <input type='text' class="form-control" id="kt_daterangepicker_1" readonly placeholder="Chọn thời gian" name="start_time" type="text" value=""/>
                            </div> --}}
                            <div class="col-md-2 my-2 my-md-0">
                                <input type="date" class="form-control" name="start_time" placeholder="Bắt đầu" value="{{ old('start_time') }}"/>
                            </div>
                            <div class="col-md-2 my-2 my-md-0">
                                <input type="date" class="form-control" name="end_time" placeholder="Kết thúc" value="{{ old('end_time') }}"/>
                            </div>
                            <div class="col-md-2 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" class="form-control" name="name" placeholder="Tên Khách hàng" value="{{ old('name') }}"/>
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>
                            <div class="col-md-2 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" class="form-control" name="phone" placeholder="SĐT" value="{{ old('phone') }}"/>
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-2 mt-5 mt-lg-0">
                        <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">Tìm kiếm</button>
                        <a href="{{ route('admin.customer.index') }}" role="button" class="btn btn-light-success px-6 font-weight-bold">Reset</a>
                    </div>
                </div>
                </form>
            </div>
            <!--end::Search Form-->

            @if($data->count())
            <!--begin: Datatable-->
            <div class="row">
                <div class="col-12">
                    <table class="table table-responsive-md table-separate">
                        <thead>
                            <tr>
                                <th scope="col">Chọn</th>
                                <th scope="col">#</th>
                                <th scope="col">Tên</th>
                                <th scope="col">SĐT</th>
                                <th scope="col">Email</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Phụ trách</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Bắt đầu</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $time = time();
                            @endphp
                            @foreach($data as $k => $v)
                            <tr>
                                <th><span style="width: 20px;"><label class="checkbox checkbox-single"><input type="checkbox" class="select_record" name="ids[]" value="{{ $v->id }}">&nbsp;<span></span></label></span></th>
                                <th scope="row" nowrap><a href="javascript:void(0);" data-toggle="collapse"  class="accordion-toggle" data-target="#collapse{{ $k }}"><i class="la la-angle-down text-success mr-1"></i> {{ ($data->currentpage()-1) * $data->perpage() + $loop->index + 1 }}</a> </th>
                                <td>{{ $v->name }}</td>
                                <td>{{ $v->phone }}</td>
                                <td>{{ $v->email }}</td>
                                <td nowrap>
                                    <span class="label label-lg font-weight-bold label-light-{{ @$status[$v->status]['class'] }} label-inline">{{ @$status[$v->status]['text'] }}</span>
                                </td>
                                <td>{{ @$v->admin->username }}</td>
                                <td>{{ $v->description }}</td>
                                <td>{{ $v->start_time ? date('d/m/Y', $v->start_time) : '' }}</td>
                                <td nowrap>
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-edit" title="Chỉnh sửa" data-id="{{ $v->id }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-remove" title="Xóa" data-id="{{ $v->id }}">
                                        <i class="la la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" class="hiddenRow text-left">
                                    <div class="accordian-body collapse" id="collapse{{ $k }}">
                                        <ul class="dtr-details pt-4">
                                            {{-- <li>
                                                <span class="dtr-title">Tiêu đề:</span>
                                                <span class="dtr-data">{{ $v->title }}</span>
                                            </li> --}}
                                            <li>
                                                <span class="dtr-title">Nguồn</span>
                                                <span class="dtr-data">{{ @$source[$v->source] }}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Tổ chức:</span>
                                                <span class="dtr-data">{{ $v->company }}</span>
                                            </li>
                                            <li>
                                                <span class="dtr-title">Thành phố:</span>
                                                <span class="dtr-data">{{ $v->province }}</span>
                                            </li>
                                            {{-- <li>
                                                <span class="dtr-title">Phản hồi:</span>
                                                <span class="dtr-data">{{ $v->response }}</span>
                                            </li> --}}
                                            <li>
                                                <span class="dtr-title">Ghi chú:</span>
                                                <span class="dtr-data">{{ $v->note }}</span>
                                            </li>
                                            {{-- <li>
                                                <span class="dtr-title">Thời gian tạo:</span>
                                                <span class="dtr-data">{{ date('d/m/Y', $v->created_time) }}</span>
                                            </li> --}}
                                            
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
                <h5 class="modal-title">Thêm Khách hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.customer.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-4 mb-3">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên khách hàng" required/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>SĐT</label>
                            <input type="text" class="form-control" name="phone" placeholder="SĐT" required/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Thành phố</label>
                            <input type="text" class="form-control" name="province" placeholder="Thành phố"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Tiêu đề</label>
                            <input type="text" class="form-control" name="title" placeholder="Tiêu đề"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Tổ chức</label>
                            <input type="text" class="form-control" name="company" placeholder="Tổ chức"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Bắt đầu xử lý</label>
                            <input type="date" class="form-control" name="start_time" placeholder="Bắt đầu xử lý"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Mô tả</label>
                            <textarea class="form-control" name="description" rows="1" placeholder="Mô tả"></textarea>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Ghi chú</label>
                            <textarea class="form-control" name="note" rows="1" placeholder="Ghi chú"></textarea>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Phản hồi của khách</label>
                            <textarea class="form-control" name="response" rows="1" placeholder="Phản hồi"></textarea>
                        </div>
                        
                        <div class="col-lg-4 mb-3">
                            <label>Phụ trách:</label>
                            <select class="form-control select2" name="admin_id" style="width: 100%">
                                @if(!empty($admins))
                                @foreach($admins as $v)
                                <option value="{{ $v->id }}" @if($v->id == auth('admin')->user()->id) check @endif>{{ $v->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Trạng thái:</label>
                            <div class="form-group">
                              <select class="form-control" name="status" id="">
                                <option value="1">Mới</option>
                                <option value="2">Hoàn thành</option>
                                <option value="3">Hủy</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Nguồn:</label>
                            <div class="form-group">
                              <select class="form-control" name="source" id="">
                                @foreach ($source as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                              </select>
                            </div>
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
            <form method="post" action="{{ route('admin.customer.create') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group row">
                        <div class="col-lg-4 mb-3">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên khách hàng" required/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>SĐT</label>
                            <input type="text" class="form-control" name="phone" placeholder="SĐT" required/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Thành phố</label>
                            <input type="text" class="form-control" name="province" placeholder="Thành phố"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Tiêu đề</label>
                            <input type="text" class="form-control" name="title" placeholder="Tiêu đề"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Tổ chức</label>
                            <input type="text" class="form-control" name="company" placeholder="Tổ chức"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Bắt đầu xử lý</label>
                            <input type="date" class="form-control" name="start_time" placeholder="Bắt đầu xử lý"/>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Mô tả</label>
                            <textarea class="form-control" name="description" rows="1" placeholder="Mô tả"></textarea>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Ghi chú</label>
                            <textarea class="form-control" name="note" rows="1" placeholder="Ghi chú"></textarea>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Phản hồi của khách</label>
                            <textarea class="form-control" name="response" rows="1" placeholder="Phản hồi"></textarea>
                        </div>
                        
                        <div class="col-lg-4 mb-3">
                            <label>Phụ trách:</label>
                            <select class="form-control select2" name="admin_id" style="width: 100%">
                                @if(!empty($admins))
                                @foreach($admins as $v)
                                <option value="{{ $v->id }}" @if($v->id == auth('admin')->user()->id) check @endif>{{ $v->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Trạng thái:</label>
                            <div class="form-group">
                              <select class="form-control" name="status" id="">
                                <option value="1">Mới</option>
                                <option value="2">Hoàn thành</option>
                                <option value="3">Hủy</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Nguồn:</label>
                            <div class="form-group">
                              <select class="form-control" name="source" id="">
                                @foreach ($source as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                              </select>
                            </div>
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
        let customer = data[id];
        $('#modalEdit input[name="id"]').val(customer.id);
        $('#modalEdit input[name="name"]').val(customer.name);
        $('#modalEdit input[name="phone"]').val(customer.phone);
        $('#modalEdit input[name="email"]').val(customer.email);
        $('#modalEdit input[name="province"]').val(customer.province);
        $('#modalEdit input[name="title"]').val(customer.title);
        $('#modalEdit input[name="company"]').val(customer.company);
        $('#modalEdit textarea[name="description"]').val(customer.description);
        $('#modalEdit textarea[name="note"]').val(customer.note);
        $('#modalEdit textarea[name="response"]').val(customer.response);
        let timestampA = customer.start_time ? customer.start_time * 1000 : null;
        let tzoffset = (new Date()).getTimezoneOffset() * 60000;
        if (timestampA) {
            let dateA = new Date(timestampA - tzoffset).toISOString().split('T')[0];
            $('#modalEdit input[name="start_time"]').val(dateA);
        } else {
            $('#modalEdit input[name="start_time"]').val('');
        }
        
        $('#modalEdit select[name="admin_id"]').val(customer.admin_id);
        $('#modalEdit select[name="status"]').val(customer.status);
        $('#modalEdit select[name="source"]').val(customer.source);
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
                        url: "{{ route('admin.customer.remove') }}",
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

    // $('#select-limit').change(function () {
    //     let name = $('#form-search input[name="name"]').val();
    //     let limit = $(this).val();
    //     let url = new URL("{{route('admin.customer.index')}}");
    //     url.searchParams.set("limit", limit);
    //     url.searchParams.set("name", name);
    //     window.location.href = url.href;
    // })
    // let now = @json(date('m/d/Y', time()));
    // let arrDate = [now, now]; 
    // let date = @json(old('start_time'));
    // if (date) {
    //     arrDate = date.split(' - ');
    // }

    // $('#kt_daterangepicker_1').daterangepicker({
    //     autoUpdateInput: false,
    //     buttonClasses: ' btn',
    //     applyClass: 'btn-primary',
    //     cancelClass: 'btn-secondary',
    //     // startDate: moment(arrDate[0]),
    //     // endDate: moment(arrDate[1]),
    // });

    const customer = {
        ids: []
    }

    $('.btn-export').click(function() {
        if (customer.ids.length) {
            let data = $.param({id: customer.ids});
            window.location.href = @json(route('admin.customer.export')) + '?' + data
        } else {
            let data = $('#form-search').serialize();
            window.location.href = @json(route('admin.customer.export')) + '?' + data;
        }
    });

    $('.select_record').change(function () {
        let ids = [];
        $('.select_record:checked').each(function () {
            let val = $(this).val();
            ids.push(val);
        });

        customer.ids = ids;
    });

    function importFile(file) {
        var file_data = file[0];
        var type = file_data.type;
        console.log(type);
        // var match = ["image/png", "image/jpg", "image/jpeg"];
        // if (type == match[0] || type == match[1] || type == match[2] || type == match[3]) {
            var form_data = new FormData();
                //thêm files vào trong form data
                form_data.append('file', file_data);
                //sử dụng ajax post
                $.ajax({
                    url: @json(route('admin.customer.import')), // gửi đến file upload.php 
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (res) {
                        if (res.success == 1) {
                            window.location.reload();
                        } else {
                            init.showNoty(res.mess, 'error');
                        }
                    }
                });
        // } else {
        //     init.showNoty('Sai định dạng!', 'error');
        //         return false;
        // }
    }
</script>
@endsection
