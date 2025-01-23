<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}

$current_user = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $use_pin = $_POST['use_pin'] === '1';

  $stmt = $pdo->prepare("UPDATE users_data SET use_pin = :use_pin WHERE name = :name");
  $stmt->execute(['use_pin' => $use_pin, 'name' => $current_user]);

  $_SESSION['use_pin'] = $use_pin;
}

$stmt = $pdo->prepare("SELECT use_pin FROM users_data WHERE name = :name");
$stmt->execute(['name' => $current_user]);
$use_pin = $stmt->fetchColumn();
?>

<div class="window">
  <div class="resizer corner tl"></div>
  <div class="resizer corner tr"></div>
  <div class="resizer corner bl"></div>
  <div class="resizer corner br"></div>
  <div class="resizer t"></div>
  <div class="resizer b"></div>
  <div class="resizer l"></div>
  <div class="resizer r"></div>

  <div class="wbody">
    <div class="wtopbar">
      <div class="wtitle">Configurações</div>
      <div class="wbtns">
        <div class="wminimize">&minus;</div>
        <div class="wmaximize">&square;</div>
        <div class="wclose">&times;</div>
      </div>
    </div>
    <div class="wcontent">
      <div class="section">
        <div class="sectiontitle">Configurações do Usuário</div>
        <form id="settingsForm" method="POST" action="settings.php">
          <label>
            <input type="radio" name="use_pin" value="0" <?php echo !$use_pin ? 'checked' : ''; ?>> Usar Senha
          </label>
          <label>
            <input type="radio" name="use_pin" value="1" <?php echo $use_pin ? 'checked' : ''; ?>> Usar PIN
          </label>
          <button type="submit">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>