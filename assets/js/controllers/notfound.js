controllers.notfound = function () {
    var notfound = templates.notfound();
    utils.render(
        'page-content',
        notfound
    );
};
