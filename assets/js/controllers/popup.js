controllers.popup_success = function (message) {
    var id = Date.now();
    var success = templates.popup_success(message, id);
    utils.render(
        'popup-alert',
        success
    );
    setTimeout(function(){
        var el = document.getElementById('mega-success-' + id);
        if (el != null) el.remove();
    }, 2500);
};

controllers.popup_fail = function (message) {
    var id = Date.now();
    var fail = templates.popup_error(message, id);
    utils.render(
        'popup-alert',
        fail
    );
    setTimeout(function(){
        var el = document.getElementById('mega-fail-' + id);
        if (el != null) el.remove();
    }, 2500);
};
