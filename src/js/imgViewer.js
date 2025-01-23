var modal = document.querySelector("#imgViewer");
var modalImg = document.getElementById("modalImg");
var images = document.querySelectorAll("img.canDisplay");
var span = document.querySelector(".closeImg");
var fullscreenBtn = document.querySelector(".wmaximize");
var zoomInBtn = document.querySelector(".zoom-in");
var zoomOutBtn = document.querySelector(".zoom-out");
images.forEach((image) => {
  image.onclick = function () {
    modal.style.opacity = 1;
    modal.style.visibility = "visible";
    modalImg.src = this.src;
  }
});

span.onclick = () => {
  modal.style.opacity = 0;
  modal.style.visibility = "hidden";
  if (modal.classList.contains("fullScrned")) {
    fullScrn();
  }
}

modal.onclick = (event) => {
  if (event.target === modal) {
    modal.style.opacity = 0;
    modal.style.visibility = "hidden";
  }
}

fullscreenBtn.onclick = () => {
  fullScrn();
}
function fullScrn() {
  if (!document.fullscreenElement) {
    modal.requestFullscreen();
    modal.classList.add("fullScrned");
  } else {
    document.exitFullscreen();
    modal.classList.remove("fullScrned");
    modalImg.style.width = null;
  }
}
zoomInBtn.onclick = () => {
  var currentWidth = modalImg.clientWidth;
  modalImg.style.width = (currentWidth * 1.2) + "px";
}

zoomOutBtn.onclick = () => {
  var currentWidth = modalImg.clientWidth;
  modalImg.style.width = (currentWidth / 1.2) + "px";
}
document.addEventListener("fullscreenchange", () => {
  if (!document.fullscreenElement) {
    modal.classList.remove("fullScrned");
    modalImg.style.width = null;
  }
});