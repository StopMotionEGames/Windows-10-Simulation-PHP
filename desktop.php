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
          <!-- <div class="section">
            <div class="sectiontitle">Imagens com muitos bytes</div>
            <p>Essa janela contem imagens pesadas, para atrasar o carregamento do usuário.
              <li>
                Nota: Esse carregamento demorado é proposital, para simular o carregamento do "Sistema Operacional,
                Windows".
                <?php
                // echo php_uname();
                ?>
              </li>
            </p>
            <div class="images">
                <img src="src/images/Nasa-31-9MB.jpg" />
                <img src="src/images/Nasa-32MB.jpg" />
                <img src="src/images/Nasa-50MB.png" />
                <img src="src/images/Nasa-128MB.png" />
            </div>
          </div> -->
          <iframe src="/windows/ms-settings" frameborder="0" width="100%" height="100%"
            sandbox="allow-forms allow-modals allow-popups allow-presentation allow-same-origin allow-scripts allow-storage-access-by-user-activation allow-top-navigation allow-top-navigation-by-user-activation"></iframe>
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
      <div class="start-button button" id="strtactbtn" title="Iniciar" onclick="startMenuActivator()"></div>
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