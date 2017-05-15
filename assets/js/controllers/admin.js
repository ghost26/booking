controllers.admin_hotels_success = function (data, params) {

    var hotels = templates.admin(data.hotels);
    utils.render(
        'page-content',
        hotels
    );
    utils.getCountries(
        params,
        'admin_load_countries_success',
        'admin_load_countries_fail'
    );
};

controllers.admin_load_countries_success = function (data, params) {
    var countries = templates.countries(data.countries);
    utils.render('hotel-countries', countries);
}

controllers.admin_load_countries_fail = function (data, params) {
    var countries = '<option selected disabled>Не удалось загрузить список стран</option>'
    utils.render('hotel-countries', countries);
}

controllers.admin_load_cities_success = function (data, params) {
    var countries = templates.cities(data.cities);
    utils.render('hotel-cities', countries);
}

controllers.admin_load_cities_fail = function (data, params) {
    var cities = '<option selected disabled>Не удалось загрузить список городов</option>'
    utils.render('hotel-cities', cities);
}

controllers.create_hotel_success = function (data, params) {
    var el_1 = document.getElementById('hotel-close-button');
    var el_2 = document.getElementById('hotel-name');
    var el_3 = document.getElementById('hotel-description');
    var el_4 = document.getElementById('hotel-address');
    if (el_1 !== null) el_1.click();
    if (el_2 !== null) el_2.value = "";
    if (el_3 !== null) el_3.value = "";
    if (el_4 !== null) el_4.value = "";
    utils.sendRequest(
        'hotels',
        'GET',
        '',
        'admin_hotels_success',
        'admin_hotels_fail'
    );
    // utils.render('hotel-cities', cities);
}
controllers.create_room_success = function (data, params) {
    var el_1 = document.getElementById('room-close-button');
    var el_2 = document.getElementById('room-title');
    var el_3 = document.getElementById('room-description');
    var el_4 = document.getElementById('room-price');
    if (el_1 !== null) el_1.click();
    if (el_2 !== null) el_2.value = "";
    if (el_3 !== null) el_3.value = "";
    if (el_4 !== null) el_4.value = "";
    utils.router();
    // utils.render('hotel-cities', cities);
}
controllers.create_room_fail = function (data, params) {
    controllers.popup_fail();
    utils.router();
}

controllers.admin_hotels_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
};

controllers.admin_load_user_info = function (data, params) {
    var elems = document.getElementsByName('creator-' + data.id);
    [].forEach.call(elems, function (el) {
        el.innerHTML = data.firstname + ' ' + data.lastname;
    });
};

controllers.admin_bookings_success = function (data, params) {
    var bookings = templates.admin_bookings(data.bookings, params);
    utils.render('page-content', bookings);
    used = {};
    for (var i = 0; i < data.bookings.length; i++) {
        if (used[data.bookings[i].user_id] == null) {
            used[data.bookings[i].user_id] = true;
            utils.sendRequest('users', 'GET', {id: data.bookings[i].user_id}, 'admin_load_user_info', 'popup_fail');
        }
    }


    var param = {
        id: params.hotel_id
    }
    utils.sendRequest(
        'hotels',
        'GET',
        param,
        'admin_booking_hotel_name',
        'notfound'
    );

};

controllers.admin_bookings_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
};

controllers.admin_booking_hotel_name = function (data, params) {
    utils.render(
        'booking-control-hotel-name',
        data.name
    );
};

controllers.create_hotel_fail = function (data, params) {
    controllers.popup_fail(data.responseJSON.error.message);
};

controllers.admin_hotel_page_success = function (data, params) {
    var rooms = templates.rooms(data.rooms, params);
    utils.render('page-content', rooms);
    var param = {
        id: params.hotel_id
    }
    utils.sendRequest(
        'hotels',
        'GET',
        param,
        'admin_load_hotel_name',
        'notfound'
    );
};

controllers.admin_load_hotel_name = function (data, params) {
    utils.render(
        'rooms-hotel-name',
        data.name
    );
}

controllers.admin_hotel_page_fail = function (data, params) {
    controllers.popup_fail('Не удалось загрузить отели')
};


