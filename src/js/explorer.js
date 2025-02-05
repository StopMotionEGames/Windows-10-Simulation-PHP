let taskbar = document.querySelector(".taskbar");
let batteryNotification = taskbar.querySelector("#battery");
let batteryIcon = batteryNotification.querySelector(".icon span");
let startMenu = document.getElementById("start-menu-f");
let startButton = document.querySelector(".start-button");
let battryLevel;
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

function batteryStatusManager() {
  navigator.getBattery().then((battery) => {
    battryLevel = `${battery.level * 100}%`;
    function updateBatteryStatus() {
      console.log(`Battery level: ${battery.level * 100}%`);
      console.log(`Battery charging: ${battery.charging ? "Yes" : "No"}`);
      let batteryLevelRounded = Math.floor(battery.level * 10);
      if (battery.charging) {
        batteryNotification.title = `Status da bateria: ${battryLevel} disponível (conectada)`;
        batteryIcon.id = `BatteryCharging${batteryLevelRounded}`;
      } else {
        batteryNotification.title = `Status da bateria: ${battryLevel} disponível`;
        batteryIcon.id = `Battery${batteryLevelRounded}`;
      }
    }

    updateBatteryStatus();

    battery.addEventListener('levelchange', updateBatteryStatus);
    battery.addEventListener('chargingchange', updateBatteryStatus);
  });

  if ('getBattery' in navigator) {
    navigator.getBattery().then((battery) => {
      function updatePowerSaveStatus() {
        console.log(`Battery saver mode: ${battery.powerSaveMode ? "Enabled" : "Disabled"}`);
        if (battery.powerSaveMode) {
          batteryNotification.title += " (Modo economia de bateria ativado)";
        }
      }

      updatePowerSaveStatus();

      battery.addEventListener('powersavemodechange', updatePowerSaveStatus);
    });
  }
}

batteryStatusManager();