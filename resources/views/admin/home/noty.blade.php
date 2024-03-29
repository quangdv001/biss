<form>
    <!--begin::Header-->
    <div class="d-flex flex-column pt-12 bg-dark-o-5 rounded-top">
        <!--begin::Title-->
        <h4 class="d-flex flex-center">
            <span class="text-dark">Thông báo</span>
            @if(($ticket->count() + $data->count()) > 0)
            <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{ ($ticket->count() + $data->count()) }}</span>
            @endif
        </h4>
        <!--end::Title-->

        <!--begin::Tabs-->
        <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-primary mt-3 px-8"
            role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab"
                    href="#topbar_notifications_events">Mới @if($new->count() > 0) ({{ $new->count() }}) @endif</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab"
                    href="#topbar_notifications_logs">Trễ hạn @if($old->count() > 0) ({{ $old->count() }}) @endif</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab"
                    href="#topbar_notifications_note">Ghi chú @if($data->count() > 0) ({{ $data->count() }}) @endif</a>
            </li>
        </ul>
        <!--end::Tabs-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="tab-content">
        <!--begin::Tabpane-->
        <div class="tab-pane active show" id="topbar_notifications_events" role="tabpanel">
            @if($new->count() > 0)
            <!--begin::Nav-->
            <div class="navi navi-hover scroll my-4 noty-list" data-scroll="true"
                data-height="300" data-mobile-height="200">
                @foreach($new as $k => $v)
                <!--begin::Item-->
                <a href="javascript:void(0);" class="navi-item noty-item {{ $v->priority == 1 ? 'bg-success' : ($v->priority == 2 ? 'bg-warning' : 'bg-danger') }}" data-id="{{ $v->id }}">
                    <div class="navi-link">
                        <div class="navi-icon mr-2">
                            <i class="flaticon2-paper-plane text-info"></i>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">
                                Bạn có Task mới "{{ $v->name }}" ở dự án "{{ @$v->project->name }}" nhóm "{{ @$v->group->name }}"
                            </div>
                            <div class="text-muted">
                                {{-- {{ $v->name }} --}}
                            </div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                @endforeach

            </div>
            <!--end::Nav-->
            @else
            <div class="d-flex flex-center text-center text-muted min-h-200px">
                Đã cập nhật!
                <br />
                Chưa có thông báo mới
            </div>
            @endif
        </div>
        <!--end::Tabpane-->
        <!--begin::Tabpane-->
        <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
            @if($old->count() > 0)
            <!--begin::Nav-->
            <div class="navi navi-hover scroll my-4 noty-list" data-scroll="true"
                data-height="300" data-mobile-height="200">
                @foreach($old as $k => $v)
                <!--begin::Item-->
                <a href="javascript:void(0);" class="navi-item noty-item {{ $v->priority == 1 ? 'bg-success' : ($v->priority == 2 ? 'bg-warning' : 'bg-danger') }}" data-id="{{ $v->id }}">
                    <div class="navi-link">
                        <div class="navi-icon mr-2">
                            <i class="flaticon2-paper-plane text-info"></i>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">
                                {{-- Bạn có ({{ $v }}) Task trễ hạn ở dự án "{{ @$group[$k]->project->name }}" nhóm "{{ @$group[$k]->name }}" --}}
                                Bạn có Task trễ "{{ $v->name }}" ở dự án "{{ @$v->project->name }}" nhóm "{{ @$v->group->name }}"
                            </div>
                            {{-- <div class="text-muted">
                                {{ $v->updated_at->diffForHumans() }}
                            </div> --}}
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                @endforeach

            </div>
            <!--end::Nav-->
            @else
            <div class="d-flex flex-center text-center text-muted min-h-200px">
                Đã cập nhật!
                <br />
                Chưa có thông báo mới
            </div>
            @endif
        </div>
        <!--end::Tabpane-->
        <!--begin::Tabpane-->
        <div class="tab-pane" id="topbar_notifications_note" role="tabpanel">
            @if($data->count() > 0)
            <!--begin::Nav-->
            <div class="navi navi-hover scroll my-4 noty-list" data-scroll="true"
                data-height="300" data-mobile-height="200">
                @foreach($data as $v)
                <!--begin::Item-->
                <a href="javascript:void(0);" class="navi-item view-note" data-id="{{ $v->id }}">
                    <div class="navi-link">
                        <div class="navi-icon mr-2">
                            <i class="flaticon2-paper-plane text-danger"></i>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">
                                "{{ @$v->adminc->username }}" vừa thêm ghi chú {{ $v->type == 1 ? 'chung' : 'công việc' }} ở dự án "{{ @$v->project->name }}" nhóm "{{ @$v->group->name }}"
                            </div>
                            {{-- <div class="text-muted">
                                {{ $v->updated_at->diffForHumans() }}
                            </div> --}}
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                @endforeach

            </div>
            <!--end::Nav-->
            @else
            <div class="d-flex flex-center text-center text-muted min-h-200px">
                Đã cập nhật!
                <br />
                Chưa có thông báo mới
            </div>
            @endif
        </div>
        <!--end::Tabpane-->
    </div>
    <!--end::Content-->
</form>