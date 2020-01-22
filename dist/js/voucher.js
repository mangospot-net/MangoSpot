function Select() {
    $.ajax({
        url: "./api/voucher",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "profiles",
        success: function (response) {
            $.each(response.data, function (i, params) {
                $('select.profiles').append('<option value="' + params.groupname + '">' + params.groupname + '</option>');
            });
        }
    });
    $.ajax({
        url: "./api/voucher",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "theme",
        success: function (themes) {
            $.each(themes.data, function (e, theme) {
                $('select#theme').append('<option value="' + theme.id + '">' + theme.name + '</option>');
            });
        }
    });
    $('select.profiles').change(function () {
        Packet($(this).val());
    });
};

function Packet(data) {
    $.ajax({
        url: "./api/voucher",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "packet": data ? data : ''
        },
        success: function (packet) {
            $('input[name="qty"]').attr('max', packet.status ? packet.data.voucher : 0);
        }
    });
}

function Tables(params) {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/voucher?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "id",
                "orderable": false,
                "className": 'text-center',
                render: function (data, type, row) {
                    return '<input type="checkbox" name="remove[]" value="' + row.id + '">';
                }
            }, {
                "data": "username",
                render: function (data, type, row) {
                    return '<a href="#print" class="text-info" data-type="one" data-toggle="modal">' + row.username + '</a>';
                }
            },
            {
                "data": "profile"
            },
            {
                "data": "price"
            },
            {
                "data": "created",
                render: function (data, type, row) {
                    return row.created ? '<a href="#print" class="text-warning" data-type="batch" data-toggle="modal">' + row.created + '</a>' : '';
                }
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    return '<a class="btn btn-danger btn-sm" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="voucher" title="Delete"><i class="fa fa-trash"></i></a>';
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
        order: [
            [5, 'desc']
        ],
        aLengthMenu: [
            [5, 10, 15, 20, 50, 75, -1],
            [5, 10, 15, 20, 50, 75, "All"]
        ],
        iDisplayLength: 10
    });
    new $.fn.dataTable.Buttons(Table, {
        buttons: [{
            text: '<i class="fa fa-trash"></i>',
            titleAttr: 'Delete',
            className: 'btn-danger btn-sm',
            action: function (e, dt, node, config) {
                Remove();
            }
        }]
    });
    Table.buttons(0, null).container().prependTo($('#tables_wrapper .dataTables_length'));
    $('#tables_wrapper .dataTables_length .btn-secondary').addClass("btn-sm");
    $('#tables_wrapper .dataTables_length button.btn-danger').removeClass("btn-secondary").attr('disabled', true);
};

function Themes(data) {
    $.ajax({
        url: "./api/voucher",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "themes": 1,
        },
        success: function (themes) {
            return themes.data.content
        }
    });
}

function Remove() {
    var totsl = $('input[name="remove[]"]:checked').length;
    swal({
        title: "Are you sure!",
        text: "Delete permanent this (" + totsl + ") data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            $('input[name="remove[]"]:checked').each(function () {
                $.ajax({
                    headers: {
                        "Api": $.cookie("BSK_API"),
                        "Key": $.cookie("BSK_KEY"),
                        "Accept": "application/json"
                    },
                    url: "./api/voucher",
                    method: "POST",
                    data: {
                        'delete': $(this).val()
                    }
                });
            });
            swal({
                title: "Delete!",
                text: "Delete data success",
                timer: 2000,
                type: 'success'
            });
            $('#CheckAll').prop('checked', false);
            $('.dataTable').DataTable().ajax.reload();
            $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', true);
        }
    });
};

function Prints(data, type, theme) {
    $.ajax({
        url: "./api/voucher",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "print": data,
            "type": type,
            "themes": theme
        },
        beforeSend: function () {
            $('#print-content').empty().html('<div class="text-center"><img src="./dist/img/loader.gif"></div>');
        },
        success: function (prints) {
            var theme = '';
            $.each(prints.print, function (e, params) {
                theme += params;
            });
            $('#print-content').html(theme).find('.qr-code').each(function (i, val) {
                $(this).qrcode({
                    render: "image",
                    size: 75,
                    text: $(this).data('code')
                });
            });
        }
    });
}

function Action(params) {
    $('body').on('click', 'a[href="#add-batch"]', function () {
        $('#create').val(moment().format('YYYY-MM-DD HH:mm:ss'));
    });
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('body').on('click', 'input[type=checkbox]', function () {
        var check = $('input[name="remove[]"]:checked');
        $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', check.length <= 0 ? true : false);
    });
    $('body').on('click', 'a[href="#print"]', function () {
        $('input#data').val($(this).html());
        $('input#type').val($(this).data('type'));
        Prints($(this).html(), $(this).data('type'));
        $('select#theme').val($("select#theme option:first").val());
    });
    $('select#theme').change(function () {
        Prints($('input#data').val(), $('input#type').val(), $(this).val());
    });
    $('.noSpaces').bind('input', function () {
        $(this).val(function (_, v) {
            return v.replace(/\s+/g, '');
        });
    });
    $('#show_paswd').click(function () {
        $('#passwd').attr('type', $(this).is(":checked") ? 'text' : 'password');
    });
    $('#profile').change(function () {
        $('#tables').DataTable().ajax.url("./api/voucher?data=" + $(this).val()).load();
    });
    $('body').on('click', '.print', function () {
        var area = $(this).val();
        var divToPrint = document.getElementById(area);
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><style>table{border-collapse: collapse; font-size: x-small;}.table td, .table th{border: 1px solid black;padding: 0 5px;}.text-uppercase{text-transform: uppercase;}</style><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
        setTimeout(function () {
            newWin.close();
        }, 10);
    });
};
(function () {
    'use strict';
    Select();
    Packet();
    Tables();
    Action();
})();