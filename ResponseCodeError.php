<?php
$reponseCode = http_response_code();
$stopCode = "";
$tabTitle = "";
$description = "";
$requestedUrl = $_SERVER['REQUEST_URI'];

switch ($reponseCode) {
  case 200:
    $stopCode = $reponseCode . "_OK";
    $tabTitle = "$reponseCode - Isso não é um Erro!";
    ob_start();
    include "ResponseCodes/200.php";
    $description = ob_get_clean();
    break;
  case 403:
    $stopCode = $reponseCode . "_FORBIDDEN";
    $tabTitle = "$reponseCode - Acesso Proiobido";
    ob_start();
    include "ResponseCodes/403.php";
    $description = ob_get_clean();
    break;
  case 404:
    $stopCode = $reponseCode . "_NOT_FOUND";
    $tabTitle = "$reponseCode - Página não Encontrada";
    ob_start();
    include "ResponseCodes/404.php";
    $description = ob_get_clean();
    break;
  case 414:
    $stopCode = $reponseCode . "_URI_TOO_LONG";
    $tabTitle = "$reponseCode - URL Longa de Mais";
    ob_start();
    include "ResponseCodes/414.php";
    $description = ob_get_clean();
    break;
  case 500:
    $stopCode = $reponseCode . "_INTERNAL_ERROR";
    $tabTitle = "$reponseCode - Erro Interno";
    ob_start();
    include "ResponseCodes/500.php";
    $description = ob_get_clean();
    break;
  default:
    $stopCode = $reponseCode . "_NOT_AT_ERROR_LIST_STILL";
    $tabTitle = "$reponseCode - Erro não Definido na Lista";
    ob_start();
    include "ResponseCodes/ErrorNotAtList.php";
    $description = ob_get_clean();
    break;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0">
    <title><?php echo $tabTitle; ?></title>
    <script defer src="../js/ourFunctions.js"></script>
    <style>
      body {
        overflow: hidden;
        flex-direction: column;
        width: 100vw;
        height: 100vh;
        max-width: calc(100vw - 44vi);
        display: flex;
        justify-content: center;
        margin: 0;
        padding-block: 0;
        padding-inline: 22vi;
        background: #0078d7;
        font-family: Segoe UI;
        color: #fff;
      }

      #\:\)::before {
        content: ":|";
      }

      .bsod0 {
        margin-block: .15vi;
        font-size: 8vi;
      }

      .bsod0 a::before {
        content: ":(";
      }

      .bsod0 a:hover::before {
        content: ":)" !important;
      }

      a {
        text-decoration: none;
        color: #fff;
      }

      p {
        font-size: 1.15vi;
        margin-block: .8vi;
        text-overflow: ellipsis;
      }

      .bsod1 {
        max-width: 45vi;
      }

      .bsod2 {
        font-size: 1.15vi;
      }

      .bsod3 {
        margin-block: 1vi;
        display: flex;
      }

      .bsod4 {
        flex-direction: column;
        margin-inline: .7vi;
        display: flex;
        max-width: 45vi !important;
      }

      .bsod5 {
        margin-block: .8vi;
        font-size: 1vi;
      }

      .bsod6 {
        font-size: 1vi;
        p{
          font-size: 1vi;
          margin: 0;
          overflow-x: hidden;
        }
      }

      .bsod7 {
        max-width: 30vi;
        margin-bottom: .6vi;
        font-size: 1.08vi;
      }

      img {
        font-size: 1vi;
        width: 6.5vi;
        height: 6.5vi;
      }
    </style>
  </head>
  <?php echo $description ?>

</html>