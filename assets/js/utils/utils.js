var utils = (function () {

    var extract_params = function (params_string) {
        var params = {};
        var raw_params = params_string.split('&');

        var j = 0;
        for (var i = raw_params.length - 1; i >= 0; i--) {
            var url_params = raw_params[i].split('=');
            if (url_params.length == 2) {
                params[url_params[0]] = url_params[1];
            } else if (url_params.length == 1) {
                params[j] = url_params[0];
                j += 1;
            } else {
                //param not readable. pass.
            }
        }

        return params;
    };

    return {
        router: function (route, data) {
            route = route || location.hash.slice(1) || 'home';
            var temp = route.split('?');
            var route_split = temp.length;
            var function_to_invoke = temp[0] || false;
            if (route_split > 1) {
                var params = extract_params(temp[1]);
            }
            if (!isAuthorized && utils.checkAuth()) {
                isAuthorized = true;
                controllers.navbar_logged_in();
            }

            var isSpecificView = false;
            if (auth[function_to_invoke]) {
                if (auth[function_to_invoke].authRequired
                    && (!isAuthorized || (isAuthorized && (['all', user.type].indexOf(auth[function_to_invoke].accessUserType) === -1)))) {
                    views['denied']();
                    isSpecificView = true;
                }
            } else {
                isSpecificView = true;
                views['notfound']();
            }

            if (!isSpecificView) {
                if (['bookmarks', 'bookings', 'admin', 'home'].indexOf(function_to_invoke) !== -1)
                    controllers.change_active_nav_item(function_to_invoke);

                if (function_to_invoke) {
                    views[function_to_invoke](data, params);
                }
            }
        },

        render: function (element_id, content) {
            var el = document.getElementById(element_id);
            if (el !== null) {
                el.innerHTML = content;
                el.scrollIntoView();
            }
        },

        openLogin: function (needOpenModal) {
            if (needOpenModal) {
                var el = document.getElementById('top-sign-open');
                if (el != null) el.click();
            }
            controllers.change_tab('top-signin', 'signin');
        },

        openRegistration: function () {
            controllers.change_tab('top-reg', 'signup');
        },

        // jquery only for beauty datepicker :)
        runPicker: function () {
            $(function () {
                $('#datetimepicker').datetimepicker({
                    minDate: new Date(),
                    format: 'MM/D/YYYY',
                });
                $('#datetimepicker2').datetimepicker({
                    minDate: new Date((new Date()).valueOf() + 1000 * 3600 * 24),
                    format: 'MM/D/YYYY',
                });
                $("#datetimepicker").on("dp.change", function (e) {
                    $('#datetimepicker2').data("DateTimePicker").minDate(new Date(e.date.valueOf() + 1000 * 3600 * 24));
                });
            });
        },

        login: function (event) {
            event.preventDefault();
            var params = {
                'email': document.getElementById('login-email').value,
                'password': document.getElementById('login-password').value
            }
            utils.sendRequest('auth', 'POST', params, 'login_success', 'login_fail')
        },

        createNewHotel: function (event) {
            event.preventDefault();
            var params = {
                'name': document.getElementById('hotel-name').value,
                'description': document.getElementById('hotel-description').value,
                'country_id': document.getElementById('hotel-countries').value,
                'city_id': document.getElementById('hotel-cities').value,
                'address': document.getElementById('hotel-address').value,
                'stars': document.getElementById('hotel-stars').value,
            }
            utils.sendRequest('hotels', 'POST', params, 'create_hotel_success', 'create_hotel_fail');
        },

        createNewRoom: function (event, hotel_id) {
            event.preventDefault();

            var params = {
                'title': document.getElementById('room-title').value,
                'description': document.getElementById('room-description').value,
                'hotel_id': hotel_id,
                'price': document.getElementById('room-price').value,
                'capacity': document.getElementById('room-capacity').value,
            }
            utils.sendRequest('rooms', 'POST', params, 'create_room_success', 'create_room_fail');
        },

        bookRoom: function (start_date, end_date, room_id) {
            if (!isAuthorized) {
                var el = document.getElementById('top-sign-open');
                if (el != null) el.click();
            } else {
                var params = {
                    'start_date': start_date,
                    'end_date': end_date,
                    'room_id': room_id
                }
                utils.sendRequest('bookings', 'POST', params, 'book_success', 'book_fail');
            }
        },

        clearCookies: function () {
            var cookies = document.cookie.split(";");
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i];
                var eqPos = cookie.indexOf("=");
                var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
        },

        register: function (event) {
            event.preventDefault();
            var radio = document.getElementById('reg-type-1').checked ? 'reg-type-1' : 'reg-type-2';
            var params = {
                'email': document.getElementById('reg-email').value,
                'firstname': document.getElementById('reg-firstname').value,
                'lastname': document.getElementById('reg-lastname').value,
                'password': document.getElementById('reg-password').value,
                'type': document.getElementById(radio).value,
            }
            utils.sendRequest('users', 'POST', params, 'register_success', 'register_fail');
        },

        addBookmark: function (hotel_id, start_date, end_date, capacity) {
            var params = {
                'hotel_id': hotel_id,
                'start_date': start_date,
                'end_date': end_date,
                'capacity': capacity
            }
            utils.sendRequest('bookmarks', 'POST', params, 'add_bookmark_success', 'add_bookmark_fail');
        },

        logout: function () {
            utils.clearCookies();
            isAuthorized = false;
            user = {};
            controllers.navbar_logged_out();
        },

        checkAuth: function () {
            if (utils.getCookie('authorizationId') && utils.getCookie('token') && utils.getCookie('type')) {
                user.firstname = utils.getCookie('firstname');
                user.lastname = utils.getCookie('lastname');
                user.type = utils.getCookie('type');
                return true;
            } else {
                return false;
            }
        },

        setCookie: function (name, value, expdays) {
            var d = new Date();
            d.setTime(d.getTime() + (expdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + (expdays == 0 ? "Thu, 01 Jan 1970 00:00:01 GMT" : d.toUTCString());
            document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/";
        },

        getCookie: function (name) {
            var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        },

        getCities: function (params, success_callback, error_callback) {
            params = {'country_id': params}
            utils.sendRequest('cities', 'GET', params, success_callback, error_callback);
        },

        getCountries: function (params, success_callback, error_callback) {
            utils.sendRequest('countries', 'GET', params, success_callback, error_callback);
        },

        runSearch: function (city_id, start_date, end_date, capacity) {
            if (!city_id) {
                controllers['home_page_search_validation_failed']();
            } else {
                controllers['home_page_search_validation_success']();
                controllers.show_loader('search_result');
                var params = {
                    'city_id': city_id,
                    'start_date': new Date(start_date + " 12:00").valueOf() / 1000,
                    'end_date': new Date(end_date + " 12:00").valueOf() / 1000,
                    'capacity': capacity
                }
                utils.sendRequest('search', 'GET', params, 'home_page_load_search', 'home_page_error');
            }
        },

        getTitle: function (number, titles) {
            cases = [2, 0, 1, 1, 1, 2];
            return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
        },

        sendRequest: function (resource, type, params, success_callback, error_callback) {
            params = params || '';
            // controllers.show_loader('page-content');
            $.ajax({
                type: type,
                url: config.api_server + resource,
                data: params,
                dataType: "json",
                success: function (data) {

                    var el = document.getElementById('mega-fail');
                    if (el != null) el.remove();
                    if (success_callback) {
                        controllers[success_callback](
                            data.response, params
                        );
                    }

                },
                error: function (rq, er, err) {
                    if (rq.status === 401) {
                        utils.logout();
                        window.location.href = "#";
                        utils.openLogin(true);
                    } else if (error_callback) {
                            controllers[error_callback](
                                rq, params
                            );
                    }
                }
            });
        }
    }
})();
