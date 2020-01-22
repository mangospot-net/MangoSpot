function Select() {
    $.ajax({
        url: "./api/transaction",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "client",
        success: function (response) {
            $.each(response.data, function (i, val) {
                $('#client').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        }
    });
    $('#client').change(function () {
        Options($(this).val(), '');
    });
    $('#packet').change(function () {
        $('#price, #values').val($(this).find(':selected').data('price'));
    });
    $('#total').change(function () {
        var price = $('#values').val();
        $('#price').val(price * $(this).val());
    });
};

function Options(data, value) {
    $.ajax({
        url: "./api/transaction",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "packet": data
        },
        beforeSend: function () {
            $('#total').val(1);
            $('#packet').empty();
        },
        success: function (response) {
            $('#packet').append('<option value="">-- Select packet --</option>').val(value);
            $.each(response.data, function (i, val) {
                $('#packet').append('<option value="' + val.id + '" data-price="' + val.value + '">' + val.name + '</option>').val(value);
            });
        }
    });
}

function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/transaction?data",
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
                "data": "groupname",
            },
            {
                "data": "total"
            },
            {
                "data": "price"
            },
            {
                "data": "info"
            },
            {
                "data": "date"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    var btn = (row.status == 1 || row.status == 'true' ?
                        '<button name="active" data-target="transaction" class="btn btn-success btn-sm" title="Approve" value="' + row.id + '"><i class="fa fa-check"></i></button>' :
                        '<button name="active" data-target="transaction" class="btn btn-warning btn-sm" title="Pending" value="' + row.id + '"><i class="fa fa-question-circle"></i></button>');
                    return '<div class="btn-group">' + btn + '<button data-toggle="dropdown" class="btn btn-primary btn-sm"><i class="fa fa-cog"></i></button>' +
                        '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item" data-toggle="modal" href="#add-data" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                        '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="transaction" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
            [6, 'desc']
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
            url: "./api/transaction",
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
                    Options(detail.data.client, detail.data.packet);
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
                    $('#values').val(detail.data.price / detail.data.total);
                }
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