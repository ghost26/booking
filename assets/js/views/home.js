views.home = function (data, params) {
    controllers.show_loader('page-content');
    var resource = 'cities';
    var type = 'GET'
    var params = ''
    utils.getCountries(
        params,
        'home_page',
        'home_page_error'
    );

};
