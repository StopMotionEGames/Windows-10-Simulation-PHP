var htmlID = document.querySelector("html");

var homeB = document.querySelector(".homeb")
var general = document.querySelector(".general");
var info = document.querySelector(".info");
var b3 = document.querySelector(".b3");
var b4 = document.querySelector(".b4");
var b5 = document.querySelector(".b5");
var b6 = document.querySelector(".b6");
var b7 = document.querySelector(".b7");
var b8 = document.querySelector(".b8");

function setSbIndicator() {
  switch (htmlID.id) {
    case "homeH":
      homeB.id = "tr"
      homeB.parentElement.onclick = null;
      break;
    case "general":
      general.id = "tr";
      general.parentElement.onclick = null;
      break;
    case "infoH":
      info.id = "tr";
      info.parentElement.onclick = null;
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