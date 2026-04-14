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
    //setTimeout(document.getElementById("error").remove(), 5000);
    } //else {
        //setTimeout(document.getElementById("error").remove(), 5000);
    //}
    return false;
  }
}