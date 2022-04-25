@extends('admin.layout.main')
@section('title')
Chi tiết dự án
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/js/pages/widgets.js"></script>
<script src="/assets/admin/themes/assets/js/pages/custom/profile/profile.js"></script>
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
                <h5 class="text-dark font-weight-bold my-1 mr-5"> Dự án </h5>
                <!--end::Page Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.project.index')}}" class="text-muted"> Danh sách </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="text-muted text-capitalize">{{$project->name}}</span>
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
    <!--begin::Profile Personal Information-->
    <div class="d-flex flex-row">
        <!--begin::Aside-->
        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
            <!--begin::Profile Card-->
            <div class="card card-custom card-stretch gutter-b">
                <!--begin::Body-->
                <div class="card-body pt-5">
                    <div class="dropdown">
                        <button class="btn btn-block btn-light dropdown-toggle text-left" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $pid > 0 ? $phase[$pid]->name : $phase->first()->name }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if(!empty($phase))
                            @foreach($phase as $v)
                            <a class="dropdown-item"
                                href="{{ route('admin.group.index', ['id' => $id, 'pid' => $v->id]) }}">{{ $v->name }}</a>
                            @endforeach
                            @endif
                            <a class="dropdown-item" href="#"><i
                                    class="fa fa-plus icon-nm mr-2 align-self-center"></i>Thêm
                                phase</a>
                        </div>
                    </div>
                    <div class="navi navi-bold navi-hover navi-active navi-link-rounded mt-3">
                        <div class="navi-item mb-2">
                            <a href="javascript:void(0);" class="navi-link py-4 active">
                                <span class="font-size-lg">
                                    Tổng quan dự án
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="accordion accordion-solid accordion-toggle-plus mt-3" id="accordionExample8">
                        <div class="card">
                            <div class="card-header" id="headingTwo8">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo8">
                                    <div class="font-size-lg">Nhóm công việc</div>
                                </div>
                            </div>
                            <div id="collapseTwo8" class="collapse show" data-parent="#accordionExample8">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex flex-column flex-grow-1">
                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modalCreate">Tạo nhóm công việc</button>
                                            {{-- <a href="javascript:void(0)" class="btn btn-lg label label-lg font-weight-bolder label-rounded label-success label-inline" data-toggle="modal" data-target="#modalCreate">Tạo nhóm</a> --}}
                                        </div>
                                    </div>
                                    @if(!empty($project->group))
                                    @foreach($project->group as $group)
                                    <div class="d-flex align-items-center mb-4">
                                        <span class="bullet bullet-bar bg-warning align-self-stretch mr-2"></span>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <a href="{{route('admin.ticket.index',['group_id'=>$group->id])}}"
                                                class="text-dark-75 text-hover-primary font-weight-bold font-size-lg text-capitalize">
                                                {{$group->name}}
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end::Body-->
            </div>
            <!--end::Profile Card-->
        </div>
        <!--end::Aside-->
        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm nhóm công việc</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <!--begin::Form-->
                    <form method="post" action="{{ route('admin.group.create') }}">
                        @csrf
                        <input type="hidden" name="project_id" value="{{$project->id}}">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label>Tên nhóm công việc</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Tên nhóm công việc" />
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <label>Nhân sự:</label>
                                    <select class="form-control select2" name="admin_group[]" multiple="multiple"
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

        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <!--begin::Card-->
            <div class="card card-custom card-stretch">

            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Personal Information-->
</div>



<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa dự án</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.group.create') }}">
                @csrf
                <input type="hidden" name="id" value="{{$project->id}}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Tên nhóm công việc</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên nhóm công việc" />
                        </div>
                        <div class="col-lg-12 mt-3">
                            <label>Nhân sự:</label>
                            <select class="form-control select2" name="admin_group[]" multiple="multiple"
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
<!--end::Content-->
@endsection
@section('custom_js')
<script>
    var data = @json(collect($project - > group ? ? []) - > keyBy('id'));

    $('.select2').select2({
        placeholder: 'Chọn',
    });

    $('.btn-edit').click(function () {
        let id = $(this).data('id');
        let group = data[id];
        $('#modalEdit input[name="id"]').val(group.id);
        $('#modalEdit input[name="name"]').val(group.name);
        let admin = [];
        if (group.admin.length > 0) {
            $.each(group.admin, function (i, v) {
                admin.push(v.id);
            });
        }
        $('#modalEdit select[name="admin_group[]"]').val(admin).trigger('change');
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
                        url: "{{ route('admin.group.remove') }}",
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

</script>
@endsection
