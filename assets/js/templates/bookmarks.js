templates.bookmarks = function (data) {
    var content = `
    <div class="container">
        <div class="panel panel-default">
            <div id="admin-panel" class="panel-body">
              <div class="col-md-6 col-xs-12">
                <h5 id="bookmarks-title">Мои закладки</h5>
              </div>
            </div>
        </div>
    </div>
    <div class="container">
      <div class="row">
    `
    if (data.length == 0) {
        content = content + templates.empty_content('Вы не добавили ни одной закладки');
    }
    for (var i = 0; i < data.length; i++) {
        var city = data[i];
        var start_date = new Date(data[i].start_date * 1000)
        var end_date = new Date(data[i].end_date * 1000)
        content += `
        <div class="col-md-6 col-xs-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">` + data[i].hotel_name + `</h3>
            </div>
            <div class="panel-body">
              <ul class="list-group">
                <li class="list-group-item"><b>О отеле: </b>` + data[i].hotel_description + `</li>
                <li class="list-group-item"><b>Адрес: </b>` + data[i].hotel_address + `</li>
                <li class="list-group-item"><b>Звезд: </b>` + data[i].hotel_stars + `</li>
                <li class="list-group-item"><b>Количество гостей: </b>` + data[i].capacity + `</li>
                <li class="list-group-item"><b>Заезд: </b>` + start_date.toLocaleDateString() + `</li>
                <li class="list-group-item"><b>Выезд: </b>` + end_date.toLocaleDateString() + `</li>
              </ul>
            </div>
            <div class="panel-footer">
              <div class="row">
                <div class="col-md-4 col-md-offset-4">
                  <a href="#offers?hotel_id=` + data[i].hotel_id + `&start_date=` + data[i].start_date + `&end_date=` +
            data[i].end_date + `&capacity=` + data[i].capacity + `" class="btn btn-primary btn-block" role="button">Посмотреть</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        `;
    }
    ;
    content += `
      </div>
    </div>`
    return content;
};
