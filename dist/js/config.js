function Change() {
    $('#smtp').change(function () {
        if ($(this).val() == 'ssl') {
            $('#port').val('465');
        } else if ($(this).val() == 'tls') {
            $('#port').val('587');
        } else {
            $('#port').val('');
        }
    });
    $('#show_paswd').click(function () {
        $('#pswd').attr('type', $(this).is(":checked") ? 'text' : 'password');
    });
    $.ajax({
        url: "./api/config",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "data",
        success: function (config) {
            $.each(config.data, function (i, val) {
                $('#' + i).val(val);
            });
        }
    });
}

function Submit() {
    $('#formCMD').validate({
        errorElement: "span",
        errorClass: 'help-block',
        ignore: "required",
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
                url: "./api/config",
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: "POST",
                data: $(form).serialize(),
                beforeSend: function () {
                    $('#terminal').empty();
                    $('button[type=submit]', '#formCMD').attr('disabled', true);
                },
                success: function (response) {
                    $('#terminal').html(response);
                    $('button[type=submit]', '#formCMD').attr('disabled', false);
                }
            });
        }
    });
}

function Action() {
    $('button#save').click(function () {
        $('form.active').submit();
    });
    $('button#reset').click(function () {
        $('form.active').trigger('reset');
    });
}
(function () {
    'use strict';
    Change();
    Submit();
    Action();
})();