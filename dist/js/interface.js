function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "ajax": {
            url: "./api/interface?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "router"
            },
            {
                "data": "name",
            },
            {
                "data": "type"
            },
            {
                "data": "mac"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    var btn = (row.status == 'false' ?
                        '<button name="active" data-target="interface" class="btn btn-success btn-sm" title="On" value="' + row.identity + row.id + '"><i class="fa fa-eye"></i></button>' :
                        '<button name="active" data-target="interface" class="btn btn-danger btn-sm" title="Off" value="' + row.identity + row.id + '"><i class="fa fa-eye-slash"></i></button>');
                    return '<div class="btn-group">' + btn +
                        '<button class="btn btn-info btn-sm" data-toggle="modal" href="#add-data" data-value="' + row.identity + row.id + '" title="Edit"><i class="fa fa-edit"></i></button>' +
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
        iDisplayLength: 10
    });
};

function Action() {
    $.ajax({
        url: "./api/interface",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "server",
        success: function (response) {
            $.each(response.data, function (i, param) {
                $('#server').append('<option value="' + param.id + '">' + param.name + '</option>');
            });
        }
    });
}

function Change() {
    $('body').on('click', '[href="#add-data"]', function () {
        $('#form-data').trigger('reset');
        $.ajax({
            url: "./api/interface",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "detail": $(this).data('value')
            },
            success: function (detail) {
                if (detail.status) {
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
                    $('#status').bootstrapToggle(detail.data.status ? 'on' : 'off');
                }
            }
        });
    });
}
(function () {
    'use strict';
    Tables();
    Action();
    Change();
    $('#server').change(function () {
        $('#tables').DataTable().ajax.url("./api/interface?data=" + $(this).val()).load();
    });
})();