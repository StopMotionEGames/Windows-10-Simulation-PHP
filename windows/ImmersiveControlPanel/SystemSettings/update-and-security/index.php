<?php
require_once "../../../../functions/get-root.php"
?>
<!DOCTYPE html>
<html lang="pt-br" id="h0">

  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta charset="UTF-8">
    <script defer src="/src/js/loader.js"></script>
    <script defer src="/src/js/ourFunctions.js"></script>
    <script defer src="/src/js/uwp-sidebar.js"></script>
    <script defer src="/src/js/winhover.js"></script>
    <link rel="stylesheet" href="/src/css/uwp-app.css">
    <link rel="stylesheet" href="/src/css/uwp-loaders.css">
    <link rel="stylesheet" href="/src/css/icons.css">
    <link rel="shortcut icon" href="/src/favicon.ico" type="image/x-icon">
    <title>Windows Update</title>
    <script>
      let home = "/Windows/ImmersiveControlPanel/SystemSettings.php"
    </script>
    <?php include_once "$root/src/filters/grainy.svg"; ?>
  </head>

  <body>
    <div class="content">
      <div class="sidebar">
        <div class="blur"></div>
        <div class="grain"></div>
        <sidebarheader>
          <div class="sidebar-item btn" onclick="openURL(home, '_Self')">
            <div class="icon">
              <span id="Home" class="sbi"></span>
            </div>
            <p>Início</p>
          </div>
          <div class="itemsTypeTitle">
            <b>Páginas</b>
          </div>
        </sidebarheader>
        <sidebarcontent>
          <div class="sidebar-item btn" onclick="openURL('delivey-optimization.php', '_Self')">
            <div id="fs" class="selected b0">
              <div></div>
            </div>
            <div class="icon">
              <span id="Sync" class="sbi"></span>
            </div>
            <p>Windows Update</p>
          </div>
          <div class="sidebar-item btn" onclick="openURL('', '_Self')">
            <div id="fs" class="selected b1">
              <div></div>
            </div>
            <div class="icon">
              <span id="DeliveryOptimization" class="sbi"></span>
            </div>
            <p>Otimização de Entrega</p>
          </div>
          <div class="sidebar-item btn" onclick="openURL('windows-security.php', '_Self')">
            <div id="fs" class="selected b2">
              <div></div>
            </div>
            <div class="icon">
              <span id="DefenderBadge12" class="sbi"></span>
            </div>
            <p>Segurança</p>
          </div>
          <div class="sidebar-item btn" onclick="openURL('backup.php', '_Self')">
            <div id="fs" class="selected b3">
              <div></div>
            </div>
            <div class="icon">
              <span id="Info" class="sbi"></span>
            </div>
            <p>Backup</p>
          </div>
        </sidebarcontent>
      </div>
      <div class="main-content">
        <div class="header">
          <progressbarI>
            <div class="two"></div>
            <div class="two"></div>
            <div class="two"></div>
            <div class="two"></div>
            <div class="two"></div>
          </progressbarI>
          <div class="header-main">
            <div class="header-content">
              <tabtitle>
                <div class="icon">
                  <span id="home" class="smallHome btn" onclick="openURL(home, '_Self')"></span>
                </div>
                Windows Update
              </tabTitle>
            </div>
          </div>
        </div>
        <div class="main-items">
          <div class="section">
            <div class="sectionTitle">
              <div class="icon">
                <span id="Refresh" class="vdlUpdate"></span>
              </div>
              <div style="display: flex; flex-direction: column; width: 100%;">
                <div id="message">Você está atualizado</div>
                <div style="margin-block: 5px;" class="checkingIf"></div>
              </div>
            </div>
            <p id="details" class="hide"></p>
            <div class="bef-button">
              <button class="button no-transition" id="check-update-button">Verificar se há atualizações</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.getElementById('check-update-button').addEventListener('click', async () => {
        const button = document.getElementById('check-update-button');
        button.classList.add('hide');
        const mainMsg = document.getElementById("message");
        const detailsMsg = document.getElementById("details");
        try {
          const checkResponse = await fetch('/functions/check_update.php');
          const checkData = await checkResponse.json();

          console.log(checkData.logs); // Mostrar logs no console

          if (checkData.updateAvailable) {
            document.getElementById('message').innerText = `Nova atualização encontrada: ${checkData.releaseData.tag_name}\nVersão: ${checkData.version}\nCompilação: ${checkData.compilation}\nUpdate: ${checkData.update}`;

            button.classList.remove('hide');
            button.innerText = 'Baixar e instalar';
            button.addEventListener('click', async () => {
              button.classList.add('hide');
              try {
                const downloadResponse = await fetch('/functions/download_update.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                    releaseData: checkData.releaseData
                  })
                });
                const downloadData = await downloadResponse.json();

                console.log(downloadData.logs); // Mostrar logs no console

                mainMsg('message').innerText = downloadData.message;
              } catch (error) {
                console.error('Erro ao baixar e instalar atualização:', error);
                mainMsg.innerText = "Erro";
                detailsMsg.innerText = `Erro ao baixar atualização: ${error}`
                detailsMsg.classList.remove("hide");
              } finally {
                button.classList.remove('hide');
                button.innerText = 'Verificar se há atualizações';
              }
            }, {
              once: true
            });
          } else {
            mainMsg.innerText = 'Você está atualizado.';
            detailsMsg.innerText = `${checkData.message}`
            detailsMsg.classList.remove("hide");
            button.classList.remove('hide');
            button.innerText = 'Verificar se há atualizações';
          }
        } catch (error) {
          console.error('Erro ao verificar atualização:', error);
          mainMsg.innerText = "Erro";
          detailsMsg.innerText = `Um ocorreu ao procurar por atualizações: ${checkData.message}, ${error}`;
          detailsMsg.classList.remove("hide");
          button.classList.remove('hide');
          button.innerText = 'Verificar se há atualizações';
        }
      });
    </script>
  </body>

</html>