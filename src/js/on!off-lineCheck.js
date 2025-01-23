var sMonth = 7; //0 = Junary | 11 = December
var sDay = 5;
var eMonth = 11; //0 = Junary | 11 = December
var eDay = 16;

var crntD = new Date(), dayOfWeek = crntD.getDay(), crtnH = crntD.getHours(), crtnM = crntD.getMinutes();
var sDate = new Date(crntD.getFullYear(), sMonth, sDay), eDate = new Date(crntD.getFullYear(), eMonth, eDay);

var targetD = 3, //Target Day of the Week - 0 Sunday
  targetH = 10, // target Hour
  targetFM = 0, // Taget First Minute of the hour
  targetLM = 29; // Taget Last Minute of the hour
var progress = document.querySelector(".checkingIf"), progressBarHTML = '<progressbarI><div class="two"></div><div class="two"></div><div class="two"></div><div class="two"></div><div class="two"></div></progressbarI>', p = document.querySelector(".checkedText"), button = document.querySelector(".onOffChecker");
let chkIcon = document.querySelector(".vdlUpdate");
function offLine() {
  button.classList.add("hide");
  progress.innerHTML = progressBarHTML;
  setTimeout(function () {
    p.innerText = 'A venda está off-line';
    progress.innerHTML = '';
    chkIcon.id = 'NetworkOffline'
    button.classList.remove("hide");
  }, Math.floor(Math.random() * 11258 + 6849));
}
function onLine() {
  button.classList.add("hide");
  progress.innerHTML = progressBarHTML;
  setTimeout(function () {
    p.innerText = 'A venda está on-line';
    progress.innerHTML = '';
    chkIcon.id = 'CheckMark'
    button.classList.remove("hide");
  }, Math.floor(Math.random() * 11258 + 6849));
}
function checkIfOnLine() {
  chkIcon.id = 'Sync';
  p.innerHTML = 'Verificando se estamos on-line...';
  if (crntD < sDate || crntD >= eDate) {
    offLine();
  } else {
    if (dayOfWeek !== targetD) {
      offLine();
    } else {
      if (crtnH === targetH && crtnM >= targetFM && crtnM <= targetLM) {
        onLine();
      } else {
        offLine();
      }
    }
  }
}