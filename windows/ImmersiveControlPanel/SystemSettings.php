<?php

require '../../vendor/autoload.php';

use React\EventLoop\Loop;
use React\Promise\Promise;

$loop = Loop::get();
$folder = './SystemSettings';
$subfolders = glob("$folder/*", GLOB_ONLYDIR);
$buttons = '';

foreach ($subfolders as $subfolder) {
  $indexPath = "$subfolder/index.php";
  $infoPath = "$subfolder/pageInfo.json";

  $promise = new Promise(function ($resolve, $reject) use ($indexPath, $infoPath, $subfolder) {
    if (file_exists($indexPath) && file_exists($infoPath)) {
      $folderName = basename($subfolder);
      $pageInfo = json_decode(file_get_contents($infoPath), true);
      $pageName = $pageInfo['pageName'] ?? '';
      $pageDesc = $pageInfo['pageDesc'] ?? '';
      $pageIcon = $pageInfo['pageIcon'] ?? '';
      $button = "<div class='button btn start' type='button' onclick=\"openURL('./$subfolder', '_Self')\"><div class='icon'><span id='$pageIcon' class='start'></span></div><div class='bcontent no-bg'><span class='no-bg'>$pageName</span><div class='no-bg'>$pageDesc</div></div></div>";
      $resolve($button);
    } else {
      $reject("File not found: $indexPath or $infoPath");
    }
  });

  $promise->then(
    function ($button) use (&$buttons) {
      $buttons .= $button;
    },
    function ($error) {
      echo $error . PHP_EOL;
    }
  );
}

$loop->run();
?>
<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <link fetchpriority="high" rel="preload" href="/src/icons/?icon=ms-settings&dynamic=true" as="image">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <script defer src="/src/js/loader.js"></script>
    <script defer src="/src/js/ourFunctions.js"></script>
    <script defer src="/src/js/winhover.js"></script>
    <script defer src="/src/js/sidebarIndicator.js"></script>
    <link rel="stylesheet" href="/src/css/uwp-app.css">
    <link rel="stylesheet" href="/src/css/uwp-loaders.css">
    <link rel="stylesheet" href="/src/css/uwp-forms.css">
    <link rel="stylesheet" href="/src/css/icons.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <style>
      #loader {
        position: fixed;
        z-index: 100;
        width: 100%;
        height: 100%;
        background-color: #0078d7;
        display: flex;
        justify-content: center;
        align-items: center;
        left: 0;
        top 0;

        transition: all .5s;

        img {
          width: 192px;
        }
      }
    </style>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        let a = document.getElementById("loader");
        a.style.opacity = 0
        setTimeout(() => {
          a.remove();
        }, 500)
      })
    </script>
  </head>

  <body>
    <div id="loader">
      <img src="/src/icons/?icon=ms-settings" alt="">
    </div>
    <div class="content">
      <div class="main-content full">
        <div class="header full home-page">
          <progressbarI>
            <div class="two"></div>
            <div class="two"></div>
            <div class="two"></div>
            <div class="two"></div>
            <div class="two"></div>
          </progressbarI>
          <div class="header-main">
            <div class="header-content">
              <div class="user-info">
                <div class="user-icon">
                </div>
                <div class="user">
                  <span id="name"><?php echo "ADM2" ?></span>
                  <span id="account-type">Conta Local</span>
                  <span id="link"><a href="#">Entrar</a></span>
                </div>
              </div>
              <div class="short-cuts">

              </div>
            </div>
          </div>
        </div>
        <div class="main-items full">
          <div class="topTools">
            <div class="input-container">
              <input type="search" placeholder="Digite para pesquisar" />
            </div>
          </div>
          <div class="buttons win-grid">
            <?php echo $buttons ?>
          </div>
        </div>
      </div>
    </div>
  </body>

</html>