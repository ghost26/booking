templates.cities = function (data) {
    var content = `<option selected disabled value="">Выберите город</option>`;
    for (var i = 0; i < data.length; i++) {
        var city = data[i]
        content += `
            <option value="` + city.city_id + `">` + city.name + `</option>
        `;
    }

    return content;

};
