var fileSelect = document.getElementById("fileSelect"),
  up_avatar_file = document.getElementById("up_avatar_file");

fileSelect.addEventListener("click", function (e) {
  if (up_avatar_file) {
    up_avatar_file.click();
  }
  e.preventDefault(); // empêche la navigation vers "#"
}, false);