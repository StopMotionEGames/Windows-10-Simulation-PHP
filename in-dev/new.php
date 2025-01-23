<head>
  <script>
    var cls = true;
    var ops;

    window.onload = () => {
      document.querySelector(".container").addEventListener("mouseenter", () => {
        cls = false;
      });
      document.querySelector(".container").addEventListener("mouseleave", () => {
        cls = true;
      });
      ops = document.querySelectorAll(".container div");
      for (let i = 0; i < ops.length; i++) {
        ops[i].addEventListener("click", () => {
          document.querySelector(".context-menu").style.display = "none";
        });
      }

      ops[0].addEventListener("click", () => {
        setTimeout(() => {
          open("https://www.youtube.com/@GamesEAnimations", "_blank")
        }, 50);
      });

      ops[1].addEventListener("click", () => {
        setTimeout(() => {
          /* YOUR FUNCTION */
          alert("Alert 2!");
        }, 50);
      });

      ops[2].addEventListener("click", () => {
        setTimeout(() => {
          /* YOUR FUNCTION */
          alert("Alert 3!");
        }, 50);
      });

      ops[3].addEventListener("click", () => {
        setTimeout(() => {
          /* YOUR FUNCTION */
          alert("Alert 4!");
        }, 50);
      });

      ops[4].addEventListener("click", () => {
        setTimeout(() => {
          /* YOUR FUNCTION */
          alert("Alert 5!");
        }, 50);
      });
    }

    document.addEventListener("contextmenu", () => {
      var e = window.event;
      e.preventDefault();

      var x = e.clientX;
      var y = e.clientY;

      var docX = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth || document.body.offsetWidth;
      var docY = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight || document.body.offsetHeight;

      var border = parseInt(getComputedStyle(document.querySelector(".container"), null).getPropertyValue('border-width'));

      var objX = parseInt(getComputedStyle(document.querySelector(".container"), null).getPropertyValue('width')) + 2;
      var objY = parseInt(getComputedStyle(document.querySelector(".container"), null).getPropertyValue('height')) + 2;

      if (x + objX > docX) {
        let diff = (x + objX) - docX;
        x -= diff + border;
      }

      if (y + objY > docY) {
        let diff = (y + objY) - docY;
        y -= diff + border;
      }

      document.querySelector(".context-menu").style.display = "block";

      document.querySelector(".context-menu").style.top = y + "px";
      document.querySelector(".context-menu").style.left = x + "px";
    });

    window.addEventListener("resize", () => {
      document.querySelector(".context-menu").style.display = "none";
    });

    document.addEventListener("click", () => {
      if (cls) {
        document.querySelector(".context-menu").style.display = "none";
      }
    });
    // document.addEventListener("wheel", function () {
    //   if (cls) {
    //     document.querySelector(".context-menu").style.display = "none";
    //     static = false;
    //   }
    // });
  </script>
  <style>
    .context-menu {
      background-color: #2b2b2b;
      position: absolute;
      z-index: 2;
      display: none;
      user-select: none;
      border: 1px solid black;
    }

    .container {
      display: flex;
      flex-direction: column;
      width: max-content;
      height: auto;
      padding: 2px;
      transition: height .5s;

      div {
        display: flex;
        font-family: arial;
        font-size: 12px;
        padding: 2px 4px;
        justify-content: center;
        align-items: center;

        p {
          margin-block: 6px
        }
      }

      div:hover {
        background: #fff;
        transition: color 0.2s !important;
        cursor: pointer;
      }
    }
  </style>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/icons.css">
</head>
<div class="context-menu">
  <div class="container">
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
    <div>
      <div class="icon">
        <span id="Home" class="ctxt-menu"></span>
      </div>
      <p>Assistir Vídeos do Meu canal</p>
    </div>
  </div>
</div>