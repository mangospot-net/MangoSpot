function Images(data) {
    $('.dropify').dropify({
        messages: {
            replace: 'Drag and drop or click to update picture <br/> 200x200px'
        },
        defaultFile: (data ? data : './dist/img/users/no_foto.jpg'),
        tpl: {
            filename: ''
        }
    });
}

function Pasword() {
    $('#FormPswd').validate({
        errorElement: "span",
        errorClass: 'help-block',
        ignore: "required",
        rules: {
            rpassword: {
                required: true,
                minlength: 5,
                equalTo: "#new"
            }
        },
        highlight: function (element) {
            $(element).closest('.help-block').removeClass('valid');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        },
        submitHandler: function (form) {
            $.ajax({
                url: "./api/profile",
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: "POST",
                dataType: "JSON",
                data: $(form).serialize(),
                beforeSend: function () {
                    $('button[type=submit]', '#FormPswd').attr('disabled', true);
                },
                success: function (response) {
                    $('#FormPswd').trigger('reset');
                    $('button[type=submit]', '#FormPswd').attr('disabled', false);
                    if (response.status) {
                        $.cookie("BSK_API", null);
                        $.cookie("BSK_KEY", null);
                        $.cookie("BSK_TOKEN", null);
                        $.removeCookie("BSK_API");
                        $.removeCookie("BSK_KEY");
                        $.removeCookie("BSK_TOKEN");
                        setTimeout(function () {
                            window.location = './'
                        }, 2000);
                    }
                    $.toast({
                        heading: "Password",
                        text: response.data,
                        position: 'bottom-right',
                        icon: response.message
                    });
                }
            });
        }
    });
}

function Uploads() {
    $('form#Upload').validate({
        ignore: "required",
        submitHandler: function (form) {
            var formData = form;
            var formData = new FormData(formData);
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            var persen = Math.round((e.loaded / e.total) * 100);
                            $('#progress').attr('aria-valuenow', persen).css('width', persen + '%').text(persen + '%');
                        }
                    });
                    return xhr;
                },
                url: "./api/profile",
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: "POST",
                dataType: "JSON",
                data: formData,
                mimeType: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $('form#Upload').trigger("reset");
                    $('#progress').attr('aria-valuenow', 0).css('width', '0%').text('');
                    $.toast({
                        heading: "Upload!",
                        text: response.data,
                        position: 'bottom-right',
                        icon: response.message
                    });
                }
            });
        }
    });
    $("input[name=images]").change(function () {
        $(this).closest("form").submit();
    });
}

function Profile() {
    $.ajax({
        url: "./api/profile",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        data: "data",
        dataType: "JSON",
        success: function (account) {
            if (account.status) {
                $.each(account.data, function (i, user) {
                    $('#' + i).val(user);
                });
                Images(account.data.image);
            }
        }
    });
}
(function () {
    'use strict';
    Pasword();
    Uploads();
    Profile();
})();