@extends('admin.layout.main')
@section('title')
AI
@endsection
@section('lib_css')
{{-- <link href="/assets/admin/themes/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
type="text/css" /> --}}
@endsection
@section('lib_js')
{{-- <script src="/assets/admin/themes/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script> --}}
{{-- <script src="/assets/admin/themes/assets/js/pages/crud/forms/editors/ckeditor-classic.js"></script> --}}
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
                    Trang chủ </h5>
                <!--end::Page Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.project.index')}}" class="text-muted">
                            AI </a>
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

    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-label">
                    AI
                    {{--<div class="text-muted pt-2 font-size-sm">Tài khoản thành viên</div>--}}
                </h3>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#timeline">Timeline</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#post_care">Bài chăm sóc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#post_ads">Bài quảng cáo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#post_sample">Bài mẫu</a>
                </li>
            </ul>
              
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active container" id="timeline">
                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.ai.send') }}" data-type="timeline">
                            @csrf
                            <input type="hidden" name="type" value="timeline">
                            <div class="form-group">
                              <label for="">Bạn là gì? Làm gì? Trong bao lâu?</label>
                              <input type="text" required
                                class="form-control" name="me" id="" aria-describedby="helpId" placeholder="Ví dụ: Trung tâm tiếng anh ở Hà Nội với hơn 10 năm hoạt động">
                            </div>
                            <div class="form-group">
                              <label for="">Mô tả về thương hiệu</label>
                              <textarea required class="form-control" name="description" id="" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Tạo nội dung AI</button>
                        </form>
                        <div class="form-group mt-4">
                          <label for="">Nội dung AI</label>
                          <textarea class="form-control" name="" id="ai_timeline" rows="30"></textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane container" id="post_care">
                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.ai.send') }}" data-type="post_care">
                            @csrf
                            <input type="hidden" name="type" value="post_care">
                            <div class="form-group">
                              <label for="">Nội dung cần viết</label>
                              <input type="text" required
                                class="form-control" name="content" id="" aria-describedby="helpId" placeholder="Nội dung cần viết">
                            </div>
                            <div class="form-group">
                              <label for="">Đối tượng bài viết hướng đến</label>
                              <input type="text" required
                                class="form-control" name="object" id="" aria-describedby="helpId" placeholder="Ví dụ: Genz">
                            </div>
                            <div class="form-group">
                              <label for="">Văn phong</label>
                              <input type="text" required
                                class="form-control" name="style" id="" aria-describedby="helpId" placeholder="Ví dụ: trang trọng, lịch sự">
                            </div>
                            <div class="form-group">
                              <label for="">Nền tảng</label>
                              <input type="text" required
                                class="form-control" name="platform" id="" aria-describedby="helpId" placeholder="Ví dụ: facebook">
                            </div>
                            <button type="submit" class="btn btn-primary">Tạo nội dung AI</button>
                        </form>
                        <div class="form-group mt-4">
                          <label for="">Nội dung AI</label>
                          <textarea class="form-control" name="" id="ai_post_care" rows="30"></textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane container" id="post_ads">
                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.ai.send') }}" data-type="post_ads">
                            @csrf
                            <input type="hidden" name="type" value="post_ads">
                            <div class="form-group">
                              <label for="">Sản phẩm / Dịch vụ</label>
                              <input type="text" required
                                class="form-control" name="product" id="" aria-describedby="helpId" placeholder="Ví dụ: Dịch vụ marketing thuê ngoài">
                            </div>
                            <button type="submit" class="btn btn-primary">Tạo nội dung AI</button>
                        </form>
                        <div class="form-group mt-4">
                          <label for="">Nội dung AI</label>
                          <textarea class="form-control" name="" id="ai_post_ads" rows="30"></textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane container" id="post_sample">
                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.ai.send') }}" data-type="post_sample">
                            @csrf
                            <input type="hidden" name="type" value="post_sample">
                            <div class="form-group">
                                <label for="">Bài viết mẫu</label>
                                <textarea required class="form-control" name="sample" id="" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Văn phong</label>
                                <input type="text" required
                                    class="form-control" name="style" id="" aria-describedby="helpId" placeholder="Ví dụ: trang trọng, lịch sự">
                            </div>
                            <div class="form-group">
                                <label for="">Tiêu đề bài viết</label>
                                <input type="text" required
                                    class="form-control" name="title" id="" aria-describedby="helpId" placeholder="Tiêu đề bài viết">
                            </div>
                            <div class="form-group">
                                <label for="">Fanpage</label>
                                <input type="text" required
                                    class="form-control" name="fanpage" id="" aria-describedby="helpId" placeholder="Tên fanpage">
                            </div>
                            <div class="form-group">
                                <label for="">Lĩnh vực</label>
                                <input type="text" required
                                    class="form-control" name="field" id="" aria-describedby="helpId" placeholder="Lĩnh vực">
                            </div>
                            <div class="form-group">
                                <label for="">Điều bạn muốn</label>
                                <input type="text" required
                                    class="form-control" name="want" id="" aria-describedby="helpId" placeholder="Điều bạn muốn">
                            </div>
                            <div class="form-group">
                                <label for="">USP</label>
                                <input type="text" required
                                    class="form-control" name="usp" id="" aria-describedby="helpId" placeholder="USP, lợi thế cạnh tranh, insight khách hàng">
                            </div>
                            <div class="form-group">
                                <label for="">Đối tượng bài viết hướng đến</label>
                                <input type="text" required
                                    class="form-control" name="object" id="" aria-describedby="helpId" placeholder="Ví dụ: Genz">
                            </div>
                            <div class="form-group">
                                <label for="">Mục tiêu bài viết hướng đến</label>
                                <input type="text" required
                                    class="form-control" name="target" id="" aria-describedby="helpId" placeholder="Mục tiêu bài viết hướng đến">
                            </div>
                            <button type="submit" class="btn btn-primary">Tạo nội dung AI</button>
                        </form>
                        <div class="form-group mt-4">
                          <label for="">Nội dung AI</label>
                          <textarea class="form-control" name="" id="ai_post_sample" rows="30"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::Card-->
</div>
<!--end::Content-->

@endsection
@section('custom_js')
<script>
    $('form').submit(function(e) {
        e.preventDefault();
        let $form = $(this);
        let url = $form.attr('action');
        let method = $form.attr('method');
        let type = $form.data('type');
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    $(`#ai_${type}`).val(res.data);
                    // if (CKEDITORS[type]) {
                    //     CKEDITORS[type].setData(res.data); // Gán nội dung vào CKEditor
                    // }
                }
            },
            error: function(xhr) {
                $('#responseMessage').text('Error: ' + xhr.responseText);
            }
        });

    });

    // let CKEDITORS = {};

    // var KTCkeditor = function () {
    //     var demos = function () {
    //         ['timeline', 'post_care', 'post_ads', 'post_sample'].forEach(type => {
    //             let id = `#ai_${type}`;
    //             ClassicEditor
    //                 .create(document.querySelector(id))
    //                 .then(editor => {
    //                     CKEDITORS[type] = editor; // lưu instance vào object
    //                 })
    //                 .catch(error => {
    //                     console.error(error);
    //                 });
    //         });
    //     }

    //     return {
    //         init: function () {
    //             demos();
    //         }
    //     };
    // }();

    // $(document).ready(function () {
    //     KTCkeditor.init();
    // });
</script>
@endsection
