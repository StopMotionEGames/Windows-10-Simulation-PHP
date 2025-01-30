<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificador de Atualizações</title>
    <script>
        function verificarAtualizacoes() {
            fetch('./check-if-updates.php')
                .then(response => response.json())
                .then(data => {
                    let resultado = data.message;
                    if (data.status === "update") {
                        resultado += "<br><strong>Arquivos alterados:</strong><ul>";
                        data.files.forEach(file => {
                            resultado += `<li>${file}</li>`;
                        });
                        resultado += "</ul>";
                        const downloadButton = document.createElement("button");
                        downloadButton.innerText = "Baixar e instalar";
                        downloadButton.onclick = baixarAtualizacoes;
                        document.getElementById("resultado").appendChild(downloadButton);
                    }
                    document.getElementById("resultado").innerHTML = resultado;
                })
                .catch(error => console.error('Erro:', error));
        }

        function baixarAtualizacoes() {
            fetch('./download-updates.php')
                .then(response => response.json())
                .then(data => {
                    let resultado = data.message;
                    resultado += "<br><pre>" + data.output + "</pre>";
                    document.getElementById("resultado").innerHTML = resultado;
                })
                .catch(error => console.error('Erro:', error));
        }
    </script>
</head>
<body>
    <h1>Verificador de Atualizações</h1>
    <button onclick="verificarAtualizacoes()">Verificar Atualizações</button>
    <div id="resultado"></div>
</body>
</html>
