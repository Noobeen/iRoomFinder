$(document).ready(function () {
  $("button").click(function () {});
});

const getData = (value) => {
  return new Promise((res, rej) => {
    var request = $.ajax({
      url: `https://openweathermap.org/data/2.5/find?q=${value}&type=like&sort=population&cnt=30&appid=439d4b804bc8187953eb36d2a8c26a02`,
      type: "GET",
      dataType: "json",
    });
    request.done((msg) => {
      res(msg);
    });

    request.fail((jqXHR, textStatus) => {
      rej("Request failed: " + textStatus);
    });
  });
};

const getCelcius = (temp) => {
    let conversionC = temp - 273;
    conversionC = conversionC * 9 / 5 + 32;
    conversionC = (conversionC-32)/1.8
    return parseFloat(conversionC).toFixed(2);
}

const FindCity = async (value) => {
  const search = document.getElementById("search_str");
  try {
    const result = await getData(search.value);
    const html = result.list.map((city) => {
      return `<tr>
          <td>
            <img
              src="http://openweathermap.org/img/wn/${
                city.weather[0].icon
              }@2x.png"
              width="50"
              height="50"
            />
          </td>
          <td>
            <b>
              <a href="/city/1255364"> ${city.name}, ${city.sys.country}</a>
            </b>
            <img src="http://openweathermap.org/images/flags/${city.sys.country.toLowerCase()}.png" />
            <b>
              <i> ${city.weather[0].description}</i>
            </b>
            <p>
              <span class="badge badge-info">${getCelcius(city.main.temp)}°С </span> temperature from ${getCelcius(city.main.temp_min)} to
              ${getCelcius(city.main.temp_max)} °С, wind ${city.wind.speed} m/s.  clouds ${
        city.clouds.all
      } %, ${city.main.pressure} hpa
            </p>
            <p>
              Geo coords
              <a href="https://openweathermap.org/weathermap?zoom=12&amp;lat=${
                city.coord.lat
              }&amp;lon=${city.coord.lon}">
                [${city.coord.lat}, ${city.coord.lon}]
              </a>
            </p>
          </td>
        </tr>`;
    });
    $("#forecast_list_ul").html(html);
  } catch (e) {
    console.log(e);
  }
};
