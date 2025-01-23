<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivos</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        padding: 20px;
      }

      form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 auto;
      }

      label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
      }

      input[type="file"] {
        margin-bottom: 20px;
      }

      input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      input[type="submit"]:hover {
        background-color: #45a049;
      }
    </style>
  </head>

  <body>

    <h2>Upload de Arquivos</h2>
    <form action="index.php" method="post" enctype="multipart/form-data">
      <label for="filesToUpload">Escolha os arquivos para fazer upload:</label>
      <input type="file" name="filesToUpload[]" id="filesToUpload" multiple>
      <input type="submit" value="Upload">
    </form>

    <?php
    // Verifica se o formulário foi enviado corretamente
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES["filesToUpload"])) {
      // Diretório onde os arquivos serão salvos
      $target_dir = "../../uploads/";

      // Verifica se a pasta de destino existe, caso contrário, cria a pasta
      if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
      }

      // Função para mover arquivos e exibir mensagens
      function uploadFile($file, $target_dir)
      {
        $target_file = $target_dir . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
          echo "O arquivo " . basename($file["name"]) . " foi carregado com sucesso.<br>";
        } else {
          echo "Desculpe, houve um erro ao carregar o arquivo " . basename($file["name"]) . ".<br>";
        }
      }

      // Processar cada arquivo carregado
      foreach ($_FILES["filesToUpload"]["tmp_name"] as $key => $tmp_name) {
        $file = [
          'name' => $_FILES["filesToUpload"]["name"][$key],
          'type' => $_FILES["filesToUpload"]["type"][$key],
          'tmp_name' => $_FILES["filesToUpload"]["tmp_name"][$key],
          'error' => $_FILES["filesToUpload"]["error"][$key],
          'size' => $_FILES["filesToUpload"]["size"][$key]
        ];

        // Chama a função para mover o arquivo
        uploadFile($file, $target_dir);
      }
    } else {
      echo "Nenhum arquivo foi enviado.";
    }
    ?>
  </body>

</html>