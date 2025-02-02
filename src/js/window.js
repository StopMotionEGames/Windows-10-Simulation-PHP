
let nomax = {};
var topmost = [];

let apps = {
  about: {
    init: () => {
      $('#win-about>.about').addClass('show');
    }
  }
}

function openapp(name) {
  if ($('#taskbar>.' + name).length != 0) {
    if ($('.window.' + name).hasClass('min')) {
      minwin(name);
    }
    focwin(name);
    return;
  }
  showwin(name);
}
function showwin(name) {
  $('.window.' + name).addClass('show-begin');
  setTimeout(() => { $('.window.' + name).addClass('show'); }, 0);
  setTimeout(() => { $('.window.' + name).addClass('notrans'); }, 200);
  if (name != 'run') {
    $('.window.' + name).attr('style', `top: 10%;left: 15%;`);
  }
  $('#taskbar>.' + wo[0]).removeClass('foc');
  $('.window.' + wo[0]).removeClass('foc');
  wo.splice(0, 0, name);
  orderwindow();
  $('.window.' + name).addClass('foc');
  if (!$('#start-menu.show')[0] && !$('#search-win.show')[0] && !$('#widgets.show')[0] && !$('#control.show')[0] && !$('#datebox.show')[0]) {
    if ($('.window.max:not(.left):not(.right)')[0]) {
      $('#dock-box').addClass('hide');
    }
    else {
      $('#dock-box').removeClass('hide');
    }
  }
  else {
    $('#dock-box').removeClass('hide')
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
  if (!$('#start-menu.show')[0] && !$('#search-win.show')[0] && !$('#widgets.show')[0] && !$('#control.show')[0] && !$('#datebox.show')[0]) {
    if ($('.window.max:not(.left):not(.right)')[0]) {
      $('#dock-box').addClass('hide');
    }
    else {
      $('#dock-box').removeClass('hide');
    }
  }
  else {
    $('#dock-box').removeClass('hide')
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

function resizewin(win, arg, resizeElt) {
  page.onmousemove = function (e) {
    resizing(win, e, arg);
  }
  page.ontouchmove = function (e) {
    resizing(win, e, arg);
  }
  function up_f() {
    page.onmousedown = null;
    page.ontouchstart = null;
    page.onmousemove = null;
    page.ontouchmove = null;
    page.ontouchcancel = null;
    page.style.cursor = 'auto';
  }
  page.onmouseup = up_f;
  page.ontouchend = up_f;
  page.ontouchcancel = up_f;
  page.style.cursor = window.getComputedStyle(resizeElt, null).cursor;
}
function resizing(win, e, arg) {
  let x, y,
    minWidth = win.dataset.minWidth ? win.dataset.minWidth : 400,
    minHeight = win.dataset.minHeight ? win.dataset.minHeight : 300,
    offsetLeft = win.getBoundingClientRect().left,
    offsetTop = win.getBoundingClientRect().top,
    offsetRight = win.getBoundingClientRect().right,
    offsetBottom = win.getBoundingClientRect().bottom;
  if (e.type.match('mouse')) {
    x = e.clientX;
    y = e.clientY;
  }
  else if (e.type.match('touch')) {
    x = e.touches[0].clientX;
    y = e.touches[0].clientY;
  }
  if (arg == 'right' && x - offsetLeft >= minWidth) {
    win.style.width = x - offsetLeft + 'px';
  }
  else if (arg == 'right') {
    win.style.width = minWidth + 'px';
  }

  if (arg == 'left' && offsetRight - x >= minWidth) {
    win.style.left = x + 'px';
    win.style.width = offsetRight - x + 'px';
  }
  else if (arg == 'left') {
    win.style.width = minWidth + 'px';
    win.style.left = offsetRight - minWidth + 'px';
  }

  if (arg == 'bottom' && y - offsetTop >= minHeight) {
    win.style.height = y - offsetTop + 'px';
  }
  else if (arg == 'bottom') {
    win.style.height = minHeight + 'px';
  }

  if (arg == 'top' && offsetBottom - y >= minHeight) {
    win.style.top = y + 'px';
    win.style.height = offsetBottom - y + 'px';
  }
  else if (arg == 'top') {
    win.style.top = offsetBottom - minHeight + 'px';
    win.style.height = minHeight + 'px';
  }

  if (arg == 'top-left') {
    if (offsetRight - x >= minWidth) {
      win.style.left = x + 'px';
      win.style.width = offsetRight - x + 'px';
    }
    else {
      win.style.left = offsetRight - minWidth + 'px';
      win.style.width = minWidth + 'px';
    }
    if (offsetBottom - y >= minHeight) {
      win.style.top = y + 'px';
      win.style.height = offsetBottom - y + 'px';
    }
    else {
      win.style.top = offsetBottom - minHeight + 'px';
      win.style.height = minHeight + 'px';
    }
  }

  else if (arg == 'top-right') {
    if (x - offsetLeft >= minWidth) {
      win.style.width = x - offsetLeft + 'px';
    }
    else {
      win.style.width = minWidth + 'px';
    }
    if (offsetBottom - y >= minHeight) {
      win.style.top = y + 'px';
      win.style.height = offsetBottom - y + 'px';
    }
    else {
      win.style.top = offsetBottom - minHeight + 'px';
      win.style.height = minHeight + 'px';
    }
  }

  else if (arg == 'bottom-left') {
    if (offsetRight - x >= minWidth) {
      win.style.left = x + 'px';
      win.style.width = offsetRight - x + 'px';
    }
    else {
      win.style.left = offsetRight - minWidth + 'px';
      win.style.width = minWidth + 'px';
    }
    if (y - offsetTop >= minHeight) {
      win.style.height = y - offsetTop + 'px';
    }
    else {
      win.style.height = minHeight + 'px';
    }
  }

  else if (arg == 'bottom-right') {
    if (x - offsetLeft >= minWidth) {
      win.style.width = x - offsetLeft + 'px';
    }
    else {
      win.style.width = minWidth + 'px';
    }
    if (y - offsetTop >= minHeight) {
      win.style.height = y - offsetTop + 'px';
    }
    else {
      win.style.height = minHeight + 'px';
    }
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
try {
  navigator.getBattery().then((battery) => {
    $('.a.dock.control>svg>path')[0].outerHTML = `<path
            d="M 4 7 C 2.3550302 7 1 8.3550302 1 10 L 1 19 C 1 20.64497 2.3550302 22 4 22 L 24 22 C 25.64497 22 27 20.64497 27 19 L 27 10 C 27 8.3550302 25.64497 7 24 7 L 4 7 z M 4 9 L 24 9 C 24.56503 9 25 9.4349698 25 10 L 25 19 C 25 19.56503 24.56503 20 24 20 L 4 20 C 3.4349698 20 3 19.56503 3 19 L 3 10 C 3 9.4349698 3.4349698 9 4 9 z M 5 11 L 5 18 L ${18 * battery.level + 5} 18 L ${18 * battery.level + 5} 11 L 5 11 z M 28 12 L 28 17 L 29 17 C 29.552 17 30 16.552 30 16 L 30 13 C 30 12.448 29.552 12 29 12 L 28 12 z"
            id="path2" fill="#000000"
        />`;

    battery.addEventListener('levelchange', () => {
      $('.a.dock.control>svg>path')[0].outerHTML = `<path
                d="M 4 7 C 2.3550302 7 1 8.3550302 1 10 L 1 19 C 1 20.64497 2.3550302 22 4 22 L 24 22 C 25.64497 22 27 20.64497 27 19 L 27 10 C 27 8.3550302 25.64497 7 24 7 L 4 7 z M 4 9 L 24 9 C 24.56503 9 25 9.4349698 25 10 L 25 19 C 25 19.56503 24.56503 20 24 20 L 4 20 C 3.4349698 20 3 19.56503 3 19 L 3 10 C 3 9.4349698 3.4349698 9 4 9 z M 5 11 L 5 18 L ${18 * battery.level + 5} 18 L ${18 * battery.level + 5} 11 L 5 11 z M 28 12 L 28 17 L 29 17 C 29.552 17 30 16.552 30 16 L 30 13 C 30 12.448 29.552 12 29 12 L 28 12 z"
                id="path2" fill="#000000"
            />`;
    });
  });
} catch (TypeError) {
  console.log('Internal error: Unable to get battery');
}

let chstX, chstY;
function ch(e) {
  $('#desktop>.choose').css('left', Math.min(chstX, e.clientX));
  $('#desktop>.choose').css('width', Math.abs(e.clientX - chstX));
  $('#desktop>.choose').css('display', 'block');
  $('#desktop>.choose').css('top', Math.min(chstY, e.clientY));
  $('#desktop>.choose').css('height', Math.abs(e.clientY - chstY));
}
$('#desktop')[0].addEventListener('mousedown', e => {
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
  }
  else {
    cx = e.clientX, cy = e.clientY;
  }
  // $(this).css('cssText', `left:${cx - deltaLeft}px;top:${cy - deltaTop}px;`);
  $(this).css('left', `${cx - deltaLeft}px`);
  $(this).css('top', `${cy - deltaTop}px`);
  if (cy <= 0) {
    // $(this).css('cssText', `left:${cx - deltaLeft}px;top:${-deltaTop}px`);
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
    // console.log(this.classList[1], nomax,this.classList[1] in nomax,not this.classList[1] in nomax);
  }
  else if (cx <= 0) {
    // $(this).css('cssText', `left:${-deltaLeft}px;top:${cy - deltaTop}px`);
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
  }
  else if (cx >= document.body.offsetWidth - 2) {
    // $(this).css('cssText', `left:calc(100% - ${deltaLeft}px);top:${cy - deltaTop}px`);
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
  }
  else if (fil) {
    $('#window-fill').removeClass('fill');
    setTimeout(() => {
      $('#window-fill').removeClass('top');
      $('#window-fill').removeClass('left');
      $('#window-fill').removeClass('right');
    }, 200);
    fil = false;
    filty = 'none';
  }
  else if ($(this).hasClass('max')) {
    deltaLeft = deltaLeft / (this.offsetWidth - (45 * 3)) * ((0.7 * document.body.offsetWidth) - (45 * 3));
    maxwin(this.classList[1], false);
    // 窗口控制按钮宽 45px
    // $(this).css('cssText', `left:${cx - deltaLeft}px;top:${cy - deltaTop}px;`);
    $(this).css('left', `${cx - deltaLeft}px`);
    $(this).css('top', `${cy - deltaTop}px`);
    $('.window.' + this.classList[1] + '>.titbar>div>.wbtg.max').html('<i class="bi bi-app"></i>');

    $(this).addClass('notrans');
  }
}
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