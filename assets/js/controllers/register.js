controllers.register_success = function (data, params) {
    var el_1 = document.getElementById('reg-email');
    var el_2 = document.getElementById('reg-firstname');
    var el_3 = document.getElementById('reg-lastname');
    var el_4 = document.getElementById('reg-password');
    var el_5 = document.getElementById('reg-error');
    var el_6 = document.getElementById('reg-success');
    var el_9 = document.getElementById('login-error');


    if (el_1 !== null) el_1.value = "";
    if (el_2 !== null) el_2.value = "";
    if (el_3 !== null) el_3.value = "";
    if (el_4 !== null) el_4.value = "";
    if (el_5 !== null) el_5.style.display = 'none';
    if (el_6 !== null) el_6.style.display = 'block';
    if (el_9 !== null) el_9.style.display = 'none';

    controllers.change_tab('top-signin', 'signin');
};

controllers.register_fail = function (err) {
    var el_1 = document.getElementById('reg-success');
    var el_2 = document.getElementById('reg-error');
    if (el_1 !== null) el_1.style.display = 'none';
    if (el_2 !== null) el_2.style.display = 'block';
    utils.render('reg-error-reason', err.responseJSON.error.message);
};
