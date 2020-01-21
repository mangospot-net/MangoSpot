function Change() {
    $('#smtp').change(function () {
        $('#port').val($('#smtp').find('option:selected').data('value'));
    });
    $('#imap').change(function () {
        $('#ports').val($('#imap').find('option:selected').data('value'));
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
    $.ajax({
        url: "./api/config",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "service",
        success: function (service) {
            $.each(service.data, function (i, val) {
                $('#command').append('<option value="' + val + '">' + ucword(val) + '</option>');
            });
        }
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
        data: "docs",
        success: function (service) {
            var docs = '';
            $.each(service.data, function (i, doc) {
                docs += '<tr>';
                docs += '<td>' + doc.name + '</td>';
                docs += '<td>' + doc.info + '</td>';
                docs += '</tr>';
            });
            $('#docs-list').html(docs);
        }
    });
}

function Submit() {
    var editor = CodeMirror.fromTextArea(document.getElementById("terminal"), {
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true,
        readOnly: 'nocursor',
        theme: 'blackboard',
        mode: 'shell'
    });
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
                    editor.setValue('Loading...');
                    $('button[type=submit]', '#formCMD').attr('disabled', true);
                },
                success: function (response) {
                    editor.setValue(response);
                    $('button[type=submit]', '#formCMD').attr('disabled', false);
                }
            });
        }
    });
    $('button#reset').click(function () {
        editor.setValue('');
        $('form.active').trigger('reset');
    });
}

function Action() {
    $('button#save').click(function () {
        $('form.active').submit();
    });
    $('button#check').click(function () {
        $.ajax({
            url: "./api/config",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST",
            data: {
                'check': true,
                'email': $('#email').val(),
                'pswd': $('#pswd').val(),
                'host': $('#host').val(),
                'port': $('#port').val()
            },
            beforeSend: function () {
                $('button#check').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (response) {
                $('#check_info').html(response).fadeIn('slow').removeClass('d-none');
                setInterval(function () {
                    $('#check_info').empty().fadeOut('slow').addClass('d-none');
                }, 5000);
                $('button#check').attr('disabled', false).html('Test');
            }
        });
    });
}

function Editor() {
    var delay;
    var themes = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
        lineNumbers: true,
        theme: 'blackboard',
        mode: 'text/html'
    });
    $('select#type').change(function () {
        $.ajax({
            url: "./api/config",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "theme": $(this).val()
            },
            beforeSend: function () {
                themes.setValue('');
                $('#content').val('');
            },
            success: function (edit) {
                themes.setValue(edit.status ? edit.data.content : '');
                $('#content').val(edit.status ? edit.data.content : '');
            }
        });
    });
    themes.on('change', themes => {
        clearTimeout(delay);
        $('#content').val(themes.getValue());
        delay = setTimeout(updatePreview, 300);
    });

    function updatePreview() {
        var previewFrame = document.getElementById('preview');
        var preview = previewFrame.contentDocument || previewFrame.contentWindow.document;
        preview.open();
        preview.write(themes.getValue());
        preview.close();
        $('#preview').css('height', previewFrame.contentWindow.document.documentElement.scrollHeight + 'px');
    }
    setTimeout(updatePreview, 300);
}
(function () {
    'use strict';
    Change();
    Submit();
    Action();
    Editor();
})();