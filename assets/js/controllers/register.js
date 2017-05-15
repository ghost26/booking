controllers.register_success = function (data, params) {
    var el_1 = document.getElementById('reg-email');
    var el_2 = document.getElementById('reg-firstname');
    var el_3 = document.getElementById('reg-lastname');
    var el_4 = document.getElementById('reg-password');
    var el_5 = document.getElementById('reg-error');
    var el_6 = document.getElementById('reg-success');


    if (el_1 !== null) el_1.value = "";
    if (el_2 !== null) el_2.value = "";
    if (el_3 !== null) el_3.value = "";
    if (el_4 !== null) el_4.value = "";
    if (el_5 !== null) el_5.style.display = 'none';
    if (el_6 !== null) el_6.style.display = 'block';


    var elems = document.querySelectorAll('.active.in');
    var elems_2 = document.querySelectorAll('.active');
    [].forEach.call(elems, function (el) {
        el.classList.remove('active', 'in');
    });
    [].forEach.call(elems_2, function (el) {
        el.classList.remove('active');
    });


    var el_7 = document.getElementById('top-signin');
    var el_8 = document.getElementById('signin');

    if (el_7 !== null) el_7.classList.add('active');
    if (el_8 !== null) el_8.classList.add('active', 'in');

};

controllers.register_fail = function (err) {
    var el_1 = document.getElementById('reg-success').style.display = 'none';
    var el_2 = document.getElementById('reg-error').style.display = 'block';
    if (el_1 !== null) el_1.style.display = 'none';
    if (el_2 !== null) el_2.style.display = 'block';
    utils.render('reg-error-reason', err.responseJSON.error.message);
};
