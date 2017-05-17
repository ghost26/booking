views.bookings = function (data, params) {
    var api_stub = 'bookings';
    controllers.show_loader('page-content');
    utils.sendRequest(
        api_stub,
        'GET',
        '',
        'user_bookings_success',
        'user_bookings_fail'
    );
};