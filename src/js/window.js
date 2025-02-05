const pages = {
  'get-title': '',
};
let nomax = {};
var topmost = [];

let apps = {
  ms_settings: {
    init: () => {
      let desktopWindow = document.querySelector(".window.ms_settings");
      desktopWindow.classList.add('foc');
      makeResizable(desktopWindow, 502, 332);
      console.log(desktopWindow);
      let content = desktopWindow.querySelector("iframe");
      content.src = "/Windows/ImmersiveControlPanel/SystemSettings.php";
    }
  },
  explorer: {
    init: () => {
      let desktopWindow = document.querySelector(".window.explorer");
      desktopWindow.classList.add('foc');
      makeResizable(desktopWindow, 146, 236);
      console.log(desktopWindow);
      let content = desktopWindow.querySelector("iframe");
      content.src = "/Windows/ImmersiveControlPanel/SystemSettings.php";
    }
  }
}

function openapp(name) {
  if (apps[name] && typeof apps[name].init === "function") {
    apps[name].init();
  } else {
    console.log(`App ${name} não encontrado ou não possui função init.`);
  }

  const taskbarElement = document.querySelector(`#taskbar .${name}`);
  if (taskbarElement) {
    const windowElement = document.querySelector(`.window.${name}`);
    if (windowElement.classList.contains('min')) {
      minwin(name);
    }
    focwin(name);
    return;
  }
  showwin(name);
}
function showwin(name) {
  const windowElement = document.querySelector(`.window.${name}`);
  windowElement.classList.add('show-begin');
  windowElement.classList.add('show');
  setTimeout(() => { windowElement.classList.add('notrans'); }, 200);
  if (name !== 'run') {
    windowElement.style.top = '10%';
    windowElement.style.left = '15%';
  }
}
function hidewin(name) {
  $('.window.' + name).removeClass('notrans');
  $('.window.' + name).removeClass('max');
  $('.window.' + name).removeClass('show');
  setTimeout(() => {
    $('.window.' + name).removeClass('show-begin');
    if (name == 'run') {
      window.setTimeout(() => {
        $('.window.' + name).attr('style', '');
      }, 200)
    }
  }, 200);
  $('.window.' + name + '>.titbar>div>.wbtg.max').html('<i class="bi bi-app"></i>');
  wo.splice(wo.indexOf(name), 1);
  focwin(wo[wo.length - 1]);
}
function maxwin(name, trigger = true) {
  if ($('.window.' + name).hasClass('max')) {
    $('.window.' + name).removeClass('left');
    $('.window.' + name).removeClass('right');
    $('.window.' + name).removeClass('max');
    $('.window.' + name + '>.titbar>div>.wbtg.max').html('<i class="bi bi-app"></i>');
    if (trigger) {
      setTimeout(() => { $('.window.' + name).addClass('notrans'); }, 200);
    }
    else if (!trigger) {
      $('.window.' + name).addClass('notrans');
    }
    if ($('.window.' + name).attr('data-pos-x') != 'null' && $('.window.' + name).attr('data-pos-y') != 'null') {
      $('.window.' + name).css('left', `${$('.window.' + name).attr('data-pos-x')}`);
      $('.window.' + name).css('top', `${$('.window.' + name).attr('data-pos-y')}`);
    }
  } else {
    if (trigger) {
      $('.window.' + name).attr('data-pos-x', `${$('.window.' + name).css('left')}`);
      $('.window.' + name).attr('data-pos-y', `${$('.window.' + name).css('top')}`);
    }
    $('.window.' + name).removeClass('notrans');
    $('.window.' + name).addClass('max');
    $('.window.' + name + '>.titbar>div>.wbtg.max').html('<svg version="1.1" width="12" height="12" viewBox="0,0,37.65105,35.84556" style="margin-top:4px;"><g transform="translate(-221.17804,-161.33903)"><g style="stroke:var(--text);" data-paper-data="{&quot;isPaintingLayer&quot;:true}" fill="none" fill-rule="nonzero" stroke-width="2" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" style="mix-blend-mode: normal"><path d="M224.68734,195.6846c-2.07955,-2.10903 -2.00902,-6.3576 -2.00902,-6.3576l0,-13.72831c0,0 -0.23986,-1.64534 2.00902,-4.69202c1.97975,-2.68208 4.91067,-2.00902 4.91067,-2.00902h14.06315c0,0 3.77086,-0.23314 5.80411,1.67418c2.03325,1.90732 1.33935,5.02685 1.33935,5.02685v13.39347c0,0 0.74377,4.01543 -1.33935,6.3576c-2.08312,2.34217 -5.80411,1.67418 -5.80411,1.67418h-13.39347c0,0 -3.50079,0.76968 -5.58035,-1.33935z"/><path d="M229.7952,162.85325h16.06111c0,0 5.96092,-0.36854 9.17505,2.64653c3.21412,3.01506 2.11723,7.94638 2.11723,7.94638v18.55642"/></g></g></svg>')
  }
}
function minwin(name) {
  if ($('.window.' + name).hasClass('min')) {
    $('.window.' + name).addClass('show-begin');
    focwin(name);
    setTimeout(() => {
      $('#taskbar>.' + name).removeClass('min');
      $('.window.' + name).removeClass('min');
      if ($('.window.' + name).hasClass('min-max')) {
        $('.window.' + name).addClass('max');
      }
      $('.window.' + name).removeClass('min-max');
    }, 0);
    setTimeout(() => {
      if (!$('.window.' + name).hasClass('max')) {
        $('.window.' + name).addClass('notrans');
      }
    }, 200);
  } else {
    focwin(null);
    if ($('.window.' + name).hasClass('max')) {
      $('.window.' + name).addClass('min-max');
    }
    $('.window.' + name).removeClass('foc');
    $('.window.' + name).removeClass('max');
    $('#taskbar>.' + name).addClass('min');
    $('.window.' + name).addClass('min');
    $('.window.' + name).removeClass('notrans');
    setTimeout(() => { $('.window.' + name).removeClass('show-begin'); }, 200);
  }
}
function makeResizable(element, minW = 100, minH = 100, size = 16) {
  const resizers = [
    { edge: 'top', cursor: 'n-resize', resizeFunc: resizeYNegative },
    { edge: 'bottom', cursor: 'n-resize', resizeFunc: resizeYPositive },
    { edge: 'left', cursor: 'e-resize', resizeFunc: resizeXNegative },
    { edge: 'right', cursor: 'e-resize', resizeFunc: resizeXPositive },
    { edge: 'top-left', cursor: 'nw-resize', resizeFunc: resizeXYNegative },
    { edge: 'top-right', cursor: 'ne-resize', resizeFunc: resizeXYTopRight },
    { edge: 'bottom-left', cursor: 'sw-resize', resizeFunc: resizeXYBottomLeft },
    { edge: 'bottom-right', cursor: 'se-resize', resizeFunc: resizeXYPositive }
  ];

  let isResizing = false;

  resizers.forEach(({ edge, cursor, resizeFunc }) => {
    const resizer = document.createElement('div');
    resizer.style.width = edge.includes('left') || edge.includes('right') ? size + 'px' : `calc(100% + ${size - 8}px)`;
    resizer.style.height = edge.includes('top') || edge.includes('bottom') ? size + 'px' : `calc(100% + ${size - 8}px)`;
    resizer.style.backgroundColor = 'transparent';
    resizer.style.position = 'absolute';
    resizer.style[edge.split('-')[0]] = edge.includes('top') || edge.includes('left') ? -10 + 'px' : '0px';
    resizer.style[edge.split('-')[1]] = edge.includes('bottom') || edge.includes('right') ? -10 + 'px' : '0px';
    resizer.style.cursor = cursor;

    resizer.addEventListener('mousedown', resizeFunc());

    element.appendChild(resizer);
  });

  function get_int_style(key) {
    return parseInt(window.getComputedStyle(element).getPropertyValue(key));
  }

  function resizeXPositive() {
    let offsetX;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      offsetX = e.clientX - element.offsetLeft - get_int_style('width');
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let x = e.clientX - element.offsetLeft - offsetX;
      if (x < minW) x = minW;
      element.style.width = x + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeYPositive() {
    let offsetY;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      offsetY = e.clientY - element.offsetTop - get_int_style('height');
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let y = e.clientY - element.offsetTop - offsetY;
      if (y < minH) y = minH;
      element.style.height = y + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeXNegative() {
    let offsetX, startX, startW, maxX;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      startX = get_int_style('left');
      startW = get_int_style('width');
      offsetX = e.clientX - startX;
      maxX = startX + startW - minW;
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let x = e.clientX - offsetX;
      let w = startW + startX - x;
      if (w < minW) w = minW;
      if (x > maxX) x = maxX;
      element.style.left = x + 'px';
      element.style.width = w + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeYNegative() {
    let offsetY, startY, startH, maxY;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      startY = get_int_style('top');
      startH = get_int_style('height');
      offsetY = e.clientY - startY;
      maxY = startY + startH - minH;
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let y = e.clientY - offsetY;
      let h = startH + startY - y;
      if (h < minH) h = minH;
      if (y > maxY) y = maxY;
      element.style.top = y + 'px';
      element.style.height = h + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeXYNegative() {
    let offsetX, offsetY, startX, startY, startW, startH, maxX, maxY;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      startX = get_int_style('left');
      startW = get_int_style('width');
      offsetX = e.clientX - startX;
      maxX = startX + startW - minW;
      startY = get_int_style('top');
      startH = get_int_style('height');
      offsetY = e.clientY - startY;
      maxY = startY + startH - minH;
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let x = e.clientX - offsetX;
      let y = e.clientY - offsetY;
      let w = startW + startX - x;
      let h = startH + startY - y;
      if (w < minW) w = minW;
      if (h < minH) h = minH;
      if (x > maxX) x = maxX;
      if (y > maxY) y = maxY;
      element.style.left = x + 'px';
      element.style.width = w + 'px';
      element.style.top = y + 'px';
      element.style.height = h + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeXYPositive() {
    let offsetX, offsetY;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      offsetX = e.clientX - element.offsetLeft - get_int_style('width');
      offsetY = e.clientY - element.offsetTop - get_int_style('height');
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }
    function elementDrag(e) {
      if (!isResizing) return;
      let x = e.clientX - element.offsetLeft - offsetX;
      let y = e.clientY - element.offsetTop - offsetY;
      if (x < minW) x = minW;
      if (y < minH) y = minH;
      element.style.width = x + 'px';
      element.style.height = y + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeXYTopRight() {
    let offsetX, offsetY, startY, startH, maxY;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      offsetX = e.clientX - element.offsetLeft - get_int_style('width');
      startY = get_int_style('top');
      startH = get_int_style('height');
      offsetY = e.clientY - startY;
      maxY = startY + startH - minH;
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let x = e.clientX - element.offsetLeft - offsetX;
      let y = e.clientY - offsetY;
      let h = startH + startY - y;
      if (x < minW) x = minW;
      if (h < minH) h = minH;
      if (y > maxY) y = maxY;
      element.style.width = x + 'px';
      element.style.top = y + 'px';
      element.style.height = h + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }

  function resizeXYBottomLeft() {
    let offsetX, offsetY, startX, startW, maxX;
    function dragMouseDown(e) {
      if (e.button !== 0) return;
      e.preventDefault();
      isResizing = true;
      startX = get_int_style('left');
      startW = get_int_style('width');
      offsetX = e.clientX - startX;
      maxX = startX + startW - minW;
      offsetY = e.clientY - element.offsetTop - get_int_style('height');
      document.addEventListener('mousemove', elementDrag);
      document.addEventListener('mouseup', closeDragElement);
      document.addEventListener('mouseleave', closeDragElement);
    }

    function elementDrag(e) {
      if (!isResizing) return;
      let x = e.clientX - offsetX;
      let y = e.clientY - element.offsetTop - offsetY;
      let w = startW + startX - x;
      if (w < minW) w = minW;
      if (x > maxX) x = maxX;
      if (y < minH) y = minH;
      element.style.left = x + 'px';
      element.style.width = w + 'px';
      element.style.height = y + 'px';
    }

    function closeDragElement() {
      isResizing = false;
      document.removeEventListener('mousemove', elementDrag);
      document.removeEventListener('mouseup', closeDragElement);
      document.removeEventListener('mouseleave', closeDragElement);
    }

    return dragMouseDown;
  }
}
let wo = [];
function orderwindow() {
  for (let i = 0; i < wo.length; i++) {
    const win = $('.window.' + wo[wo.length - i - 1]);
    if (topmost.includes(wo[wo.length - i - 1])) {
      win.css('z-index', 10 + i + 50);
    } else {
      win.css('z-index', 10 + i);
    }
  }
}
function focwin(name) {
  $('.window.' + wo[0]).removeClass('foc');
  wo.splice(wo.indexOf(name), 1);
  wo.splice(0, 0, name);
  orderwindow();
  $('.window.' + name).addClass('foc');
}
let chstX, chstY;
function ch(e) {
  $('.desktop>.choose').css('left', Math.min(chstX, e.clientX));
  $('.desktop>.choose').css('width', Math.abs(e.clientX - chstX));
  $('.desktop>.choose').css('display', 'block');
  $('.desktop>.choose').css('top', Math.min(chstY, e.clientY));
  $('.desktop>.choose').css('height', Math.abs(e.clientY - chstY));
}
$('.desktop')[0].addEventListener('mousedown', e => {
  chstX = e.clientX;
  chstY = e.clientY;
  this.onmousemove = ch;
})

const page = document.getElementsByTagName('html')[0];
const titbars = document.querySelectorAll('.window>.titbar');
const wins = document.querySelectorAll('.window');
let deltaLeft = 0, deltaTop = 0, fil = false, filty = 'none', bfLeft = 0, bfTop = 0;
function win_move(e) {
  let cx, cy;
  if (e.type == 'touchmove') {
    cx = e.targetTouches[0].clientX, cy = e.targetTouches[0].clientY;
  } else {
    cx = e.clientX, cy = e.clientY;
  }
  $(this).css('left', `${cx - deltaLeft}px`);
  $(this).css('top', `${cy - deltaTop}px`);
  if (cy <= 0) {
    $(this).css('left', `${cx - deltaLeft}px`);
    $(this).css('top', `${-deltaTop}px`);
    if (!(this.classList[1] in nomax)) {
      $('#window-fill').addClass('top');
      setTimeout(() => {
        $('#window-fill').addClass('fill');
      }, 0);
      fil = this;
      filty = 'top';
    }
  } else if (cx <= 0) {
    $(this).css('left', `${-deltaLeft}px`);
    $(this).css('top', `${cy - deltaTop}px`);
    if (!(this.classList[1] in nomax)) {
      $('#window-fill').addClass('left');
      setTimeout(() => {
        $('#window-fill').addClass('fill');
      }, 0);
      fil = this;
      filty = 'left';
    }
  } else if (cx >= document.body.offsetWidth - 2) {
    $(this).css('left', `calc(100% - ${deltaLeft}px)`);
    $(this).css('top', `${cy - deltaTop}px`);
    if (!(this.classList[1] in nomax)) {
      $('#window-fill').addClass('right');
      setTimeout(() => {
        $('#window-fill').addClass('fill');
      }, 0);
      fil = this;
      filty = 'right';
    }
  } else if (fil) {
    $('#window-fill').removeClass('fill');
    setTimeout(() => {
      $('#window-fill').removeClass('top');
      $('#window-fill').removeClass('left');
      $('#window-fill').removeClass('right');
    }, 200);
    fil = false;
    filty = 'none';
  } else if ($(this).hasClass('max')) {
    deltaLeft = deltaLeft / (this.offsetWidth - (45 * 3)) * ((0.7 * document.body.offsetWidth) - (45 * 3));
    maxwin(this.classList[1], false);
    $(this).css('left', `${cx - deltaLeft}px`);
    $(this).css('top', `${cy - deltaTop}px`);
    $('.window.' + this.classList[1] + '>.titbar>div>.wbtg.max').html('<i class="bi bi-app"></i>');
    $(this).addClass('notrans');
  }
}

function startDrag(e) {
  document.body.classList.add('no-select');
  document.addEventListener('mousemove', win_move);
  document.addEventListener('mouseup', stopDrag);
}

function stopDrag() {
  document.body.classList.remove('no-select');
  document.removeEventListener('mousemove', win_move);
  document.removeEventListener('mouseup', stopDrag);
}

// Adicione o evento de mousedown à barra de título da janela
$('.titbar').on('mousedown', startDrag);
for (let i = 0; i < wins.length; i++) {
  const win = wins[i];
  const titbar = titbars[i];
  titbar.addEventListener('mousedown', (e) => {
    if ($('.taskmgr>.titbar>div>input').is(':focus')) {
      return
    }
    let x = window.getComputedStyle(win, null).getPropertyValue('left').split("px")[0];
    let y = window.getComputedStyle(win, null).getPropertyValue('top').split("px")[0];
    if (y != 0) {
      bfLeft = x;
      bfTop = y;
    }
    deltaLeft = e.clientX - x;
    deltaTop = e.clientY - y;
    page.onmousemove = win_move.bind(win);
  })
  titbar.addEventListener('touchstart', (e) => {
    let x = window.getComputedStyle(win, null).getPropertyValue('left').split("px")[0];
    let y = window.getComputedStyle(win, null).getPropertyValue('top').split("px")[0];
    if (y != 0) {
      bfLeft = x;
      bfTop = y;
    }
    deltaLeft = e.targetTouches[0].clientX - x;
    deltaTop = e.targetTouches[0].clientY - y;
    page.ontouchmove = win_move.bind(win);
  })
}
page.addEventListener('mouseup', () => {
  page.onmousemove = null;
  if (fil) {
    if (filty == 'top') {
      maxwin(fil.classList[1], false);
    }
    else if (filty == 'left') {
      $(fil).addClass('left');
      maxwin(fil.classList[1], false);
    }
    else if (filty == 'right') {
      $(fil).addClass('right');
      maxwin(fil.classList[1], false);
    }
    setTimeout(() => {
      $('#window-fill').removeClass('fill');
      $('#window-fill').removeClass('top');
      $('#window-fill').removeClass('left');
      $('#window-fill').removeClass('right');
    }, 200);
    $('.window.' + fil.classList[1]).attr('data-pos-x', `${bfLeft}px`);
    $('.window.' + fil.classList[1]).attr('data-pos-y', `${bfTop}px`);
    fil = false;
  }
});
page.addEventListener('touchend', () => {
  page.ontouchmove = null;
  if (fil) {
    if (filty == 'top')
      maxwin(fil.classList[1], false);
    else if (filty == 'left') {
      maxwin(fil.classList[1], false);
      $(fil).addClass('left');
    } else if (filty == 'right') {
      maxwin(fil.classList[1], false);
      $(fil).addClass('right');
    }
    setTimeout(() => {
      $('#window-fill').removeClass('fill');
      $('#window-fill').removeClass('top');
      $('#window-fill').removeClass('left');
      $('#window-fill').removeClass('right');
    }, 200);
    setTimeout(() => {
      $('.window.' + fil.classList[1]).attr('data-pos-x', `${bfLeft}px`);
      $('.window.' + fil.classList[1]).attr('data-pos-y', `${bfTop}px`);
    }, 200);
    fil.setAttribute('style', `left:${bfLeft}px;top:${bfTop}px`);
    fil = false;
  }
});


document.getElementsByTagName('body')[0].onload = function nupd() {
  document.querySelectorAll('.window').forEach(w => {
    let qw = $(w), wc = w.classList[1];
    // window: onmousedown="focwin('explorer')" ontouchstart="focwin('explorer')"
    qw.attr('onmousedown', `focwin('${wc}')`);
    qw.attr('ontouchstart', `focwin('${wc}')`);
    // titbar: oncontextmenu="return showcm(event,'titbar','edge')" ondblclick="maxwin('edge')"
    qw = $(`.window.${wc}>.titbar`);
    qw.attr('oncontextmenu', `return showcm(event,'titbar','${wc}')`);
    if (!(wc in nomax)) {
      qw.attr('ondblclick', `maxwin('${wc}')`);
    }
    // icon: onclick="return showcm(event,'titbar','explorer')"
    qw = $(`.window.${wc}>.titbar>.icon`);
    qw.attr('onclick', `let os=$(this).offset();stop(event);return showcm({clientX:os.left-5,clientY:os.top+this.offsetHeight+3},'titbar','${wc}')`);
    qw.mousedown(stop);
    $(`.window.${wc}>.titbar>div>.wbtg`).mousedown(stop);
  });
  document.querySelectorAll('.window>div.resize-bar').forEach(w => {
    for (const n of ['top', 'bottom', 'left', 'right', 'top-right', 'top-left', 'bottom-right', 'bottom-left']) {
      w.insertAdjacentHTML('afterbegin', `<div class="resize-knob ${n}" onmousedown="resizewin(this.parentElement.parentElement, '${n}', this)"></div>`);
    }
  });
};