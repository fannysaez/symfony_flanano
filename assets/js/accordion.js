var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function () {
    this.classList.toggle("active-accordion");

    var panel = this.nextElementSibling;
    if ((panel.style.display === "flex", panel.style.maxHeight)) {
      panel.style.display = "none";
      panel.style.maxHeight = null;
    } else {
      panel.style.display = "flex";
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}
