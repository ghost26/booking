templates.admin = function (data) {
    var content = `
      <div class="container">
          <div class="panel panel-default">
              <div id="admin-panel" class="panel-body">
                <div class="col-md-12">
                  <h5 id="admin-title">Панель управления отелями</h5>
                </div>
                <div id="admin-head-buttom" class="col-md-6 col-xs-12">
                  <button type="button" class="btn btn-success search_buttom" data-toggle="modal" data-target="#addHotel">Добавить отель</button>
                </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="addHotel" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Добавить отель</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" id="hotel-form" onsubmit="utils.createNewHotel(event)">
                      <fieldset>
                          <div id="hotel-error" class="alert alert-danger" role="alert" style="display: none">
                              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                              Ошибка : <p id="hotel-error-reason"></p>
                          </div>
                          <div class="control-group">
                              <label class="control-label" for="hotel-name">Название:</label>
                              <div class="controls">
                                  <input id="hotel-name" name="name" class="form-control" type="text" placeholder="Название отеля" class="input-large" required="">
                              </div>
                          </div>

                          <div class="control-group">
                              <label class="control-label" for="hotel-description">Описание:</label>
                              <div class="controls">
                                  <input id="hotel-description" name="desq" class="form-control" type="text" placeholder="Описание отеля" class="input-large" required="">
                              </div>
                          </div>

                          <div class="control-group">
                              <label class="control-label" for="hotel-countries">Страна:</label>
                              <div class="controls">
                                  <select id="hotel-countries" class="form-control" required="true" onchange="utils.getCities(this.value, 'admin_load_cities_success', 'admin_load_cities_fail')">
                                  <option selected disabled>Выберете страну</option>
                                  </select>
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label" for="hotel-cities">Город:</label>
                              <div class="controls">
                                <select class="form-control" required="true" id="hotel-cities">
                                <option selected disabled value="">Выберите город</option>
                                </select>
                              </div>
                          </div>

                          <div class="control-group">
                              <label class="control-label" for="hotel-address">Адрес:</label>
                              <div class="controls">
                                  <input id="hotel-address" name="address" class="form-control" type="text" placeholder="Введите полный адресс" class="input-large" required="">
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label" for="hotel-stars">Звезды:</label>
                              <div class="controls">
                                <select class="form-control" id = "hotel-stars">
                                  <option disabled>Выберите количество звезд</option>

                        `;
    for (var stars = 1; stars <= 5; stars++) {
        content += `
                                <option ` + ((stars == 3) ? 'selected ' : '') + `value="` + stars + `">` + stars + `</option>
                            `;
    }
    content += `
                                </select>
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label" for="add-new-hotel"></label>
                              <div class="controls">
                                <div class="modal-footer">
                                  <center>
                                    <button id="add-new-hotel" class="btn btn-success">Добавить</button>
                                    <button id="hotel-close-button"type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                                  </center>
                                </div>
                              </div>
                          </div>
                      </fieldset>
                  </form>
                </div>
            </div>

        </div>
    </div>
      <div class="container">
        <div class="row">
        `
    if (data.length == 0) {
        content = content + templates.empty_content('Вы не добавили ни одного отеля');
    }
    for (var i = 0; i < data.length; i++) {
        var hotel = data[i]
        content = content + `
              <div class="col-md-4 col-xs-12">
                <div class="thumbnail">
                  <div class="caption">
                    <h3>` + hotel.name + `</h3>
                    <span class="label label-warning">` + hotel.stars + ` <span class="glyphicon glyphicon-star" aria-hidden="true"></span></span>
                    <h5><c>` + hotel.description + `</c></h5>
                    <ul class="list-group">
                      <li class="list-group-item"><b>Адрес:</b> ` + hotel.address + `</li>
                    </ul>
                    <div class="">
                      <a href="#admin_bookings?hotel_id=` + hotel.id + `" class="btn btn-default btn-block" role="button">Бронирования</a>
                    </div>
                    <div class="">
                      <a id="button_add_room" href="#admin_hotel_page?hotel_id=` + hotel.id + `" class="btn btn-default btn-block" role="button">Комнаты</a>
                    </div>
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
