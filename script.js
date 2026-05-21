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