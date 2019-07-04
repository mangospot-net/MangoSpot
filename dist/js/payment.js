function Rp(data) {
    var reverse = data.toString().split('').reverse().join(''),
        rupiah = reverse.match(/\d{1,3}/g);
    rupiah = rupiah.join('.').split('').reverse().join('');
    return 'Rp. ' + rupiah;
}

function Tables() {
    $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/payment?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "date"
            },
            {
                "data": "total"
            },
            {
                "data": "value",
                render: function (data, type, row) {
                    return Rp(row.value);
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
            [0, 'desc']
        ],
        iDisplayLength: 10,
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(),
                data;
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };
            voucher = api
                .column(1, {
                    page: 'current'
                })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            total = api
                .column(2, {
                    page: 'current'
                })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            api.column(1, {
                    page: 'current'
                }).data()
                .reduce(function (a, b) {
                    var avg1 = voucher / end;
                    var avg2 = total / end;
                    $('.avg_1').html(avg1.toFixed(2));
                    $('.avg_2').html('Rp. ' + Rp(avg2.toFixed(0)));
                }, 0);
            $(api.column(1).footer()).html(voucher);
            $(api.column(2).footer()).html('Rp. ' + Rp(total));
        }
    });
};

function Select() {
    $('input#daterange').daterangepicker({
        opens: 'left',
        locale: {
            format: 'YYYY-MM-DD'
        },
        "cancelClass": "btn-secondary",
    });
    $('#daterange').change(function () {
        $('#tables').DataTable().ajax.url("./api/payment?data=" + $(this).val()).load();
    });
};
(function () {
    'use strict';
    Tables();
    Select();
})();