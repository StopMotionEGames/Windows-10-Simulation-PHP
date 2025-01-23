<?php
// Obtenha os parâmetros da URL
$iconName = isset($_GET['icon']) ? $_GET['icon'] : null;
$isDynamic = isset($_GET['dynamic']) ? $_GET['dynamic'] === 'true' : false;

// Verifique o tema do usuário
$theme = (isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME']) && $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] === 'dark') ? 'dark' : 'light';

// Defina o caminho do ícone com base nos parâmetros
if ($iconName) {
  if ($isDynamic) {
    $iconPath = "/src/icons/{$theme}/{$iconName}.webp";
  } else {
    $iconPath = "/src/icons/static/{$iconName}.webp";
  }

  // Redirecione para o ícone correto
  header("Location: {$iconPath}");
  exit;
} else {
  echo "Nome do ícone não especificado na URL.";
}
?>
<style>
  body {
    background-color: #121212;
    color: #ffffff;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
  }

  h1,
  h2 {
    color: #bb86fc;
  }

  p,
  ul,
  li {
    line-height: 1.6;
  }

  a {
    color: #03dac6;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }

  form {
    background-color: #1e1e1e;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
  }

  label {
    display: block;
    margin-bottom: 8px;
    color: #bb86fc;
  }

  input[type="text"],
  input[type="submit"],
  input[type="radio"] {
    margin-bottom: 10px;
  }

  input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #333;
    border-radius: 4px;
    background-color: #333;
    color: #fff;
  }

  input[type="submit"] {
    background-color: #03dac6;
    color: #000;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
  }

  input[type="submit"]:hover {
    background-color: #018786;
  }

  input[type="radio"] {
    margin-right: 5px;
  }
</style>

<body>
  <form method='get' function=''>
    <label for='icon'>Nome do ícone</label><br />
    <input type='text' name='icon' placeholder='e.g ms-settings' /><br />
    <label for='dynamic'>O ícone tem versões para os temas claro e escuro?</label><br />
    <input type='radio' name='dynamic' value='true' /> Sim
    <input type='radio' name='dynamic' value='false' checked /> Não<br />
    <input type='submit' value='Enviar' />
  </form>
  <h1>Ícones</h1>
  <p>Esta página é usada para redirecionar para os ícones corretos com base nos parâmetros fornecidos na URL.</p>
  <h2>Parâmetros</h2>
  <ul>
    <li><strong>icon</strong> - O nome do ícone que você deseja obter. Por exemplo, <code>ms-settings</code>.</li>
    <li><strong>dynamic</strong> - Se o ícone tem versões para os temas claro e escuro. Pode ser <code>true</code> ou
      <code>false</code>.
    </li>
  </ul>
  <h2>Exemplos</h2>
  <p>Para obter o ícone <code>ms-settings</code> com versões para os temas claro e escuro, você pode usar o seguinte
    URL:</p>
  <ul>
    <li><a
        href="/windows_10/src/icons/?icon=ms-settings&dynamic=true">/windows_10/src/icons/?icon=ms-settings&dynamic=true</a>
    </li>
  </ul>
  <p>Para obter o ícone <code>msedge</code>, você pode usar o seguinte URL:</p>
  <ul>
    <li><a href="/windows_10/src/icons/?icon=msedge&dynamic=false">/windows_10/src/icons/?icon=msedge&dynamic=false</a>
      <p>
        dynamic=false nesse caso, porque o ícone <code>msedge</code> não tem versões para os temas claro e escuro.
        <strong>Nota:</strong> Se você não especificar o parâmetro <code>dynamic</code>, ele será considerado como
        <code>false</code> por padrão.
      </p>
    </li>
  </ul>
</body>