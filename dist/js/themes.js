function Select() {
    $.ajax({
        url: "./api/themes",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "type",
        beforeSend: function () {
            $('#id').empty().append('<option value="0">-- New Themes --</option>');
        },
        success: function (response) {
            $.each(response.data, function (i, val) {
                $('#id').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        }
    });
    $.ajax({
        url: "./api/themes",
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
};

function Action() {
    var delay;
    var editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
        lineNumbers: true,
        theme: 'blackboard',
        mode: 'text/html'
    });
    $('select#id').change(function () {
        $.ajax({
            url: "./api/themes",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "detail": $(this).val()
            },
            success: function (edit) {
                if (edit.status) {
                    $.each(edit.data, function (i, val) {
                        $('#' + i).val(val);
                    });
                    editor.setValue(edit.data.content);
                    $('button.removed').attr('disabled', false).attr('data-value', edit.data.id);
                } else {
                    editor.setValue('');
                    $('#name, #content').empty().val('');
                    $('a[href="#delete"]').attr('disabled', true);
                    $('button.removed').attr('disabled', true).attr('data-value', 0);
                }
            }
        });
    });
    editor.on('change', editor => {
        clearTimeout(delay);
        $('#content').val(editor.getValue());
        delay = setTimeout(updatePreview, 300);
    });

    function updatePreview() {
        var previewFrame = document.getElementById('preview');
        var preview = previewFrame.contentDocument || previewFrame.contentWindow.document;
        preview.open();
        preview.write(editor.getValue());
        preview.close();
    }
    setTimeout(updatePreview, 300);
};
(function () {
    'use strict';
    Select();
    Action();
})();