controllers.denied = function () {
    var denied = templates.denied();
    utils.render(
        'page-content',
        denied
    );
};
