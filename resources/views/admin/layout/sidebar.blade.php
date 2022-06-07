<!--begin::Aside-->
<div class="flex-row-auto offcanvas-mobile w-300px w-xxl-350px" id="kt_profile_aside">
    <!--begin::Profile Card-->
    <div class="card card-custom card-stretch">
        <!--begin::Body-->
        <div class="card-body pt-10 p-4">

            <!--begin::Nav-->
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">

                
                <div class="navi-item mb-2">
                    <a href="{{ route('admin.group.index', ['id' => $project->id, 'pid' => $pid]) }}"
                        class="navi-link py-4 @if(Route::is('admin.group.index')) active @endif">
                        <span class="navi-icon mr-2">
                            <span class="svg-icon "><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo8\dist/../src/media/svg/icons\Home\Home.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z M10,13 C9.44771525,13 9,13.4477153 9,14 L9,17 C9,17.5522847 9.44771525,18 10,18 L14,18 C14.5522847,18 15,17.5522847 15,17 L15,14 C15,13.4477153 14.5522847,13 14,13 L10,13 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span>
                        </span>
                        <span class="navi-text font-size-lg">
                            Tổng quan dự án
                        </span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <div class="navi-link py-4 d-flex justify-content-between">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle p-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="navi-icon mr-2">
                                    <span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo8\dist/../src/media/svg/icons\Code\Compiling.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                </span>
                                <span class="navi-text font-size-lg">
                                    {{ $phase[$pid]->name }} ({{ date('d/m', $phase[$pid]->start_time)  }} - {{ date('d/m', $phase[$pid]->end_time) }})
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                @if(!empty($phase))
                                @foreach($phase as $v)
                                <a class="dropdown-item"
                                    href="{{ route('admin.group.index', ['id' => $project->id, 'pid' => $v->id]) }}">{{ $v->name }} ({{ date('d/m', $v->start_time)  }} - {{ date('d/m', $v->end_time) }})</a>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div>
                            <a href="javascript:void(0);" data-toggle="modal" class="{{$isAdmin?'':'d-none'}}"
                            data-target="#modalCreatePhase"><span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo8\dist/../src/media/svg/icons\Code\Plus.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                    <path d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span></a>
                        </div>
                    </div>
                </div>
                <div class="navi-item mb-2">
                    <div class="navi-link py-4 d-flex justify-content-between">
                        <div>
                            <span class="navi-icon mr-2">
                                <span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo8\dist/../src/media/svg/icons\Text\Bullet-list.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                        <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                            <span class="navi-text font-size-lg">
                                Nhóm công việc
                            </span>
                        </div>
                        <div>
                            <a href="javascript:void(0);" data-toggle="modal" class="{{$isAdmin?'':'d-none'}}"
                            data-target="#modalCreateGroup"><span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo8\dist/../src/media/svg/icons\Code\Plus.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                    <path d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span></a>
                        </div>
                    </div>
                </div>
                @if(!empty($project->group))
                @foreach($project->group as $group)
                <div class="navi-item mb-2 ml-6">
                    <div class="navi-link py-4 d-flex justify-content-between @if(Route::is('admin.ticket.index') && $group->id == $gid) active @endif">
                        <a href="{{ route('admin.ticket.index', ['gid' => $group->id, 'pid' => $pid]) }}"
                            class="">
                            <span class="navi-icon mr-2">
                                <span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo8\dist/../src/media/svg/icons\General\Thunder.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M12.3740377,19.9389434 L18.2226499,11.1660251 C18.4524142,10.8213786 18.3592838,10.3557266 18.0146373,10.1259623 C17.8914367,10.0438285 17.7466809,10 17.5986122,10 L13,10 L13,4.47708173 C13,4.06286817 12.6642136,3.72708173 12.25,3.72708173 C11.9992351,3.72708173 11.7650616,3.85240758 11.6259623,4.06105658 L5.7773501,12.8339749 C5.54758575,13.1786214 5.64071616,13.6442734 5.98536267,13.8740377 C6.10856331,13.9561715 6.25331908,14 6.40138782,14 L11,14 L11,19.5229183 C11,19.9371318 11.3357864,20.2729183 11.75,20.2729183 C12.0007649,20.2729183 12.2349384,20.1475924 12.3740377,19.9389434 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                            <span class="navi-text font-size-lg">
                                {{$group->name}}
                            </span>
                        </a>
                        <div class="{{$isAdmin?'':'d-none'}}">
                            <a href="javascript:void(0);" class="mr-2 btn-edit-group" data-id="{{ $group->id }}">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn-remove-group" data-id="{{ $group->id }}">
                                <i class="la la-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
                

            </div>
            <!--end::Nav-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Profile Card-->
</div>
<div class="modal fade" id="modalCreateGroup" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
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
                            <input type="text" class="form-control" name="name" placeholder="Tên nhóm công việc" autocomplete="off" required/>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <label>Phòng</label>
                            <select class="form-control" name="role_id">
                                <option value="0">Mời chọn</option>
                                @if($role->count() > 0)
                                @foreach($role as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                                @endif
                              </select>
                        </div>
                        @if(!empty($phase))
                            @foreach($phase as $v)
                                <div class="col-lg-12 mt-4">
                                    <label>Khối lượng{{ $v->name }} ({{ date('d/m', $v->start_time)  }} - {{ date('d/m', $v->end_time) }})</label>
                                    <input type="number" class="form-control" name="qty[{{$v->id}}]" placeholder="Khối lượng công việc" value="1" min="1"/>
                                </div>
                            @endforeach
                        @endif
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
<div class="modal fade" id="modalCreatePhase" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm phase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!--begin::Form-->
            <form method="post" action="{{ route('admin.group.createPhase') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Tên phase</label>
                            <input type="text" class="form-control" name="name"
                                placeholder="Tên phase" required/>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <label>Ngày bắt đầu:</label>
                            <input type="date" class="form-control" name="start_time" placeholder="Ngày bắt đầu" required/>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <label>Ngày kết thúc:</label>
                            <input type="date" class="form-control" name="end_time" placeholder="Ngày kết thúc" required/>
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
<div class="modal fade" id="modalEditGroup" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa nhóm công việc</h5>
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
                            <input type="text" class="form-control" name="name" placeholder="Tên nhóm công việc" required/>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <label>Phòng</label>
                            <select class="form-control" name="role_id">
                                <option value="0">Mời chọn</option>
                                @if($role->count() > 0)
                                @foreach($role as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                                @endif
                              </select>
                        </div>
                        @if(!empty($phase))
                            @foreach($phase as $v)
                                <div class="col-lg-12 mt-4">
                                    <label>Khối lượng {{ $v->name }} ({{ date('d/m', $v->start_time)  }} - {{ date('d/m', $v->end_time) }})</label>
                                    <input type="number" class="form-control" name="qty[{{$v->id}}]" placeholder="Khối lượng công việc" value="1" min="1" data-phase="{{$v->id}}" required/>
                                </div>
                            @endforeach
                        @endif
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
<!--end::Aside-->

@push('custom_js')
<script>
    var group = @json(collect($project->group ?? [])->keyBy('id'));

    $('.select2').select2({
        placeholder: 'Chọn',
    });

    $('.btn-edit-group').click(function () {
        let id = $(this).data('id');
        let g = group[id];
        
        $('#modalEditGroup input[name="id"]').val(g.id);
        $('#modalEditGroup input[name="name"]').val(g.name);
        $('#modalEditGroup select[name="role_id"]').val(g.role_id);
        $('#modalEditGroup input[name="qty"]').val(g.qty);
        $('#modalEditGroup input[type=number]').each(function () {
            let phase_id = $(this).data('phase');
            let qty = 1;
            console.log(g, phase_id);
            if (g.phase_group.length > 0) {
                let phase_group = g.phase_group.find(v => v.phase_id == phase_id);
                console.log(phase_group);
                qty = !!phase_group ? phase_group.qty : 1;
            }
            $(this).val(qty);
        });
        $('#modalEditGroup').modal('show');
    });

    $('.btn-remove-group').click(function () {
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
@endpush