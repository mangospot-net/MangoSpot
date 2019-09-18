function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "ajax": {
            url: "./api/pool?data",
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
                "data": "address"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    return '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                        '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item" data-toggle="modal" href="#add-data" data-value="' + row.identity + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                        '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.identity + row.id + '" data-target="pool" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
        url: "./api/pool",
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
                $('#server, #router').append('<option value="' + param.id + '">' + param.name + '</option>');
            });
        }
    });
}

function Change() {
    $('body').on('click', '[href="#add-data"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $('#form-data').trigger('reset');
        $('#router').attr('disabled', false);
        $.ajax({
            url: "./api/pool",
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
            success: function (detail) {
                $('#router').attr('disabled', detail.status);
                if (detail.status) {
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
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
        $('#tables').DataTable().ajax.url("./api/pool?data=" + $(this).val()).load();
    });
})();