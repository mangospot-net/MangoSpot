function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/history?data",
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
                "data": "username"
            },
            {
                "data": "profile"
            },
            {
                "data": "reply"
            },
            {
                "data": "date",
                render: function (data, type, row) {
                    var time = row.date.split('.');
                    return time[0];
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
        }]
    });
    Table.buttons(0, null).container().prependTo($('#tables_wrapper .dataTables_length'));
    $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', true);
    $('#tables_wrapper .dataTables_length button').removeClass("btn-secondary");
};

function Select() {
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('body').on('click', 'input[type=checkbox]', function () {
        var check = $('input[name="delete[]"]:checked');
        $('button#action').attr('disabled', check.length <= 0 ? true : false);
        $('#tables_wrapper .dataTables_length button.btn-danger').attr('disabled', check.length <= 0 ? true : false);
    });
    $('#profile').change(function () {
        $('#tables').DataTable().ajax.url("./api/history?data=" + $(this).val()).load();
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
                    url: "./api/history",
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
};

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
                url: "./api/history",
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