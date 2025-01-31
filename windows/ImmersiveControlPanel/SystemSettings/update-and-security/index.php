<!DOCTYPE html>
<html>

  <head>
    <title>Simulador Windows Update</title>
    <link rel="stylesheet" href="/src/css/debug.css">
    <style>
      body {
        background-image: url(/src/images/BGs/img0.jpg);
        margin: 0;
        overflow: hidden;
        font-family: Arial, sans-serif;
      }

      .taskbar {
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 40px;
        background-color: #333;
        color: white;
        display: flex;
        align-items: center;
      }

      .start-button {
        margin-left: 10px;
        padding: 5px 10px;
      }

      #check-update-button {
        margin-left: 20px;
        padding: 5px 10px;
      }
    </style>
  </head>

  <body>
    <div class="taskbar">
      <button class="start-button">Iniciar</button>
      <button id="check-update-button">Verificar se há atualizações</button>
    </div>
    <div id="message"></div>
    <script>
      document.getElementById('check-update-button').addEventListener('click', async () => {
        try {
          const checkResponse = await fetch('/functions/check_update.php');
          const checkData = await checkResponse.json();

          console.log(checkData.logs); // Mostrar logs no console

          if (checkData.updateAvailable) {
            document.getElementById('message').innerText = `Nova atualização encontrada: ${checkData.releaseData.tag_name}\nVersão: ${checkData.version}\nCompilação: ${checkData.compilation}\nUpdate: ${checkData.update}`;

            const downloadButton = document.createElement('button');
            downloadButton.innerText = 'Baixar e instalar';
            downloadButton.addEventListener('click', async () => {
              try {
                const downloadResponse = await fetch('/functions/download_update.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({ releaseData: checkData.releaseData })
                });
                const downloadData = await downloadResponse.json();

                console.log(downloadData.logs); // Mostrar logs no console

                document.getElementById('message').innerText = downloadData.message;
              } catch (error) {
                console.error('Erro ao baixar e instalar atualização:', error);
              }
            });
            document.getElementById('message').appendChild(downloadButton);
          } else {
            document.getElementById('message').innerText = checkData.message;
          }
        } catch (error) {
          console.error('Erro ao verificar atualizações:', error);
          document.getElementById('message').innerText = 'Erro ao verificar atualizações: ' + error.message;
        }
      });
    </script>
  </body>

</html>