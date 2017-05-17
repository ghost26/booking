controllers.navbar_logged_in = function (data, params) {

    var el_1 = document.getElementById('navbar-enter');
    var el_2 = document.getElementById('navbar-reg');
    var el_3 = document.getElementById('navbar-logout');
    var el_4 = document.getElementById('profile-name')
    if (el_1 !== null) el_1.style.display = 'none';
    if (el_2 !== null) el_2.style.display = 'none';
    if (el_3 !== null) el_3.style.display = 'block';
    if (el_4 !== null) el_4.innerHTML = user.firstname + ' ' + user.lastname;


    if (user.type == 'customer') {

        var el_5 = document.getElementById('navbar-bookings');
        var el_6 = document.getElementById('navbar-bookmarks');
        if (el_5 !== null) el_5.style.display = 'block';
        if (el_6 !== null) el_6.style.display = 'block';
        var elems = document.getElementsByName('bookmarks-icon');
        [].forEach.call(elems, function (el) {
            el.style.display = 'block';
        });
    } else {
        var el_7 = document.getElementById('navbar-admin');
        if (el_7 !== null) el_7.style.display = 'block';
    }
};

controllers.navbar_logged_out = function (data, params) {
    var el_1 = document.getElementById('navbar-enter');
    var el_2 = document.getElementById('navbar-reg');
    var el_3 = document.getElementById('navbar-logout');
    var el_4 = document.getElementById('profile-name');
    var el_5 = document.getElementById('navbar-bookings');
    var el_6 = document.getElementById('navbar-bookmarks');
    var el_7 = document.getElementById('navbar-admin');
    var el_8 = document.getElementById('login-password');

    if (el_1 != null) el_1.style.display = 'block';
    if (el_2 != null) el_2.style.display = 'block';
    if (el_3 != null) el_3.style.display = 'none';
    if (el_4 != null) el_4.innerHTML = 'Мой профиль';
    if (el_5 != null) el_5.style.display = 'none';
    if (el_6 != null) el_6.style.display = 'none';
    if (el_7 != null) el_7.style.display = 'none';
    if (el_8 != null) el_8.value = '';

    var elems = document.getElementsByName('bookmarks-icon');
    [].forEach.call(elems, function (el) {
        el.style.display = 'none';
    });
};

controllers.change_active_nav_item = function (function_to_invoke) {
    var elems = document.getElementsByName('navbar-item');
    [].forEach.call(elems, function (el) {
        el.classList.remove('active');
    });
    var menu_item = document.getElementById('navbar-' + function_to_invoke);
    if (menu_item !== null) menu_item.classList.add('active');
}

controllers.change_tab = function (top_element_tab, bottom_element_tab) {
    var elems = document.querySelectorAll('.active.in');
    var elems_2 = document.querySelectorAll('.active');
    [].forEach.call(elems, function (el) {
        el.classList.remove('active', 'in');
    });
    [].forEach.call(elems_2, function (el) {
        el.classList.remove('active');
    });
    var el_7 = document.getElementById(top_element_tab);
    var el_8 = document.getElementById(bottom_element_tab);
    if (el_7 !== null) el_7.classList.add('active');
    if (el_8 !== null) el_8.classList.add('active', 'in');
}
