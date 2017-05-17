views.bookmarks = function (data, params) {
    var api_stub = 'bookmarks';
    controllers.show_loader('page-content');
    utils.sendRequest(
        api_stub,
        'GET',
        '',
        'show_bookmarks_success',
        'show_bookmarks_fail'
    );
}
