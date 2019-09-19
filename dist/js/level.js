function Select() {
    $.ajax({
        url: "./api/level",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "type",
        success: function (response) {
            $.each(response.data, function (i, val) {
                $('#value').append('<option value="' + val.id + '">' + val.name + '</option>');
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
            url: "./api/level?data",
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
                "data": "value",
            },
            {
                "data": "id",
                className: 'dt-body-right',
                render: function (data, type, row) {
                    var btn = (row.status == 1 || row.status == 'true' ?
                        '<button name="active" data-target="level" class="btn btn-success btn-sm" title="On" value="' + row.id + '"><i class="fa fa-eye"></i></button>' :
                        '<button name="active" data-target="level" class="btn btn-danger btn-sm" title="Off" value="' + row.id + '"><i class="fa fa-eye-slash"></i></button>');
                    return '<div class="btn-group">' + btn + '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                        '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item" data-toggle="modal" href="#add-data" data-value="' + row.id + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                        '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.id + '" data-target="level" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
            [2, 'desc']
        ],
        iDisplayLength: 10
    });
};

function Action() {
    $('.select2').select2({
        placeholder: 'Select menu',
        allowClear: true
    });
    $('body').on('click', 'a[href="#add-data"]', function () {
        var id_data = $(this).data('value');
        $('#id').val(id_data);
        $('#value').val(null).trigger('change');
        $('#form-data').trigger('reset');
        $.ajax({
            url: "./api/level",
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
                    $('#value').val(detail.data.value.split(",")).select2();
                    $('#status').bootstrapToggle(detail.data.status ? 'on' : 'off');
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
