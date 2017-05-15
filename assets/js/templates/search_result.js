templates.search_result = function (data, start_date, end_date, capacity) {
    var content = ` `
    if (data.length == 0) {
        content = content + templates.empty_content('Предложений не найдено');
    }
    for (var i = 0; i < data.length; i++) {
        var result = data[i]
        content = content + `
              <div class="col-md-4 col-xs-12">
                <div class="thumbnail">
                  <div class="caption"> <a id="add_bookmarks" name="bookmarks-icon" onclick="utils.addBookmark(` + result.hotel_id + `,` + start_date + `,` + end_date + `,` + capacity + `)" title="Добавить в закладки" style="cursor: pointer; display: ` + ((isAuthorized && user.type === 'customer') ? 'block' : 'none') + `"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span></a>

                    <h3>` + result.hotel_name + `</h3>
                    <span class="label label-warning">` + result.stars + ` <span class="glyphicon glyphicon-star" aria-hidden="true"></span></span>
                    <h5><c>` + result.hotel_description + `</c></h5>
                    <ul class="list-group">
                      <li class="list-group-item"><b>Цена:</b> от ` + result.min_value + ` до ` + result.max_value + `</li>
                      <li class="list-group-item"><b>Адрес:</b> ` + result.hotel_address + `</li>
                    </ul>
                    <a href="#offers?hotel_id=` + result.hotel_id + `&start_date=` + start_date + `&end_date=` +
            end_date + `&capacity=` + capacity + `" class="btn btn-primary btn-block" role="button">Просмотреть ` + result.cnt
            + ` ` + utils.getTitle(result.cnt, ['предложение', 'предложения', 'предложений']) + `</a>
                  </div>
                </div>
              </div>
            `;
    }
    return content;

};
