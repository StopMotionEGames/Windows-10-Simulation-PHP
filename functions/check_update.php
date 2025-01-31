<?php
require "FilesManager.php";

// Mostrar erros de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir cabeçalho para JSON
header('Content-Type: application/json');

$debug = true;
$logs = '';

function getCurrentVersion($root, $debug, &$logs)
{
  $jsonFilePath = "$root/Windows/System32/winver.json";
  if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    if ($debug)
      $logs .= "Current version data: $jsonData\n";
    return json_decode($jsonData, true);
  } else {
    if ($debug)
      $logs .= "Current version file not found.\n";
    return null;
  }
}

function checkForUpdates($currentVersion, $debug, &$logs)
{
  $githubApiUrl = 'https://api.github.com/repos/StopMotionEGames/Windows-10-Simulation-PHP/releases';
  $token = getenv("GITHUB_TOKEN");

  if (!$token) {
    $logs .= "Token do GitHub não encontrado nas variáveis de ambiente.\n";
    return ['logs' => $logs];
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $token",
    'Accept: application/vnd.github+json',
    'X-GitHub-Api-Version: 2022-11-28'
  ));
  $response = curl_exec($ch);
  $curlError = curl_error($ch);
  $curlInfo = curl_getinfo($ch);
  curl_close($ch);

  if ($debug) {
    $logs .= "cURL Info:\n" . print_r($curlInfo, true) . "\n";
    $logs .= "cURL Error:\n$curlError\n";
    $logs .= "API Response:\n$response\n";
  }

  if (!$response) {
    $logs .= "Falha ao obter resposta da API do GitHub. Erro: $curlError\n";
    return ['logs' => $logs];
  }

  $releasesData = json_decode($response, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    $logs .= "Erro ao decodificar JSON da resposta do GitHub: " . json_last_error_msg() . "\n";
    return ['logs' => $logs];
  }

  // Filtrar pelo release mais recente para o branch específico
  $latestRelease = null;
  foreach ($releasesData as $release) {
    if (strpos($release['tag_name'], $currentVersion['branch']) !== false) {
      $latestRelease = $release;
      break;
    }
  }

  if (!$latestRelease) {
    $logs .= "Nenhum release encontrado para o branch: " . $currentVersion['branch'] . "\n";
    return ['logs' => $logs];
  }

  // Extrair as três primeiras linhas da descrição
  $descriptionLines = explode(PHP_EOL, $latestRelease['body']);
  $version = isset($descriptionLines[0]) ? trim($descriptionLines[0]) : '';
  $compilation = isset($descriptionLines[1]) ? trim($descriptionLines[1]) : '';
  $update = isset($descriptionLines[2]) ? trim($descriptionLines[2]) : '';

  if ($latestRelease['tag_name'] > $currentVersion['tag']) {
    $logs .= "Nova atualização encontrada: {$latestRelease['tag_name']}\n";
    $logs .= "Versão: $version\n";
    $logs .= "Compilação: $compilation\n";
    $logs .= "Update: $update\n";
    return [
      'releaseData' => $latestRelease,
      'version' => $version,
      'compilation' => $compilation,
      'update' => $update,
      'logs' => $logs
    ];
  } else {
    $logs .= "Nenhuma atualização disponível ou release incompatível.\n";
    return ['logs' => $logs];
  }
}

$root = rootDirectory();
$currentVersion = getCurrentVersion($root, $debug, $logs);
if ($currentVersion) {
  $releaseInfo = checkForUpdates($currentVersion, $debug, $logs);
  if (isset($releaseInfo['releaseData'])) {
    echo json_encode([
      'message' => 'Nova atualização encontrada: ' . $releaseInfo['releaseData']['tag_name'] . "\nVersão: " . $releaseInfo['version'] . "\nCompilação: " . $releaseInfo['compilation'] . "\nUpdate: " . $releaseInfo['update'],
      'updateAvailable' => true,
      'logs' => $releaseInfo['logs'],
      'releaseData' => $releaseInfo['releaseData'] // Adicionar releaseData na resposta JSON
    ]);
  } else {
    echo json_encode([
      'message' => 'Nenhuma atualização disponível.',
      'updateAvailable' => false,
      'logs' => $releaseInfo['logs']
    ]);
  }
} else {
  echo json_encode([
    'message' => 'Não foi possível encontrar a versão atual.',
    'updateAvailable' => false,
    'logs' => $logs
  ]);
}
?>