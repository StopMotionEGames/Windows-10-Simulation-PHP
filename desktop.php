<!-- Desktop -->
<div class="desktop">
  <div id="desktop-windows">
    <!-- Janelas de Area de Trabalho -->
    <div class="window">
      <div class="resizer corner tl"></div>
      <div class="resizer corner tr"></div>
      <div class="resizer corner bl"></div>
      <div class="resizer corner br"></div>
      <div class="resizer t"></div>
      <div class="resizer b"></div>
      <div class="resizer l"></div>
      <div class="resizer r"></div>

      <div class="wbody uwp">
        <div class="wtopbar">
          <div class="wtitle">Configurações</div>
          <div class="wbtns">
            <div class="wminimize">&minus;</div>
            <div class="wmaximize">&square;</div>
            <div class="wclose">&times;</div>
          </div>
        </div>
        <div class="wcontent">
          <iframe src="/windows/ms-settings" frameborder="0" width="100%" height="100%"></iframe>
        </div>
      </div>
    </div>
  </div>
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
      <div class="start-button button" id="strtactbtn" title="Iniciar" onclick="startMenuActivator()"><svg
          xmlns="https://www.w3.org/TR/SVG2" viewBox="0 0 48 48">
          <path fill="#0a78d4"
            d="M20 23H1V7.6L20 5v18Zm27 0H22V4.6L47 1v22Zm-27 2H1v16l1sss9 2V25Zm2 0h25v21.5l-25-3V25Z" />
        </svg>
      </div>
      <div class="icons">
        <div class="explorer taskbar-app"><img src="/src/icons/?icon=explorer"></div>
        <div class="msedge taskbar-app"><img src="/src/icons/?icon=msedge"></div>
        <div class="ms-settings taskbar-app"><img src="/src/icons/?icon=ms-settings&dynamic=true"></div>
      </div>
    </div>
    <div class="right"></div>
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
          addWindowFunctionality(); // Reaplicar funcionalidade após atualizar conteúdo
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
    addWindowFunctionality();
  });
</script>