<?php
require_once 'FilesManager.php';

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
      $logs .= "Current version data: " . $jsonData . "\n";
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
    $logs .= "cURL Error:\n" . $curlError . "\n";
    $logs .= "API Response:\n" . $response . "\n";
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

function downloadAndUpdate($releaseData, $root, $debug, &$logs)
{
  $tempDir = "$root/Windows/SoftwareDistribution/Temp";
  $logs = '';

  $downloadUrl = '';
  foreach ($releaseData['assets'] as $asset) {
    if (strpos($asset['name'], 'update') !== false) {
      $downloadUrl = $asset['browser_download_url'];
      break;
    }
  }

  // Se não houver asset específico, usa o zipball_url
  if (!$downloadUrl && isset($releaseData['zipball_url'])) {
    $downloadUrl = $releaseData['zipball_url'];
  }

  if ($downloadUrl) {
    $downloadPath = "$tempDir/update.zip";
    file_put_contents($downloadPath, fopen($downloadUrl, 'r'));

    $zip = new ZipArchive;
    if ($zip->open($downloadPath) === TRUE) {
      $zip->extractTo($tempDir);
      $zip->close();

      // Mover arquivos para o diretório raiz
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
$currentVersion = getCurrentVersion($root, $debug, $logs);
$releaseInfo = checkForUpdates($currentVersion, $debug, $logs);
if (isset($releaseInfo['releaseData'])) {
  $logs = downloadAndUpdate($releaseInfo['releaseData'], $root, $debug, $logs);
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