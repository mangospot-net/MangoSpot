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
    $.ajax({
        url: "./api/users",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "level",
        success: function (level) {
            $.each(level.data, function (o, lev) {
                $('select#reseller').append('<option value="' + lev.id + '">' + lev.name + '</option>');
            });
        }
    });
};

function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
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
            "className": 'text-center',
            render: function (data, type, row) {
                return '<input type="checkbox" name="remove[]" value="' + row.id + '">';
            }
        }, {
            "data": "username",
            render: function (data, type, row) {
                return (row.profiles ? row.username : '<a href="javascript:void(0)" class="text-info details-control">' + row.username + '</a>');
            }
        }, {
            "data": "profiles",
        }, {
            "data": "created",
            render: function (data, type, row) {
                return row.created ? '<a href="#print" class="text-warning" data-type="batch" data-toggle="modal" data-value="' + row.created + '">' + row.created + '</a>' : '';
            }
        }, {
            "data": "description",
        }, {
            "data": "id",
            className: 'dt-body-right',
            render: function (data, type, row) {
                var btnPrint = '<a href="#print" class="btn btn-sm btn-warning" data-type="one" data-toggle="modal" data-value="' + row.username + '"><i class="fa fa-print"></i></a>';
                return '<div class="btn-group">' + btnPrint + '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                    '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                    '<a class="dropdown-item" data-toggle="modal" href="#add-one" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                    '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="users" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
                    '</div></div>';
            }
        }],
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
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', aData.id);
        },
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
    $('#tables tbody').on('click', 'td a.details-control', function () {
        var tr = $(this).closest('tr');
        var row = Table.row(tr);
        var text = $(this).html();
        var html = $(this);
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
                "detail": tr.attr('id')
            },
            beforeSend: function () {
                html.html('<img src="dist/img/load.gif" />');
            },
            success: function (result) {
                html.html(text);
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    if (Table.row('.shown').length) {
                        $('a.details-control', Table.row('.shown').node()).click();
                    }
                    row.child(format(result.data, tr.attr('id'))).show();
                    tr.addClass('shown');
                }
            }
        });
    });
    Table.buttons(0, null).container().prependTo($('#tables_wrapper .dataTables_length'));
    $('#tables_wrapper .dataTables_length .btn-secondary').addClass("btn-sm");
    $('#tables_wrapper .dataTables_length button.btn-danger').removeClass("btn-secondary").attr('disabled', true);
};

function format(data) {
    var showRow = '<div class="row">';
    showRow += '<div class="col-md-6"><table class="table table-striped" width="100%">';
    showRow += '<tr><td width="50%">Shared User</td><td width="50%">: ' + (data.shared ? data.shared : '') + (data.ppp ? data.ppp : '') + '</td></tr>';
    showRow += '<tr><td>Rate Limit</td><td>: ' + (data.rate ? data.rate : '') + '</td></tr>';
    showRow += '<tr><td>Quota Limit</td><td>: ' + (data.quota ? data.quota : '') + '</td></tr>';
    showRow += '</table></div>';
    showRow += '<div class="col-md-6"><table class="table table-striped" width="100%">';
    showRow += '<tr><td width="50%">Access Period</td><td width="50%">: ' + (data.period ? data.period : '') + '</td></tr>';
    showRow += '<tr><td>Access Many Time</td><td>: ' + (data.times ? data.times : '') + '</td></tr>';
    showRow += '<tr><td>Access Per Day</td><td>: ' + (data.daily ? data.daily : '') + '</td></tr>';
    showRow += '</table></div>';
    showRow += '</div>';
    return showRow;
}

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
                    url: "./api/users",
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

function Durations(data) {
    $('#period').timeDurationPicker({
        setVals: parseInt(data.period ? data.period : 0),
        onSelect: function (element, seconds, duration, text) {
            $('#period').val(seconds);
        }
    });
    $('#times').timeDurationPicker({
        setVals: parseInt(data.times ? data.times : 0),
        onSelect: function (element, seconds, duration, text) {
            $('#times').val(seconds);
        }
    });
    $('#daily').timeDurationPicker({
        setVals: parseInt(data.daily ? data.daily : 0),
        onSelect: function (element, seconds, duration, text) {
            $('#daily').val(seconds);
        }
    });
}

function Action(params) {
    $('body').on('click', 'a[href="#add-batch"]', function () {
        $('#create').val(moment().format('YYYY-MM-DD HH:mm:ss'));
    });
    $('body').on('click', 'a[href="#add-users"]', function () {
        $('#timer').val(moment().format('YYYY-MM-DD HH:mm:ss'));
    });
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('body').on('click', 'input[type=checkbox]', function () {
        var check = $('input[name="remove[]"]:checked');
        $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', check.length <= 0 ? true : false);
    });
    $('body').on('click', 'a[href="#add-one"]', function () {
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
                "detail": $(this).data('value')
            },
            beforeSend: function () {
                $('.manual').show();
                $('#id, #ppp').val('');
                $('#form-one').trigger('reset');
                $('#passwd').attr('type', 'password');
                Durations(['period', 'times', 'daily']);
            },
            success: function (detail) {
                Durations(detail.data);
                if (detail.status) {
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
                    if (detail.data.profiles) {
                        $('.manual').hide();
                    } else {
                        $('.manual').show();
                    }
                    $('#checkPPP').prop('checked', detail.data.ppp ? true : false);
                }
            }
        });
    });
    $('body').on('click', 'a[href="#print"]', function () {
        $('input#data').val($(this).data('value'));
        $('input#type').val($(this).data('type'));
        Prints($(this).data('value'), $(this).data('type'));
        $('select#theme').val($("select#theme option:first").val());
    });
    $('#quota_numb').change(function () {
        if ($(this).val() == 0 || $(this).val() == null) {
            $('input#volume, input#quota').empty().val('');
        } else {
            $('input#volume, input#quota').val($(this).val() + $('#quota_code').val());
        }
    });
    $('#quota_code').change(function () {
        if ($('#quota_numb').val() == 0 || $('#quota_numb').val() == null) {
            $('input#volume, input#quota').empty().val('');
        } else {
            $('input#volume, input#quota').val($('#quota_numb').val() + $(this).val());
        }
    });
    $('select#theme').change(function () {
        Prints($('input#data').val(), $('input#type').val(), $(this).val());
    });
    $('.UpperCase').bind('input', function () {
        $(this).val(function () {
            return this.value.toUpperCase();
        })
    });
    $('#show_paswd').click(function () {
        $('#passwd').attr('type', $(this).is(":checked") ? 'text' : 'password');
    });
    $('#profile').change(function () {
        $('#tables').DataTable().ajax.url("./api/users?data=" + $(this).val() + "&users=" + $('#reseller').val()).load();
    });
    $('#reseller').change(function () {
        $('#tables').DataTable().ajax.url("./api/users?data=" + $('#profile').val() + "&users=" + $(this).val()).load();
    });
    $('#profiles').change(function () {
        if ($(this).val()) {
            $('.manual').hide();
        } else {
            $('.manual').show();
        }
    });
    $('#quota').keyup(function () {
        $('input#valume').val($(this).val());
    });
    $('#checkPPP').click(function () {
        $('#ppp').val($(this).is(":checked") ? 'PPP' : '');
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
    Action();
})();