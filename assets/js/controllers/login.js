controllers.login_success = function (data, params) {
    utils.setCookie('authorizationId', data.authorizationId, 30);
    utils.setCookie('token', data.token, 30);
    utils.setCookie('firstname', data.user.firstname, 30);
    utils.setCookie('lastname', data.user.lastname, 30);
    utils.setCookie('type', data.user.type, 30);
    if (utils.checkAuth()) {
        isAuthorized = true;
    }
    var el_1 = document.getElementById('reg-success');
    var el_2 = document.getElementById('login-error');
    var el_3 = document.getElementById('close-button');
    if (el_1 !== null) el_1.style.display = 'none';
    if (el_2 !== null) el_2.style.display = 'none';
    if (el_3 !== null) el_3.click();
    controllers.navbar_logged_in();
    utils.router();
};

controllers.login_fail = function (err) {
    var el_1 = document.getElementById('reg-success');
    var el_2 = document.getElementById('login-error');
    if (el_1 !== null) el_1.style.display = 'none';
    if (el_2 !== null) el_2.style.display = 'block';
};
