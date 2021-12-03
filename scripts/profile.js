var profile_displayed = 0;

function toggleProfile() {
  if(document.getElementById("profile-menu").style.display == "none") {
    document.getElementById("profile-menu").style.display = "flex";
    profile_displayed = 1;
  }
  else {
    document.getElementById("profile-menu").style.display = "none";
    profile_displayed = 0;
  }
}

document.addEventListener("click", (event) => {
  var profile_menu = document.getElementById("profile-menu").contains(event.target);
  var profile_icon_button = document.getElementById("profile-icon-button").contains(event.target);
  if(!profile_menu && !profile_icon_button) document.getElementById("profile-menu").style.display = "none";
});