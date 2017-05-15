templates.bookings = function (data) {
    var content = `
  <div class="container">
      <div class="panel panel-default">
          <div id="admin-panel" class="panel-body">
            <div class="col-md-6 col-xs-12">
              <h5 id="bookmarks-title">Мои бронирования</h5>
            </div>
          </div>
      </div>
  </div>
  <div class="container">
    <div class="row">
  `
    if (data.length == 0) {
        content = content + templates.empty_content('Вы не забронировали ни одного отеля');
    }
    for (var i = 0; i < data.length; i++) {
        var bookings = data[i];
        var create_date = new Date(data[i].created_at * 1000).toLocaleDateString();
        var start_date = new Date(data[i].start_date * 1000).toLocaleDateString();
        var end_date = new Date(data[i].end_date * 1000).toLocaleDateString();
        content += `
      <div class="col-md-6 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">` + data[i].hotel_name + `</h3>
          </div>
          <div class="panel-body">
            <ul class="list-group">
              <li class="list-group-item"><b>Информация: </b>Бронирование №` + data[i].id + ` от ` + create_date + `</li>
              <li class="list-group-item"><b>Дата заезда: </b>` + start_date + `</li>
              <li class="list-group-item"><b>Дата выезда: </b>` + end_date + `</li>
              <li class="list-group-item"><b>Адресс: </b>` + data[i].hotel_address + `</li>
            </ul>
          </div>
        </div>
      </div>
      `;
    }
    content += `
    </div>
  </div>`
    return content;

};
