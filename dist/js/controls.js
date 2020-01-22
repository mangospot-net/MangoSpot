var url = window.location.pathname;
var filename = url.substring(url.lastIndexOf('/') + 1);

function ucword(data) {
    var result = data.replace('.html', '').toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
    return result;
}

function Logout() {
    $.cookie("BSK_API", null);
    $.cookie("BSK_KEY", null);
    $.cookie("BSK_AUTH", null);
    $.cookie("BSK_TOKEN", null);
    $.removeCookie("BSK_API");
    $.removeCookie("BSK_KEY");
    $.removeCookie("BSK_AUTH");
    $.removeCookie("BSK_TOKEN");
    window.location = 'login.html';
};
$('body').on('click', 'a.logout', function () {
    Logout();
});

$.ajax({
    url: "./api/data",
    method: "GET",
    data: "identity",
    dataType: "JSON",
    success: function (identity) {
        $('link[rel="shortcut icon"]').attr('href', identity.data.icon);
        $('img.brand-img').attr('src', identity.data.logo).attr('alt', identity.data.title);
        $('title').html(identity.data.data + ' | ' + (filename ? ucword(filename) : 'Dashboard') + ' ' + identity.data.title);
    }
});
$.ajax({
    url: "./api/data",
    method: "GET",
    data: "config",
    dataType: "JSON",
    success: function (config) {
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function () {
            OneSignal.init({
                appId: config.data.on_api,
                autoResubscribe: true,
            });
            if ($.cookie("BSK_AUTH")) {
                OneSignal.sendTag("key", $.cookie("BSK_AUTH"));
            } else {
                OneSignal.deleteTag("key");
            }
        });
    }
});
$.ajax({
    url: "./api/data",
    headers: {
        "Api": $.cookie("BSK_API"),
        "Key": $.cookie("BSK_KEY"),
        "Accept": "application/json"
    },
    method: "GET",
    data: "accept",
    dataType: "JSON",
    success: function (accept) {
        if (jQuery.inArray(ucword(filename), accept.data) == -1) {
            window.location = (accept.status ? './' : 'login.html');
        }
    }
});
$('[data-include]').each(function (index, element) {
    var target = $(this).data('include');
    $(this).load(target, function () {
        $.ajax({
            url: "./api/data",
            headers: {
                "Api": $.cookie("BSK_API"),
                "Key": $.cookie("BSK_KEY"),
                "Accept": "application/json"
            },
            method: "GET",
            data: "menu",
            dataType: "JSON",
            success: function (data) {
                var html = '';
                $.each(data.data, function (i, menu) {
                    html += '<ul class="navbar-nav flex-column">';
                    html += '<li class="nav-item">';
                    html += '<a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#page_' + menu.id + '">';
                    html += '<i class="material-icons">' + menu.icon + '</i>';
                    html += '<span class="nav-link-text">' + menu.name + '</span>';
                    html += '</a>';
                    html += '<ul id="page_' + menu.id + '" class="nav flex-column collapse collapse-level-1">';
                    html += '<li class="nav-item">';
                    if (menu.children) {
                        html += '<ul class="nav flex-column">';
                        $.each(menu.children, function (index, subs) {
                            html += '<li class="nav-item">';
                            html += '<a class="nav-link" href="' + subs.value + '.html">' + subs.name + '</a>';
                            html += '</li>';
                        });
                        html += '</ul>';
                    }
                    html += '</li>';
                    html += '</ul>';
                    html += '</li>';
                    html += '</ul>';
                });
                $('#list-menu').html(html).find("a[href='" + (filename ? filename : './') + "']").each(function () {
                    $(this).closest('li.nav-item').addClass("active");
                    $(this).closest('ul.collapse').addClass('show');
                });
            }
        });
        $('body').find("a[href='" + (filename ? filename : './') + "']").each(function () {
            $(this).closest('li.nav-item').addClass("active");
            $(this).closest('ul.collapse').addClass('show');
        });
    });
});
$.ajax({
    url: "./api/profile",
    headers: {
        "Api": $.cookie("BSK_API"),
        "Key": $.cookie("BSK_KEY"),
        "Accept": "application/json"
    },
    method: "GET",
    data: "data",
    dataType: "JSON",
    success: function (account) {
        if (account.status) {
            $.each(account.data, function (i, user) {
                $('.user_' + i).html(user);
            });
            if (account.data.image) {
                $('.user-image').attr('src', account.data.image);
            }
        }
    }
});
$('form[id^=import-]').each(function (index, element) {
    var id = $(this).attr('id').replace('import-', '');
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var modals = $(this).data('modal');
    var extent = $(this).data('extension');
    $('#import-' + id).validate({
        errorElement: "span",
        errorClass: 'help-block',
        ignore: "required",
        rules: {
            file: {
                required: true,
                extension: extent ? extent : "xls"
            }
        },
        highlight: function (element) {
            $(element).closest('.help-block').removeClass('valid');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        },
        submitHandler: function (form) {
            var formData = form;
            var formData = new FormData(formData);
            $.ajax({
                url: "./api/" + action,
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: method,
                dataType: "JSON",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $('button[type=submit]', '#import-' + id).prop('disabled', true);
                },
                success: function (response) {
                    $.toast({
                        heading: "Import " + ucword(id),
                        text: response.data,
                        position: 'bottom-right',
                        icon: response.message
                    });
                    if (typeof modals == 'undefined') {
                        $('#add-' + id).modal('hide');
                        $('.dataTable').DataTable().ajax.reload();
                    }
                    $('#import-' + id).trigger('reset');
                    $('button[type=submit]', '#import-' + id).prop('disabled', true);
                }
            });
        }
    });
});
$('form[id^=form-]').each(function (index, element) {
    var id = $(this).attr('id').replace('form-', '');
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var modals = $(this).data('modal');
    var resets = $(this).data('reset');
    $("#form-" + id).validate({
        errorElement: "span",
        errorClass: 'help-block',
        ignore: "required",
        highlight: function (element) {
            $(element).closest('.help-block').removeClass('valid');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        success: function (label, element) {
            label.addClass('help-block valid');
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        },
        submitHandler: function (form) {
            $.ajax({
                url: "./api/" + action,
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: method,
                dataType: "JSON",
                data: $(form).serialize(),
                beforeSend: function () {
                    $('button[type=submit]', "#form-" + id).attr('disabled', true);
                },
                success: function (response) {
                    if (id == 'login') {
                        if (response.status) {
                            $.cookie("BSK_API", response.data.api, {
                                expires: response.data.exp
                            });
                            $.cookie("BSK_KEY", response.data.key, {
                                expires: response.data.exp
                            });
                            $.cookie("BSK_AUTH", response.data.auth, {
                                expires: response.data.exp
                            });
                            $.cookie("BSK_TOKEN", response.data.token, {
                                expires: response.data.exp
                            });
                            window.location = './';
                        }
                    } else {
                        if (typeof modals == 'undefined') {
                            $('#add-' + id).modal('hide');
                            $('.dataTable').DataTable().ajax.reload();
                        }
                    }
                    $.toast({
                        heading: ucword(id),
                        text: response.data,
                        position: 'bottom-right',
                        icon: response.message
                    });
                    if (typeof resets == 'undefined') {
                        $('#form-' + id).trigger('reset');
                    }
                    $('a[class^=refresh-]').trigger('click');
                    $('button[type=submit]', "#form-" + id).attr('disabled', false);
                }
            });
        }
    });
});
$('body').on('click', 'button[name="active"]', function () {
    $.ajax({
        url: "./api/" + $(this).data('target'),
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "POST",
        data: {
            "active": $(this).val()
        },
        success: function (active) {
            $('.dataTable').DataTable().ajax.reload();
        }
    });
});
$('body').on('click', 'a.refresh-tabel', function () {
    $('.dataTable').DataTable().ajax.reload();
});
$('body').on('click', '[href="#delete"]', function () {
    var id_delete = $(this).data("value");
    var url_delete = $(this).data('target');
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
                url: "./api/" + url_delete,
                headers: {
                    "Api": $.cookie("BSK_API"),
                    "Key": $.cookie("BSK_KEY"),
                    "Accept": "application/json"
                },
                method: "POST",
                dataType: "JSON",
                data: {
                    'delete': id_delete
                },
                success: function (remove) {
                    $('a[class^=refresh-]').trigger('click');
                    $('.dataTable').DataTable().ajax.reload();
                    swal({
                        title: "Delete!",
                        text: remove.data,
                        timer: 2000,
                        type: remove.message
                    });
                }
            })
        }
    });
});