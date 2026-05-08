function register() {
  const registroGo = document.forms["RegistroGoVelez"];
  if (registroGo["Password"].value === registroGo["Password2"].value) {
    registroGo.checkValidity();
  } else if (registroGo["Password"].value != registroGo["Password2"].value) {
    if (document.getElementById("error") == null) {
      let error = document.createElement("div");
      error.id = "error"
      registroGo["Password2"].after(error);
      error.append("Las contraseñas no coinciden");
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