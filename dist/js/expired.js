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
                render: function (data, type, row) {
                    return '<input type="checkbox" name="delete[]" value="' + row.username + '" data-price="' + (row.price ? row.price : 0) + '">';
                }
            }, {
                "data": "profile"
            },
            {
                "data": "username"
            },
            {
                "data": "time"
            },
            {
                "data": "expired"
            },
            {
                "data": "price"
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
            [3, 'desc']
        ],
        iDisplayLength: 10
    });
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
    $('#CheckAll').click(function (e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });

    function Rp(data) {
        var reverse = data.toString().split('').reverse().join(''),
            rupiah = reverse.match(/\d{1,3}/g);
        rupiah = rupiah.join('.').split('').reverse().join('');
        return 'Rp. ' + rupiah;
    }

    $('body').on('click', 'input[type=checkbox]', function () {
        var total = 0;
        var check = $('input[name="delete[]"]:checked');
        $('button#action').attr('disabled', check.length <= 0 ? true : false);
        check.each(function () {
            total += parseInt($(this).data('price'));
        });
        $('#price').val(total);
        $('#total').val(Rp(total));
    });
    $('#profile').change(function () {
        $('#tables').DataTable().ajax.url("./api/expired?data=" + $(this).val()).load();
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
(function () {
    'use strict';
    Tables();
    Select();
    Remove();
})();