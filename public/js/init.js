$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
// show alert after redirect
    var success_message = $('.success_message').val();
    var error_message = $('.error_message').val();
    if (success_message) {
        setTimeout(function () {
            init.showNoty(success_message, 'success');
        }, 1000);
    }
    if (error_message) {
        setTimeout(function () {
            init.showNoty(error_message, 'error');
        }, 1000);
    }

});

var init = {
    conf: {
        ajax_sending: false
    },
    showNoty: function (text, type) {
        swal.fire({
            text: text,
            icon: type,
            showConfirmButton: false,
            timer: 2000
        })
    },
    formatNumber: function(n, decPlaces, thouSeparator, decSeparator) {
        var
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 0 : decPlaces,
            decSeparator = decSeparator == undefined ? "." : decSeparator,
            thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
            sign = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
    },
    makeSlug: function(title){
        var slug;
 
        //Đổi chữ hoa thành chữ thường
        slug = title.toLowerCase();
    
        //Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        //Xóa các ký tự đặt biệt
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        //Đổi khoảng trắng thành ký tự gạch ngang
        slug = slug.replace(/ /gi, "-");
        //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
        //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        //Xóa các ký tự gạch ngang ở đầu và cuối
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        return slug;
    },
    is_phone: function (num) {
	    return (/^(0)(\d{9})$/i).test(num);
	},
	is_email: function (str) {
	    return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/).test(this.util_trim(str));
    },
    util_trim: function (str) {
	    return (/string/).test(typeof str) ? str.replace(/^\s+|\s+$/g, "") : "";
    },
    uploadImage: function(file, callback){
        var file_data = file[0];
        var type = file_data.type;
        var match = ["image/png", "image/jpg", "image/jpeg"];
        if (type == match[0] || type == match[1] || type == match[2] || type == match[3]) {
            var form_data = new FormData();
                //thêm files vào trong form data
                form_data.append('file', file_data);
                //sử dụng ajax post
                $.ajax({
                    url: BASE_URL + '/editor/uploadImage', // gửi đến file upload.php 
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (res) {
                        if (res.success == 1) {
                            callback(res.url);
                        } else {
                            init.showNoty(res.mess, 'error');
                        }
                    }
                });
        } else {
            init.notyPopup('Sai định dạng!', 'error');
                return false;
        }
    },
    
};