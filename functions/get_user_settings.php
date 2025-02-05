<?php
// session_start();
require_once 'get-root.php';
require_once "$root/db.php";

function getCurrentUserId()
{
  // Supondo que o ID do usuário esteja armazenado na sessão após o login
  return $_SESSION['id'] ?? null;
}

// Supondo que você tenha uma função para obter o ID do usuário atual
$userId = getCurrentUserId();

// Consultar as configurações do usuário
$stmt = $pdo->prepare("SELECT * FROM user_settings WHERE id = :id");
$stmt->execute(['id' => $userId]);
$userSettings = $stmt->fetch(mode: PDO::FETCH_ASSOC);

if ($userSettings) {
  foreach ($userSettings as $key => $value) {
    $_SESSION[$key] = $value;
  }
}
// echo "<p>$userId</p>";
// echo $_SESSION["system_color_theme"];