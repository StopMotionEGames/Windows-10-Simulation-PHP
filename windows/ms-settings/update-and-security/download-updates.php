<?php
// Caminho para o diretório do projeto
$destDir = '/var/www/html';

// Função para verificar se é um repositório Git
function isGitRepo($dir) {
    return is_dir($dir . '/.git');
}

// Navegar até o diretório do projeto
chdir($destDir);

// Verificar se estamos no diretório do repositório Git
if (!isGitRepo($destDir)) {
    echo json_encode(["message" => "Erro: O diretório especificado não é um repositório Git.", "status" => "error"]);
    exit();
}

// Caminho para o script shell
$scriptPath = '$destDir/sync.sh';

// Executar o script shell
chdir($destDir);  // Garantir que estamos no diretório raiz do projeto
$output = shell_exec("bash $scriptPath 2>&1");

// Para depuração
file_put_contents('/var/www/html/baixar.log', "Output:\n$output\n", FILE_APPEND);

// Mostrar a saída do comando
echo json_encode(["message" => "Atualizações instaladas com sucesso!", "output" => htmlentities($output)]);
?>
