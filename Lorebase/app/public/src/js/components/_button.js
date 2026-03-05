document.addEventListener("DOMContentLoaded", function (e) {
  document.querySelectorAll(".button").forEach(function (elem) {
    elem.addEventListener("click", function (ev) {
      console.log("Clic sur le bouton : ", elem);
    });
  });
});
