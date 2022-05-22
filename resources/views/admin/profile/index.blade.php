@extends('admin.layout.main')
@section('title')
Thông tin cá nhân
@endsection
@section('lib_js')
<script src="/assets/admin/themes/assets/js/pages/widgets.js"></script>
<script src="/assets/admin/themes/assets/js/pages/custom/profile/profile.js"></script>
@endsection
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 " id="kt_subheader">
    <div
        class=" w-100  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Mobile Toggle-->
            <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none"
                id="kt_subheader_mobile_toggle">
                <span></span>
            </button>
            <!--end::Mobile Toggle-->

            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    Profile </h5>
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
        <!--begin::Aside-->
        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
            <!--begin::Profile Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::User-->
                    <div class="d-flex align-items-center">
                        <div
                            class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                            <div class="symbol-label"
                                style="background-image:url({{ auth('admin')->user()->avatar ? Storage::url(auth('admin')->user()->avatar) : '/assets/admin/themes/assets/media/users/default.jpg' }})">
                            </div>
                            <i class="symbol-badge bg-success"></i>
                        </div>
                        <div>
                            <a href="#"
                                class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                                {{ auth('admin')->user()->name ? auth('admin')->user()->name : auth('admin')->user()->username }}
                            </a>
                            <div class="text-muted">
                                {{ !empty(auth('admin')->user()->roles()) ? implode(', ', auth('admin')->user()->roles()->pluck('name')->toArray()) : '' }}
                            </div>
                        </div>
                    </div>
                    <!--end::User-->

                    <!--begin::Contact-->
                    <div class="py-9">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Email:</span>
                            <a href="#"
                                class="text-muted text-hover-primary">{{ auth('admin')->user()->email }}</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">SĐT:</span>
                            <span class="text-muted">{{ auth('admin')->user()->phone }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Ngày sinh:</span>
                            <span class="text-muted">{{ auth('admin')->user()->birthday ? date('d/m/Y', auth('admin')->user()->birthday) : '' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold mr-2">Địa chỉ:</span>
                            <span class="text-muted">{{ auth('admin')->user()->address }}</span>
                        </div>
                    </div>
                    <!--end::Contact-->

                    <!--begin::Nav-->
                    <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                    
                        <div class="navi-item mb-2">
                            <a href="{{ route('admin.profile.index') }}"
                                class="navi-link py-4 active">
                                <span class="navi-icon mr-2">
                                    <span class="svg-icon">
                                        <!--begin::Svg Icon | path:/assets/admin/themes/assets/media/svg/icons/General/User.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24"
                                            version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path
                                                    d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                                    fill="#000000" fill-rule="nonzero"
                                                    opacity="0.3" />
                                                <path
                                                    d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                                    fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon--></span> </span>
                                <span class="navi-text font-size-lg">
                                    Thông tin cá nhân
                                </span>
                            </a>
                        </div>
                        
                        <div class="navi-item mb-2">
                            <a href="{{ route('admin.profile.password') }}"
                                class="navi-link py-4 ">
                                <span class="navi-icon mr-2">
                                    <span class="svg-icon">
                                        <!--begin::Svg Icon | path:/assets/admin/themes/assets/media/svg/icons/Communication/Shield-user.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24"
                                            version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
                                                    fill="#000000" opacity="0.3" />
                                                <path
                                                    d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z"
                                                    fill="#000000" opacity="0.3" />
                                                <path
                                                    d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
                                                    fill="#000000" opacity="0.3" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon--></span> </span>
                                <span class="navi-text font-size-lg">
                                    Đổi mật khẩu
                                </span>
                            </a>
                        </div>
                        
                    </div>
                    <!--end::Nav-->
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
                <!--begin::Header-->
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Thông tin cá nhân
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">Cập nhật thông tin cá nhân</span>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success mr-2 btn-submit">Cập nhật</button>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Form-->
                <form class="form" method="post" enctype="multipart/form-data">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h5 class="font-weight-bold mb-6">Thông tin người dùng</h5>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="image-input image-input-outline"
                                    id="kt_profile_avatar"
                                    style="background-image: url(/assets/admin/themes/assets/media/users/default.jpg)">
                                    <div class="image-input-wrapper"
                                        style="background-image: url({{ auth('admin')->user()->avatar ? Storage::url(auth('admin')->user()->avatar) : '/assets/admin/themes/assets/media/users/default.jpg' }})">
                                    </div>

                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" name="profile_avatar"
                                            accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="profile_avatar_remove" />
                                    </label>

                                    <span
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip"
                                        title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>

                                    <span
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="remove" data-toggle="tooltip"
                                        title="Remove avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>
                                <span class="form-text text-muted">Định dạng file: png, jpg,
                                    jpeg.</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Tài khoản</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid"
                                    type="text" value="{{ auth('admin')->user()->username }}" readonly/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Mã nhân sự</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid"
                                    type="text" value="{{ auth('admin')->user()->mns }}" readonly/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Họ tên</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" name="name"
                                    type="text" value="{{ auth('admin')->user()->name }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Ngày sinh</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" name="birthday"
                                    type="date" value="{{ auth('admin')->user()->birthday ? date('Y-m-d', auth('admin')->user()->birthday) : '' }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Địa chỉ</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" name="address"
                                    type="text" value="{{ auth('admin')->user()->address }}" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h5 class="font-weight-bold mt-10 mb-6">Thông tin liên lạc</h5>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">SĐT</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group input-group-lg input-group-solid">
                                    <div class="input-group-prepend"><span
                                            class="input-group-text"><i
                                                class="la la-phone"></i></span></div>
                                    <input type="text"
                                        class="form-control form-control-lg form-control-solid" name="phone"
                                        value="{{ auth('admin')->user()->phone }}" placeholder="SĐT" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group input-group-lg input-group-solid">
                                    <div class="input-group-prepend"><span
                                            class="input-group-text"><i
                                                class="la la-at"></i></span></div>
                                    <input type="text"
                                        class="form-control form-control-lg form-control-solid" name="email"
                                        value="{{ auth('admin')->user()->email }}" placeholder="Email" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                </form>
                <!--end::Form-->
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Personal Information-->
</div>
<!--end::Content-->
@endsection
@section('custom_js')
<script>
    $('.btn-submit').click(function(){
        $('.form').submit();
    });
</script>
@endsection