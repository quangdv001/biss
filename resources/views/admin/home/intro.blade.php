@extends('admin.layout.main')
@section('title')
Biss
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
                    Dashboard </h5>
                <!--end::Page Title-->

            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->

    </div>
</div>
<!--end::Subheader-->

<div class="content flex-column-fluid" id="kt_content">
    <!--begin::Dashboard-->
    <!--begin::Row-->
    <div class="row">
        
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-4">
                    <iframe id="note1" sandbox="allow-scripts" security="restricted"
                        src="https://bissbrand.com/phong-marketing-thue-ngoai/embed/" width="100%" height="450"
                        title="&#8220;Phòng Marketing thuê ngoài&#8221; &#8212; Biss Brand" frameborder="0"
                        marginwidth="0" marginheight="0" scrolling="no" class="wp-embedded-content" style="height: 100%"></iframe>
                </div>
                <div class="col-xl-4">
                    <iframe width="" height="450" src="https://www.youtube.com/embed/RDwO3k5dypQ?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;"></iframe>
                </div>
                <div class="col-xl-4">
                    <div class="fb-page" data-href="https://www.facebook.com/marketingbiss" data-tabs="timeline"
                                data-width="2000" data-height="450" data-small-header="false" data-adapt-container-width="true"
                                data-hide-cover="false" data-show-facepile="true">
                                <blockquote cite="https://www.facebook.com/marketingbiss" class="fb-xfbml-parse-ignore"><a
                                        href="https://www.facebook.com/marketingbiss">Biss Agency</a></blockquote>
                            </div>
        
                </div>
                

            </div>
            <div class="row mt-6">
                <div class="col-xl-4">

                    <iframe id="note2" sandbox="allow-scripts" security="restricted"
                        src="https://bissbrand.com/dich-vu-thiet-ke-profile-cong-ty/embed/" width="100%" height="400"
                        title="&#8220;Thiết kế hồ sơ năng lực&#8221; &#8212; Biss Brand" frameborder="0" marginwidth="0"
                        marginheight="0" scrolling="no" class="wp-embedded-content"></iframe>
                </div>
                <div class="col-xl-4">
                    <iframe id="note3" sandbox="allow-scripts" security="restricted"
                        src="https://bissbrand.com/thiet-ke-bo-nhan-dien-thuong-hieu-chuyen-nghiep/embed/" width="100%"
                        height="400" title="&#8220;Thiết kế nhận diện thương hiệu&#8221; &#8212; Biss Brand"
                        frameborder="0" marginwidth="0" marginheight="0" scrolling="no"
                        class="wp-embedded-content"></iframe>
                </div>
                <div class="col-xl-4">
                    <iframe sandbox="allow-scripts" security="restricted"
                        src="https://bissbrand.com/thiet-ke-website-chuyen-nghiep/embed/" width="100%" height="400"
                        title="&#8220;Dự án đã triển khai&#8221; &#8212; Biss Brand" frameborder="0"
                        marginwidth="0" marginheight="0" scrolling="no" class="wp-embedded-content"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

    <!--end::Dashboard-->
</div>
<!--end::Content-->
@endsection
@section('custom_js')
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v13.0&appId=2718091718206751&autoLogAppEvents=1"
    nonce="9n7ISk0n"></script>
<script type='text/javascript'>
    ! function (c, d) {
        "use strict";
        var e = !1,
            n = !1;
        if (d.querySelector)
            if (c.addEventListener) e = !0;
        if (c.wp = c.wp || {}, !c.wp.receiveEmbedMessage)
            if (c.wp.receiveEmbedMessage = function (e) {
                    var t = e.data;
                    if (t)
                        if (t.secret || t.message || t.value)
                            if (!/[^a-zA-Z0-9]/.test(t.secret)) {
                                for (var r, a, i, s = d.querySelectorAll('iframe[data-secret="' + t
                                        .secret + '"]'), n = d.querySelectorAll(
                                        'blockquote[data-secret="' + t.secret + '"]'), o = 0; o < n
                                    .length; o++) n[o].style.display = "none";
                                for (o = 0; o < s.length; o++){

                                    if (r = s[o], e.source === r.contentWindow) {
                                        if (r.removeAttribute("style"), "height" === t.message) {
                                            if (1e3 < (i = parseInt(t.value, 10))) i = 1e3;
                                            else if (~~i < 200) i = 200;
                                            r.height = i
                                        }
                                        if ("link" === t.message)
                                            if (a = d.createElement("a"), i = d.createElement("a"),
                                                a.href = r.getAttribute("src"), i.href = t.value, i
                                                .host === a.host)
                                                if (d.activeElement === r) c.top.location.href = t
                                                    .value
                                    }
                                }

                            }
                }, e) c.addEventListener("message", c.wp.receiveEmbedMessage, !1), d
                .addEventListener("DOMContentLoaded", t, !1), c.addEventListener("load", t, !1);

        function t() {
            if (!n) {
                n = !0;
                for (var e, t, r = -1 !== navigator.appVersion.indexOf("MSIE 10"), a = !!navigator
                        .userAgent.match(/Trident.*rv:11\./), i = d.querySelectorAll(
                            "iframe.wp-embedded-content"), s = 0; s < i.length; s++) {
                    if (!(e = i[s]).getAttribute("data-secret")) t = Math.random().toString(36)
                        .substr(2, 10), e.src += "#?secret=" + t, e.setAttribute("data-secret", t);
                    if (r || a)(t = e.cloneNode(!0)).removeAttribute("security"), e.parentNode
                        .replaceChild(t, e)
                }
            }
        }
    }(window, document);

</script>
@endsection
