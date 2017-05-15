templates.countries = function (data) {
    var content = `<option selected disabled value="">Выберите страну</option>`;
    for (var i = 0; i < data.length; i++) {
        var country = data[i];
        content += `
            <option value="` + country.id + `">` + country.name + `</option>
        `;
    }

    return content;

};
