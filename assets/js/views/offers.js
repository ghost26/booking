views.offers = function (data, params) {
    var resource = 'search';

    utils.sendRequest(
        resource,
        'GET',
        params,
        'offers_success',
        'offers_fail'
    );

};
