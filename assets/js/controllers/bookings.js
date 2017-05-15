controllers.user_bookings_success = function (data, params) {
    var bookings = templates.bookings(data.bookings)
    utils.render(
        'page-content',
        bookings
    );
};

controllers.user_bookings_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
};
