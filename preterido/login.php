<div class="login-container">
  <div class="usr-decor">
    <img src="../src/images/usr-icon.png" alt="">
    <p class="usr-name">ADM</p>
  </div>
  <form id="loginForm" method="POST">
    <!-- <input type="email" name="email" placeholder="Email" required> -->
    <input type="password" name="password" placeholder="Senha" required>
    <div class="bef-button">
      <input class="button btn" type="submit" name="submit" value="Entrar" onclick="load()"></input>
    </div>
  </form>
  <div class="loader" style="visibility: hidden;">
    Bem-vindo
    <progressringI style="width: 35px;height: 35px;"></progressringI>
  </div>
</div>
<script>
  let form = document.querySelector("#loginForm")
  const progressringI = document.querySelector(".loader progressringI")
  const loader = document.querySelector('.loader');
  function load() {
    form.style.display = 'none';
    loader.style.visibility = 'visible';
    progressringI.innerHTML = "<div class=\"dotsPRing\"><div class=\"innerPRing\"></div></div><div class=\"dotsPRing\" style=\"animation-delay: 0.2s\"><div class=\"innerPRing\"></div></div><div class=\"dotsPRing\" style=\"animation-delay: 0.4s\"><div class=\"innerPRing\"></div></div><div class=\"dotsPRing\" style=\"animation-delay: 0.6s\"><div class=\"innerPRing\"></div></div><div class=\"dotsPRing\" style=\"animation-delay: 0.8s\"><div class=\"innerPRing\"></div></div>"
  }
</script>