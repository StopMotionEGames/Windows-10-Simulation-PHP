<?php
$pr_path = "/windows_10"
  ?>
<!DOCTYPE html>
<html lang="pt-BR">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Windows Simulation</title>
    <link rel="stylesheet" href="<?php echo $pr_path?>/css/formElements.css">
    <link rel="stylesheet" href="<?php echo $pr_path ?>/css/lockScreen.css">
    <link rel="stylesheet" href="<?php echo $pr_path ?>/css/loadAnim.css">
    <link rel="stylesheet" href="<?php echo $pr_path ?>/css/desktop.css">
    <link rel="stylesheet" href="<?php echo $pr_path ?>/css/icons.css">
    <?php include "src/filters/grainy.svg"; ?>
    <script defer src="<?php echo $pr_path ?>/js/taskbar-startMenu.js"></script>
  </head>

  <body>
    <?php
    session_start();
    if (!isset($_SESSION["login"])) {
      if (isset($_POST["submit"])) {
        $email = "2@2";
        $password = "2";
        $formEmail = $_POST["email"];
        $formPassword = $_POST["password"];
        if (/*$email == $formEmail &&*/ $password == $formPassword) {
          // sleep(3);
          $_SESSION["login"] = true;
          header("location: $pr_path");
        } else {

        }
      }
      require "login.php";
    } else {
      require "desktop.php";
    }
    ?>
  </body>

</html>