controllers.popup_success = function (message) {
    var success = templates.popup_success(message);
    utils.render(
        'popup-alert',
        success
    );
};

controllers.popup_fail = function (message) {
    var fail = templates.popup_error(message);
    utils.render(
        'popup-alert',
        fail
    );
};
