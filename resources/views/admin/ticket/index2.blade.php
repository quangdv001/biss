@extends('admin.layout.main')
@section('title')
{{ $group->name }}
@endsection
@section('lib_css')
<link href="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/js/pages/widgets.js"></script>
<script src="/assets/admin/themes/assets/js/pages/custom/profile/profile.js"></script>
<script src="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/admin/themes/assets/js/pages/crud/forms/widgets/bootstrap-switch.js"></script>
@endsection
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 " id="kt_subheader">
    <div class=" w-100  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Mobile Toggle-->
            <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                <span></span>
            </button>
            <!--end::Mobile Toggle-->

            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    {{ $project->name }} </h5>
                <!--end::Page Title-->

                <!--begin::Breadcrumb-->
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->


    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">
    <!--begin::Profile Personal Information-->
    <div class="d-flex flex-row">
        @include('admin.layout.sidebar')
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <!--begin::Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Header-->
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">{{ $group->name }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">Danh sách công việc</span>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                        data-target="#modalCreate">Thêm công việc</button>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body">
                    
                    <!--begin: Datatable-->
                    @php
                    $time = time()
                    @endphp
                    @if($data->count())
                    <table class="table table-separate table-head-custom table-checkable" id="kt_datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Duyệt Khách</th>
                                <th>Sản phẩm</th>
                                <th>Deadline</th>
                                <th>Hoàn thành</th>
                                <th>Khối lượng</th>
                                <th>Độ ưu tiên</th>
                                <th>Người xử lý</th>
                                <th>Người tạo</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            
                            @foreach($data as $k => $v)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $v->name }}</td>
                                <td>{{ $v->description }}</td>
                                <td><a href="{{ $v->input }}" target="_blank">Xem</a></td>
                                <td><a href="{{ $v->output }}" target="_blank">Xem</a></td>
                                <td>{{ $v->deadline_time ? date('d/m', $v->deadline_time) : '' }}</td>
                                <td>{{ $v->complete_time ? date('d/m', $v->complete_time) : '' }}</td>
                                <td>{{ $v->qty }}</td>
                                <td>
                                    @if($v->priority == 1)
                                        <span style="white-space: nowrap;" class="label label-lg font-weight-bold label-light-success label-inline">Thấp</span>
                                    @elseif($v->priority == 2)
                                        <span style="white-space: nowrap;" class="label label-lg font-weight-bold label-light-warning label-inline">Trung bình</span>
                                    @else
                                        <span style="white-space: nowrap;" class="label label-lg font-weight-bold label-light-danger label-inline">Cao</span>
                                    @endif
                                </td>
                                <td>{{ !empty($v->admin) ? implode(', ', $v->admin->pluck('username')->toArray()) : '' }}</td>
                                <td>{{ @$v->creator->username }}</td>
                                </td>
                                <td>
                                    <span
                                        class="label label-lg font-weight-bold label-light-{{ $v->status ? 'success' : 'danger' }} label-inline">{{ $v->status ? 'Hoàn thành' : 'Mới' }}</span>
                                </td>
                                <td nowrap>
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-edit"
                                        title="Chỉnh sửa" data-id="{{ $v->id }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-remove"
                                        title="Xóa thành viên" data-id="{{ $v->id }}">
                                        <i class="la la-trash"></i>
                                    </a>
                                </td>
                                @endforeach
                                
                            </tr>
                        </tbody>

                    </table>
                    @endif
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Personal Information-->
</div>
<!--end::Content-->
<!-- Modal Create-->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm công việc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.ticket.create') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="group_id" value="{{ $gid }}">
                <input type="hidden" name="phase_id" value="{{ $pid }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên cv" required/>
                        </div>
                        <div class="col-lg-6">
                            <label>Mô tả</label>
                            <input type="text" class="form-control" name="description" placeholder="Mô tả cv"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Khách Duyệt</label>
                            <input type="text" class="form-control" name="input" placeholder="Khách Duyệt"/>
                        </div>
                        <div class="col-lg-6">
                            <label>Sản phẩm</label>
                            <input type="text" class="form-control" name="output" placeholder="Sản phẩm"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Khối lượng</label>
                            <input type="number" class="form-control" name="qty" placeholder="Khối lượng công việc" value="1" min="1"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Độ ưu tiên</label>
                            <select class="form-control select2" name="priority" style="width: 100%">
                                <option value="1">Thấp</option>
                                <option value="2" selected>Trung bình</option>
                                <option value="3">Cao</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Deadline:</label>
                            <input type="date" class="form-control" name="deadline_time" placeholder="Deadline" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Người xử lý:</label>
                        <div class="col-lg-12">
                            <select class="form-control select2" name="admin[]" multiple="multiple" style="width: 100%">
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
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" data-on-text="Hoàn thành" data-off-text="Mới" data-on-color="primary"/>
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
                <h5 class="modal-title">Chỉnh sửa công việc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.ticket.create') }}">
                @csrf
                <input type="hidden" name="id" value="">
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="group_id" value="{{ $gid }}">
                <input type="hidden" name="phase_id" value="{{ $pid }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên cv" required/>
                        </div>
                        <div class="col-lg-6">
                            <label>Mô tả</label>
                            <input type="text" class="form-control" name="description" placeholder="Mô tả cv"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Khách Duyệt</label>
                            <input type="text" class="form-control" name="input" placeholder="Khách Duyệt"/>
                        </div>
                        <div class="col-lg-6">
                            <label>Sản phẩm</label>
                            <input type="text" class="form-control" name="output" placeholder="Sản phẩm"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Khối lượng</label>
                            <input type="number" class="form-control" name="qty" placeholder="Khối lượng công việc" value="1" min="1"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Độ ưu tiên</label>
                            <select class="form-control select2" name="priority" style="width: 100%">
                                <option value="1">Thấp</option>
                                <option value="2" selected>Trung bình</option>
                                <option value="3">Cao</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Deadline:</label>
                            <input type="date" class="form-control" name="deadline_time" placeholder="Deadline" required disabled/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Người xử lý:</label>
                        <div class="col-lg-12">
                            <select class="form-control select2" name="admin[]" multiple="multiple" style="width: 100%">
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
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" data-on-text="Hoàn thành" data-off-text="Mới" data-on-color="primary"/>
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
$('#kt_datatable').DataTable({
    responsive: true,
    paging: true,
});
var data = @json($data->keyBy('id'));
$('.btn-edit').click(function(){
    let id = $(this).data('id');
    let ticket = data[id];
    $('#modalEdit input[name="name"]').val(ticket.name);
    $('#modalEdit input[name="description"]').val(ticket.description);
    $('#modalEdit input[name="input"]').val(ticket.input);
    $('#modalEdit input[name="output"]').val(ticket.output);
    $('#modalEdit input[name="qty"]').val(ticket.qty);
    $('#modalEdit select[name="priority"]').val(ticket.priority).trigger('change');
    let timestamp = ticket.deadline_time ? ticket.deadline_time*1000 : null;
    if(timestamp){
        let tzoffset = (new Date()).getTimezoneOffset() * 60000;
        let date = new Date(timestamp - tzoffset).toISOString().split('T')[0];
        $('#modalEdit input[name="deadline_time"]').val(date);
    } else {
        $('#modalEdit input[name="deadline_time"]').val('');
    }
    $('#modalEdit input[name="id"]').val(ticket.id);
    let admin = []
    if(ticket.admin.length > 0){
        $.each(ticket.admin, function(i, v){
            admin.push(v.id);
        });
    }
    $('#modalEdit select[name="admin[]"]').val(admin).trigger('change');
    $('#modalEdit input[name="status"]').prop('checked', ticket.status == 1 ? true : false).trigger('change');
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
                    url: "{{ route('admin.ticket.remove') }}",
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
</script>
@endsection
