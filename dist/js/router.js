function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/router?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "description"
            },
            {
                "data": "nasname",
            },
            {
                "data": "username"
            },
            {
                "data": "port"
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    return '<div class="btn-group">' +
                        '<button class="btn btn-warning btn-sm reboot" value="' + row.id + '" title="Reboot"><i class="fa fa-power-off"></i></button>' +
                        '<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#add-data" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i></button>' +
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
            [4, 'desc']
        ],
        iDisplayLength: 10
    });
};

function Action() {
    $('body').on('click', 'button[data-target="#add-data"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $('#form-data').trigger('reset');
        $('#password').attr('type', 'password');
        $('.badge').removeClass('badge-success').removeClass('badge-secondary');
        $.ajax({
            url: "./api/router",
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
                }
            }
        });
    });
    $('#show_paswd').click(function () {
        $('#password').attr('type', $(this).is(":checked") ? 'text' : 'password');
    });
    $('#test_router').click(function () {
        $.ajax({
            url: "./api/router",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST",
            dataType: "JSON",
            data: {
                "test": $('#id').val(),
                "port": $('#port').val(),
                "user": $('#username').val(),
                "pswd": $('#password').val()
            },
            beforeSend: function () {
                $('.badge').removeClass('badge-success').removeClass('badge-secondary').html('<img src="dist/img/load.gif">');
            },
            success: function (testing) {
                $('.badge').html('').addClass(testing.data ? 'badge-success' : 'badge-secondary');
            }
        });
    });
    $('body').on('click', 'button.reboot', function () {
        var id_reboot = $(this).val();
        swal({
            title: "Are you sure!",
            text: "Reboot this router?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, reboot it!",
            cancelButtonText: "Cancel",
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "./api/router",
                    headers: {
                        "Api": $.cookie("BSK_API"),
                        "Key": $.cookie("BSK_KEY"),
                        "Accept": "application/json"
                    },
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        'reboot': id_reboot
                    },
                    success: function (reboot) {
                        swal({
                            title: "Reboot!",
                            text: reboot.data,
                            timer: 2000,
                            type: reboot.message
                        });
                    }
                })
            }
        });
    });
};

(function () {
    'use strict';
    Tables();
    Action();
})();