function Window(id) {
 var _minW = 100,
  _minH = 1,
  _resizePixel = 5,
  _hasEventListeners = !!window.addEventListener,
  _window,
  _windowTitle,
  _windowContent,
  _dialogButtonPane,
  _maxX, _maxY,
  _startX, _startY,
  _startW, _startH,
  _leftPos, _topPos,
  _isDrag = false,
  _isResize = false,
  _isButton = false,
  _resizeMode = '',
  _buttons,
  _tabBoundary,
  _zIndex,
  _zIndexFlag = false,
  _setCursor,
  _setWindowContent,
  _addEvent = function (elm, evt, callback) {
   if (elm == null || typeof (elm) == undefined)
    return;
   if (_hasEventListeners)
    elm.addEventListener(evt, callback, false);
   else if (elm.attachEvent)
    elm.attachEvent('on' + evt, callback);
   else
    elm['on' + evt] = callback;
  },
  _returnEvent = function (evt) {
   if (evt.stopPropagation)
    evt.stopPropagation();
   if (evt.preventDefault)
    evt.preventDefault();
   else {
    evt.returnValue = false;
    return false;
   }
  },
  _onFocus = function (evt) {
   evt = evt || window.event;
   evt.target.classList.add('focus');
   return _returnEvent(evt);
  },
  _onBlur = function (evt) {
   evt = evt || window.event;
   evt.target.classList.remove('focus');
   return _returnEvent(evt);
  },
  _onClick = function (evt) {
   evt = evt || window.event;
   return _returnEvent(evt);
  },
  _onMouseDown = function (evt) {
   evt = evt || window.event;
   _zIndexFlag = true;
   if (_zIndexFlag) {
    _window.style.zIndex = _zIndex + 1;
    _zIndexFlag = false;
   } else {
    _window.style.zIndex = _zIndex;
   }
   if (!(evt.target === _window || evt.target === _windowTitle))
    return;
   var rect = _getOffset(_window);
   _maxX = Math.max(
    document.documentElement["clientWidth"],
    document.body["scrollWidth"],
    document.documentElement["scrollWidth"],
    document.body["offsetWidth"],
    document.documentElement["offsetWidth"]
   );
   _maxY = Math.max(
    document.documentElement["clientHeight"],
    document.body["scrollHeight"],
    document.documentElement["scrollHeight"],
    document.body["offsetHeight"],
    document.documentElement["offsetHeight"]
   );
   if (rect.right > _maxX)
    _maxX = rect.right;
   if (rect.bottom > _maxY)
    _maxY = rect.bottom;
   _startX = evt.pageX;
   _startY = evt.pageY;
   _startW = _window.clientWidth;
   _startH = _window.clientHeight;
   _leftPos = rect.left;
   _topPos = rect.top;
   if (evt.target === _windowTitle && _resizeMode == '') {
    _isDrag = true;
   }
   else if (_resizeMode != '') {
    _isResize = true;
   }
   var r = _window.getBoundingClientRect();
   return _returnEvent(evt);
  },
  _onMouseMove = function (evt) {
   evt = evt || window.event;
   if (!(evt.target === _window || evt.target === _windowTitle) && !_isDrag && _resizeMode == '')
    return;
   if (_isDrag) {
    var dx = _startX - evt.pageX,
     dy = _startY - evt.pageY,
     left = _leftPos - dx,
     top = _topPos - dy,
     scrollL = Math.max(document.body.scrollLeft, document.documentElement.scrollLeft),
     scrollT = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
    if (dx < 0) {
     if (left + _startW > _maxX)
      left = _maxX - _startW;
    }
    if (dx > 0) {
     if (left < 0)
      left = 0;
    }
    if (dy < 0) {
     if (top + _startH > _maxY)
      top = _maxY - _startH;
    }
    if (dy > 0) {
     if (top < 0)
      top = 0;
    }
    _window.style.left = left + 'px';
    _window.style.top = top + 'px';
    if (evt.clientY > window.innerHeight - 32)
     scrollT += 32;
    else if (evt.clientY < 32)
     scrollT -= 32;
    if (evt.clientX > window.innerWidth - 32)
     scrollL += 32;
    else if (evt.clientX < 32)
     scrollL -= 32;
    if (top + _startH == _maxY)
     scrollT = _maxY - window.innerHeight + 20;
    else if (top == 0)
     scrollT = 0;
    if (left + _startW == _maxX)
     scrollL = _maxX - window.innerWidth + 20;
    else if (left == 0)
     scrollL = 0;
    if (_startH > window.innerHeight) {
     if (evt.clientY < window.innerHeight / 2)
      scrollT = 0;
     else
      scrollT = _maxY - window.innerHeight + 20;
    }
    if (_startW > window.innerWidth) {
     if (evt.clientX < window.innerWidth / 2)
      scrollL = 0;
     else
      scrollL = _maxX - window.innerWidth + 20;
    }
    window.scrollTo(scrollL, scrollT);
   }
   else if (_isResize) {
    var dw, dh, w, h;
    if (_resizeMode == 'w') {
     dw = _startX - evt.pageX;
     if (_leftPos - dw < 0)
      dw = _leftPos;
     w = _startW + dw;
     if (w < _minW) {
      w = _minW;
      dw = w - _startW;
     }
     _window.style.width = w + 'px';
     _window.style.left = (_leftPos - dw) + 'px';
    }
    else if (_resizeMode == 'e') {
     dw = evt.pageX - _startX;
     if (_leftPos + _startW + dw > _maxX)
      dw = _maxX - _leftPos - _startW;
     w = _startW + dw;
     if (w < _minW)
      w = _minW;
     _window.style.width = w + 'px';
    }
    else if (_resizeMode == 'n') {
     dh = _startY - evt.pageY;
     if (_topPos - dh < 0)
      dh = _topPos;
     h = _startH + dh;
     if (h < _minH) {
      h = _minH;
      dh = h - _startH;
     }
     _window.style.height = h + 'px';
     _window.style.top = (_topPos - dh) + 'px';
    }
    else if (_resizeMode == 's') {
     dh = evt.pageY - _startY;
     if (_topPos + _startH + dh > _maxY)
      dh = _maxY - _topPos - _startH;
     h = _startH + dh;
     if (h < _minH)
      h = _minH;
     _window.style.height = h + 'px';
    }
    else if (_resizeMode == 'nw') {
     dw = _startX - evt.pageX;
     dh = _startY - evt.pageY;
     if (_leftPos - dw < 0)
      dw = _leftPos;
     if (_topPos - dh < 0)
      dh = _topPos;
     w = _startW + dw;
     h = _startH + dh;
     if (w < _minW) {
      w = _minW;
      dw = w - _startW;
     }
     if (h < _minH) {
      h = _minH;
      dh = h - _startH;
     }
     _window.style.width = w + 'px';
     _window.style.height = h + 'px';
     _window.style.left = (_leftPos - dw) + 'px';
     _window.style.top = (_topPos - dh) + 'px';
    }
    else if (_resizeMode == 'sw') {
     dw = _startX - evt.pageX;
     dh = evt.pageY - _startY;
     if (_leftPos - dw < 0)
      dw = _leftPos;
     if (_topPos + _startH + dh > _maxY)
      dh = _maxY - _topPos - _startH;
     w = _startW + dw;
     h = _startH + dh;
     if (w < _minW) {
      w = _minW;
      dw = w - _startW;
     }
     if (h < _minH)
      h = _minH;
     _window.style.width = w + 'px';
     _window.style.height = h + 'px';
     _window.style.left = (_leftPos - dw) + 'px';
    }
    else if (_resizeMode == 'ne') {
     dw = evt.pageX - _startX;
     dh = _startY - evt.pageY;
     if (_leftPos + _startW + dw > _maxX)
      dw = _maxX - _leftPos - _startW;
     if (_topPos - dh < 0)
      dh = _topPos;
     w = _startW + dw;
     h = _startH + dh;
     if (w < _minW)
      w = _minW;
     if (h < _minH) {
      h = _minH;
      dh = h - _startH;
     }
     _window.style.width = w + 'px';
     _window.style.height = h + 'px';
     _window.style.top = (_topPos - dh) + 'px';
    }
    else if (_resizeMode == 'se') {
     dw = evt.pageX - _startX;
     dh = evt.pageY - _startY;
     if (_leftPos + _startW + dw > _maxX)
      dw = _maxX - _leftPos - _startW;
     if (_topPos + _startH + dh > _maxY)
      dh = _maxY - _topPos - _startH;
     w = _startW + dw;
     h = _startH + dh;
     if (w < _minW)
      w = _minW;
     if (h < _minH)
      h = _minH;
     _window.style.width = w + 'px';
     _window.style.height = h + 'px';
    }
    _setWindowContent();
   }
   else if (!_isButton) {
    var cs, rm = '';
    if (evt.target === _window || evt.target === _windowTitle) {
     var rect = _getOffset(_window);
     if (evt.pageY < rect.top + _resizePixel)
      rm = 'n';
     else if (evt.pageY > rect.bottom - _resizePixel)
      rm = 's';
     if (evt.pageX < rect.left + _resizePixel)
      rm += 'w';
     else if (evt.pageX > rect.right - _resizePixel)
      rm += 'e';
    }
    if (rm != '' && _resizeMode != rm) {
     if (rm == 'n' || rm == 's')
      cs = 'ns-resize';
     else if (rm == 'e' || rm == 'w')
      cs = 'ew-resize';
     else if (rm == 'ne' || rm == 'sw')
      cs = 'nesw-resize';
     else if (rm == 'nw' || rm == 'se')
      cs = 'nwse-resize';
     _setCursor(cs);
     _resizeMode = rm;
    }
    else if (rm == '' && _resizeMode != '') {
     _setCursor('');
     _resizeMode = '';
    }
   }
   return _returnEvent(evt);
  };
 _onMouseUp = function (evt) {
  evt = evt || window.event;
  if (!(evt.target === _window || evt.target === _windowTitle) && !_isDrag && _resizeMode == '')
   return;
  if (_isDrag) {
   _setCursor('');
   _isDrag = false;
  }
  else if (_isResize) {
   _setCursor('');
   _isResize = false;
   _resizeMode = '';
  }
  return _returnEvent(evt);
 },
  _getOffset = function (elm) {
   var rect = elm.getBoundingClientRect(),
    offsetX = window.scrollX || document.documentElement.scrollLeft,
    offsetY = window.scrollY || document.documentElement.scrollTop;
   return {
    left: rect.left + offsetX,
    top: rect.top + offsetY,
    right: rect.right + offsetX,
    bottom: rect.bottom + offsetY
   }
  },
  _setCursor = function (cur) {
   _window.style.cursor = cur;
   _windowTitle.style.cursor = cur;
  },
  _setWindowContent = function () {
   var _dialogContentStyle = getComputedStyle(_windowContent),
    _dialogButtonPaneStyle,
    _dialogButtonPaneStyleBefore;
   var w = _window.clientWidth
    - parseInt(_dialogContentStyle.left)
    - 16
    ,
    h = _window.clientHeight - (
     parseInt(_dialogContentStyle.top)
     + 16
    );
   _windowContent.style.width = w + 'px';
   _windowContent.style.height = h + 'px';
   if (_dialogButtonPane)
    _dialogButtonPane.style.width = w + 'px';
   _windowTitle.style.width = (w - 16) + 'px';
  },
  _showDialog = function () {
   _window.style.display = 'block';
  },
  _init = function (id) {
   _window = document.getElementById(id);
   _window.style.visibility = 'hidden';
   _window.style.display = 'block';
   _windowTitle = _window.querySelector('.titlebar');
   _windowContent = _window.querySelector('.content');
   _dialogButtonPane = _window.querySelector('.buttonpane');
   _buttons = _window.querySelectorAll('button');
   var _dialogStyle = getComputedStyle(_window),
    _dialogTitleStyle = getComputedStyle(_windowTitle),
    _dialogContentStyle = getComputedStyle(_windowContent),
    _dialogButtonPaneStyle,
    _dialogButtonPaneStyleBefore,
    _dialogButtonStyle;
   _minW = Math.max(_window.clientWidth, _minW,
    + (_buttons.length > 1 ?
     + (_buttons.length - 1) * parseInt(_dialogButtonStyle.width)
     + (_buttons.length - 1 - 1) * 16
     + (_buttons.length - 1 - 1) * 16 / 2
     : 0)
   );
   _window.style.width = _minW + 'px';
   _minH = Math.max(_window.clientHeight, _minH,
    + parseInt(_dialogContentStyle.top)
    + (2 * parseInt(_dialogStyle.border))
    + 16
    + 12
    + 12
    + 12
    + (_buttons.length > 1 ?
     + parseInt(_dialogButtonPaneStyleBefore.borderBottom)
     - parseInt(_dialogButtonPaneStyleBefore.top)
     + parseInt(_dialogButtonPaneStyle.height)
     + parseInt(_dialogButtonPaneStyle.bottom)
     : 0)
   );
   _window.style.height = _minH + 'px';
   _setWindowContent();
   _window.style.left = ((window.innerWidth - _window.clientWidth) / 2) + 'px';
   _window.style.top = ((window.innerHeight - _window.clientHeight) / 2) + 'px';
   _window.style.display = 'none';
   _window.style.visibility = 'visible';
   _windowTitle.tabIndex = '0';
   _tabBoundary = document.createElement('div');
   _tabBoundary.tabIndex = '0';
   _window.appendChild(_tabBoundary);
   _addEvent(_window, 'mousedown', _onMouseDown);
   _addEvent(document, 'mousemove', _onMouseMove);
   _addEvent(document, 'mouseup', _onMouseUp);
   if (_buttons[0].textContent == '')
    _buttons[0].innerHTML = '&#x2716;';
   for (var i = 0; i < _buttons.length; i++) {
    _addEvent(_buttons[i], 'click', _onClick);
    _addEvent(_buttons[i], 'focus', _onFocus);
    _addEvent(_buttons[i], 'blur', _onBlur);
   }
   _addEvent(_windowTitle, 'focus', _adjustFocus);
   _addEvent(_tabBoundary, 'focus', _adjustFocus);
   _zIndex = _window.style.zIndex;
  };
 _init(id);
 this.showWindow = _showDialog;
 return this;
}