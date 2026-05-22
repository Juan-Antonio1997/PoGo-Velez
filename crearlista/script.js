/* Añado un detector de evento para el elemento con ID "weatherSelection" para que, 
si cambia su valor (es un input tipo radio), ejecute la función "getWeather" */
document.getElementById("weatherSelection").addEventListener("change", getWeather);
/* Creo la función "getWeather" */
function getWeather() {
    /* Inicio una variable llamada "weatherValue", con valor nulo */
    weatherValue = null;
    /* Obtengo todos los elementos cuyo nombre sea "Tiempo_atmos" */
    weatherList = document.getElementsByName('Tiempo_atmos');
    /* Abro un bucle for para que, si el elemento con nombre "Tiempo_atmos" 
    fue seleccionado, obtener su valor */
    for (i = 0; i < weatherList.length; i++) {
        if (weatherList[i].checked) {
            weatherValue = weatherList[i].value;
        }
    }
    /* Escribo en el atributo con ID "weatherName" el valor de "weathervalue" */
    document.getElementById("weatherName").innerHTML = weatherValue;
}

/* Añado un detector de evento para el elemento con ID "bossSelection" para que, 
si cambia su valor (es un input tipo radio), ejecute la función "getBoss" */
document.getElementById("bossSelection").addEventListener("change", getBoss);
/* Creo la función "getBoss" */
function getBoss() {
    /* Inicio una variable llamada "bossValue", con valor nulo */
    bossValue = null;
    /* Obtengo todos los elementos cuyo nombre sea "ID_Raid" */
    bossList = document.getElementsByName('ID_Raid');
    /* Abro un bucle for para que, si el elemento con nombre "ID_Raid" 
    fue seleccionado, obtener el valor de su atributo "title" */
    for (i = 0; i < bossList.length; i++) {
        if (bossList[i].checked) {
            bossValue = bossList[i].title;
        }
    }
    /* Escribo en el atributo con ID "bossName" el valor de "bossValue" */
    document.getElementById("bossName").innerHTML = bossValue;
}

/* Obtengo los elementos que tengan como clase "closeAlertBtn" */
var closeAlert = document.getElementsByClassName("closeAlertBtn");
/* Inicio una variable para un bucle for */
var i;
/* Abro un bucle for desde 0 hasta el número de elementos con 
clase "closeAlertBtn" */
for (i = 0; i < closeAlert.length; i++) {
    /* Añado una función para cada elemento de forma que, si se 
    pulsa en ese elemento, seleccione su abuelo (el padre de su
    padre), lo oculte y le de como valor de opacidad 0 */
    closeAlert[i].onclick = function () {
        var childDiv = this.parentElement;
        div = childDiv.parentElement;
        div.style.opacity = "0";
        setTimeout(function () { div.style.display = "none"; }, 600);
    }
}