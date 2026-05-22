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