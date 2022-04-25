<!--begin::Aside-->
<div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
    <!--begin::Profile Card-->
    <div class="card card-custom card-stretch gutter-b">
        <!--begin::Body-->
        <div class="card-body pt-5">
            <div class="dropdown">
                <button class="btn btn-block btn-light dropdown-toggle text-left" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $pid > 0 ? $phase[$pid]->name : $phase->first()->name }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if(!empty($phase))
                    @foreach($phase as $v)
                    <a class="dropdown-item"
                        href="{{ route('admin.group.index', ['id' => $id, 'pid' => $v->id]) }}">{{ $v->name }}</a>
                    @endforeach
                    @endif
                    <a class="dropdown-item" href="#"><i class="fa fa-plus icon-nm mr-2 align-self-center"></i>Thêm
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
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
