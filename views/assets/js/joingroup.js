$(document).ready(function ($) {
    $('.join-group').on('click', function (event) {
        event.preventDefault();
        var $button = $(this);
        var data = {
            'groupId': parseInt($(this).data('id'))
        };
        data[yupeTokenName] = yupeToken;
        $.post($(this).data('url'), data, function (response) {
            if (response.result) {
                $button.hide();
                $('#notifications').notify({ message: { text: response.data } }).show();
            } else {
                $('#notifications').notify({ message: { text: response.data }, type: 'error' }).show();
            }
        }, 'json');
    });

    $('.leave-group').on('click', function (event) {
        event.preventDefault();
        var $button = $(this);
        var data = {
            'groupId': parseInt($(this).data('id'))
        };
        data[yupeTokenName] = yupeToken;
        $.post($(this).data('url'), data, function (response) {
            if (response.result) {
                $button.hide();
                $('#notifications').notify({ message: { text: response.data } }).show();
            } else {
                $('#notifications').notify({ message: { text: response.data }, type: 'error' }).show();
            }
        }, 'json');
    });
});