<?php

require '../../vendor/autoload.php';

use React\EventLoop\Factory;
use React\Promise\Promise;

$loop = Factory::create();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beta Elements</title>
    <script defer src="/src/js/loader.js"></script>
    <script defer src="/src/js/ourFunctions.js"></script>
    <script defer src="/src/js/winhover.js"></script>
    <script defer src="/src/js/sidebarIndicator.js"></script>
    <link rel="stylesheet" href="/src/css/uwp-app.css">
    <link rel="stylesheet" href="/src/css/loadAnim.css">
    <link rel="stylesheet" href="/src/css/icons.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  </head>

  <body>
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
            <input type="search" placeholder="Digite para pesquisar" />
          </div>
          <div class="buttons win-grid">
            <?php echo $buttons ?>
          </div>
        </div>
      </div>
    </div>
  </body>

</html>