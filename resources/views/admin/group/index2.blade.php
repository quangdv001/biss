@extends('admin.layout.main')
@section('title')
{{ $project->name }}
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/js/pages/widgets.js"></script>
<script src="/assets/admin/themes/assets/js/pages/custom/profile/profile.js"></script>
<script src="/assets/admin/themes/assets/js/pages/features/charts/apexcharts.js"></script>
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
                <div class="card-header py-3 d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Tổng quan dự án
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">Báo cáo {{ $phase[$pid]->name }}</span>
                    </div>
                    <div class="{{empty($project->extra_link)?'d-none':''}}">
                        <a href="{{$project->extra_link}}" target="_blank" class="btn btn-primary font-weight-bolder">Tài liệu dự án</a>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            Báo cáo nội dung công việc
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <table class="table text-center table-responsive-md">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nội dung</th>
                                            <th scope="col">Số lượng HĐ</th>
                                            <th scope="col">Mới</th>
                                            <th scope="col">Hết hạn</th>
                                            <th scope="col">Hoàn thành</th>
                                            @if(!$isGuest)
                                            <th scope="col">Hoàn thành đúng hạn</th>
                                            <th scope="col">Hoàn thành trễ</th>
                                            @endif
                                            <th scope="col">Tiến độ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($phase[$pid]->group))
                                            @foreach($phase[$pid]->group as $k => $gr)
                                                <tr>
                                                    <td scope="row">{{$k + 1}}</td>
                                                    <td class="text-left">{{$gr['name']}}</td>
                                                    <td>{{$gr['phase_qty'] ?? 0}} </td>
                                                    <td>{{$reportGroup[@$gr['id']]['report']['new'] ?? 0}}</td>
                                                    <td>{{$reportGroup[@$gr['id']]['report']['expired'] ?? 0}}</td>
                                                    <td>{{$reportGroup[@$gr['id']]['report']['done'] ?? 0}}</td>
                                                    @if(!$isGuest)
                                                    <td>{{$reportGroup[@$gr['id']]['report']['done_on_time'] ?? 0}}</td>
                                                    <td>{{$reportGroup[@$gr['id']]['report']['done_out_time'] ?? 0}}</td>
                                                    @endif
                                                    <td>{{$reportGroup[@$gr['id']]['report']['percent'] ?? 0}} %</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-lg-12">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            Báo cáo thành viên
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <table class="table text-center table-responsive-md">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Thành viên</th>
                                            <th scope="col">Số lượng</th>
                                            <th scope="col">Mới</th>
                                            <th scope="col">Hết hạn</th>
                                            <th scope="col">Hoàn thành</th>
                                            @if(!$isGuest)
                                            <th scope="col">Hoàn thành đúng hạn</th>
                                            <th scope="col">Hoàn thành trễ</th>
                                            @endif
                                            <th scope="col">Tiến độ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($reportMember))
                                            @foreach($reportMember as $k => $member)
                                                <tr>
                                                    <td scope="row">{{$k + 1}}</td>
                                                    <td class="text-left">{{$member['username']}}</td>
                                                    <td>{{$member['report']['total']}}</td>
                                                    <td>{{$member['report']['new']}}</td>
                                                    <td>{{$member['report']['expired']}}</td>
                                                    <td>{{$member['report']['done']}}</td>
                                                    @if(!$isGuest)
                                                    <td>{{$member['report']['done_on_time']}}</td>
                                                    <td>{{$member['report']['done_out_time']}}</td>
                                                    @endif
                                                    <td>{{$member['report']['percent']}} %</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Personal Information-->
</div>
<!--end::Content-->
@endsection
@section('custom_js')
@endsection
