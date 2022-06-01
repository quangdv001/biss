<div class="dropdown my-noty">
    <!--begin::Toggle-->
    <div class="topbar-item mr-3 position-relative" data-toggle="dropdown" data-offset="10px,0px">
        <span class="btn btn-text btn-danger btn-sm font-weight-bold btn-font-md noty-numb d-none justify-content-center"><div class="align-self-center">1</div></span>
        <div class="btn btn-icon btn-clean h-40px w-40px btn-dropdown pulse pulse-white">
            <span class="svg-icon svg-icon-lg">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg--><svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                    viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path
                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                            fill="#000000" opacity="0.3" />
                        <path
                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                            fill="#000000" />
                    </g>
                </svg>
                <!--end::Svg Icon--></span> <span class="pulse-ring"></span>
        </div>
    </div>
    <!--end::Toggle-->

    <!--begin::Dropdown-->
    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
        
    </div>
    <!--end::Dropdown-->
</div>

<style>
.noty-numb{
    position: absolute;
    top: -13px;
    right: -7px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    color: #fff;
    font-weight: bold;
    
}
</style>


@push('custom_js')
<script>
    $(document).ready(function(){
        $.ajax({
            type: 'POST',
            url : "{{ route('admin.home.getNoty') }}",
            success: function(res){
                if(res.success){
                    if(res.count){
                        $('.noty-numb').removeClass('d-none');
                        $('.noty-numb').addClass('d-flex');
                        $('.noty-numb div').text(res.count);
                    }
                }
            }
        })
    });

    $('.my-noty').on('show.bs.dropdown', function(){
        $.ajax({
            type: 'POST',
            url : "{{ route('admin.home.getNoty') }}",
            success: function(res){
                if(res.success){
                    $('.my-noty .dropdown-menu').html(res.html);
                }
            }
        })
    });

    $(document).on('click', '.noty-item', function(){
        if(!init.conf.ajax_sending){
            let id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url : "{{ route('admin.home.detailNoty') }}",
                data: {id},
                beforeSend: function(){
                    init.conf.ajax_sending = true;
                },
                success: function(res){
                    if(res.success){
                        window.location.href = res.url;
                    }
                },
                complete: function(){
                    init.conf.ajax_sending = false;
                }
            });
        }
    });

    $(document).on('click', '.view-note', function(){
        if(!init.conf.ajax_sending){
            let id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url : "{{ route('admin.home.viewNoty') }}",
                data: {id},
                beforeSend: function(){
                    init.conf.ajax_sending = true;
                },
                success: function(res){
                    if(res.success){
                        window.location.href = res.url;
                    }
                },
                complete: function(){
                    init.conf.ajax_sending = false;
                }
            });
        }
    });
</script>
@endpush