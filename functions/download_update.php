<?php
require_once 'get-root.php';

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
  $downloadDir = "$root/Windows/SoftwareDistribution/Download";
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
    $downloadPath = "$downloadDir/update.zip";
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
      // Garantir que o diretório de download existe
      if (!is_dir($downloadDir)) {
        mkdir($downloadDir, 0777, true);
        $logs .= "Diretório de download criado: $downloadDir\n";
      }
      file_put_contents($downloadPath, $data);
      $logs .= "Arquivo baixado com sucesso para $downloadPath\n";

      // Garantir que o diretório temporário existe
      if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
        $logs .= "Diretório temporário criado: $tempDir\n";
      }

      $zip = new ZipArchive;
      if ($zip->open($downloadPath) === TRUE) {
        $zip->extractTo($tempDir);
        $zip->close();
        $logs .= "Arquivo extraído com sucesso para $tempDir\n";

        // Mover arquivos para o diretório raiz
        recursiveMove($tempDir, $root);
        $logs .= "Arquivos movidos para a raiz.\n";

        // Atualizar winver.json
        $winverPath = "$root/Windows/System32/winver.json";
        $winverData = [
          'branch' => $releaseData['target_commitish'],
          'version' => $releaseData['body'] ? explode("\r\n", $releaseData['body'])[0] : '',
          'compilation' => $releaseData['body'] ? explode("\r\n", $releaseData['body'])[1] : '',
          'update' => $releaseData['body'] ? explode("\r\n", $releaseData['body'])[2] : '',
          'tag' => $releaseData['tag_name']
        ];
        file_put_contents($winverPath, json_encode($winverData, JSON_PRETTY_PRINT));
        $logs .= "winver.json atualizado com sucesso.\n";
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