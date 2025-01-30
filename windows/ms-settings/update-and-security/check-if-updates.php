<?php
putenv('GIT_TERMINAL_PROMPT=1');
putenv('GIT_ASKPASS=/usr/bin/echo');
putenv('GIT_SSH_COMMAND=ssh -o "StrictHostKeyChecking=no"');
// Caminho para o script shell
$scriptPath = '/var/www/html/check-for-updates.sh';

$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin
    1 => array("pipe", "w"),  // stdout
    2 => array("pipe", "w")   // stderr
);

$process = proc_open('bash ' . $scriptPath, $descriptorspec, $pipes, null, null);

if (is_resource($process)) {
    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    $error_output = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    $return_value = proc_close($process);

    // Adicionar log de depuração
    file_put_contents('/var/www/html/check-updates.log', "Output:\n$output\nError Output:\n$error_output\nReturn Value:\n$return_value\n", FILE_APPEND);

    $output_lines = explode("\n", trim($output));
    $status = array_shift($output_lines); // Pega a primeira linha como status

    if ($status === "update") {
        echo json_encode(["message" => "Algumas atualizações foram encontradas.", "status" => "update", "files" => $output_lines]);
    } elseif ($status === "no_update") {
        echo json_encode(["message" => "Nenhuma atualização encontrada.", "status" => "no_update"]);
    } else {
        echo json_encode(["message" => "Erro: $status", "status" => "error"]);
    }
}