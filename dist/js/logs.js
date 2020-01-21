function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/logs?data",
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
                "className": "text-center",
                render: function (data, type, row) {
                    return '<input type="checkbox" name="delete[]" value="' + row.id + '">';
                }
            },
            {
                "data": "date",
                render: function (data, type, row) {
                    return '<a href="#add-data" data-toggle="modal" data-value="' + row.id + '">' + row.date + '</a>';
                }
            },
            {
                "data": "facility"
            },
            {
                "data": "priority"
            },
            {
                "data": "syslog"
            },
            {
                "data": "message",
                "className": "d-inline-block text-truncate"
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
            [1, 'desc']
        ],
        iDisplayLength: 10,
        createdRow: function (row, data, dataIndex) {
            $(row).addClass('bg-' + data.color);
        },
        columnDefs: [{
            targets: 5,
            createdCell: function (td, cellData, rowData, row, col) {
                $(td).css('max-width', '300px');
            }
        }]
    });
    new $.fn.dataTable.Buttons(Table, {
        buttons: [{
            text: '<i class="fa fa-eraser"></i>',
            titleAttr: 'Clear',
            className: 'btn-warning btn-sm',
            action: function (e, dt, node, config) {
                Clears();
            }
        }, {
            text: '<i class="fa fa-trash"></i>',
            titleAttr: 'Delete',
            className: 'btn-danger btn-sm',
            action: function (e, dt, node, config) {
                Remove();
            }
        }, {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            title: '',
            titleAttr: 'Print',
            className: 'btn-info btn-sm',
            exportOptions: {
                columns: [1, 2, 3, 4, 5]
            },
            customize: function (win) {
                $(win.document.body)
                    .css('background', 'none')

                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            }
        }]
    });
    Table.buttons(0, null).container().prependTo($('#tables_wrapper .dataTables_length'));
    $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', true);
    $('#tables_wrapper .dataTables_length button').removeClass("btn-secondary");
    $('body').on('click', 'a[href="#add-data"]', function () {
        $.ajax({
            url: "./api/logs",
            method: "GET",
            dataType: "JSON",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            data: {
                'detail': $(this).data('value')
            },
            success: function (detail) {
                var table = '';
                $.each(detail.data, function (i, val) {
                    table += '<tr><td class="align-top">' + ucword(i) + '</td><td>' + val + '</td></tr>';
                });
                $('#detail-log').html(table);
            }
        });
    });
};

function Select() {
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('body').on('click', 'input[type=checkbox]', function () {
        var check = $('input[name="delete[]"]:checked');
        $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', check.length <= 0 ? true : false);
    });
    $('select.select2').select2({
        placeholder: 'Select menu',
        allowClear: true
    }).each(function (index, element) {
        $.ajax({
            url: "./api/logs",
            method: "GET",
            dataType: "JSON",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            data: {
                'option': $(this).attr('id')
            },
            success: function (result) {
                $.each(result.data, function (i, val) {
                    $(element).append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            }
        });
    });
    $('#Filter').submit(function (e) {
        e.preventDefault();
        $('#tables').DataTable().ajax.url("./api/logs?data&" + $("#Filter").serialize()).load();
    });
};

function Remove() {
    var Removed = $('input[name="delete[]"]:checked').length;
    swal({
        title: "Are you sure!",
        text: "Delete permanent this " + Removed + " data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (Removed > 0 && isConfirm) {
            $('input[name="delete[]"]:checked').each(function () {
                $.ajax({
                    url: "./api/logs",
                    method: "POST",
                    headers: {
                        "Api": $.cookie("BSK_API"),
                        "Key": $.cookie("BSK_KEY"),
                        "Accept": "application/json"
                    },
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
}

function Clears() {
    swal({
        title: "Are you sure!",
        text: "Claer permanent this data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirms) {
        if (isConfirms) {
            $.ajax({
                url: "./api/logs",
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: "POST",
                dataType: "JSON",
                data: "reset",
                success: function (remov) {
                    $('.dataTable').DataTable().ajax.reload();
                    swal({
                        title: "Clear!",
                        text: remov.data,
                        timer: 2000,
                        type: 'success'
                    });
                }
            })
        }
    });
}
(function () {
    'use strict';
    Tables();
    Select();
})();