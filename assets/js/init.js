//initialise globals
var templates = {};
var controllers = {};
var views = {};
var user = {};
var isAuthorized = false;
var auth = {
    'home': {'authRequired': false, 'accessUserType': 'all'},
    'admin': {'authRequired': true, 'accessUserType': 'owner'},
    'hotel': {'authRequired': true, 'accessUserType': 'owner'},
    'bookmarks': {'authRequired': true, 'accessUserType': 'customer'},
    'admin_bookings': {'authRequired': true, 'accessUserType': 'owner'},
    'admin_hotel_page': {'authRequired': true, 'accessUserType': 'owner'},
    'offers': {'authRequired': false, 'accessUserType': 'all'},
    'bookings': {'authRequired': true, 'accessUserType': 'customer'},
};

window.onload = function () {

    //register routers
    window.addEventListener(
        "hashchange",
        function () {
            utils.router()
        }
    );

    utils.router();
};