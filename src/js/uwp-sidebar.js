var htmlID = document.querySelector("html");

var b0 = document.querySelector(".b0")
var b1 = document.querySelector(".b1");
var b2 = document.querySelector(".b2");
var b3 = document.querySelector(".b3");
var b4 = document.querySelector(".b4");
var b5 = document.querySelector(".b5");
var b6 = document.querySelector(".b6");
var b7 = document.querySelector(".b7");
var b8 = document.querySelector(".b8");

function setSbIndicator() {
  switch (htmlID.id) {
    case "h0":
      b0.id = "tr"
      b0.parentElement.onclick = null;
      break;
    case "h1":
      b1.id = "tr";
      b1.parentElement.onclick = null;
      break;
    case "h2":
      b2.id = "tr";
      b2.parentElement.onclick = null;
      break;
    case "h3":
      b3.id = "tr";
      b3.parentElement.onclick = null;
      break;
    case "h4":
      b4.id = "tr"
      b4.parentElement.onclick = null;
      break;
    case "h5":
      b5.id = "tr";
      b5.parentElement.onclick = null;
      break;
    case "h6":
      b6.id = "tr";
      b6.parentElement.onclick = null;
      break;
    case "h7":
      b7.id = "tr";
      b7.parentElement.onclick = null;
      break;
    case "h8":
      b8.id = "tr";
      b8.parentElement.onclick = null;
      break;
  }
}
setSbIndicator();