<?php
require_once 'FilesManager.php';

// Mostrar erros de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir cabeçalho para JSON
header('Content-Type: application/json');

function getCurrentVersion($root)
{
  $jsonFilePath = "$root/Windows/System32/winver.json";
  if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    return json_decode($jsonData, true);
  } else {
    return null;
  }
}

function checkForUpdates($currentVersion)
{
  $githubApiUrl = 'https://api.github.com/repos/StopMotionEGames/Windows-10-Simulation-PHP/releases/latest';
  $token = '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';
  $logs = '';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: token ' . $token
  ));
  $response = curl_exec($ch);
  $curlError = curl_error($ch);
  curl_close($ch);

  if (!$response) {
    $logs .= "Falha ao obter resposta da API do GitHub. Erro: $curlError\n";
    return ['logs' => $logs];
  }

  $releaseData = json_decode($response, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    $logs .= "Erro ao decodificar JSON da resposta do GitHub: " . json_last_error_msg() . "\n";
    return ['logs' => $logs];
  }

  if (!isset($releaseData['tag_name']) || !isset($releaseData['body'])) {
    $logs .= "Dados de release inválidos obtidos da API do GitHub.\n";
    return ['logs' => $logs];
  }

  // Extrair as três primeiras linhas da descrição
  $descriptionLines = explode(PHP_EOL, $releaseData['body']);
  $version = isset($descriptionLines[0]) ? trim($descriptionLines[0]) : '';
  $compilation = isset($descriptionLines[1]) ? trim($descriptionLines[1]) : '';
  $update = isset($descriptionLines[2]) ? trim($descriptionLines[2]) : '';

  // Verificar se a nova versão é maior que 0.0.0 e se o branch é o correto
  if ($releaseData['tag_name'] > 'v0.0.0' && strpos($releaseData['tag_name'], $currentVersion['branch']) !== false) {
    $logs .= "Nova atualização encontrada: {$releaseData['tag_name']}\n";
    $logs .= "Versão: $version\n";
    $logs .= "Compilação: $compilation\n";
    $logs .= "Update: $update\n";
    return [
      'releaseData' => $releaseData,
      'version' => $version,
      'compilation' => $compilation,
      'update' => $update,
      'logs' => $logs
    ];
  } else {
    $logs .= "Nenhuma atualização disponível ou branch incompatível.\n";
    return ['logs' => $logs];
  }
}

function downloadAndUpdate($releaseData, $root)
{
  $tempDir = "$root/Windows/SoftwareDistribution/Temp";
  $logs = '';

  $downloadUrl = '';
  foreach ($releaseData['releaseData']['assets'] as $asset) {
    if (strpos($asset['name'], 'update') !== false) {
      $downloadUrl = $asset['browser_download_url'];
      break;
    }
  }

  if ($downloadUrl) {
    $downloadPath = "$tempDir/update.zip";
    file_put_contents($downloadPath, fopen($downloadUrl, 'r'));

    $zip = new ZipArchive;
    if ($zip->open($downloadPath) === TRUE) {
      $zip->extractTo($tempDir);
      $zip->close();

      // Move files to the root directory
      recursiveMove($tempDir, $root);
      $logs .= "Arquivos movidos para a raiz.\n";
    } else {
      $logs .= "Falha ao abrir o arquivo zip.\n";
    }
  } else {
    $logs .= "URL de download não encontrada.\n";
  }

  return $logs;
}

function recursiveMove($src, $dst)
{
  $dir = opendir($src);
  @mkdir($dst);
  while (false !== ($file = readdir($dir))) {
    if (($file != '.') && ($file != '..')) {
      if (is_dir($src . '/' . $file)) {
        recursiveMove($src . '/' . $file, $dst . '/' . $file);
      } else {
        rename($src . '/' . $file, $dst . '/' . $file);
      }
    }
  }
  closedir($dir);
}

$root = rootDirectory();
$currentVersion = getCurrentVersion($root);
$releaseInfo = checkForUpdates($currentVersion);
if (isset($releaseInfo['releaseData'])) {
  $logs = downloadAndUpdate($releaseInfo, $root);
  echo json_encode([
    'message' => 'Atualização concluída com sucesso!',
    'logs' => $logs
  ]);
} else {
  echo json_encode([
    'message' => 'Nenhuma atualização disponível para baixar.',
    'logs' => $releaseInfo['logs']
  ]);
}
?>