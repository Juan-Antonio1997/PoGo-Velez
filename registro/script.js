/* Creo una función "register" para comprobar la validación de mi registro */
function register() {
  /* Creo una variable para el formulario con id "RegistroGoVelez" */
  const registroGo = document.forms["RegistroGoVelez"];
  if (registroGo["Password"].value === registroGo["Password2"].value) {
    /* Si el valor de las contraseñas son iguales, compruebo la validez del 
    formulario (asegurando que se respeten las reglas establecidas en el HTML) */
    registroGo.checkValidity();
  } else if (registroGo["Password"].value != registroGo["Password2"].value) {
    /* Si el valor de las contraseñas no son iguales, obtengo el elemento cuyo 
    ID sea "passwordErrorAlert" */
    const passErrAlert = document.getElementById("passwordErrorAlert");
    if (passErrAlert == null) {
      /* Si el valor del elemento "passwordErrorAlert" es nulo, creo un elemento 
      de tipo div */
      const passwordErrorAlert = document.createElement("div");
      /* Le asigno la ID "passwordErrorAlert" */
      passwordErrorAlert.id = "passwordErrorAlert";
      /* Y le asigno la clase "alertBox" */
      passwordErrorAlert.className = "alertBox";
      /* Creo otro elemento div */
      const alertBox = document.createElement("div");
      /* Le asigno la clase "alertError" */
      alertBox.className = "alertError";
      /* Y hago que el segundo div sea descendiente del primero */
      passwordErrorAlert.appendChild(alertBox);
      /* Creo un elemento tipo span */
      const closeAlertBtn = document.createElement("span");
      /* Le asigno la clase "closeAlertBtn" */
      closeAlertBtn.className = "closeAlertBtn";
      /* Hago que ese span sea descendiente del segundo div */
      alertBox.appendChild(closeAlertBtn);
      /* Creo una variable cuyo valor sea la "x" que usé en otras alertas */
      const alertBtn = "×"
      /* Lo introduzco en el span */
      closeAlertBtn.append(alertBtn);
      /* Creo un nodo de texto con el mensaje de error */
      const passwordErrorText = document.createTextNode("Las contraseñas no coinciden");
      /* Muevo ese texto dentro del segundo div (después del span) */
      alertBox.appendChild(passwordErrorText);
      /* Coloco esa alerta después del div del input del correo electrónico que 
      hay en el formulario */
      document.getElementById("emailDiv").after(passwordErrorAlert);
      /* Le doy funcionalidad a la alerta. Primero obtengo los elementos que 
      tengan como clase "closeAlertBtn" */
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
    }
    if (passErrAlert != null && passErrAlert.style.opacity == 0 && passErrAlert.style.display == "none") {
      /* Si el valor del elemento "passwordErrorAlert" no es nulo, su opacidad es 0 y 
      está oculto, le quito el atributo style, dejándolo como antes */
      passErrAlert.removeAttribute(style);
    }
    /* Como las contraseñas no coinciden, hago que devuelva "false" para que no se envíen 
    los datos del formulario a la base de datos */
    return false;
  }
}

/* Añado un detector de evento para el elemento con ID "teamSelection" para que, 
si cambia su valor (es un input tipo radio), ejecute la función "getTeamName" */
document.getElementById("teamSelection").addEventListener("change", getTeamName);
/* Creo la función "getTeamName" */
function getTeamName() {
  /* Inicio una variable llamada "teamValue", con valor nulo */
  teamValue = null;
  /* Obtengo todos los elementos cuya clase sea "radioImg" (no hay otro conjunto 
  de elementos input tipo radio) con esa clase en la página de registro */
  teamList = document.getElementsByClassName('radioImg');
  /* Abro un bucle for para que, si el elemento con nombre "radioImg" 
  fue seleccionado, obtener su valor */
  for (i = 0; i < teamList.length; i++) {
    if (teamList[i].checked) {
      teamValue = teamList[i].value;
    }
  }
  /* Escribo en el atributo con ID "teamName" el valor de "teamValue" */
  document.getElementById("teamName").innerHTML = teamValue;
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