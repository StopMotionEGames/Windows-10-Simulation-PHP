<?php
session_start();
require 'db.php';

// ob_start();
// set_error_handler('customErrorHandler');

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
  echo "<div class='error-message'>";
  echo "<b>Error:</b> [$errno] $errstr - $errfile:$errline";
  echo "</div>";
  return true;
}

function authenticate($username, $password, $pin)
{
  global $pdo;
  $stmt = $pdo->prepare("SELECT * FROM users_data WHERE name = :name");
  $stmt->execute(['name' => $username]);
  $user = $stmt->fetch();

  if ($user) {
    // Autenticação usando PIN
    if ($user['use_pin']) {
      if (!empty($pin)) {
        if ($pin === $user['pin']) {
          $_SESSION['username'] = $username;
          $_SESSION['use_pin'] = $user['use_pin'];
          return true;
        } else {
          echo "<div class='error-message'>PIN incorreto.</div>";
        }
      } else {
        echo "<div class='error-message'>Por favor, insira o PIN.</div>";
      }
    } else { // Autenticação usando Senha
      if (!empty($password)) {
        if ($password === $user['pass']) {
          $_SESSION['username'] = $username;
          $_SESSION['use_pin'] = $user['use_pin'];
          return true;
        } else {
          echo "<div class='error-message'>Senha incorreta.</div>";
        }
      } else {
        echo "<div class='error-message'>Por favor, insira a senha.</div>";
      }
    }
  } else {
    echo "<div class='error-message'>Usuário não encontrado.</div>";
  }

  return false;
}

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$pin = $_POST['pin'] ?? null;
$login_successful = false;

if (!empty($username)) {
  $login_successful = authenticate($username, $password, $pin);
}

$session_active = isset($_SESSION['username']);

$use_pin = false;
if (!empty($username)) {
  $stmt = $pdo->prepare("SELECT use_pin FROM users_data WHERE name = :name");
  $stmt->execute(['name' => $username]);
  $use_pin = $stmt->fetchColumn();
}

$stmt = $pdo->prepare("SELECT * FROM users_data");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html style='background: #000' lang="pt-br">

  <head>
    <link rel="preload" fetchpriority="high" href="src/fonts/sb.woff2" as="font" type="font/woff2"
      crossorigin="anonymous">
    <link rel="preload" as="image" href="src/images/w10-bootLogo.svg" type="image/svg+xml">
    <link rel="preload" as="image" href="src/images/w11-bootLogo.svg" type="image/svg+xml">
    <link rel="preload" as="style" href="src/css/boot-animation.css" type="text/css" fetchpriority="high"
      onload="this.rel='stylesheet'">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Desktop</title>
    <link rel="stylesheet" type="text/css" href="src/css/formElements.css">
    <link rel="stylesheet" type="text/css" href="src/css/icons.css">
    <link rel="stylesheet" type="text/css" href="src/css/lockScreen.css">
    <link rel="stylesheet" type="text/css" href="src/css/window.css">
    <link rel="stylesheet" type="text/css" href="src/css/desktop.css">
    <script>
      function fontLoaded() {
        return document.fonts.load('1em sb');
      }
      let s, e, c, t, h, b = true, i;
      // Função para detectar o sistema operacional
      function detectOS() {
        navigator.userAgentData.getHighEntropyValues(["platformVersion"])
          .then(ua => {
            if (navigator.userAgentData.platform === "Windows") {
              const majorPlatformVersion = parseInt(ua.platformVersion.split('.')[0]);
              if (majorPlatformVersion >= 13) {
                // W11
                s = 0xE100;
                e = 0xE176;
                t = 0;
                c = s;
                b = false;
              }
              else if (majorPlatformVersion > 0) {
                // W10
                s = 0xE052;
                e = 0xE0C6;
                t = 500;
                c = s;
              }
              else {
                // Early Windows
                s = 0xE052;
                e = 0xE0C6;
                t = 500;
                c = s;
              }
            }
            else if (navigator.userAgentData.platform === "Android") {
              // Android
              s = 0xE052;
              e = 0xE0C6;
              t = 500;
              c = s;
            }
            else {
              // Not Windows and not Android
              s = 0xE052;
              e = 0xE0C6;
              t = 500;
              c = s;
            }
          });
      }
      detectOS();

      function start() {
        h = document.getElementById("loadAnm");
        u();
      }
      let touchCount = 0;
      let touchTimeout;

      function goFullscreen() {
        const htmlElement = document.documentElement; // Seleciona a tag <html>
        if (!document.fullscreenElement) {
          if (htmlElement.requestFullscreen) {
            htmlElement.requestFullscreen();
          } else if (htmlElement.mozRequestFullScreen) { // Firefox
            htmlElement.mozRequestFullScreen();
          } else if (htmlElement.webkitRequestFullscreen) { // Chrome, Safari e Opera
            htmlElement.webkitRequestFullscreen();
          } else if (htmlElement.msRequestFullscreen) { // IE/Edge
            htmlElement.msRequestFullscreen();
          }
        }
      }

      function resetTouchCount() {
        touchCount = 0;
      }

      document.addEventListener('touchstart', () => {
        touchCount++;
        clearTimeout(touchTimeout);
        touchTimeout = setTimeout(resetTouchCount, 500); // Reseta o contador após 500ms de inatividade

        if (touchCount === 5 && !document.fullscreenElement) {
          if (confirm("Deseja entrar em tela cheia?")) {
            goFullscreen();
          }
          resetTouchCount();
        }
      });

      function u() {
        if (c > e) {
          if (b) h.textContent = " ";
          setTimeout(() => {
            c = s;
            u();
          }, t);
        } else {
          h.textContent = String.fromCharCode(c);
          c++;
          setTimeout(u, 25);
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
        i = document.getElementById("boot-logo");
        if (b) i.src = "src/images/w10-bootLogo.svg";
        else i.src = "src/images/w11-bootLogo.svg";
        fontLoaded().then(() => {
          start();
        });
      });

      window.addEventListener('load', () => {
        let i = document.getElementById("loadBody");
        i.classList.add("fade-out");
        setTimeout(() => { document.body.removeChild(i); }, 600);
      });
    </script>
  </head>

  <body style="background-image: url(src/images/BGs/img100.jpg);">
    <div id="loadBody">
      <div class="aTF"></div>
      <div class="anm">
        <div class="logo"><img id="boot-logo"></div>
        <div class="loadFlex">
          <div class="load">
            <div id="loadAnm"> </div>
            <p id="loadText">
              <?php if ($login_successful || $session_active): ?>
                Resumindo o Windows
              <?php else: ?>
                Iniciando o Windows
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
    </div>
    <?php if ($login_successful || $session_active): ?>
      <script>
        document.addEventListener('DOMContentLoaded', (event) => {
          document.body.style.backgroundImage = 'url(/src/images/BGs/img0.jpg)';
          const script = document.createElement('script');
          script.src = '/src/js/taskbar-startMenu.js';
          document.body.appendChild(script);
        });
      </script>
      <?php require 'desktop.php'; ?>
    <?php else: ?>
      <div class="login-container">
        <h2><?php echo htmlspecialchars($username ?? 'Login'); ?></h2>
        <form id="loginForm" action="index.php" method="POST">
          <div class="radio-buttons">
            <?php foreach ($users as $user): ?>
              <div class="radio-button">
                <input type="radio" id="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" name="username"
                  value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" <?php echo ($username === $user['name'] || !$username) ? 'checked' : ''; ?>>
                <label for="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                  <img src="src/images/<?php echo htmlspecialchars($user['name'] ?? ''); ?>.png"
                    alt="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                  <span><?php echo htmlspecialchars($user['name'] ?? ''); ?></span>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="input-container btn-integrated">
            <input type="password" id="password" name="password" placeholder="Senha" required>
            <button type="submit"></button>
          </div>
        </form>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$login_successful): ?>
          <p>Usuário ou senha incorretos.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </body>

</html>

<script>
  document.addEventListener('DOMContentLoaded', (event) => {
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(loginForm);
      fetch('index.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(html => {
          document.body.innerHTML = html;
          if (html.includes('desktop')) {
            document.body.style.backgroundImage = 'url(/src/images/BGs/img100.jpg)';
            const script = document.createElement('script');
            script.src = '/src/js/taskbar-startMenu.js';
            document.body.appendChild(script);
          } else {
            document.body.style.backgroundImage = 'url(/src/images/img0.jpg)';
          }
        });
    });

    const updateFields = (selectedUsername) => {
      fetch('getUserConfig.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'username=' + encodeURIComponent(selectedUsername)
      })
        .then(response => response.json())
        .then(data => {
          const inputContainer = document.querySelector('.input-container');
          inputContainer.innerHTML = '';

          if (data.use_pin) {
            const pinInput = document.createElement('input');
            pinInput.type = 'password';
            pinInput.id = 'pin';
            pinInput.name = 'pin';
            pinInput.placeholder = 'PIN';
            pinInput.pattern = '[0-9]*';
            pinInput.inputmode = 'numeric';
            pinInput.required = true;
            inputContainer.appendChild(pinInput);
          } else {
            const passwordInput = document.createElement('input');
            passwordInput.type = 'password';
            passwordInput.id = 'password';
            passwordInput.name = 'password';
            passwordInput.placeholder = 'Senha';
            passwordInput.required = true;
            inputContainer.appendChild(passwordInput);
          }

          const submitButton = document.createElement('button');
          submitButton.type = 'submit';
          inputContainer.appendChild(submitButton);

          const title = document.querySelector('h2');
          title.textContent = selectedUsername;
        });
    };

    const selectedRadioButton = document.querySelector('input[name="username"]:checked');
    if (selectedRadioButton) {
      updateFields(selectedRadioButton.value);
    }

    const radioButtons = document.querySelectorAll('input[name="username"]');
    radioButtons.forEach(radio => {
      radio.addEventListener('change', function () {
        updateFields(this.value);
      });
    });
  });
</script>