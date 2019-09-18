function Tables() {
    $('table[id^=tables-]').each(function (index, element) {
        var id = $(this).attr('id').replace('tables-', '');
        var Table = $('#tables-' + id).DataTable({
            "responsive": true,
            "ajax": {
                url: "./api/dhcp?type=" + id + "&data",
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
                    "data": "interface",
                },
                {
                    "data": "name"
                },
                {
                    "data": "lease"
                },
                {
                    "data": "pool"
                },
                {
                    "data": "id",
                    className: 'dt-body-right',
                    render: function (data, type, row) {
                        var btn = (row.status == 'false' ?
                            '<button name="active" data-target="dhcp" class="btn btn-success btn-sm" title="On" value="' + row.identity + row.id + '*' + id + '"><i class="fa fa-eye"></i></button>' :
                            '<button name="active" data-target="dhcp" class="btn btn-danger btn-sm" title="Off" value="' + row.identity + row.id + '*' + id + '"><i class="fa fa-eye-slash"></i></button>');
                        return '<div class="btn-group">' + btn + '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                            '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                            '<a class="dropdown-item" data-toggle="modal" href="#add-' + id + '" data-value="' + row.identity + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                            '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.identity + row.id + '*' + id + '" data-target="dhcp" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
            iDisplayLength: 10
        });
        new $.fn.dataTable.Buttons(Table, {
            buttons: [{
                text: '<i class="fa fa-plus"></i> Add',
                className: 'btn btn-info btn-sm',
                attr: {
                    href: '#add-' + id
                },
                action: function (e, dt, node, config) {
                    $('#add-' + id).modal('show');
                }
            }, {
                text: '<i class="fa fa-refresh"></i>',
                className: 'btn btn-success btn-sm reload',
            }]
        });
        Table.buttons(0, null).container().prependTo($('#tables-' + id + '_wrapper .dataTables_length'));
        $('#tables-' + id + '_wrapper .dataTables_length').addClass("input-group");
    });
};

function Selected(data, value, gets) {
    $.ajax({
        url: "./api/dhcp",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        data: {
            "router": data
        },
        method: "GET",
        dataType: "JSON",
        beforeSend: function () {
            $('#pool').empty().html('<option value="static-only">Static Only</option>').val(gets);
            $('#interface, #interfaces').empty().html('<option value="">-- Select Data --</option>').val(value);
        },
        success: function (routers) {
            $.each(routers.data.interface, function (i, val) {
                $('#interface, #interfaces').append('<option value="' + val.name + '">' + val.name + '</option>').val(value);
            });
            $.each(routers.data.pool, function (e, vals) {
                $('#pool').append('<option value="' + vals.name + '">' + vals.name + '</option>').val(gets);
            });
        }
    });
}

function Action() {
    $.ajax({
        url: "./api/dhcp",
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
                $('#server, #router, #routers').append('<option value="' + param.id + '">' + param.name + '</option>');
            });
        }
    });
    $('#router, #routers').change(function () {
        Selected($(this).val(), '', 'static-only');
    });
}

function DNSServer(data) {
    $.ajax({
        url: "./api/dhcp",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "dns": data
        },
        beforeSend: function () {
            $('#form-dns').trigger('reset');
            $('#remote').attr('checked', false);
        },
        success: function (response) {
            if (response.status) {
                $.each(response.data, function (i, dns) {
                    $('#' + i).val(dns);
                });
                $('#remote').attr('checked', response.data.remote);
            }
        }
    });
}

function Change() {
    $('body').on('click', '[href="#add-server"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $('#form-server').trigger('reset');
        $('#router').attr('disabled', false);
        $('#interface').empty().html('<option value="">-- Select Data --</option>');
        $('#pool').empty().html('<option value="static-only">Static Only</option>');
        $.ajax({
            url: "./api/dhcp",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "detail": id_data,
                "type": "server"
            },
            success: function (detail) {
                $('#router').attr('disabled', detail.status);
                if (detail.status) {
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
                    Selected(detail.data.router, detail.data.interface, detail.data.pool);
                    $('#status').bootstrapToggle(detail.data.status ? 'on' : 'off');
                }
            }
        });
    });
    $('body').on('click', '[href="#add-client"]', function () {
        $('#client').val(0);
        $('#form-client').trigger('reset');
        $('#routers').attr('disabled', false);
        $('#interfaces').empty().html('<option value="">-- Select Data --</option>');
        $.ajax({
            url: "./api/dhcp",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "detail": $(this).data('value'),
                "type": "client"
            },
            success: function (details) {
                $('#routers').attr('disabled', details.status);
                if (details.status) {
                    $.each(details.data, function (e, shows) {
                        if (e == 'dns' || e == 'ntp') {
                            $('#' + e).attr('checked', shows);
                        } else {
                            $('#' + e).val(shows);
                        }
                    });
                    Selected(details.data.routers, details.data.interfaces, '');
                    $('#active').bootstrapToggle(details.data.active ? 'on' : 'off');
                }
            }
        });
    });
    $('body').on('click', '.reload', function () {
        $('#tables-server').DataTable().ajax.url("./api/dhcp?type=server&data=" + $('#server').val()).load();
        $('#tables-client').DataTable().ajax.url("./api/dhcp?ype=client&data=" + $('#server').val()).load();
    });
    $('#server').change(function () {
        DNSServer($(this).val());
        $('#tables-server').DataTable().ajax.url("./api/dhcp?type=server&data=" + $(this).val()).load();
        $('#tables-client').DataTable().ajax.url("./api/dhcp?ype=client&data=" + $(this).val()).load();
    });
}
(function () {
    'use strict';
    Tables();
    Action();
    Change();
})();