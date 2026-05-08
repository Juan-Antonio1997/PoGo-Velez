document.getElementById("weatherSelection").addEventListener("change", getWeather);

function getWeather() {
    weatherValue = null;
    weatherList = document.getElementsByName('Tiempo_atmos');
    for (i = 0; i < weatherList.length; i++) {
        if (weatherList[i].checked) {
            weatherValue = weatherList[i].value;
        }
    }
    document.getElementById("weatherName").innerHTML = weatherValue;
}

document.getElementById("bossSelection").addEventListener("change", getBoss);

function getBoss() {
    bossValue = null;
    bossList = document.getElementsByName('ID_Raid');
    for (i = 0; i < bossList.length; i++) {
        if (bossList[i].checked) {
            bossValue = bossList[i].title;
        }
    }
    document.getElementById("bossName").innerHTML = bossValue;
}