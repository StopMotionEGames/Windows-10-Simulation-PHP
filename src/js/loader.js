window.addEventListener("load", () => {
 const loadPanel = document.querySelector("progressbarI");
 loadPanel.classList.add("hide");
 loadPanel.addEventListener("trabsitionend", () => {
  document.body.removeChild(loadPanel);
 });
})