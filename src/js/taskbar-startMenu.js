let startMenu = document.getElementById("start-menu-f");
let startButton = document.querySelector(".start-button");
function startMenuActivator() {
  if (startMenu.classList.contains("unactived")) {
    startButton.classList.add("focus");
    startMenu.classList.replace("unactived", "actived");
    setTimeout(() => {
      startMenu.focus();
    }, 10);

  } else if (startMenu.classList.contains("actived")) {
    startButton.classList.remove("focus");
    startMenu.classList.replace("actived", "unactived");
  }
}
function startMenuUnactivator() {
  const isMouseOverButton = startButton.matches(":hover");
  if (!isMouseOverButton) {
    startButton.classList.remove("focus");
    startMenu.classList.replace("actived", "unactived");
  }
}