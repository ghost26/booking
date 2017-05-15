views.bookings = function (data, params) {
    var api_stub = 'bookings';


    // function (resource, type, params, success_callback, error_callback)
    utils.sendRequest(
        api_stub,
        'GET',
        '',
        'user_bookings_success',
        'user_bookings_fail'
    );
};