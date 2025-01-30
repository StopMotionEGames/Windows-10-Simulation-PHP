<?php
// Caminho para o script shell
$scriptPath = '/var/www/html/check-for-updates.sh';

// Executar o script shell
$output = shell_exec("bash $scriptPath 2>&1");

// Processar a saída do script shell
$output_lines = explode("\n", trim($output));
$status = $output_lines[0];
unset($output_lines[0]); // Remove o status da primeira linha

if ($status === "update") {
    echo json_encode(["message" => "Algumas atualizações foram encontradas.", "status" => "update", "files" => $output_lines]);
} else {
    echo json_encode(["message" => "Nenhuma atualização encontrada.", "status" => "no_update"]);
}
?>
