function register() {
  const registroGo = document.forms["RegistroGoVelez"];
  const passErrAlert = document.getElementById("passwordErrorAlert");
  if (registroGo["Password"].value === registroGo["Password2"].value) {
    registroGo.checkValidity();
  } else if (registroGo["Password"].value != registroGo["Password2"].value) {
    if (passErrAlert == null) {
      const passwordErrorAlert = document.createElement("div");
      passwordErrorAlert.id = "passwordErrorAlert";
      passwordErrorAlert.className = "alertBox";
      const alertBox = document.createElement("div");
      alertBox.className = "alertError";
      passwordErrorAlert.appendChild(alertBox);
      const closeAlertBtn = document.createElement("span");
      closeAlertBtn.className = "closeAlertBtn";
      alertBox.appendChild(closeAlertBtn);
      const alertBtn = "×"
      closeAlertBtn.append(alertBtn);
      const passwordErrorText = document.createTextNode("Las contraseñas no coinciden");
      alertBox.appendChild(passwordErrorText);
      document.getElementById("emailDiv").after(passwordErrorAlert);
      var closeAlert = document.getElementsByClassName("closeAlertBtn");
      var i;
      for (i = 0; i < closeAlert.length; i++) {
        closeAlert[i].onclick = function () {
          var childDiv = this.parentElement;
          div = childDiv.parentElement;
          div.style.opacity = "0";
          setTimeout(function () { div.style.display = "none"; }, 600);
        }
      }
    }
    if (passErrAlert != null && passErrAlert.style.opacity == 0 && passErrAlert.style.display == "none") {
      passErrAlert.removeAttribute(style);
    }
    return false;
  }
}

document.getElementById("teamSelection").addEventListener("change", getTeamName);
function getTeamName() {
  teamValue = null;
  teamList = document.getElementsByClassName('radioImg');
  for (i = 0; i < teamList.length; i++) {
    if (teamList[i].checked) {
      teamValue = teamList[i].value;
    }
  }
  document.getElementById("teamName").innerHTML = teamValue;
}

var closeAlert = document.getElementsByClassName("closeAlertBtn");
var i;
for (i = 0; i < closeAlert.length; i++) {
  closeAlert[i].onclick = function () {
    var childDiv = this.parentElement;
    div = childDiv.parentElement;
    div.style.opacity = "0";
    setTimeout(function () { div.style.display = "none"; }, 600);
  }
}