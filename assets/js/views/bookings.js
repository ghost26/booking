views.bookings = function (data, params) {
    var api_stub = 'bookings';

    utils.sendRequest(
        api_stub,
        'GET',
        '',
        'user_bookings_success',
        'user_bookings_fail'
    );
};