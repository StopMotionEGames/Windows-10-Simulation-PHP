<?php
require_once 'FilesManager.php';

// Desativar a exibição de erros de PHP na saída HTTP
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Definir cabeçalho para JSON
header('Content-Type: application/json');

$debug = true;
$logs = '';

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
    $file = fopen($downloadUrl, 'r');

    if ($file) {
      file_put_contents($downloadPath, $file);
      fclose($file);

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
      $logs .= "Falha ao abrir stream para $downloadUrl.\n";
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
      if (is_dir("$src/$file")) {
        recursiveMove("$src/$file", "$dst/$file");
      } else {
        rename("$src/$file", "$dst/$file");
      }
    }
  }
  closedir($dir);
}

$releaseData = json_decode(file_get_contents('php://input'), true)['releaseData']; // Obter releaseData do POST JSON
$root = rootDirectory();

if ($releaseData) {
  $logs = downloadAndUpdate($releaseData, $root, $debug, $logs);
  echo json_encode([
    'message' => 'Atualização concluída com sucesso!',
    'logs' => $logs
  ]);
} else {
  echo json_encode([
    'message' => 'Nenhuma atualização disponível para baixar.',
    'logs' => 'Erro: releaseData não fornecido.'
  ]);
}
?>