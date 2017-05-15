templates.empty_content = function (msg) {
    content = `<div class="container">
                    <div class="panel panel-default">
                        <div class="panel-body">` + msg +
        `</div>
                    </div> 
                </div>`

    return content;
};