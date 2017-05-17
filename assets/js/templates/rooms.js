templates.rooms = function (data, params) {
    var content = `
      <div class="container">
          <div class="panel panel-default">
              <div id="admin-panel" class="panel-body">
                <div class="col-md-6 col-xs-6">
                  <h5 id="admin-title">Управление отеля <span id="rooms-hotel-name"></span></h5>
                </div>
                <div id="admin-head-buttom" class="col-md-6 col-xs-6" >
                  <button type="button" class="btn btn-success search_buttom" data-toggle="modal" data-target="#addRoom">Добавить комнату</button>
                </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="addRoom" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Добавить комнату</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" id="room-form" onsubmit="utils.createNewRoom(event` + `,` + params.hotel_id + `)">
                      <fieldset>
                          <div class="control-group">
                              <label class="control-label" for="room-title">Название:</label>
                              <div class="controls">
                                  <input id="room-title" name="title" class="form-control" type="text" placeholder="Название комнаты" class="input-large" required="">
                              </div>
                          </div>

                          <div class="control-group">
                              <label class="control-label" for="room-description">Описание:</label>
                              <div class="controls">
                                  <input id="room-description" name="desq" class="form-control" type="text" placeholder="Описание комнаты" class="input-large" required="">
                              </div>
                          </div>

                          <div class="control-group">
                              <label class="control-label" for="room-price">Цена:</label>
                              <div class="controls">
                                  <input id="room-price" name="address" class="form-control" type="number" placeholder="Введите цену" class="input-large" required="">
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label" for="room-capacity">Вместимость:</label>
                              <div class="controls">
                                <select class="form-control" id = "room-capacity">
                                  <option disabled>Укажите вместимость</option>

                        `;

    for (var capacity = 1; capacity <= 10; capacity++) {
        content += `<option ` + ((capacity == 2) ? 'selected ' : '') + `value="` + capacity + `">` + capacity + `</option>`;
    }
    content += `
                                </select>
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label" for="add-new-room"></label>
                              <div class="controls">
                                <div class="modal-footer">
                                  <div style="text-align: center;">
                                    <button id="add-new-room" class="btn btn-success">Добавить</button>
                                    <button id="room-close-button"type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                                  </div>
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
        content = content + templates.empty_content('Вы еще не добавили ни одной комнаты в Ваш отель');
    }
    for (var i = 0; i < data.length; i++) {
        var room = data[i]
        content = content + `
              <div class="col-md-4 col-xs-12">
                <div class="thumbnail">
                  <div class="caption">
                    <h3>` + room.title + `</h3>
                    <h5><c>` + room.description + `</c></h5>
                    <ul class="list-group">
                      <li class="list-group-item"><b>Цена:</b> ` + room.price + `</li>
                      <li class="list-group-item"><b>Вместимость:</b> ` + room.capacity + `</li>
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
