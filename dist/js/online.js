function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "ajax": {
            url: "./api/online?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "server"
            },
            {
                "data": "users",
            },
            {
                "data": "type"
            },
            {
                "data": "address"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    return '<button class="btn btn-danger btn-sm off-user" data-id="' + row.identity + '" data-type="' + row.type + '" value="' + row.id + '"><i class="fa fa-power-off"></i></button>';
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
        url: "./api/online",
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

function Delete() {
    $('body').on('click', 'button.off-user', function () {
        var id = $(this).val();
        var data = $(this).data('id');
        var type = $(this).data('type');
        swal({
            title: "Are you sure!",
            text: "Disconnect this user?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, disconnect it!",
            cancelButtonText: "Cancel",
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "./api/online",
                    headers: {
                        "Api": $.cookie("BSK_API"),
                        "Key": $.cookie("BSK_KEY"),
                        "Accept": "application/json"
                    },
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "type": type,
                        "delete": data
                    },
                    success: function (remove) {
                        $('.dataTable').DataTable().ajax.reload();
                        swal({
                            title: "Delete!",
                            text: remove.data,
                            timer: 2000,
                            type: 'success'
                        });
                    }
                });
            }
        });
    });
}
(function () {
    'use strict';
    Tables();
    Action();
    Delete();
    $('#server').change(function () {
        $('#tables').DataTable().ajax.url("./api/online?data=" + $(this).val() + "&type=" + $('#service').val()).load();
    });
    $('#service').change(function () {
        $('#tables').DataTable().ajax.url("./api/online?data=" + $('#server').val() + "&type=" + $(this).val()).load();
    });
    setInterval(function () {
        $('#tables').DataTable().ajax.url("./api/online?data=" + $('#server').find('option:selected').val() + "&type=" + $('#service').find('option:selected').val()).load();
    }, 10000);
})();