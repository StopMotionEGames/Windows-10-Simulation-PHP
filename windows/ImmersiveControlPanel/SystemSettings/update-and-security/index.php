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
    <div class="debug">
      <pre id="debug-log" tabindex="-1"></pre>
    </div>
    <script>
      document.getElementById('check-update-button').addEventListener('click', () => {
        fetch('/functions/check_update.php')
          .then(response => response.json())
          .then(data => {
            document.getElementById('message').innerText = data.message;
            document.getElementById('debug-log').innerText = data.logs;
            if (data.updateAvailable) {
              const downloadButton = document.createElement('button');
              downloadButton.innerText = 'Baixar e instalar';
              downloadButton.addEventListener('click', () => {
                fetch('/functions/download_update.php')
                  .then(response => response.json())
                  .then(result => {
                    document.getElementById('message').innerText = result.message;
                    document.getElementById('debug-log').innerText = result.logs;
                  });
              });
              document.getElementById('message').appendChild(downloadButton);
            }
          }).catch(error => {
            document.getElementById('message').innerText = 'Erro ao verificar atualizações: ' + error;
            document.getElementById('debug-log').innerText = error;
          });
      });
    </script>
  </body>

</html>