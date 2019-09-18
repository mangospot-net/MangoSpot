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

function Options() {
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
        success: function (response) {
            $.each(response.data, function (i, param) {
                $('select#server').append('<option value="' + param.name + '">' + param.name + '</option>');
            });
        }
    });
    $.ajax({
        url: "./api/active",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "level",
        success: function (respons) {
            $.each(respons.data, function (e, level) {
                $('select#seller').append('<option value="' + level.id + '">' + level.name + '</option>');
            });
        }
    });
}

function Action() {
    $('#server').change(function () {
        $('#tables').DataTable().ajax.url("./api/active?data=" + $(this).val() + "&users=" + $('#seller').val()).load();
    });
    $('#seller').change(function () {
        $('#tables').DataTable().ajax.url("./api/active?data=" + $('#server').val() + "&users=" + $(this).val()).load();
    });
}
(function () {
    'use strict';
    Tables();
    Options();
    Action();

    function Refreh() {
        $('#tables').DataTable().ajax.url("./api/active?data=" + $('#server').val() + "&users=" + $('#seller').val()).load();
    }
    setInterval(Refreh, 10000);
})();