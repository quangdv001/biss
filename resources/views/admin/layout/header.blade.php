<!--begin::Header Mobile-->
<div id="kt_header_mobile" class="header-mobile ">
    <!--begin::Logo-->
    <a href="index.html">
        <img alt="Logo" src="/assets/admin/themes/assets/media/logos/logo-default.png" class="max-h-30px" />
    </a>
    <!--end::Logo-->

    <!--begin::Toolbar-->
    <div class="d-flex align-items-center">

        <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
            <span></span>
        </button>

        <button class="btn p-0 ml-2" id="kt_header_mobile_topbar_toggle">
            <span class="svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg--><svg
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                        <path
                            d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                            fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>
                <!--end::Svg Icon--></span> </button>
    </div>
    <!--end::Toolbar-->
</div>
<!--end::Header Mobile-->

<!--begin::Header-->
<div id="kt_header" class="header  header-fixed ">
    <!--begin::Container-->
    <div class=" container-fluid ">
        <!--begin::Left-->
        <div class="d-none d-lg-flex align-items-center mr-3">
            <!--begin::Logo-->
            <a href="{{ route('admin.home.index') }}" class="mr-20">
                <img alt="Logo" src="https://bissbrand.com/wp-content/uploads/2020/11/BISS-LOGO-FINAL-1.png"
                    class="logo-default max-h-35px" />
            </a>
            <!--end::Logo-->
        </div>
        <!--end::Left-->

        <!--begin::Topbar-->
        <div class="topbar  topbar-minimize ">
            <!--begin::User-->
            <div class="dropdown">
                <!--begin::Toggle-->
                <div class="topbar-item" data-toggle="dropdown" data-offset="0px,0px">
                    <div class="btn btn-icon btn-clean h-40px w-40px btn-dropdown">
                        <span class="svg-icon svg-icon-lg">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg--><svg
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path
                                        d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <path
                                        d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                        fill="#000000" fill-rule="nonzero" />
                                </g>
                            </svg>
                            <!--end::Svg Icon--></span> </div>
                </div>
                <!--end::Toggle-->

                <!--begin::Dropdown-->
                <div
                    class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg p-0">
                    <!--begin::Header-->
                    <div class="d-flex align-items-center p-8 rounded-top">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-md bg-light-primary mr-3 flex-shrink-0">
                            <img src="/assets/admin/themes/assets/media/users/default.jpg" alt="" />
                        </div>
                        <!--end::Symbol-->

                        <!--begin::Text-->
                        <div class="text-dark m-0 flex-grow-1 mr-3 font-size-h5">{{ auth()->user()->name ? auth()->user()->name : auth()->user()->username }}</div>
                        <!--end::Text-->
                    </div>
                    <div class="separator separator-solid"></div>
                    <!--end::Header-->

                    <!--begin::Nav-->
                    <div class="navi navi-spacer-x-0 pt-5">
                        <!--begin::Item-->
                        <a href="{{ route('admin.profile.index') }}"
                            class="navi-item px-8">
                            <div class="navi-link">
                                <div class="navi-icon mr-2">
                                    <i class="flaticon2-calendar-3 text-success"></i>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold">
                                        Thông tin cá nhân
                                    </div>
                                    <div class="text-muted">
                                        Thông tin cá nhân và mật khẩu
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->

                        <!--begin::Footer-->
                        <div class="navi-separator mt-3"></div>
                        <div class="navi-footer  px-8 py-5">
                            <a href="{{ route('admin.auth.logout') }}"
                                class="btn btn-light-primary font-weight-bold">Đăng xuất</a>
                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Dropdown-->
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->

<!--begin::Header Menu Wrapper-->
<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
    <div class=" container-fluid ">
        <!--begin::Header Menu-->
        <div id="kt_header_menu"
            class="header-menu header-menu-left header-menu-mobile  header-menu-layout-default header-menu-root-arrow ">
            <!--begin::Header Nav-->
            <ul class="menu-nav ">
                <li class="menu-item  menu-item-submenu menu-item-rel @if(Route::is('admin.home.index')) menu-item-open menu-item-here @endif">
                    <a href="{{ route('admin.home.index') }}" class="menu-link"><span class="menu-text">Dashboard</span></a>
                </li>
                <li class="menu-item  menu-item-submenu menu-item-rel @if(Route::is(['admin.role.*', 'admin.account.*'])) menu-item-open menu-item-here @endif" data-menu-toggle="click"
                    aria-haspopup="true"><a href="javascript:;" class="menu-link menu-toggle"><span
                            class="menu-text">Admin</span><span class="menu-desc"></span><i
                            class="menu-arrow"></i></a>
                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                        <ul class="menu-subnav">
                            <li class="menu-item  menu-item-submenu" data-menu-toggle="hover"
                                aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <i class="la la-bars mr-2"></i>
                                    <span class="menu-text">Tài khoản</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-right">
                                    <ul class="menu-subnav">
                                        <li class="menu-item " aria-haspopup="true"><a
                                                href="features/bootstrap/typography.html"
                                                class="menu-link "><i
                                                    class="menu-bullet menu-bullet-dot"><span></span></i><span
                                                    class="menu-text">Danh sách</span></a></li>
                                        <li class="menu-item " aria-haspopup="true"><a
                                                href="features/bootstrap/buttons.html"
                                                class="menu-link "><i
                                                    class="menu-bullet menu-bullet-dot"><span></span></i><span
                                                    class="menu-text">Thêm mới</span></a></li>
                                        
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item  menu-item-submenu @if(Route::is('admin.role.*')) menu-item-open menu-item-here @endif">
                                <a href="{{ route('admin.role.index') }}" class="menu-link">
                                    <i class="la la-bars mr-2"></i>
                                    <span class="menu-text">Chức vụ</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!--end::Header Nav-->
        </div>
        <!--end::Header Menu-->
    </div>
</div>
<!--end::Header Menu Wrapper-->