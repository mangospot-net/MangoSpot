function Rp(data) {
    var reverse = data.toString().split('').reverse().join(''),
        rupiah = reverse.match(/\d{1,3}/g);
    rupiah = rupiah.join('.').split('').reverse().join('');
    return rupiah;
}

function Tables() {
    var Table = $('#tables').DataTable({
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
            "data": "date",
        }, {
            "data": "total",
            "className": 'text-center',
            render: function (data, type, row) {
                return '<a href="#show-data" data-toggle="modal" data-value="' + row.id + '">' + row.total + '</a>';
            }
        }, {
            "data": "value",
            "className": 'text-right',
            render: function (data, type, row) {
                return Rp(row.value);
            }
        }, {
            "data": "discount",
            "className": 'text-right',
            render: function (data, type, row) {
                return Rp(row.discount);
            }
        }, {
            "data": "price",
            "className": 'text-right',
            render: function (data, type, row) {
                return Rp(row.price);
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
            users = api
                .column(1, {
                    page: 'current'
                })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            price = api
                .column(2, {
                    page: 'current'
                })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            discount = api
                .column(3, {
                    page: 'current'
                })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            income = api
                .column(4, {
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
                    var avg1 = users / end;
                    var avg2 = price / end;
                    var avg3 = discount / end;
                    var avg4 = income / end;
                    $('.avg_1').html(avg1.toFixed(2));
                    $('.avg_2').html(Rp(avg2.toFixed(0)));
                    $('.avg_3').html(Rp(avg3.toFixed(0)));
                    $('.avg_4').html(Rp(avg4.toFixed(0)));
                }, 0);
            $(api.column(1).footer()).html(users);
            $(api.column(2).footer()).html(Rp(price));
            $(api.column(3).footer()).html(Rp(discount));
            $(api.column(4).footer()).html(Rp(income));
        }
    });
    new $.fn.dataTable.Buttons(Table, {
        buttons: [{
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            title: '',
            footer: 'true',
            titleAttr: 'Print',
            className: 'btn-info btn-sm',
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
    $('#tables_wrapper .dataTables_length button').removeClass("btn-secondary");
    $('#tables tbody').on('click', 'tr > td a[href="#show-data"]', function () {
        $.ajax({
            url: "./api/payment",
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
                $('#detail-data').empty();
            },
            success: function (result) {
                var text = '';
                var numb = 0;
                $.each(result.data, function (e, vals) {
                    $('.detail_' + e).html(vals);
                });
                $.each(result.data.data, function (i, rows) {
                    numb++;
                    text += '<tr>';
                    text += '<td align="center">' + numb + '</td>';
                    text += '<td>' + rows.username + '</td>';
                    text += '<td>' + rows.profile + '</td>';
                    text += '<td>' + rows.time + '</td>';
                    text += '<td align="right">' + rows.price + '</td>';
                    text += '<td align="right">' + rows.discount + '</td>';
                    text += '<td align="right">' + rows.total + '</td>';
                    text += '</tr>';
                });
                $('#detail-data').html(text);
            }
        });
    });
};

function Charts(data) {
    chart = new Highcharts.Chart({
        chart: {
            type: 'area',
            renderTo: 'data-chart',
            animation: Highcharts.svg,
        },
        title: {
            text: 'Payment Chart'
        },
        subtitle: {
            text: data.subtitle
        },
        xAxis: {
            categories: data.categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Income'
            }
        },
        plotOptions: {
            area: {
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        tooltip: {
            headerFormat: '<table><tr><th colspan="2" align="center">{point.key}</th></tr>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        series: data.series
    });
};

function runChart(data) {
    $.ajax({
        url: "./api/payment",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "chart": data ? data : ''
        },
        success: function (result) {
            Charts(result.data);
        }
    });
}

function Select() {
    runChart(moment().subtract(10, "days").format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'));
    $('input#daterange').daterangepicker({
        opens: 'left',
        locale: {
            format: 'YYYY-MM-DD'
        },
        startDate: moment().subtract(10, "days"),
        endDate: moment(),
        "cancelClass": "btn-secondary",
    });
    $('#daterange').change(function () {
        runChart($(this).val());
        $('#tables').DataTable().ajax.url("./api/payment?data=" + $(this).val()).load();
    });
    $('body').on('click', '.print', function () {
        var area = $(this).val();
        var divToPrint = document.getElementById(area);
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><style>table{border-collapse: collapse; font-size: small;}.table td, .table th{border: 1px solid black;padding: 0 5px;}.text-uppercase{text-transform: uppercase;}</style><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
        setTimeout(function () {
            newWin.close();
        }, 10);
    });
};
(function () {
    'use strict';
    Tables();
    Select();
})();