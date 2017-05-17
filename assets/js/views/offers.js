views.offers = function (data, params) {
    var resource = 'search';
    controllers.show_loader('page-content');
    utils.sendRequest(
        resource,
        'GET',
        params,
        'offers_success',
        'offers_fail'
    );

};
