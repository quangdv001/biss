<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../../">
    <meta charset="utf-8" />
    <title>Biss</title>
    <meta name="description" content="Login page example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->


    <!--begin::Page Custom Styles(used by this page)-->
    <link href="/assets/admin/themes/assets/css/pages/login/classic/login-3.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="/assets/admin/themes/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/themes/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/themes/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->

    <link rel="shortcut icon" href="https://bissbrand.com/wp-content/uploads/2020/11/BISS-LOGO-FINAL-1.png" />

</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="header-fixed subheader-enabled page-loading">

    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid"
                style="background-image: url(/assets/admin/themes/assets/media/bg/bg-1.jpg);">
                <div class="login-form text-center text-white p-7 position-relative overflow-hidden">
                    <!--begin::Login Header-->
                    <div class="d-flex flex-center mb-5">
                        <a href="#">
                            <img src="https://bissbrand.com/wp-content/uploads/2020/11/BISS-LOGO-FINAL-1.png" class="max-h-150px" alt="" />
                        </a>
                    </div>
                    <!--end::Login Header-->

                    <!--begin::Login Sign in form-->
                    <div class="login-signin">
                        <div class="mb-5">
                            <h3>Đăng nhập</h3>
                            <p class="opacity-60 font-weight-bold">Nhập thông tin tài khoản để đăng nhập vào quản trị:</p>
                        </div>
                        @error('error')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <form class="form" method="post">
                            @csrf
                            <div class="form-group">
                                <input
                                    class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5"
                                    type="text" placeholder="Tài khoản" name="username" autocomplete="off" required/>
                            </div>
                            <div class="form-group">
                                <input
                                    class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5"
                                    type="password" placeholder="Mật khẩu" name="password" required/>
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center px-8">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-white text-white m-0">
                                        <input type="checkbox" name="remember" />
                                        <span></span>
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                {{-- <a href="javascript:;" id="kt_login_forgot" class="text-white font-weight-bold">Forget
                                    Password ?</a> --}}
                            </div>
                            <div class="form-group text-center mt-10">
                                <button type="submit"
                                    class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3">Đăng nhập</button>
                            </div>
                        </form>
                        {{-- <div class="mt-10">
                            <span class="opacity-70 mr-4">
                                Don't have an account yet?
                            </span>
                            <a href="javascript:;" id="kt_login_signup" class="text-white font-weight-bold">Sign Up</a>
                        </div> --}}
                    </div>
                    <!--end::Login Sign in form-->

                </div>
            </div>
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->


    <script>
        var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";

    </script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#8950FC",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#6993FF",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#EEE5FF",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#E1E9FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                }
            },
            "font-family": "Poppins"
        };

    </script>
    <!--end::Global Config-->

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="/assets/admin/themes/assets/plugins/global/plugins.bundle.js"></script>
    <script src="/assets/admin/themes/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="/assets/admin/themes/assets/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->


    <!--begin::Page Scripts(used by this page)-->
    <script src="/assets/admin/themes/assets/js/pages/custom/login/login-general.js"></script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>
