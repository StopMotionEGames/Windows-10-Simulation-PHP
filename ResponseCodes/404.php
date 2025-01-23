<body>
  <div class="bsod0"><a href="/"></a></div>
  <div class="bsod1">
    <p>
      Você parou em uma página de erro e poderá retornar agora a página anterior. Isso deve por essa página não ter sida encontrada.
    </p>
  </div>
  <div class="bsod2">Nada para coletar</div>
  <div class="bsod3">
    <img src="/ResponseCodes/imgs/404.png" alt="">
    <div class="bsod4">
      <div class="bsod7">
        Nosso servidor detectou que você tentou acessar uma página que pode não existir.
      </div>
      <div class="bsod5">
        Você pode ter digitado o link de forma errada, ou ele está quebrado.
      </div>
      <div class="bsod6">
        <p>O que falhou: <?php echo $requestedUrl ?><br></p>
        <br>
        Código de parada: <?php echo $stopCode ?>
      </div>
    </div>
  </div>
</body>