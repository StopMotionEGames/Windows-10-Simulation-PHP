<div class="dwm">
  <div id="window-fill"></div>
  <div class="window ms_settings">
    <div class="titbar">
      <img src="src/icons/?icon=ms-settings&dynamic=true" class="icon">
      <p>Configurações</p>
      <div>
        <a class="a wbtg" onclick="minwin('ms_settings')">–</a>
        <a class="a wbtg max" onclick="maxwin('ms_settings')">▢</a>
        <a class="a wbtg red" onclick="hidewin('ms_settings')">&times;</a>
      </div>
    </div>
    <div class="content">
      <iframe frameborder="0" src="" width="100%" height="100%"></iframe>
    </div>
  </div>
  <div class="window explorer">
    <div class="titbar">
      <img src="src/icons/?icon=ms-settings&dynamic=true" class="icon">
      <p>Configurações</p>
      <div>
        <a class="a wbtg" onclick="minwin('explorer')">–</a>
        <a class="a wbtg max" onclick="maxwin('explorer')">▢</a>
        <a class="a wbtg red" onclick="hidewin('explorer')">&times;</a>
      </div>
    </div>
    <div class="content">
      <iframe frameborder="0" src="" width="100%" height="100%"></iframe>
    </div>
  </div>
</div>
<div class="desktop">
  <div class="start-menu unactived" id="start-menu-f" onblur="startMenuUnactivator()" tabindex="-1">
    <div class="blur"></div>
    <div class="grain"></div>
    <div class="actions-buttons">
      <div class="power-menu-activator" id="strtactbtn">
        <div class="icon">
          <span id="PowerButton" class="start-action-button"></span>
        </div>
      </div>
      <div class="logout" id="strtactbtn" onclick="window.location.href = 'logout.php'">
        <div class="icon">
          <span id="Bluetooth" class="start-action-button"></span>
        </div>
      </div>
    </div>
    <div class="varied-content"></div>
  </div>
  <div class="taskbar">
    <div class="blur"></div>
    <div class="grain"></div>
    <div class="left">
      <div class="start-button button" id="strtactbtn" title="Iniciar" onclick="startMenuActivator()">
        <svg xmlns="https://www.w3.org/TR/SVG2" viewBox="0 0 48 48">
          <path fill="#0a78d4"
            d="M20 23H1V7.6L20 5v18Zm27 0H22V4.6L47 1v22Zm-27 2H1v16l1sss9 2V25Zm2 0h25v21.5l-25-3V25Z" />
        </svg>
      </div>
      <div class="icons">
        <div class="explorer taskbar-app" onclick="openapp('explorer')"><img src="/src/icons/?icon=explorer"></div>
        <div class="msedge taskbar-app"><img src="/src/icons/?icon=msedge"></div>
        <div class="ms-settings taskbar-app" onclick="openapp('ms_settings')"><img
            src="/src/icons/?icon=ms-settings&dynamic=true"></div>
      </div>
    </div>
    <div class="right">
      <div class="taskbar-notification">
        <div id="battery">
          <div class="icon">
            <span id="Battery10" class="taskbar-notif-icon"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', (event) => {
    // Submissão do formulário via AJAX
    const settingsForm = document.getElementById('settingsForm');
    settingsForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(settingsForm);
      fetch('settings.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(html => {
          document.getElementById('desktop-windows').innerHTML += html;
        });
    });

    // Carregar script dinamicamente após clicar em ms-settings
    document.querySelector('.ms-settings').addEventListener('click', function () {
      fetch('settings.php')
        .then(response => response.text())
        .then(html => {
          document.getElementById('desktop-windows').innerHTML += html;
          addWindowFunctionality(); // Adicionar funcionalidade após carregar conteúdo
        });
    });
  });
</script>