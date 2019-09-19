function Tables() {
    var Table = $('#tables').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./api/active?data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "POST"
        },
        "columns": [{
                "data": "server"
            },
            {
                "data": "username"
            },
            {
                "data": "profile"
            },
            {
                "data": "address",
            },
            {
                "data": "time"
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
};

function Action(data) {
    $.ajax({
        url: "./api/active",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "server",
        beforeSend: function () {
            $('select#server').empty().append('<option value="">-- All Server --</option>').val(data);
        },
        success: function (response) {
            $.each(response.data, function (i, param) {
                $('select#server').append('<option value="' + param.name + '">' + param.name + '</option>').val(data);
            });
        }
    });
}
(function () {
    'use strict';
    Tables();
    Action();
    $('#server').change(function () {
        $('#chang').val($(this).val());
        $('#tables').DataTable().ajax.url("./api/active?data=" + $(this).val()).load();
    });

    function Refreh() {
        Action($('#chang').val());
        $('#tables').DataTable().ajax.reload();
    }
    setInterval(Refreh, 10000);
})();