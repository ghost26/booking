views.home = function (data, params) {
    var resource = 'cities';
    var type = 'GET'
    var params = ''

    // utils.request(
    //     api_stub,
    //     'home_page',
    //     'home_page_error'
    // );

    utils.getCountries(
        params,
        'home_page',
        'home_page_error'
    );

};
