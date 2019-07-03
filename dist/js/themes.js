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
};

function Action() {
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
                    $('button.removed').attr('disabled', false).attr('data-value', edit.data.id);
                } else {
                    $('#name, #content').empty().val('');
                    $('a[href="#delete"]').attr('disabled', true);
                    $('button.removed').attr('disabled', true).attr('data-value', 0);
                }
            }
        });
    });
};

(function () {
    'use strict';
    Select();
    Action();
})();