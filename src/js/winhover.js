var color1;
var color2;
var color3;
var color4;
setInterval(() => {
  if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
    color1 = "#ffffff33";
    color2 = "#ffffff00";
    color3 = "#ffffffb3";
    color4 = "#ffffff1a";
  }
  else {
    color1 = "#0000000f";
    color2 = "#ffffff00";
    color3 = "#000000a2";
    color4 = "#00000015";
  }
}, 66.66);

document.querySelectorAll(".btn").forEach((b) => {
  b.onmouseleave = (e) => {
    e.target.style.background = null;
    e.target.style.borderImage = null;
  };

  b.addEventListener("mousemove", (e) => {
    const rect = e.target.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    e.target.style.background = `radial-gradient(circle at ${x}px ${y}px , ${color1},${color2} )`;
    e.target.style.borderImage = `radial-gradient(20% 75% at ${x}px ${y}px ,${color3},${color4} ) 1 / 1px / 0px stretch `;
  });
});