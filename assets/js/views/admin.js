views.admin = function (data, params) {
    var api_stub = 'hotels';

    utils.sendRequest(
        api_stub,
        'GET',
        '',
        'admin_hotels_success',
        'admin_hotels_fail'
    );
};

views.admin_bookings = function (data, params) {
    var api_stub = 'bookings';
    utils.sendRequest(
        api_stub,
        'GET',
        params,
        'admin_bookings_success',
        'admin_bookings_fail'
    );
};

views.admin_hotel_page = function (data, params) {
    var api_stub = 'rooms';

    utils.sendRequest(
        api_stub,
        'GET',
        params,
        'admin_hotel_page_success',
        'admin_hotel_page_fail'
    );
};
