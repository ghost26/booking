templates.hello_text = function (data) {
    var content = `
    <div class="container">
    <div class="panel panel-default">
  <div class="panel-body">
        <div id="hello_text">
            <h2>Привет 🙂</h2>
            <p>
            Найдите Ваш идеальный отель по выгодной цене
            </p>
        </div>
        </div>
        </div>
        </div>
    `;

    return content;
};

templates.loader = function(data){
    var content = `
        <div id=loader>
            <img id=load-image src="assets/images/loader.gif">
        </div>
    `;
    return content;
};