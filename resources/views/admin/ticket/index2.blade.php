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
                        <button type="button" class="btn btn-warning mr-2" data-toggle="modal"
                        data-target="#modalNote">Thêm ghi chú</button>
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
                    <table class="table table-separate table-head-custom collapsed display display" id="kt_datatable">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1" style="min-width: 100px">Tên</th>
                                <th data-priority="1">Ghi chú</th>
                                <th data-priority="1">Deadline</th>
                                <th data-priority="1">Hoàn thành</th>
                                <th data-priority="1">Khách duyệt</th>
                                <th data-priority="1">Sản phẩm</th>
                                <th data-priority="1">Độ ưu tiên</th>
                                <th data-priority="1">Trạng thái</th>
                                <th data-priority="2">Khối lượng</th>
                                <th data-priority="2">Người xử lý</th>
                                <th data-priority="2">Người tạo</th>
                                <th data-priority="2">Mô tả</th>
                                <th data-priority="2">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            
                            @foreach($data as $k => $v)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td >{{ $v->name }}</td>
                                <td>{{ $v->note }}</td>
                                <td>{{ $v->deadline_time ? date('d/m', $v->deadline_time) : '' }}</td>
                                <td>{{ $v->complete_time ? date('d/m', $v->complete_time) : '' }}</td>
                                <td><a href="{{ $v->input }}" target="_blank" class="{{empty($v->input)?'d-none':''}}">Xem</a></td>
                                <td><a href="{{ $v->output }}" target="_blank" class="{{empty($v->output)?'d-none':''}}">Xem</a></td>
                                
                                <td nowrap>
                                    @if($v->priority == 1)
                                        <span  class="label label-lg font-weight-bold label-light-success label-inline">Thấp</span>
                                    @elseif($v->priority == 2)
                                        <span  class="label label-lg font-weight-bold label-light-warning label-inline">Trung bình</span>
                                    @else
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline">Cao</span>
                                    @endif
                                </td>
                                <td nowrap>
                                    <span
                                        class="label label-lg font-weight-bold label-light-{{ $v->status_cl }} label-inline">{{ $v->status_lb }}</span>
                                </td>
                                <td>{{ $v->qty }}</td>
                                <td>{{ !empty($v->admin) ? implode(', ', $v->admin->pluck('username')->toArray()) : '' }}</td>
                                <td>{{ @$v->creator->username }}</td>
                                <td>{{ $v->description }}</td>
                                <td nowrap>
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-note"
                                        title="Ghi chú" data-id="{{ $v->id }}">
                                        <i class="la la-sticky-note"></i>
                                    </a>
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
            <form method="post" action="{{ route('admin.ticket.createAjax') }}" id="formCreate">
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
                        <div class="col-lg-8">
                            <label>Người xử lý</label>
                            <select class="form-control select2" name="admin[]" multiple="multiple" style="width: 100%">
                                @if(!empty($admins))
                                @foreach($admins as $v)
                                <option value="{{ $v->id }}">{{ $v->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
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
            <form method="post" action="{{ route('admin.ticket.create') }}" id="formEdit">
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
                            <input type="date" class="form-control" name="deadline_time" placeholder="Deadline" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-8">
                            <label>Người xử lý</label>
                            <select class="form-control select2" name="admin[]" multiple="multiple" style="width: 100%">
                                @if(!empty($admins))
                                @foreach($admins as $v)
                                <option value="{{ $v->id }}">{{ $v->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Trạng thái:</label>
                            <div>
                                <input data-switch="true" type="checkbox" name="status" data-on-text="Hoàn thành" data-off-text="Mới" data-on-color="primary" @if(auth('admin')->user()->hasRole(['guest'])) readonly @endif/>
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

{{-- modal note --}}
<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ghi chú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Body-->
            <div class="card-body p-0 pb-4">
                <div class="tab-content pt-5">
                    <!--begin::Tab Content-->
                    <div class="tab-pane active" id="kt_apps_contacts_view_tab_1"
                        role="tabpanel">
                        <div class="container">
                            {{-- @if(auth('admin')->user()->hasRole(['super_admin', 'account', 'guest'])) --}}
                            <form class="form">
                                <div class="form-group">
                                    <textarea
                                        class="form-control form-control-lg form-control-solid inp-note"
                                        id="exampleTextarea" rows="3"
                                        placeholder="Thêm ghi chú"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="javascript:void(0);"
                                            class="btn btn-light-primary font-weight-bold btn-add-note">Thêm ghi chú</a>
                                    </div>
                                </div>
                            </form>
                            {{-- @endif --}}
                            <div class="separator separator-dashed my-10"></div>

                            <!--begin::Timeline-->
                            <div class="timeline timeline-3" style="max-height: 500px;
                            overflow: auto;">
                                <div class="timeline-items list-notes">
                                    @if($notes->count() > 0)
                                    @foreach($notes as $v)
                                    <div class="timeline-item">
                                        <div class="timeline-media">
                                            <img alt="Pic"
                                                src="{{ @$v->admin->avatar ? Storage::url($v->admin->avatar) : '/assets/admin/themes/assets/media/users/default.jpg' }}" />
                                        </div>
                                        <div class="timeline-content">
                                            <div
                                                class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="mr-2">
                                                    <a href="#"
                                                        class="text-dark-75 text-hover-primary font-weight-bold">
                                                        {{ @$v->admin->username }}
                                                    </a>
                                                    <span class="text-muted ml-2">
                                                        {{ $v->created_at->diffForHumans() }}
                                                    </span>
                                                    {{-- <span
                                                        class="label label-light-success font-weight-bolder label-inline ml-2">new</span> --}}
                                                </div>
                                            </div>
                                            <p class="p-0">
                                                {!! nl2br($v->note) !!}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <!--end::Timeline-->
                        </div>
                    </div>
                    <!--end::Tab Content-->
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditNote" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa Ghi chú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Body-->
            <div class="card-body p-0 pb-4">
                <div class="tab-content pt-5">
                    <!--begin::Tab Content-->
                    <div class="tab-pane active" id="kt_apps_contacts_view_tab_1"
                        role="tabpanel">
                        <div class="container">
                            {{-- @if(auth('admin')->user()->hasRole(['super_admin', 'account', 'guest'])) --}}
                            <form class="form" method="post" action="{{ route('admin.ticket.editNote') }}">
                                @csrf
                                <input type="hidden" name="id" value="">
                                <div class="form-group">
                                    <textarea
                                        class="form-control form-control-lg form-control-solid"
                                        id="exampleTextarea" rows="3" name="note"
                                        placeholder="Thêm ghi chú"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-light-primary">Sửa ghi chú</button>
                                    </div>
                                </div>
                            </form>
                            {{-- @endif --}}
                            <div class="separator separator-dashed my-10"></div>
                            <!--end::Timeline-->
                        </div>
                    </div>
                    <!--end::Tab Content-->
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>
@endsection
@section('custom_js')
<script>
$('#kt_datatable').DataTable({
    responsive: true,
    pageLength: 100,
    paging: true,
    columns:[
        {},
        {},
        {},
        {},
        {},
        {},
        {},
        {},
        {},
        {"className": "none"},
        {"className": "none"},
        {"className": "none"},
        {"className": "none"},
        {"className": "none"},
    ],
});
var data = @json($data->keyBy('id'));
let user_id = @json(auth('admin')->user()->id);
let is_admin = @json(auth('admin')->user()->hasRole(['super_admin', 'account']));
let is_guest = @json(auth('admin')->user()->hasRole(['guest']));
$(document).on('click', '.btn-edit', function(){
    let id = $(this).data('id');
    let ticket = data[id];
    if(is_admin || user_id == ticket.admin_id_c){
        $('#modalEdit input[name="deadline_time"]').attr("readonly", false);
    } else {
        $('#modalEdit input[name="deadline_time"]').attr("readonly", true);
    }

    $('#modalEdit input[name="name"]').val(ticket.name);
    $('#modalEdit input[name="description"]').val(ticket.description);
    $('#modalEdit textarea[name="note"]').val(ticket.note);
    $('#modalEdit input[name="input"]').val(ticket.input);
    $('#modalEdit input[name="output"]').val(ticket.output);
    $('#modalEdit input[name="qty"]').val(ticket.qty);
    $('#modalEdit select[name="priority"]').val(ticket.priority).trigger('change');
    let timestamp = ticket.deadline_time ? ticket.deadline_time*1000 : null;
    if(timestamp){
        let tzoffset = (new Date()).getTimezoneOffset() * 60000;
        let date = new Date(timestamp - tzoffset).toISOString().split('T')[0];
        $('#modalEdit input[name="deadline_time"]').val(date).trigger('change');
    } else {
        $('#modalEdit input[name="deadline_time"]').val('').trigger('change');
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


    if(is_guest){
        $('#modalEdit input').attr("readonly", true);
        $('#modalEdit select').attr("readonly", true);
    }
    $('#modalEdit').modal('show');
});

$(document).on('click', '.btn-note', function(){
    let id = $(this).data('id');
    let ticket = data[id];
    $('#modalEditNote input[name="id"]').val(ticket.id);
    $('#modalEditNote textarea[name="note"]').val(ticket.note);
    $('#modalEditNote').modal('show');
});

$(document).on('click', '.btn-remove', function(){
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
                            init.showNoty(res.mess, 'error');
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

$('.btn-add-note').click(function(){
    let note = $('.inp-note').val();
    let admin_id = @json(auth('admin')->user()->id);
    let group_id = @json($gid);
    let phase_id = @json($pid);
    if(!note){
        init.showNoty('Mời nhập ghi chú!', 'error');
        return false;
    }
    if(!init.conf.ajax_sending){
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.ticket.createNote') }}",
            data: {note, admin_id, group_id, phase_id},
            beforeSend: function(){
                init.conf.ajax_sending = true;
            },
            success: function(res){
                if(res.success){
                    $('.list-notes').html(res.html);
                    $('.inp-note').val('');
                } else {
                    init.showNoty('Có lỗi xảy ra!', 'error');
                }
            },
            complete: function(){
                init.conf.ajax_sending = false;
            }
        })
    }
});

$("#formCreate").submit(function(e) {
    //prevent Default functionality
    e.preventDefault();

    //get the action-url of the form
    var actionurl = e.currentTarget.action;
    //do your own request an handle the results
    if(!init.conf.ajax_sending){
        $.ajax({
            url: actionurl,
            type: 'post',
            data: $("#formCreate").serialize(),
            beforeSend: function(){
                init.conf.ajax_sending = true;
            },
            success: function(res) {
                if(res.success){
                    $('#modalCreate input[name="name"]').val('');
                    $('#modalCreate input[name="description"]').val('');
                    $('#modalCreate input[name="input"]').val('');
                    $('#modalCreate input[name="output"]').val('');
                    init.showNoty('Tạo ticket thành công!', 'success');
                } else {
                    init.showNoty(res.mess, 'error');
                }
            },
            complete: function(){
                init.conf.ajax_sending = false;
            }
        });
    }

});

$("#formEdit").submit(function(e) {
    //prevent Default functionality
    e.preventDefault();

    //get the action-url of the form
    var actionurl = e.currentTarget.action;
    //do your own request an handle the results
    if(!init.conf.ajax_sending){
        $.ajax({
            url: actionurl,
            type: 'post',
            data: $("#formEdit").serialize(),
            beforeSend: function(){
                init.conf.ajax_sending = true;
            },
            success: function(res) {
                if(res.success){
                    init.showNoty(res.mess, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    init.showNoty(res.mess, 'error');
                }
            },
            complete: function(){
                init.conf.ajax_sending = false;
            }
        });
    }

});

$("#modalCreate").on('hide.bs.modal', function () {
    window.location.reload();
});

if(window.location.href.indexOf('#modalNote') != -1) {
    $('#modalNote').modal('show');
  }
</script>
@endsection
