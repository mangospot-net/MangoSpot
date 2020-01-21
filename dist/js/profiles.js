function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/profiles?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
            "data": "groupname",
        }, {
            "data": "shared",
        }, {
            "data": "rate"
        }, {
            "data": "price"
        }, {
            "data": "discount"
        }, {
            "data": "id",
            "className": 'text-right details-control',
            render: function (data, type, row) {
                var btn = '<button class="btn btn-warning btn-sm detail-row"><i class="fa fa-question"></i></button>';
                return '<div class="btn-group">' + btn + '<button data-toggle="dropdown" class="btn btn-info btn-sm"><i class="fa fa-cog"></i></button>' +
                    '<div role="menu" class="dropdown-menu dropdown-menu-right">' +
                    '<a class="dropdown-item" data-toggle="modal" href="#add-data" data-value="' + row.groupname + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>' +
                    '<a class="dropdown-item" data-toggle="modal"  href="#delete" data-value="' + row.groupname + '" data-target="profiles" title="Delete"><i class="fa fa-trash"></i> Delete</a>' +
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
        aLengthMenu: [
            [5, 10, 15, 20, 50, 75, -1],
            [5, 10, 15, 20, 50, 75, "All"]
        ],
        order: [
            [5, 'desc']
        ],
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', aData.groupname);
        },
        iDisplayLength: 10
    });
    $('#tables tbody').on('click', 'td.details-control button.detail-row', function () {
        var tr = $(this).closest('tr');
        var row = Table.row(tr);
        var text = $(this).html();
        var html = $(this);
        $.ajax({
            url: "./api/profiles",
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
                html.html('<i class="fa fa-refresh fa-spin"></i>');
            },
            success: function (result) {
                html.html(text);
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    if (Table.row('.shown').length) {
                        $('.detail-row', Table.row('.shown').node()).click();
                    }
                    row.child(format(result.data, tr.attr('id'))).show();
                    tr.addClass('shown');
                }
            }
        });
    });
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

function Action() {
    $('body').on('click', 'a[href="#add-data"]', function () {
        $.ajax({
            url: "./api/profiles",
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
                $('#profiles, #ppp').val('');
                $('#form-data').trigger('reset');
                Durations(['period', 'times', 'daily']);
            },
            success: function (detail) {
                if (detail.status) {
                    Durations(detail.data);
                    $.each(detail.data, function (i, show) {
                        $('#' + i).val(show);
                    });
                    $('#profiles').val(detail.data.groupname);
                    $('#checkPPP').prop('checked', detail.data.ppp ? true : false);
                }
            }
        });
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
    $('#checkPPP').click(function () {
        $('#ppp').val($(this).is(":checked") ? 'PPP' : '');
    });
    $('.UpperCase').bind('input', function () {
        $(this).val(function () {
            return this.value.toUpperCase();
        })
    });
};

(function () {
    'use strict';
    Tables();
    Action();
})();