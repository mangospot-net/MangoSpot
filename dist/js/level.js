function Select() {
    $.ajax({
        url: "./api/level",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "type",
        success: function (response) {
            var menu = '';
            $.each(response.data, function (i, val) {
                menu += '<li class="list-group-item pa-10">';
                menu += '<div class="custom-control custom-checkbox">';
                menu += '<input type="checkbox" name="value[]" value="' + val.id + '" class="custom-control-input" id="value_' + val.id + '">';
                menu += '<label class="custom-control-label" for="value_' + val.id + '">' + val.name + '</label>';
                menu += '</div>';
                menu += '</li>';
            });
            $('#list_value').html(menu);
        }
    });
    $.ajax({
        url: "./api/level",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "radius",
        success: function (radius) {
            var nasid = '';
            $.each(radius.data, function (i, nas) {
                nasid += '<li class="list-group-item pa-10">';
                nasid += '<div class="custom-control custom-checkbox">';
                nasid += '<input type="checkbox" name="data[]" value="' + nas.id + '" class="custom-control-input" id="data_' + nas.id + '">';
                nasid += '<label class="custom-control-label" for="data_' + nas.id + '">' + nas.name + '</label>';
                nasid += '</div>';
                nasid += '</li>';
            });
            $('#list_data').html(nasid);
        }
    });
};

function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/level?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "name"
            },
            {
                "data": "value",
            },
            {
                "data": "data"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    var btn = (row.status == 1 || row.status == 'true' ?
                        '<button name="active" data-target="level" class="btn btn-success btn-sm" title="On" value="' + row.id + '"><i class="fa fa-eye"></i></button>' :
                        '<button name="active" data-target="level" class="btn btn-danger btn-sm" title="Off" value="' + row.id + '"><i class="fa fa-eye-slash"></i></button>');
                    return '<div class="btn-group">' + btn + '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                        '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item" data-toggle="modal" href="#add-data" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                        '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="level" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
                        '</div>' +
                        '</div>';
                }
            }
        ],
        oLanguage: {
            sLengthMenu: "_MENU_",
            sSearch: "",
            sSearchPlaceholder: "Search...",
            oPaginate: {
                sPrevious: "<i class='fa fa-backward'></i>",
                sNext: "<i class='fa fa-forward'></i>"
            }
        },
        aLengthMenu: [
            [5, 10, 15, 20, 50, 75, -1],
            [5, 10, 15, 20, 50, 75, "All"]
        ],
        order: [
            [2, 'desc']
        ],
        iDisplayLength: 10
    });
};

function Action() {
    $('body').on('click', 'a[href="#add-data"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $.ajax({
            url: "./api/level",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "detail": id_data
            },
            beforeSend: function () {
                $('#form-data').trigger('reset');
                $('button#id').attr('disabled', true);
                $('input[id^=value_], input[id^=data_]').attr('checked', false);
            },
            success: function (detail) {
                if (detail.status) {
                    $.each(detail.data, function (i, show) {
                        if (jQuery.inArray(i, ['value', 'data']) !== -1) {
                            $.each(detail.data[i], function (e, list) {
                                $('#' + i + '_' + list).attr('checked', true);
                            });
                        } else {
                            $('#' + i).val(show);
                        }
                    });
                    $('#status').bootstrapToggle(detail.data.status ? 'on' : 'off');
                    var checked = $('input[name="value[]"]:checked');
                    $('button#id').attr('disabled', checked.length <= 0 ? true : false);
                }
            }
        });
    });
    $('body').on('click', 'input[type=checkbox][name="value[]"]', function () {
        var check = $('input[name="value[]"]:checked');
        $('button#id').attr('disabled', check.length <= 0 ? true : false);
    });
};

(function () {
    'use strict';
    Select();
    Tables();
    Action();
})();
