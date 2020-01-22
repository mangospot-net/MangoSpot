function Select() {
    $('select').each(function (index, element) {
        $.ajax({
            url: "./api/packet",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: $(element).attr('name'),
            success: function (response) {
                $.each(response.data, function (i, val) {
                    $(element).append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            }
        });
    });
};

function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/packet?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "client"
            },
            {
                "data": "groupname",
            },
            {
                "data": "price"
            },
            {
                "data": "total"
            }, {
                "data": "voucher"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    var btn = (row.status == 1 || row.status == 'true' ?
                        '<button name="active" data-target="packet" class="btn btn-success btn-sm" title="On" value="' + row.id + '"><i class="fa fa-eye"></i></button>' :
                        '<button name="active" data-target="packet" class="btn btn-danger btn-sm" title="Off" value="' + row.id + '"><i class="fa fa-eye-slash"></i></button>');
                    var dfl = (row.defaults == 1 || row.defaults == 'true' ?
                        '<button class="btn btn-info btn-sm" title="Default"><i class="fa fa-bell"></i></button>' :
                        '<button name="default" class="btn btn-warning btn-sm" title="No" value="' + row.id + '"><i class="fa fa-bell-slash"></i></button>'
                    );
                    return '<div class="btn-group">' + dfl + btn + '<button data-toggle="dropdown" class="btn btn-primary btn-sm"><i class="fa fa-cog"></i></button>' +
                        '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item" data-toggle="modal" href="#add-data" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                        '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="packet" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
            [5, 'desc']
        ],
        iDisplayLength: 10
    });
};

function Action() {
    $('body').on('click', 'a[href="#add-data"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $('#form-data').trigger('reset');
        $.ajax({
            url: "./api/packet",
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
                if (detail.status) {
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
                    $('#status').bootstrapToggle(detail.data.status ? 'on' : 'off');
                }
            }
        });
    });
    $('body').on('click', 'button[name="default"]', function () {
        $.ajax({
            url: "./api/packet",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST",
            dataType: "JSON",
            data: {
                "default": $(this).val()
            },
            success: function (active) {
                $('#tables').DataTable().ajax.reload();
            }
        });
    });
};

(function () {
    'use strict';
    Select();
    Tables();
    Action();
})();