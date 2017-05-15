templates.offers = function (data, params) {
    var start_date = new Date(params.start_date * 1000).toLocaleDateString();
    var end_date = new Date(params.end_date * 1000).toLocaleDateString();
    var content = `
    <div class="container">
        <div class="panel panel-default">
            <div id="admin-panel" class="panel-body">
              <div class="col-md-12">
                <h5 id="bookmarks-title">Доступные комнаты в <span id="bookmarks-hotel-name"></span> c ` + start_date + ` по ` + end_date + `</h5>
              </div>
            </div>
        </div>
    </div>
    <div class="container">
      <div class="row">`;

    if (data.length == 0) {
        content = content + templates.empty_content('Предложений не найдено в этом отеле');
    }
    for (var i = 0; i < data.length; i++) {
        var offer = data[i];
        content += `
          <div class="col-md-6 col-xs-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Комната ` + (i + 1) + `</h3>
              </div>
              <div class="panel-body">
                  <li class="list-group-item"><b>Название: </b>` + data[i].title + `</li>
                  <li class="list-group-item"><b>Описание: </b>` + data[i].description + `</li>
                  <li class="list-group-item"><b>Количество гостей: </b>` + data[i].capacity + `</li>
              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-md-12">
                    <a class="btn btn-success btn-block" role="button" style="display: ` + (((!isAuthorized) || (isAuthorized && user.type == 'customer')) ? 'block;' : 'none;') + `" onclick="utils.bookRoom(` + params.start_date + `,` + params.end_date + `,` + data[i].id + `)">Забронировать за ` + data[i].price + ` ` + utils.getTitle(data[i].price, ['рубль', 'рубля', 'рублей']) + `</a>
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
