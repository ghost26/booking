controllers.offers_success = function (data, params) {
    var offers = templates.offers(data.offers, params);

    utils.render(
        'page-content',
        offers
    );

    var param = {
        id: params.hotel_id
    }
    utils.sendRequest(
        'hotels',
        'GET',
        param,
        'offers_hotel_success',
        'offers_hotel_fail'
    );
};

controllers.offers_hotel_success = function (data, params) {
    utils.render(
        'bookmarks-hotel-name',
        data.name
    );
};

controllers.book_success = function (data, params) {
    controllers.popup_success('Забронировано!');
    utils.router();
};

controllers.book_fail = function (data, params) {
    controllers.popup_fail('К сожалению вы не успели!');
    utils.router();
};

controllers.offers_hotel_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
};

controllers.offers_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
};