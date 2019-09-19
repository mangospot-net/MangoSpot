function Select() {
    $.ajax({
        url: "./api/users",
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
        url: "./api/users",
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
};

function Tables(params) {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "ajax": {
            url: "./api/users?data",
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
                "data": "profiles",
            },
            {
                "data": "shared"
            },
            {
                "data": "rate"
            },
            {
                "data": "expired"
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
                    return '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                        '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item" data-toggle="modal" href="#add-one" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                        '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="users" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
        order: [
            [6, 'desc']
        ],
        aLengthMenu: [
            [5, 10, 15, 20, 50, 75, -1],
            [5, 10, 15, 20, 50, 75, "All"]
        ],
        iDisplayLength: 10
    });
    new $.fn.dataTable.Buttons(Table, {
        buttons: [{
            text: '<i class="fa fa-trash-o"></i>',
            action: function (e, dt, node, config) {
                if ($('input[name="remove[]"]:checked').length > 0) {
                    $('#removed').trigger('submit');
                }
            }
        }]
    });
    Table.buttons(0, null).container().prependTo($('#tables_wrapper .dataTables_length'));
    $('#tables_wrapper .dataTables_length .btn-secondary').addClass("btn-sm");
};

function Themes(data) {
    $.ajax({
        url: "./api/users",
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
var Import = function () {
    $('#FormImport').validate({
        errorElement: "span",
        errorClass: 'help-block',
        ignore: "required",
        rules: {
            file: {
                required: true,
                extension: "xls"
            }
        },
        highlight: function (element) {
            $(element).closest('.help-block').removeClass('valid');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        },
        submitHandler: function (form) {
            var formData = form;
            var formData = new FormData(formData);
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            var persen = Math.round((e.loaded / e.total) * 100);
                            $('#progress').attr('aria-valuenow', persen).css('width', persen + '%').text(persen + '%');
                        }
                    });
                    return xhr;
                },
                url: "./api/users",
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: "POST",
                dataType: "JSON",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $('.bar').show();
                    $('#FormImport button[type=submit]').prop('disabled', true);
                },
                success: function (response) {
                    $('.bar').hide();
                    $('#tables').DataTable().ajax.reload();
                    $.toast({
                        heading: "Import Data",
                        text: response.data,
                        position: 'bottom-right',
                        icon: response.message
                    });
                    $('#FormImport button[type=submit]').prop('disabled', false);
                    $('#add-import').modal('hide');
                    $("#FormImport").trigger('reset');
                }
            });
        }
    });
};

function Remove() {
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('#removed').submit(function (e) {
        e.preventDefault();
        var forms = $(this).serialize();
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
                $.ajax({
                    url: "./api/users",
                    headers: {
                        "Api": $.cookie("BSK_API"),
                        "Key": $.cookie("BSK_KEY"),
                        "Accept": "application/json"
                    },
                    method: "POST",
                    dataType: "JSON",
                    data: forms,
                    success: function (remove) {
                        $('#CheckAll').prop('checked', false);
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
};

function Prints(data, type, theme) {
    $.ajax({
        url: "./api/users",
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
            var numbs = 0;
            $.each(prints.data, function (e, params) {
                theme += prints.themes.content;
            });
            $('#print-content').html(theme);
            $.each(prints.data, function (i, param) {
                numbs++;
                $('#print-content').html(function (index, html) {
                    return html
                        .replace('[NO]', numbs)
                        .replace('[identity]', param.identity)
                        .replace('[profile]', param.profile ? param.profile : '')
                        .replace('[username]', param.username)
                        .replace('[password]', param.password)
                        .replace('[price]', param.price)
                        .replace('[QR-Code]', '<div class="qr-' + param.username + '" data-text="https://wifi.mangospot.net/login?username=' + param.username + '&password=' + param.password + '"></div>');
                }).find('.qr-' + param.username).qrcode({
                    render: "image",
                    size: 75,
                    text: $('.qr-' + param.username).data('text')
                });
            });
        }
    });
}

function Action(params) {
    $('body').on('click', 'a[href="#add-batch"]', function () {
        $('#create').val(moment().format('YYYY-MM-DD HH:mm:ss'));
    });
    $('body').on('click', 'a[href="#add-import"]', function () {
        $('#timer').val(moment().format('YYYY-MM-DD HH:mm:ss'));
    });
    $('body').on('click', 'a[href="#add-one"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $('#form-one').trigger('reset');
        $('#passwd').attr('type', 'password');
        $.ajax({
            url: "./api/users",
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
        $('#tables').DataTable().ajax.url("./api/users?data=" + $(this).val()).load();
    });
    $('#quota').keyup(function () {
        $('#valume').val($(this).val());
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
    Tables();
    Remove();
    Action();
    Import();
})();