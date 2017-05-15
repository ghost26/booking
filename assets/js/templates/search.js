templates.search = function (data) {
    var content = `
    <div class="search-form">
      <div class="container">
    <div id="panel-search" class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Поиск</h2>
        </div>
        <div id="search">
          <div class="panel-body">
              <div class="row">
          <form data-toggle="validator" role="form">
          <div class="form-group">
            <div class="col-md-12">
              <div id="search-error" class="alert alert-danger" role="alert" style="display: none">
                                          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                          <span class="sr-only">Ошибка валидации:</span>
                                          Выберите страну и город!
                                        </div>
            </div>

            <div class="col-xs-12 col-md-6">
              <select id="select-type" class="form-control" required="true"  onchange="utils.getCities(this.value, 'home_page_load_cities', 'home_page_error')">
              <option selected disabled>Выберете страну</option>
    `;
    for (var i = 0; i < data.length; i++) {
        var country = data[i]
        content += `
            <option value="` + country.id + `">` + country.name + `</option>
        `;
    }
    content += `
              </select>
            </div>
            <div class="col-xs-12 col-md-6">
              <select class="form-control" required="true" id="cities_list">
              <option selected disabled value="">Выберите город</option>
              </select>
            </div>
            <div class="col-md-5 col-xs-12">
              <h5>Дата заезда</h5>
              <div class="form-group">
                <div class="input-group date" id="datetimepicker">
                  <input id="dateFrom" type="text" required="true" class="form-control" />	<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
                </div>
              </div>
            </div>
            <div class="col-md-5 col-xs-12">
              <h5>Дата выезда</h5>
              <div class="form-group">
                <div class="input-group date" id="datetimepicker2">
                  <input id="dateTo" type="text" required="true" class="form-control" />	<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-xs-12">
              <h5>Кол-во человек</h5>
              <select class="form-control" id = "capacity">
                <option disabled>Выберите количество гостей</option>
                <option value="1">1</option>
                <option selected value="2">2</option>
      `;
    for (var capacity = 3; capacity <= 10; capacity++) {
        content += `
              <option value="` + capacity + `">` + capacity + `</option>
          `;
    }
    content += `
              </select>
            </div>
            </form>
          </div>
        </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-success search_buttom btn-block" onclick="utils.runSearch(document.getElementById('cities_list').value, document.getElementById('dateFrom').value, document.getElementById('dateTo').value, document.getElementById('capacity').value)">Найти</button>
            </div>
          </div>
        </div>
</div>
    </div>
  </div>
        <div id="search_result">

        </div>
    `;

    return content;

};
