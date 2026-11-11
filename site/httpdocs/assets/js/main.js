/* Academy İzmir — main.js (v-1)
   Header, mobil menü, ana sayfa hero videosu ve yatay şerit davranışı.
   Veri okuma ya da HTML üretme yok. JS bağlantıları data- attribute'larıyla yapılır. */
document.documentElement.className = document.documentElement.className.replace('no-js', 'js');

/* Son giriş yöntemi: klavye ya da işaretçi. Menü açılınca odağın nereye gideceğine karar verir,
   böylece dokunma/tıklama sonrası odak çerçevesi görünmez, klavyede görünür kalır. */
var lastInput = 'pointer';
document.addEventListener('keydown', function (event) {
  if (event.key === 'Tab' || event.key === 'Enter' || event.key === ' ' || event.key.indexOf('Arrow') === 0) {
    lastInput = 'keyboard';
  }
}, true);
document.addEventListener('pointerdown', function () { lastInput = 'pointer'; }, true);

(function () {
  'use strict';

  var header = document.querySelector('[data-site-header]');
  var toggle = document.querySelector('[data-menu-toggle]');
  var menu = document.querySelector('[data-mobile-menu]');
  if (!header || !toggle || !menu) { return; }

  var FOCUSABLE = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';
  var inertTargets = document.querySelectorAll('[data-menu-inert]');
  var logo = header.querySelector('a');
  /* Masaüstü menü eşiği: components.css ile birebir aynı değer (64em). */
  var desktopQuery = window.matchMedia('(min-width: 64em)');
  var isOpen = false;

  /* ---- Header: kaydırınca yalnızca alt çizgi belirir ---- */
  function onScroll() {
    header.classList.toggle('is-scrolled', window.scrollY > 0);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---- Mobil menü ---- */
  function trapList() {
    var items = [logo, toggle];
    Array.prototype.forEach.call(menu.querySelectorAll(FOCUSABLE), function (el) { items.push(el); });
    return items;
  }

  function setInert(value) {
    Array.prototype.forEach.call(inertTargets, function (el) {
      if (value) { el.setAttribute('inert', ''); } else { el.removeAttribute('inert'); }
    });
  }

  function openMenu() {
    isOpen = true;
    menu.classList.add('is-open');
    document.body.classList.add('is-menu-open');
    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'Menüyü kapat');
    setInert(true);
    var first = menu.querySelector(FOCUSABLE);
    if (lastInput === 'keyboard' && first) {
      first.focus();
    } else {
      menu.focus({ preventScroll: true });
    }
  }

  function closeMenu(restoreFocus) {
    isOpen = false;
    menu.classList.remove('is-open');
    document.body.classList.remove('is-menu-open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Menüyü aç');
    setInert(false);
    if (restoreFocus) { toggle.focus(); }
  }

  toggle.addEventListener('click', function () {
    if (isOpen) { closeMenu(true); } else { openMenu(); }
  });

  menu.addEventListener('click', function (event) {
    if (event.target.closest('a')) { closeMenu(false); }
  });

  document.addEventListener('keydown', function (event) {
    if (!isOpen) { return; }
    if (event.key === 'Escape') {
      event.preventDefault();
      closeMenu(true);
      return;
    }
    if (event.key === 'Tab') {
      var items = trapList();
      var first = items[0];
      var last = items[items.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }
  });

  function onViewportChange(event) {
    if (event.matches && isOpen) { closeMenu(false); }
  }
  if (desktopQuery.addEventListener) {
    desktopQuery.addEventListener('change', onViewportChange);
  } else {
    desktopQuery.addListener(onViewportChange);
  }
})();

/* ---- Hero videosu ---------------------------------------------------------------------
   Otomatik oynatma yalnızca hareket azaltma ve veri tasarrufu tercihi yoksa başlar.
   Video preload="none" olduğundan, tercih varsa dosya hiç indirilmez; poster görünür
   ve oynat butonu videoyu başlatır. Ekran dışına çıkınca durur. */
(function () {
  'use strict';

  var video = document.querySelector('[data-hero-video]');
  var playButton = document.querySelector('[data-video-play]');
  var soundButton = document.querySelector('[data-video-sound]');
  if (!video || !playButton || !soundButton) { return; }

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
  var saveData = !!(connection && connection.saveData);
  var autoplayAllowed = !reduceMotion.matches && !saveData;
  var pausedByUser = false;
  var visible = true;

  function setIcon(button, name) {
    button.querySelector('.material-symbols-outlined').textContent = name;
  }

  function syncPlayButton() {
    var playing = !video.paused && !video.ended;
    playButton.setAttribute('aria-label', playing ? 'Videoyu duraklat' : 'Videoyu oynat');
    setIcon(playButton, playing ? 'pause' : 'play_arrow');
  }

  function syncSoundButton() {
    var soundOn = !video.muted;
    soundButton.setAttribute('aria-pressed', soundOn ? 'true' : 'false');
    setIcon(soundButton, soundOn ? 'volume_up' : 'volume_off');
  }

  function startPlayback() {
    var promise = video.play();
    if (promise && promise.catch) { promise.catch(syncPlayButton); }
  }

  playButton.addEventListener('click', function () {
    if (video.paused) {
      pausedByUser = false;
      startPlayback();
    } else {
      pausedByUser = true;
      video.pause();
    }
  });

  soundButton.addEventListener('click', function () {
    video.muted = !video.muted;
    syncSoundButton();
  });

  video.addEventListener('play', syncPlayButton);
  video.addEventListener('pause', syncPlayButton);
  video.addEventListener('ended', syncPlayButton);

  function onMotionPreferenceChange(event) {
    if (event.matches) {
      video.pause();
      pausedByUser = true;
    }
  }
  if (reduceMotion.addEventListener) {
    reduceMotion.addEventListener('change', onMotionPreferenceChange);
  } else {
    reduceMotion.addListener(onMotionPreferenceChange);
  }

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
      if (!visible) {
        if (!video.paused) { video.pause(); }
      } else if (autoplayAllowed && !pausedByUser && video.paused) {
        startPlayback();
      }
    }, { threshold: 0.25 }).observe(video);
  }

  syncPlayButton();
  syncSoundButton();
  if (autoplayAllowed) { startPlayback(); }
})();

/* ---- Yatay şeritler (kazananlar, mobilde eğitmenler) -----------------------------------------
   Önceki/sonraki butonları ilgili şeridi kaydırır. Şerit yalnızca kaydırılabiliyorsa klavye
   odağı alır (tabindex="0"), masaüstünde ızgaraya dönen şeritte gereksiz Tab durağı olmaz. */
(function () {
  'use strict';

  var strips = document.querySelectorAll('[data-strip]');
  if (!strips.length) { return; }

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  Array.prototype.forEach.call(strips, function (strip) {
    var section = strip.parentElement.closest('section') || document;
    var previous = section.querySelector('[data-strip-prev]');
    var next = section.querySelector('[data-strip-next]');

    function update() {
      var max = strip.scrollWidth - strip.clientWidth;
      if (max > 1) { strip.setAttribute('tabindex', '0'); } else { strip.removeAttribute('tabindex'); }
      if (previous) { previous.setAttribute('aria-disabled', strip.scrollLeft <= 1 ? 'true' : 'false'); }
      if (next) { next.setAttribute('aria-disabled', strip.scrollLeft >= max - 1 ? 'true' : 'false'); }
    }

    function step(direction) {
      var button = direction < 0 ? previous : next;
      if (!button || button.getAttribute('aria-disabled') === 'true') { return; }
      strip.scrollBy({
        left: direction * Math.max(strip.clientWidth * 0.8, 200),
        behavior: reduceMotion.matches ? 'auto' : 'smooth'
      });
    }

    if (previous) { previous.addEventListener('click', function () { step(-1); }); }
    if (next) { next.addEventListener('click', function () { step(1); }); }
    strip.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });
})();

/* ---- Sayfa içi navigasyon (Eğitimlerimiz) ---------------------------------------------------
   Kaydırmaya göre etkin bölümü belirler (aria-current="true"), dar ekranda etkin bağlantıyı
   yatay listede görünür tutar. Tıklayınca bölüme gitme tarayıcı bağlantısıyla (scroll-margin) olur. */
(function () {
  'use strict';

  var nav = document.querySelector('[data-page-nav]');
  if (!nav) { return; }

  var list = nav.querySelector('[data-page-nav-list]');
  var links = Array.prototype.slice.call(nav.querySelectorAll('[data-page-nav-link]'));
  var sections = links.map(function (link) { return document.getElementById(link.getAttribute('href').slice(1)); });
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var current = -1;
  var ticking = false;

  function reveal(link) {
    if (list.scrollWidth <= list.clientWidth + 1) { return; }
    var target = link.offsetLeft - (list.clientWidth - link.offsetWidth) / 2;
    list.scrollTo({ left: Math.max(0, target), behavior: reduceMotion.matches ? 'auto' : 'smooth' });
  }

  /* Kenar gradyanları: kaydırılacak içerik olan yönde görünür, uca gelince kaybolur. */
  function updateFade() {
    var max = list.scrollWidth - list.clientWidth;
    nav.classList.toggle('has-fade-start', max > 1 && list.scrollLeft > 1);
    nav.classList.toggle('has-fade-end', max > 1 && list.scrollLeft < max - 1);
  }
  list.addEventListener('scroll', updateFade, { passive: true });
  window.addEventListener('resize', updateFade);
  updateFade();

  function setCurrent(index) {
    if (index === current) { return; }
    current = index;
    links.forEach(function (link, i) {
      if (i === index) { link.setAttribute('aria-current', 'true'); } else { link.removeAttribute('aria-current'); }
    });
    if (index >= 0) { reveal(links[index]); }
  }

  function update() {
    ticking = false;
    /* Eşik: bağlantıyla gidilen bölümün durduğu yer (scroll-padding + scroll-margin). */
    var padding = parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 0;
    var margin = sections[0] ? parseFloat(getComputedStyle(sections[0]).scrollMarginTop) || 0 : 0;
    var line = padding + margin + 2;
    var index = -1;
    sections.forEach(function (section, i) {
      if (section && section.getBoundingClientRect().top <= line) { index = i; }
    });
    var atBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2;
    if (atBottom) { index = sections.length - 1; }
    setCurrent(index);
  }

  function requestUpdate() {
    if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
  }

  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', requestUpdate);
  update();
})();

/* ---- Video oynatıcı (otomatik oynatma yok) ------------------------------------------------------
   Poster + oynat butonu görünür. Tıklayınca kontrollerle oynar. JS yokken tarayıcı kontrolleri
   (controls attribute'u) kullanılır. */
(function () {
  'use strict';

  var players = document.querySelectorAll('[data-video-player]');
  if (!players.length) { return; }

  Array.prototype.forEach.call(players, function (player) {
    var video = player.querySelector('video');
    var start = player.querySelector('[data-video-start]');
    if (!video || !start) { return; }

    video.removeAttribute('controls');

    start.addEventListener('click', function () {
      Array.prototype.forEach.call(players, function (other) {
        var otherVideo = other.querySelector('video');
        if (other !== player && otherVideo && !otherVideo.paused) { otherVideo.pause(); }
      });
      video.setAttribute('controls', '');
      player.classList.add('is-playing');
      var promise = video.play();
      if (promise && promise.catch) { promise.catch(function () { player.classList.remove('is-playing'); }); }
      video.focus({ preventScroll: true });
    });

    video.addEventListener('ended', function () {
      player.classList.remove('is-playing');
      video.removeAttribute('controls');
    });
  });
})();

/* ---- Kenar gradyanı ipuçlu yatay kaydırma alanları (filtre satırları) ---------------------------- */
(function () {
  'use strict';

  Array.prototype.forEach.call(document.querySelectorAll('[data-fade-scroll]'), function (wrap) {
    var list = wrap.querySelector('.fade-scroll-list');
    if (!list) { return; }

    function update() {
      var max = list.scrollWidth - list.clientWidth;
      wrap.classList.toggle('has-fade-start', max > 1 && list.scrollLeft > 1);
      wrap.classList.toggle('has-fade-end', max > 1 && list.scrollLeft < max - 1);
    }

    list.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });
})();

/* ---- Kazananlar filtreleri (yalnızca winners.html) ----------------------------------------------
   Yıl ve kategori filtreleri, aria-pressed düğmeleri, aria-live sayaç, adres parametreleri
   (history.replaceState: geri tuşu etkilenmez). JS yokken filtreler görünmez, tüm liste açıktır. */
(function () {
  'use strict';

  var root = document.querySelector('[data-filters]');
  var list = document.querySelector('[data-winners]');
  if (!root || !list) { return; }

  var items = Array.prototype.slice.call(list.querySelectorAll('[data-year][data-category]'));
  var groups = Array.prototype.slice.call(list.querySelectorAll('[data-year-group]'));
  var status = root.querySelector('[data-filter-status]');
  var empty = document.querySelector('[data-filter-empty]');
  var yearButtons = Array.prototype.slice.call(root.querySelectorAll('[data-filter-year]'));
  var categoryButtons = Array.prototype.slice.call(root.querySelectorAll('[data-filter-category]'));

  function valid(buttons, attr, value) {
    return buttons.some(function (button) { return button.getAttribute(attr) === value; });
  }

  var params = new URLSearchParams(window.location.search);
  var state = { year: params.get('year') || 'all', category: params.get('category') || 'all' };
  if (!valid(yearButtons, 'data-filter-year', state.year)) { state.year = 'all'; }
  if (!valid(categoryButtons, 'data-filter-category', state.category)) { state.category = 'all'; }

  function apply(updateUrl) {
    var count = 0;
    items.forEach(function (item) {
      var show = (state.year === 'all' || item.getAttribute('data-year') === state.year) &&
        (state.category === 'all' || item.getAttribute('data-category') === state.category);
      item.classList.toggle('is-hidden', !show);
      if (show) { count += 1; }
    });
    groups.forEach(function (group) {
      var any = group.querySelector('[data-year]:not(.is-hidden)');
      group.classList.toggle('is-hidden', !any);
    });
    yearButtons.forEach(function (button) {
      button.setAttribute('aria-pressed', button.getAttribute('data-filter-year') === state.year ? 'true' : 'false');
    });
    categoryButtons.forEach(function (button) {
      button.setAttribute('aria-pressed', button.getAttribute('data-filter-category') === state.category ? 'true' : 'false');
    });
    /* Boş durumda duyuru, görünen boş durum mesajıyla aynıdır; çift görünmesin diye görsel olarak gizlenir. */
    status.textContent = count > 0 ? count + ' öğrenci gösteriliyor' : 'Bu liste yakında eklenecek.';
    status.classList.toggle('visually-hidden', count === 0);
    if (empty) { empty.classList.toggle('is-hidden', count > 0); }

    if (updateUrl) {
      var next = new URLSearchParams();
      if (state.year !== 'all') { next.set('year', state.year); }
      if (state.category !== 'all') { next.set('category', state.category); }
      var query = next.toString();
      window.history.replaceState(null, '', window.location.pathname + (query ? '?' + query : '') + window.location.hash);
    }
  }

  root.addEventListener('click', function (event) {
    var button = event.target.closest('button');
    if (!button) { return; }
    if (button.hasAttribute('data-filter-year')) { state.year = button.getAttribute('data-filter-year'); }
    else if (button.hasAttribute('data-filter-category')) { state.category = button.getAttribute('data-filter-category'); }
    else { return; }
    apply(true);
  });

  /* Geçersiz yıl/kategori parametresi varsa adres çubuğundan temizle. */
  var hadInvalid = (params.has('year') && params.get('year') !== state.year) ||
    (params.has('category') && params.get('category') !== state.category);
  apply(hadInvalid);
})();

/* ---- Lightbox (galeri, native <dialog>) -----------------------------------------------------------
   Fotoğraf bağlantıları -1600 sürümüne gider (JS yokken normal bağlantı). JS varken dialog açılır:
   önceki/sonraki düğmeleri, ok tuşları, dokunmatikte kaydırma, sayaç. Esc native dialog ile kapatır,
   kapanınca odak tıklanan fotoğrafa döner, arka plan kaymaz. */
(function () {
  'use strict';

  var dialog = document.querySelector('[data-lightbox]');
  var tiles = Array.prototype.slice.call(document.querySelectorAll('[data-lightbox-item]'));
  if (!dialog || !tiles.length || typeof dialog.showModal !== 'function') { return; }

  var image = dialog.querySelector('[data-lightbox-image]');
  var stage = dialog.querySelector('[data-lightbox-stage]');
  var counter = dialog.querySelector('[data-lightbox-counter]');
  var previous = dialog.querySelector('[data-lightbox-prev]');
  var next = dialog.querySelector('[data-lightbox-next]');
  var closeButton = dialog.querySelector('[data-lightbox-close]');
  var BLANK = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
  var current = 0;
  var trigger = null; /* açan fotoğraf (yedek) */

  function show(index) {
    current = index;
    var tile = tiles[index];
    var thumb = tile.querySelector('img');
    image.setAttribute('width', tile.getAttribute('data-w'));
    image.setAttribute('height', tile.getAttribute('data-h'));
    image.alt = thumb.alt;
    image.src = tile.getAttribute('href');
    counter.textContent = (index + 1) + ' / ' + tiles.length;
    previous.setAttribute('aria-disabled', index === 0 ? 'true' : 'false');
    next.setAttribute('aria-disabled', index === tiles.length - 1 ? 'true' : 'false');
    [index - 1, index + 1].forEach(function (i) {
      if (tiles[i]) { new Image().src = tiles[i].getAttribute('href'); }
    });
  }

  function go(step) {
    var target = current + step;
    if (target < 0 || target > tiles.length - 1) { return; }
    show(target);
  }

  tiles.forEach(function (tile, index) {
    tile.addEventListener('click', function (event) {
      event.preventDefault();
      trigger = tile;
      show(index);
      dialog.showModal();
      document.body.classList.add('is-lightbox-open');
    });
  });

  previous.addEventListener('click', function () { go(-1); });
  next.addEventListener('click', function () { go(1); });
  closeButton.addEventListener('click', function () { dialog.close(); });

  dialog.addEventListener('close', function () {
    document.body.classList.remove('is-lightbox-open');
    image.src = BLANK;
    /* Odak, o an gösterilen fotoğrafa döner. Ekranda değilse görünür olacak şekilde kaydırılır
       (hareket azaltma tercihinde animasyonsuz). */
    var shown = tiles[current] || trigger;
    if (shown) {
      shown.focus({ preventScroll: true });
      var rect = shown.getBoundingClientRect();
      var headerHeight = document.querySelector('[data-site-header]') ? document.querySelector('[data-site-header]').offsetHeight : 0;
      if (rect.top < headerHeight || rect.bottom > window.innerHeight) {
        shown.scrollIntoView({
          block: 'center',
          behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
        });
      }
    }
  });

  dialog.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowLeft') { event.preventDefault(); go(-1); }
    if (event.key === 'ArrowRight') { event.preventDefault(); go(1); }
  });

  /* Dokunmatik kaydırma: yatay hareket 50 px'i geçerse önceki/sonraki. */
  var startX = null;
  var startY = null;
  stage.addEventListener('pointerdown', function (event) {
    if (event.pointerType === 'mouse') { return; }
    startX = event.clientX;
    startY = event.clientY;
  });
  stage.addEventListener('pointerup', function (event) {
    if (startX === null) { return; }
    var dx = event.clientX - startX;
    var dy = event.clientY - startY;
    startX = null;
    if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) { go(dx < 0 ? 1 : -1); }
  });
  stage.addEventListener('pointercancel', function () { startX = null; });
})();

/* ---- Eğitim ön başvuru formu (yalnızca application.html) ---------------------------------------------------------
   JS varken tarayıcı doğrulaması kapatılır (noValidate), kendi mesajlarımız gösterilir. JS yokken
   required/type/pattern ile HTML5 doğrulaması çalışır. Doğrulama: alandan çıkınca (blur) ve gönderimde.
   Hatalı gönderimde hata özeti (role="alert") ve ilk hatalı alana odak. */
(function () {
  'use strict';

  var form = document.querySelector('[data-application-form]');
  if (!form) { return; }
  form.noValidate = true;

  var summary = form.querySelector('[data-form-summary]');
  var summaryList = form.querySelector('[data-form-summary-list]');
  var fields = Array.prototype.slice.call(form.querySelectorAll('[data-validate]'));

  var MIN_BIRTHDATE = '1930-01-01';

  var MESSAGES = {
    name: 'Lütfen adınızı yazın.',
    birthdate: 'Lütfen doğum tarihinizi seçin.',
    'age-group': 'Lütfen bir yaş grubu seçin.',
    education: 'Lütfen eğitim durumunuzu seçin.',
    phone: 'Lütfen geçerli bir telefon numarası yazın.',
    email: 'Lütfen geçerli bir e-posta adresi yazın.',
    program: 'Lütfen bir eğitim seçin.',
    consent: 'Devam etmek için aydınlatma metnini okuduğunuzu onaylayın.'
  };
  var BIRTHDATE_INVALID = 'Lütfen geçerli bir doğum tarihi seçin.';

  /* Doğum tarihi: max bugünün (yerel) tarihi. Native date girdisinin değeri her zaman YYYY-MM-DD'dir,
     bu yüzden metin karşılaştırması tarih sırasını verir. */
  function todayIso() {
    var now = new Date();
    var month = String(now.getMonth() + 1);
    var day = String(now.getDate());
    return now.getFullYear() + '-' + (month.length < 2 ? '0' : '') + month + '-' + (day.length < 2 ? '0' : '') + day;
  }
  var birthInput = form.querySelector('[data-validate="birthdate"]');
  if (birthInput) { birthInput.max = todayIso(); }

  /* ---- Alan maskeleri ----------------------------------------------------------------------------
     Telefon: yalnızca rakam, "0 (XXX) XXX XX XX" biçimi, en fazla 11 rakam. Başa 0 eklenir, 12 rakamlı
     90 ile başlayan (+90) numaralarda 90 atılır. Biçim karakterleri yalnızca bir sonraki rakam
     geldiğinde eklenir (Backspace takılmaz). value biçimli kalır, ek aria gerekmez.
     Engellenen karakterler sessizce yok sayılır. */
  function phoneDigits(raw) {
    var digits = raw.replace(/\D/g, '');
    var shift = 0;
    if (digits.length >= 12 && digits.indexOf('90') === 0) {
      digits = '0' + digits.slice(2);
      shift = -1;
    } else if (digits !== '' && digits.charAt(0) !== '0') {
      digits = '0' + digits;
      shift = 1;
    }
    return { digits: digits.slice(0, 11), shift: shift };
  }

  function formatPhone(digits) {
    var out = digits.slice(0, 1);
    if (digits.length > 1) { out += ' (' + digits.slice(1, 4); }
    if (digits.length > 4) { out += ') ' + digits.slice(4, 7); }
    if (digits.length > 7) { out += ' ' + digits.slice(7, 9); }
    if (digits.length > 9) { out += ' ' + digits.slice(9, 11); }
    return out;
  }

  /* n. rakamdan hemen sonraki konum (n = 0: başa). */
  function caretAfterDigit(formatted, n) {
    if (n <= 0) { return 0; }
    var seen = 0;
    for (var i = 0; i < formatted.length; i += 1) {
      if (/\d/.test(formatted.charAt(i))) {
        seen += 1;
        if (seen === n) { return i + 1; }
      }
    }
    return formatted.length;
  }

  function applyPhone(input, caretDigits) {
    var result = phoneDigits(input.value);
    var formatted = formatPhone(result.digits);
    if (formatted === input.value) { return; }
    input.value = formatted;
    var n = Math.min(Math.max(caretDigits + result.shift, 0), result.digits.length);
    var pos = caretAfterDigit(formatted, n);
    /* Rakamdan sonra yalnızca biçim karakterleri varsa ve ardından rakam geliyorsa onların ötesine geç
       (silme sonrası imleç eski yerinde, bir sonraki rakamın önünde kalır). İlk rakamda (0) geçilmez. */
    if (n > 1) {
      while (pos < formatted.length && !/\d/.test(formatted.charAt(pos)) && /\d/.test(formatted.slice(pos))) { pos += 1; }
    }
    input.setSelectionRange(pos, pos);
  }

  function digitsBefore(input) {
    var pos = input.selectionStart === null ? input.value.length : input.selectionStart;
    return (input.value.slice(0, pos).match(/\d/g) || []).length;
  }

  var phoneInput = form.querySelector('[data-validate="phone"]');
  if (phoneInput) {
    /* Alan doluyken (11 rakam) seçim yoksa yazılan ya da yapıştırılan rakamlar yok sayılır, son hane
       dışarı itilmez. Seçim varsa seçimin yerine geçer. beforeinput anındaki değer ve seçim saklanır. */
    var phoneBefore = null;
    phoneInput.addEventListener('beforeinput', function () {
      phoneBefore = { value: phoneInput.value, start: phoneInput.selectionStart, end: phoneInput.selectionEnd };
    });
    phoneInput.addEventListener('input', function (event) {
      var before = phoneBefore;
      phoneBefore = null;
      if (before && before.start === before.end && before.value.replace(/\D/g, '').length >= 11 &&
          (event.inputType === 'insertText' || event.inputType === 'insertFromPaste' || event.inputType === 'insertFromDrop')) {
        phoneInput.value = before.value;
        phoneInput.setSelectionRange(before.start, before.start);
        return;
      }
      applyPhone(phoneInput, digitsBefore(phoneInput));
    });
    phoneInput.addEventListener('change', function () { tidy(phoneInput); });

    /* Backspace/Delete biçim karakterine (boşluk, parantez) denk gelirse en yakın rakamı siler. */
    phoneInput.addEventListener('keydown', function (event) {
      if ((event.key !== 'Backspace' && event.key !== 'Delete') || event.ctrlKey || event.metaKey || event.altKey) { return; }
      var start = phoneInput.selectionStart;
      if (start === null || start !== phoneInput.selectionEnd) { return; }
      var value = phoneInput.value;
      var back = event.key === 'Backspace';
      var neighbour = back ? value.charAt(start - 1) : value.charAt(start);
      if (neighbour === '' || /\d/.test(neighbour)) { return; }
      event.preventDefault();
      var index = -1;
      var i;
      if (back) {
        for (i = start - 1; i >= 0; i -= 1) { if (/\d/.test(value.charAt(i))) { index = i; break; } }
      } else {
        for (i = start; i < value.length; i += 1) { if (/\d/.test(value.charAt(i))) { index = i; break; } }
      }
      if (index < 0) { return; }
      if (index === 0) { return; } /* zorunlu baştaki 0 silinmez, imleç yerinde kalır */
      var before = (value.slice(0, index).match(/\d/g) || []).length;
      var digits = value.replace(/\D/g, '');
      digits = phoneDigits(digits.slice(0, before) + digits.slice(before + 1)).digits;
      var formatted = formatPhone(digits);
      phoneInput.value = formatted;
      var pos = caretAfterDigit(formatted, before);
      phoneInput.setSelectionRange(pos, pos);
      phoneInput.dispatchEvent(new Event('input', { bubbles: true }));
    });
  }

  /* Ad Soyad: harf (Unicode, Türkçe dahil), birleştirici işaret, boşluk, kesme işareti (' ve ’), tire.
     Filtre input olayında çalışır, IME yazımı (compositionstart-compositionend) sırasında araya girmez. */
  var NAME_DISALLOWED = /[^\p{L}\p{M} '\u2019\-]/gu;
  var nameInput = form.querySelector('[data-validate="name"]');
  var composing = false;

  function filterName() {
    var value = nameInput.value;
    var filtered = value.replace(NAME_DISALLOWED, '');
    if (filtered === value) { return; }
    var pos = nameInput.selectionStart === null ? value.length : nameInput.selectionStart;
    var caret = value.slice(0, pos).replace(NAME_DISALLOWED, '').length;
    nameInput.value = filtered;
    nameInput.setSelectionRange(caret, caret);
  }

  if (nameInput) {
    nameInput.addEventListener('compositionstart', function () { composing = true; });
    nameInput.addEventListener('compositionend', function () { composing = false; filterName(); });
    nameInput.addEventListener('input', function (event) {
      if (composing || event.isComposing) { return; }
      filterName();
    });
  }

  /* Alandan çıkınca ve gönderimde: ad soyadda baş/son boşluk atılır, ardışık boşluklar teke iner;
     telefon biçimlenir (otomatik doldurma input olayı vermese de). */
  function tidy(field) {
    var key = field.getAttribute('data-validate');
    if (key === 'name') {
      field.value = field.value.replace(NAME_DISALLOWED, '').replace(/ {2,}/g, ' ').trim();
    } else if (key === 'phone') {
      field.value = formatPhone(phoneDigits(field.value).digits);
    }
  }

  /* Telefon: maske sonrası 0 ile başlayan 11 rakam geçerlidir. */
  function isPhone(value) {
    return /^0\d{10}$/.test(phoneDigits(value).digits);
  }

  var VALIDATORS = {
    /* Tek kelime de kabul edilir, en az 2 harf yeterlidir. */
    name: function (value) { return (value.match(/\p{L}/gu) || []).length >= 2; },
    phone: isPhone,
    email: function (value) { var v = value.trim(); return v === '' || /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v); },
    'age-group': function (value) { return value !== ''; },
    education: function (value) { return value !== ''; },
    program: function (value) { return value !== ''; }
  };

  /* Doğum tarihi: boşsa "seçin" mesajı; yarım yazılmış (badInput), bugünden ileri ya da min'den eski
     tarih "geçerli tarih" mesajı verir. */
  function birthdateError(field) {
    if (field.validity && field.validity.badInput) { return BIRTHDATE_INVALID; }
    var value = field.value;
    if (value === '') { return MESSAGES.birthdate; }
    if (!/^\d{4}-\d{2}-\d{2}$/.test(value) || value < MIN_BIRTHDATE || value > todayIso()) { return BIRTHDATE_INVALID; }
    return '';
  }

  function errorFor(field) {
    var key = field.getAttribute('data-validate');
    if (key === 'birthdate') { return birthdateError(field); }
    return isValid(field) ? '' : MESSAGES[key];
  }

  function isValid(field) {
    var key = field.getAttribute('data-validate');
    if (key === 'consent') { return field.checked; }
    return VALIDATORS[key](field.value);
  }

  function check(field) {
    var error = document.getElementById(field.id + '-error');
    var message = errorFor(field);
    var ok = message === '';
    if (ok) {
      field.removeAttribute('aria-invalid');
      error.textContent = '';
      error.hidden = true;
    } else {
      field.setAttribute('aria-invalid', 'true');
      error.textContent = message;
      error.hidden = false;
    }
    return ok;
  }

  /* Gönder butonuna basılırken ya da Tab ile geçilirken alanın blur doğrulaması atlanır: hata satırı
     butonu aşağı iterek tıklamayı kaçırırdı, gönderim zaten tüm alanları doğrular. Safari'de tıklama
     odak vermediğinden relatedTarget'a güvenilmez: fare/dokunma için pointerdown bayrağı tutulur,
     bayrak pointerup/pointercancel/click ile sıfırlanır. Klavyede Tab ile geçişte relatedTarget kullanılır. */
  var submitButton = form.querySelector('button[type="submit"]');
  var submitPressed = false;
  if (submitButton) {
    submitButton.addEventListener('pointerdown', function () { submitPressed = true; });
    ['pointerup', 'pointercancel', 'click'].forEach(function (type) {
      submitButton.addEventListener(type, function () { submitPressed = false; });
    });
    document.addEventListener('pointerup', function () { submitPressed = false; });
  }

  fields.forEach(function (field) {
    field.addEventListener('blur', function (event) {
      if (submitPressed || (submitButton && event.relatedTarget === submitButton)) { return; }
      tidy(field);
      check(field);
    });
    var revalidate = function () { if (field.getAttribute('aria-invalid') === 'true') { check(field); } };
    field.addEventListener('input', revalidate);
    field.addEventListener('change', revalidate);
  });

  function showSummary(invalid) {
    summaryList.innerHTML = '';
    invalid.forEach(function (field) {
      var item = document.createElement('li');
      var link = document.createElement('a');
      link.href = '#' + field.id;
      link.textContent = errorFor(field);
      link.addEventListener('click', function (event) { event.preventDefault(); field.focus(); });
      item.appendChild(link);
      summaryList.appendChild(item);
    });
    summary.hidden = false;
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    fields.forEach(tidy);
    var invalid = fields.filter(function (field) { return !check(field); });
    if (invalid.length) {
      showSummary(invalid);
      invalid[0].focus();
      return;
    }
    summary.hidden = true;
    /* data-prototype: form henüz bir sunucuya bağlı değil. Yayın aşamasında bu blok kaldırılır,
       form gerçek gönderime bağlanır. */
    var prototype = document.querySelector('[data-prototype]');
    if (prototype) {
      form.hidden = true;
      prototype.hidden = false;
      prototype.focus();
    }
  });

  /* application.html?program=<program-id>: ilgili ana program seçili gelir. Geçersiz id'de seçim yapılmaz. */
  var programId = new URLSearchParams(window.location.search).get('program');
  var select = form.querySelector('select[data-validate="program"]');
  if (programId && select && !select.value) {
    var match = Array.prototype.slice.call(select.options).filter(function (option) {
      return option.value !== '' && option.value === programId;
    })[0];
    if (match) { select.value = match.value; }
  }
})();
