<?php
// Caminho para o script shell
$scriptPath = '/var/www/html/check-updates.sh';

// Definir variável de ambiente HOME
putenv('HOME=/var/www');

// Adicionar diretório seguro antes de executar o script
shell_exec("git config --global --add safe.directory /var/www/html");

// Executar o script shell
$output = shell_exec("bash $scriptPath 2>&1");

// Processar a saída do script shell
$output_lines = explode("\n", trim($output));
$status = array_shift($output_lines); // Pega a primeira linha como status

// Adicionar log de depuração
file_put_contents('/var/www/html/check-updates-php.log', "Output:\n$output\n", FILE_APPEND);

if ($status === "update") {
    echo json_encode(["message" => "Algumas atualizações foram encontradas.", "status" => "update", "files" => $output_lines]);
} elseif ($status === "no_update") {
    echo json_encode(["message" => "Nenhuma atualização encontrada.", "status" => "no_update"]);
} else {
    echo json_encode(["message" => "Erro: $status", "status" => "error"]);
}
?>
