function Identity() {
    $.ajax({
        url: "./api/data",
        method: "GET",
        data: "identity",
        dataType: "JSON",
        success: function (identity) {
            $.each(identity.data, function (i, val) {
                $('#' + i).val(val);
                $('img[alt="' + i + '"]').attr('src', val);
            });
        }
    });
    $('body').on('click', 'a.thumbnail', function () {
        var ide = $(this).data('id');
        window.KCFinder = {
            callBack: function (url) {
                window.KCFinder = null;
                $(this).html('<div style="margin:5px">Loading...</div>');
                var img = new Image();
                img.src = url;
                img.onload = function () {
                    $.ajax({
                        url: "./api/identity",
                        headers: {
                            "Api": $.cookie("BSK_API"),
                            "Key": $.cookie("BSK_KEY"),
                            "Accept": "application/json"
                        },
                        method: "POST",
                        data: ide + '=' + url,
                        dataType: "JSON",
                        beforeSend: function () {
                            $('img[alt=' + ide + ']').attr('src', 'dist/img/loading.gif');
                        },
                        success: function (image) {
                            $('img[alt=' + ide + ']').attr('src', url);
                        }
                    });
                }
            }
        };
        window.open('./media/browse.php?type=image&dir=image/public',
            'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'directories=0, resizable=1, scrollbars=0, width=1000, height=600'
        );
    });
    $('body').on('click', 'a.images', function () {
        window.KCFinder = {
            callBack: function (imgs) {
                window.KCFinder = null;
                var img = new Image();
                img.src = imgs;
                img.onload = function () {
                    $('img[alt="image"]').attr('src', imgs);
                    $('input[name="img"]').val(imgs);
                }
            }
        };
        window.open('./media/browse.php?type=image&dir=image/public',
            'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'directories=0, resizable=1, scrollbars=0, width=1000, height=600'
        );
    });
}

function EditCover(data) {
    var lists = '<img src="' + data.data.img + '" class="d-86 rounded mr-15 img-responsive">';
    lists += '<div class="w-65">';
    lists += '<h6 class="mb-5">' + data.data.title + '</h6>';
    lists += '<p>' + data.data.info + '</p>';
    lists += '</div>';
    lists += '<input type="hidden" name="title[]" value="' + data.data.title + '">';
    lists += '<input type="hidden" name="info[]" value="' + data.data.info + '">';
    lists += '<input type="hidden" name="cover[]" value="' + data.data.img + '">';
    lists += '<a href="javascript:void(0);" class="ml-auto delete-cover"><i class="fa fa-trash"></i></a>';
    lists += '<a href="javascript:void(0);" class="ml-auto edit-cover"><i class="fa fa-edit"></i></a>';
    $('li', '#cover-list').eq(data.data.add).html(lists);
}

function NewCover(data) {
    var list = '<li class="list-group-item d-flex" style="cursor:move">';
    list += '<img src="' + data.data.img + '" class="d-86 rounded mr-15 img-responsive">';
    list += '<div class="w-65">';
    list += '<h6 class="mb-5">' + data.data.title + '</h6>';
    list += '<p>' + data.data.info + '</p>';
    list += '</div>';
    list += '<input type="hidden" name="title[]" value="' + data.data.title + '">';
    list += '<input type="hidden" name="info[]" value="' + data.data.info + '">';
    list += '<input type="hidden" name="cover[]" value="' + data.data.img + '">';
    list += '<a href="javascript:void(0);" class="ml-auto delete-cover"><i class="fa fa-trash"></i></a>';
    list += '<a href="javascript:void(0);" class="ml-auto edit-cover"><i class="fa fa-edit"></i></a>';
    list += '</li>';
    $('#cover-list').append(list);
}

function AddCover() {
    $('#cover-data').submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: "./api/identity",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: 'POST',
            dataType: "JSON",
            data: $(this).serialize(),
            beforeSend: function () {
                $('button[type=submit]', "#cover-data").attr('disabled', true);
            },
            success: function (response) {
                if (response.data.add) {
                    EditCover(response);
                } else {
                    NewCover(response);
                }
                $('.modal').modal('hide');
                $('input[name="img"]').val('');
                $("#cover-data").trigger('reset');
                $('img[alt="image"]').attr('src', 'dist/img/blank.png');
                $('button[type=submit]', "#cover-data").attr('disabled', false);
            }
        });
    });
    $('body').on('click', 'a.delete-cover', function () {
        $(this).closest('li').remove();
    });
    $('body').on('click', 'a[href="#add-data"]', function () {
        $('input[name="img"]').val('');
        $('input[name="add"]').val('');
        $("#cover-data").trigger('reset');
        $('img[alt="image"]').attr('src', 'dist/img/blank.png');
    });
    $('body').on('click', 'a.edit-cover', function () {
        var data = $(this).closest('li');
        $('#add-data').modal('show');
        $('input[name="title"]', '#cover-data').val(data.find('h6').html());
        $('textarea[name="info"]', '#cover-data').val(data.find('p').html());
        $('input[name="img"]', '#cover-data').val(data.find('img').attr('src'));
        $('input[name="add"]', '#cover-data').val($(this).parents("li").index());
        $('img[alt="image"]', '#cover-data').attr('src', data.find('img').attr('src'));
    });
    $("#cover-list").sortable();
    $.ajax({
        url: "./api/identity",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: 'GET',
        dataType: "JSON",
        data: "cover",
        success: function (covers) {
            var view = '';
            $.each(covers.data, function (i, list) {
                view += '<li class="list-group-item d-flex" style="cursor:move">';
                view += '<img src="' + list.image + '" class="d-86 rounded mr-15 img-responsive">';
                view += '<div class="w-65">';
                view += '<h6 class="mb-5">' + list.title + '</h6>';
                view += '<p>' + list.info + '</p>';
                view += '</div>';
                view += '<input type="hidden" name="title[]" value="' + list.title + '">';
                view += '<input type="hidden" name="info[]" value="' + list.info + '">';
                view += '<input type="hidden" name="cover[]" value="' + list.image + '">';
                view += '<a href="javascript:void(0);" class="ml-auto delete-cover"><i class="fa fa-trash"></i></a>';
                view += '<a href="javascript:void(0);" class="ml-auto edit-cover"><i class="fa fa-edit"></i></a>';
                view += '</li>';
            });
            $("#cover-list").html(view);
        }
    });
}

function runGMap() {
    var map;
    map = new GMaps({
        div: '#map1',
        lat: -7.575488699999999,
        lng: 110.82432719999997,
    });
    GMaps.on('marker_added', map, function (marker) {
        $('#lat').val(marker.getPosition().lat());
        $('#lng').val(marker.getPosition().lng());
    });
    GMaps.on('click', map.map, function (e) {
        map.removeMarkers();
        var lat = e.latLng.lat();
        var lng = e.latLng.lng();
        var template = $('#edit_marker_template').text();
        var content = template.replace(/{{lat}}/, lat).replace(/{{lng}}/, lng);
        map.addMarker({
            lat: lat,
            lng: lng
        });
    });
    $.getJSON("./api/data?identity", function (gmp) {
        map.setCenter(gmp.data.lat, gmp.data.lng);
        map.addMarker({
            lat: gmp.data.lat,
            lng: gmp.data.lng
        });
    });
};
(function () {
    'use strict';
    runGMap();
    Identity();
    AddCover();
})();