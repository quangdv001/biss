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

                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Dự án </h5>
                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.project.index')}}" class="text-muted"> Danh sách </a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="text-muted">Chi tiết dự án</span>
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
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark text-capitalize">{{$data->name}}</h3>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-primary btn-sm font-size-sm font-weight-bolder" data-toggle="modal" data-target="#modalCreate">Thêm nhóm</a>
                        </div>
                    </div>
                    <!--end::Header-->

                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Item-->
                        @if(!empty($data->group))
                            @foreach($data->group as $group)
                                <div class="d-flex align-items-center mb-3">
                                    <!--begin::Text-->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1 text-capitalize">
                                            {{$group->name}}
                                        </a>
                                    </div>
                                    <!--end::Text-->

                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="" data-placement="left">
                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right" style="">
                                            <!--begin::Navigation-->
                                            {{--<ul class="navi navi-hover">--}}
                                                {{--<li class="navi-item">--}}
                                                    {{--<a href="#" class="navi-link">--}}
                                                        {{--<span class="navi-icon"><i class="flaticon-settings"></i></span>--}}
                                                        {{--<span class="navi-text">Chi tiết</span>--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                            <ul class="navi navi-hover">
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link btn-edit" data-id="{{$group->id}}">
                                                        <span class="navi-icon"><i class="la la-edit"></i></span>
                                                        <span class="navi-text">Chỉnh sửa</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul class="navi navi-hover">
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link btn-remove" data-id="{{$group->id}}">
                                                        <span class="navi-icon"><i class="la la-trash"></i></span>
                                                        <span class="navi-text">Xóa</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                            @endforeach
                        @endif
                        <!--end:Item-->

                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Profile Card-->
            </div>
            <!--end::Aside-->
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
                    <input type="hidden" name="project_id" value="{{$data->id}}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label>Tên nhóm công việc</label>
                                <input type="text" class="form-control" name="name" placeholder="Tên nhóm công việc"/>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <label>Nhân sự:</label>
                                <select class="form-control select2" name="admin_group[]" multiple="multiple" style="width: 100%">
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

    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
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
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label>Tên nhóm công việc</label>
                                <input type="text" class="form-control" name="name" placeholder="Tên nhóm công việc"/>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <label>Nhân sự:</label>
                                <select class="form-control select2" name="admin_group[]" multiple="multiple" style="width: 100%">
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
        var data = @json(collect($data->group ?? [])->keyBy('id'));

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
</script>
@endsection