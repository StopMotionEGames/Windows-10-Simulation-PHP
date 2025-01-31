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

  if ($debug) {
    $logs .= "URL de download: $downloadUrl\n";
  }

  if ($downloadUrl) {
    $downloadPath = "$tempDir/update.zip";
    $token = getenv('GITHUB_TOKEN');
    if (!$token) {
      $logs .= "Token de acesso indefinido no servidor\n";
    } else {
      $logs .= "Token de acesso: $token\n";
    }

    // Usar cURL para baixar o arquivo com autenticação
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $downloadUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Authorization: Bearer $token",
      'Accept: application/octet-stream',
      'X-GitHub-Api-Version: 2022-11-28'
    ));
    curl_setopt($ch, CURLOPT_FAILONERROR, true); // Para capturar erros HTTP
    $data = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($debug) {
      $logs .= "HTTP Status Code: $httpCode\n";
      $logs .= "cURL Error: $curlError\n";
    }

    if ($httpCode == 200 && $data) {
      file_put_contents($downloadPath, $data);

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
      $logs .= "Falha ao abrir stream para $downloadUrl. HTTP Status Code: $httpCode, Erro: $curlError\n";
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