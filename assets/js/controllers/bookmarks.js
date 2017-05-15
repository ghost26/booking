controllers.show_bookmarks_success = function (data, params) {
    var bookmarks = templates.bookmarks(data.bookmarks);

    utils.render(
        'page-content',
        bookmarks
    );

};

controllers.show_bookmarks_fail = function (data, params) {
    controllers.popup_fail();
};

controllers.add_bookmark_success = function (data, params) {
    controllers.popup_success('Закладка успешно добавлена');
}

controllers.add_bookmark_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
}