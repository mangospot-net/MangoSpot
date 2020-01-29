function Rp(data) {
    var reverse = data.toString().split('').reverse().join(''),
        rupiah = reverse.match(/\d{1,3}/g);
    rupiah = rupiah.join('.').split('').reverse().join('');
    return rupiah;
};

function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/expired?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
            "data": "username",
            "orderable": false,
            "className": 'text-center',
            render: function (data, type, row) {
                return '<input type="checkbox" name="delete[]" value="' + row.username + '" data-price="' + (row.price ? row.price : 0) + '" data-discount="' + (row.discount ? row.discount : 0) + '" data-income="' + (row.total ? row.total : 0) + '">';
            }
        }, {
            "data": "username"
        }, {
            "data": "profile"
        }, {
            "data": "time"
        }, {
            "data": "usages"
        }, {
            "data": "price"
        }, {
            "data": "discount"
        }, {
            "data": "total"
        }, ],
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
            [3, 'desc']
        ],
        iDisplayLength: 10
    });
    new $.fn.dataTable.Buttons(Table, {
        buttons: [{
            text: '<i class="fa fa-code"></i>',
            titleAttr: 'Script',
            className: 'btn-info btn-sm',
            action: function (e, dt, node, config) {
                $('#add-data').modal('show');
            }
        }, {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print',
            title: '',
            className: 'btn-success btn-sm',
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7]
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
    $('#tables_wrapper .dataTables_length .btn-sm').removeClass('btn-secondary');
};

function Select() {
    $.ajax({
        url: "./api/expired",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        data: "profile",
        method: "GET",
        dataType: "JSON",
        success: function (detail) {
            $.each(detail.data, function (i, show) {
                $('#profile').append('<option value="' + show.groupname + '">' + show.groupname + '</option>');
            });
        }
    });
    $.ajax({
        url: "./api/expired",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        data: "level",
        method: "GET",
        dataType: "JSON",
        success: function (level) {
            $.each(level.data, function (i, lev) {
                $('#reseller').append('<option value="' + lev.id + '">' + lev.name + '</option>').val(level.value);
            });
        }
    });
    $.ajax({
        url: "./api/expired",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "code",
        success: function (details) {
            $.each(details.data, function (i, val) {
                $('#code').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        }
    });
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('body').on('click', 'input[type=checkbox]', function () {
        var value = 0;
        var price = 0;
        var piece = 0;
        var check = $('input[name="delete[]"]:checked');
        $('button#action').attr('disabled', check.length <= 0 ? true : false);
        check.each(function () {
            price += parseInt($(this).data('price'));
            value += parseInt($(this).data('income'));
            piece += parseInt($(this).data('discount'));
        });
        $('#price').val(price);
        $('#income').val(value);
        $('#discount').val(piece);
        $('#count').val(Rp(price));
        $('#piece').val(Rp(piece));
        $('#total').val(Rp(value));
    });
    $('#profile').change(function () {
        $('#tables').DataTable().ajax.url("./api/expired?data=" + $(this).val() + "&users=" + $('#reseller').val()).load();
    });
    $('#reseller').change(function () {
        $('#tables').DataTable().ajax.url("./api/expired?data=" + $('#profile').val() + "&users=" + $(this).val()).load();
    });
};

function Remove() {
    $('#removed').submit(function (e) {
        e.preventDefault();
        var forms = $(this).serialize();
        swal({
            title: "Are you sure!",
            text: "Delete permanent this data?",
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
                    url: "./api/expired",
                    headers: {
                        "Api": $.cookie("BSK_API"),
                        "Key": $.cookie("BSK_KEY"),
                        "Accept": "application/json"
                    },
                    method: "POST",
                    dataType: "JSON",
                    data: forms,
                    success: function (remov) {
                        $('#CheckAll').prop('checked', false);
                        $('button#action').attr('disabled', true);
                        $('.dataTable').DataTable().ajax.reload();
                        $('#value, #price, #count, #total').val('');
                        swal({
                            title: "Delete!",
                            text: remov.data,
                            timer: 2000,
                            type: 'success'
                        });
                    }
                })
            }
        });
    });
};

function Editor() {
    CodeMirror.modeURL = "../vendors/codemirror/mode/%N/%N.js";
    var editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
        lineNumbers: false,
        theme: 'blackboard'
    });
    $('#code').change(function () {
        editor.setValue('');
        $.ajax({
            url: "./api/expired",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            dataType: "JSON",
            data: {
                "detail": $(this).val()
            },
            success: function (code) {
                editor.setValue(code.data.info);
                CodeMirror.autoLoadMode(editor, 'php');
                editor.setOption("mode", 'text/x-php');
            }
        });
    });
}
(function () {
    'use strict';
    Tables();
    Select();
    Remove();
    Editor();
})();
