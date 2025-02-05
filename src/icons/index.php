<script>
  document.addEventListener('DOMContentLoaded', (event) => {
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
    const theme = prefersDarkScheme ? 'dark' : 'light';
    const urlParams = new URLSearchParams(window.location.search);
    console.log("Tema detectado pelo JavaScript: " + theme);
    if (!urlParams.has('theme')) {
      // document.cookie = `theme=${theme}; path=/`;
      urlParams.set('theme', theme);
      window.location.search = urlParams.toString();
    }
  });
</script>
<?php
session_start();
function getCurrentTheme()
{
  if (isset($_SESSION['system_color_theme']))
    return $_SESSION['system_color_theme'];
  else
    return 'light';
}
$icon = $_GET['icon'] ?? null;
$format = $_GET['format'] ?? 'webp';
$dynamic = isset($_GET['dynamic']) ? filter_var($_GET['dynamic'], FILTER_VALIDATE_BOOLEAN) : false;
$theme = getCurrentTheme();
if ($dynamic) {
  $iconPath = "./$theme/$icon.$format";
} else {
  $iconPath = "./static/$icon.$format";
}
if (!$icon) {
  http_response_code(400);
  echo "Parâmetro 'icon' é obrigatório, não está definido";
} else if (!file_exists($iconPath)) {
  http_response_code(404);
  echo "O ícone $icon.$format não foi encontrado.";
} else {
  while ($theme === null) {
    echo "Aguardando detecção do tema...";
  }
  header("Location: $iconPath");
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
</style>

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
    <input type='text' name='icon' placeholder='e.g. ms-settings' required /><br />
    <label for='dynamic'>Ícone Dinâmico ou ícone Estatico?</label><br />
    <input type='radio' name='dynamic' value='true' /> Dinâmico
    <input type='radio' name='dynamic' value='false' checked /> Estático<br />
    <label for="format">Formato do ícone</label>
    <input type="text" name="format" placeholder="e.g. webp">
    <input type='submit' value='Enviar' />
  </form>

  <h1>Ícones</h1>
  <p>Esta página é usada para redirecionar para os ícones corretos com base nos parâmetros fornecidos na URL.</p>
  <h2>Parâmetros</h2>
  <ul>
    <li>
      <strong>icon</strong> - O nome do ícone que você deseja obter. Por exemplo, <code>ms-settings</code>.
    </li>
    <li>
      <strong>format</strong> - O formato do ícone. Por exemplo, <code>png</code>
    </li>
    <li>
      <strong>dynamic</strong> - Se o ícone tem versões para os temas claro e escuro, pode ser <code>true</code>, ou se
      o ícone não tiver, ou quer a versão padrão, é melhor usar <code>false</code>.
    </li>
  </ul>
  <h2>Notas</h2>
  <ul>
    <li>
      Se você não especificar o parâmetro <code>dynamic</code>, ele será considerado como <code>false</code> por
      padrão.
    </li>
    <li>
      Se você escolher dynamic como falso, não necessáriamente seu ícone não tem sua versão para os dois temas, mas
      você
      pode estar querendo a versão padrão dele.
    </li>
    <li>
      O formato padrão dos ícones são <code>.webp</code>. Então se <code>format</code> estiver vazio ou não for
      especificado, o sccript tentará achar pelo ícone com o formato <code>.webp</code>
    </li>
  </ul>
  <h2>Exemplos</h2>
  <p>
    Para obter o ícone <code>ms-settings</code> com versões para os temas claro e escuro, você pode usar o seguinte
    URL:
  </p>
  <ul>
    <li>
      <a
        href="/src/icons/?icon=ms-settings&format=webp&dynamic=true">/src/icons/?icon=ms-settings&format=webp&dynamic=true</a>
    </li>
  </ul>
  <p>Para obter o ícone <code>msedge</code>, você pode usar o seguinte URL:</p>
  <ul>
    <li>
      <a href="/src/icons/?icon=msedge&format=webp&dynamic=false">/src/icons/?icon=msedge&format=webp&dynamic=false</a>
      <p>
        <code>dynamic=false</code> nesse caso, porque o ícone <code>msedge</code> não tem versões para os temas claro
        e escuro.
      </p>
    </li>
  </ul>
  <p>Para obter o ícone <code>ms-store</code> na sua versão padrão, basta usar o seguinte URL:</p>
  <ul>
    <li>
      <a
        href="/src/icons/?icon=ms-store&format=webp&dynamic=false">/src/icons/?icon=ms-store&format=webp&dynamic=false</a>
    </li>
    <p>ou...</p>
    <li>
      <a href="/src/icons/?icon=ms-store">/src/icons/?icon=ms-store</a>
      <p>
        <code>dynamic</code> não está especificados nesse caso, porque estamos se referindo ao ícone
        <code>ms-store</code> em sua
        versão estática.
      </p>
      <p>
        <code>format</code> não está especificado, pois o ícone <code>ms-store</code> é uma imagem <code>.webp</code>.
      </p>
    </li>
  </ul>
</body>