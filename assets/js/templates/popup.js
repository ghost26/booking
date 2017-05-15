templates.popup_success = function (message) {
    if (typeof message != 'string') {
        message = '';
    }
    var content = `
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>OK! </strong>` + message + `</div>
    `;

    return content;
};

templates.popup_error = function (message) {
    if (typeof message != 'string') {
        message = '';
    }
    var content = `
    <div class="alert alert-warning alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Ошибка! </strong>` + message + `</div>
    `;

    return content;
};
