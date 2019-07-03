function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "ajax": {
            url: "./api/wireless?data",
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
                "data": "radio",
            },
            {
                "data": "signal"
            },
            {
                "data": "rx"
            },
            {
                "data": "tx"
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
};

function Select() {
    $.ajax({
        url: "./api/wireless",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "router",
        success: function (response) {
            $.each(response.data, function (i, val) {
                $('#router').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        }
    });
};
(function () {
    'use strict';
    Tables();
    Select();
    setInterval(function () {
        $('#tables').DataTable().ajax.url("./api/wireless?data=" + $('#router').find('option:selected').val()).load();
    }, 3000);
})();