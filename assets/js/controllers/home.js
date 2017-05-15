controllers.home_page = function (data, params) {
    var search = templates.search(data.countries);
    var hello_text = templates.hello_text();
    var final_content = hello_text + search;
    utils.render(
        'page-content',
        final_content
    );

    utils.runPicker();
};


controllers.home_page_load_cities = function (data, params) {
    var cities = templates.cities(data.cities);
    utils.render(
        'cities_list',
        cities
    );
};

controllers.home_page_load_search = function (data, params) {
    var offers = templates.search_result(data.offers, params['start_date'], params['end_date'], params['capacity']);
    utils.render(
        'search_result',
        offers
    );
};
controllers.home_page_search_validation_failed = function (data, params) {
    var element = document.getElementById('search-error');
    if (element !== null) element.style.display = 'block';
};

controllers.home_page_search_validation_success = function (data, params) {
    var element = document.getElementById('search-error')
    if (element !== null) element.style.display = 'none';
};

controllers.home_page_error = function (data, params) {
    utils.render(
        'page-content',
        data
    );
};

