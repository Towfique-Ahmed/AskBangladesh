/* =========================================================================
   AskBangladesh — interaction layer
   No dependencies. Every module no-ops when its markup is absent.
   ========================================================================= */

(function () {
  'use strict';

  const $  = (sel, root) => (root || document).querySelector(sel);
  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------- theme */

  const theme = (function () {
    const KEY = 'askbd-theme';
    const root = document.documentElement;

    function apply(name) {
      root.setAttribute('data-theme', name);
      try { localStorage.setItem(KEY, name); } catch (e) { /* private mode */ }
    }

    let saved = null;
    try { saved = localStorage.getItem(KEY); } catch (e) { /* ignore */ }
    if (saved) {
      apply(saved);
    } else if (window.matchMedia('(prefers-color-scheme: light)').matches) {
      apply('light');
    }

    const btn = $('#theme-toggle');
    if (btn) {
      btn.addEventListener('click', function () {
        apply(root.getAttribute('data-theme') === 'light' ? 'dark' : 'light');
      });
    }
    return { apply: apply };
  })();

  /* ------------------------------------------------------------- clocks */

  function pad(n) { return String(n).padStart(2, '0'); }

  function bdParts(date) {
    // Bangladesh Standard Time is a fixed UTC+06:00 with no daylight saving.
    const utc = date.getTime() + date.getTimezoneOffset() * 60000;
    return new Date(utc + 6 * 3600000);
  }

  const clockTargets = $$('[data-bd-clock]');
  const clockDate    = $('[data-bd-date]');
  const analogHands  = {
    h: $('[data-hand="h"]'), m: $('[data-hand="m"]'), s: $('[data-hand="s"]')
  };

  const DAYS   = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'];

  function tickClock() {
    const now = bdParts(new Date());
    const txt = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
    clockTargets.forEach(function (el) { el.textContent = txt; });

    if (clockDate) {
      clockDate.textContent = DAYS[now.getDay()] + ', ' + now.getDate() + ' ' +
                              MONTHS[now.getMonth()] + ' ' + now.getFullYear();
    }

    if (analogHands.h) {
      const s = now.getSeconds(), m = now.getMinutes(), h = now.getHours() % 12;
      analogHands.s.style.transform = 'rotate(' + (s * 6) + 'deg)';
      analogHands.m.style.transform = 'rotate(' + (m * 6 + s * 0.1) + 'deg)';
      analogHands.h.style.transform = 'rotate(' + (h * 30 + m * 0.5) + 'deg)';
    }

    $$('[data-zone-clock]').forEach(function (el) {
      const zone = el.getAttribute('data-zone-clock');
      try {
        el.textContent = new Intl.DateTimeFormat('en-GB', {
          timeZone: zone, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
        }).format(new Date());
      } catch (e) { el.textContent = '--:--:--'; }
    });

    $$('[data-zone-day]').forEach(function (el) {
      const zone = el.getAttribute('data-zone-day');
      try {
        el.textContent = new Intl.DateTimeFormat('en-GB', {
          timeZone: zone, weekday: 'short', day: 'numeric', month: 'short'
        }).format(new Date());
      } catch (e) { el.textContent = ''; }
    });
  }

  if (clockTargets.length || analogHands.h || $('[data-zone-clock]')) {
    tickClock();
    setInterval(tickClock, 1000);
  }

  /* --------------------------------------------------------- countdowns */

  function tickCountdowns() {
    $$('[data-countdown]').forEach(function (el) {
      const target = new Date(el.getAttribute('data-countdown')).getTime();
      let diff = Math.max(0, target - Date.now());
      const d = Math.floor(diff / 86400000); diff -= d * 86400000;
      const h = Math.floor(diff / 3600000);  diff -= h * 3600000;
      const m = Math.floor(diff / 60000);    diff -= m * 60000;
      const s = Math.floor(diff / 1000);

      const set = function (sel, val) {
        const node = $(sel, el);
        if (node) { node.textContent = sel === '[data-cd-d]' ? val : pad(val); }
      };
      set('[data-cd-d]', d); set('[data-cd-h]', h);
      set('[data-cd-m]', m); set('[data-cd-s]', s);
    });
  }

  if ($('[data-countdown]')) {
    tickCountdowns();
    setInterval(tickCountdowns, 1000);
  }

  /* ------------------------------------------------------ scroll effects */

  const progress = $('#scroll-progress');
  const toTop    = $('#to-top');

  function onScroll() {
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
    if (progress) { progress.style.width = pct + '%'; }
    if (toTop) { toTop.classList.toggle('is-on', window.scrollY > 500); }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (toTop) {
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  }

  /* ------------------------------------------------------------ reveals */

  const revealTargets = $$('[data-reveal]');
  if (revealTargets.length) {
    if (reduceMotion || !('IntersectionObserver' in window)) {
      revealTargets.forEach(function (el) { el.classList.add('is-in'); });
    } else {
      const io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry, i) {
          if (!entry.isIntersecting) { return; }
          const delay = parseInt(entry.target.getAttribute('data-reveal') || '0', 10) || (i * 40);
          setTimeout(function () { entry.target.classList.add('is-in'); }, delay);
          io.unobserve(entry.target);
        });
      }, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
      revealTargets.forEach(function (el) { io.observe(el); });
    }
  }

  /* -------------------------------------------------- animated counters */

  const counters = $$('[data-count-to]');
  if (counters.length && 'IntersectionObserver' in window && !reduceMotion) {
    const co = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) { return; }
        const el = entry.target;
        const to = parseFloat(el.getAttribute('data-count-to'));
        const dec = parseInt(el.getAttribute('data-count-dec') || '0', 10);
        const suffix = el.getAttribute('data-count-suffix') || '';
        const start = performance.now();
        const dur = 1400;
        (function step(now) {
          const t = Math.min(1, (now - start) / dur);
          const eased = 1 - Math.pow(1 - t, 3);
          el.textContent = (to * eased).toLocaleString('en-US', {
            minimumFractionDigits: dec, maximumFractionDigits: dec
          }) + suffix;
          if (t < 1) { requestAnimationFrame(step); }
        })(start);
        co.unobserve(el);
      });
    }, { threshold: 0.4 });
    counters.forEach(function (el) { co.observe(el); });
  } else {
    counters.forEach(function (el) {
      const dec = parseInt(el.getAttribute('data-count-dec') || '0', 10);
      el.textContent = parseFloat(el.getAttribute('data-count-to')).toLocaleString('en-US', {
        minimumFractionDigits: dec, maximumFractionDigits: dec
      }) + (el.getAttribute('data-count-suffix') || '');
    });
  }

  /* ------------------------------------------------------- progress bars */

  const bars = $$('.bar__fill[data-width]');
  if (bars.length) {
    if ('IntersectionObserver' in window) {
      const bo = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) { return; }
          entry.target.style.width = entry.target.getAttribute('data-width') + '%';
          bo.unobserve(entry.target);
        });
      }, { threshold: 0.3 });
      bars.forEach(function (el) { bo.observe(el); });
    } else {
      bars.forEach(function (el) { el.style.width = el.getAttribute('data-width') + '%'; });
    }
  }

  /* ------------------------------------------------------- pointer glow */

  $$('.tile').forEach(function (tile) {
    tile.addEventListener('pointermove', function (ev) {
      const r = tile.getBoundingClientRect();
      tile.style.setProperty('--mx', (ev.clientX - r.left) + 'px');
      tile.style.setProperty('--my', (ev.clientY - r.top) + 'px');
    });
  });

  /* --------------------------------------------------------- global nav */

  const navToggle = $('#nav-toggle');
  const mainnav   = $('#mainnav');
  if (navToggle && mainnav) {
    navToggle.addEventListener('click', function () {
      const open = mainnav.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', String(open));
      navToggle.textContent = open ? '✕' : '☰';
    });
  }

  /* ------------------------------------------------------ global search */

  (function globalSearch() {
    const input = $('#global-search-input');
    const panel = $('#global-search-results');
    if (!input || !panel) { return; }

    let timer = null;
    let cursor = -1;
    let rows = [];

    function close() {
      panel.hidden = true;
      input.setAttribute('aria-expanded', 'false');
      cursor = -1;
    }

    function render(results, query) {
      rows = results;
      if (!results.length) {
        panel.innerHTML = '<div class="sresult__empty">No match for “' +
          escapeHtml(query) + '”. Try a district, a river, “gold”, “999” or “prayer”.</div>';
      } else {
        panel.innerHTML = results.map(function (r, i) {
          return '<a class="sresult" role="option" data-i="' + i + '" href="' + escapeHtml(r.url) + '">' +
            '<span class="sresult__icon">' + escapeHtml(r.icon) + '</span>' +
            '<span><span class="sresult__title">' + escapeHtml(r.title) + '</span>' +
            '<span class="sresult__sub">' + escapeHtml(r.subtitle) + '</span></span>' +
            '<span class="sresult__cat">' + escapeHtml(r.category) + '</span></a>';
        }).join('');
      }
      panel.hidden = false;
      input.setAttribute('aria-expanded', 'true');
      cursor = -1;
    }

    function search(query) {
      fetch('api/search.php?q=' + encodeURIComponent(query) + '&limit=8')
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (input.value.trim() === query) { render(data.results || [], query); }
        })
        .catch(function () { close(); });
    }

    input.addEventListener('input', function () {
      const q = input.value.trim();
      clearTimeout(timer);
      if (q.length < 2) { close(); return; }
      timer = setTimeout(function () { search(q); }, 160);
    });

    input.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape') { close(); input.blur(); return; }
      if (panel.hidden || !rows.length) { return; }

      if (ev.key === 'ArrowDown' || ev.key === 'ArrowUp') {
        ev.preventDefault();
        cursor += (ev.key === 'ArrowDown' ? 1 : -1);
        if (cursor < 0) { cursor = rows.length - 1; }
        if (cursor >= rows.length) { cursor = 0; }
        $$('.sresult', panel).forEach(function (el, i) {
          el.classList.toggle('is-cursor', i === cursor);
          if (i === cursor) { el.scrollIntoView({ block: 'nearest' }); }
        });
      } else if (ev.key === 'Enter' && cursor >= 0) {
        ev.preventDefault();
        window.location.href = rows[cursor].url;
      }
    });

    input.addEventListener('focus', function () {
      if (input.value.trim().length >= 2 && rows.length) { panel.hidden = false; }
    });

    document.addEventListener('click', function (ev) {
      if (!panel.contains(ev.target) && ev.target !== input) { close(); }
    });

    // "/" focuses search from anywhere, the way a docs site behaves.
    document.addEventListener('keydown', function (ev) {
      const tag = (document.activeElement && document.activeElement.tagName) || '';
      if (ev.key === '/' && !/INPUT|TEXTAREA|SELECT/.test(tag)) {
        ev.preventDefault();
        input.focus();
        input.select();
      }
    });
  })();

  function escapeHtml(str) {
    return String(str == null ? '' : str).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  /* --------------------------------------------------------------- typer */

  (function typer() {
    const el = $('[data-typer]');
    if (!el) { return; }
    let phrases;
    try { phrases = JSON.parse(el.getAttribute('data-typer')); } catch (e) { return; }
    if (!Array.isArray(phrases) || !phrases.length) { return; }

    if (reduceMotion) { el.textContent = phrases[0]; return; }

    let pi = 0, ci = 0, deleting = false;
    (function step() {
      const phrase = phrases[pi];
      ci += deleting ? -1 : 1;
      el.textContent = phrase.slice(0, ci);

      let delay = deleting ? 32 : 62;
      if (!deleting && ci === phrase.length) { delay = 1600; deleting = true; }
      else if (deleting && ci === 0) { deleting = false; pi = (pi + 1) % phrases.length; delay = 320; }
      setTimeout(step, delay);
    })();
  })();

  /* ------------------------------------------------------------ filters */

  // Generic list filter: a search box + chips filtering [data-filter-item].
  (function listFilter() {
    const box = $('[data-filter-input]');
    const chips = $$('[data-filter-chip]');
    const items = $$('[data-filter-item]');
    const empty = $('[data-filter-empty]');
    const counter = $('[data-filter-count]');
    if (!items.length) { return; }

    // Honour a chip the server pre-selected (e.g. ?division=Sylhet).
    const preset = chips.find(function (c) { return c.classList.contains('is-active'); });
    let group = preset ? preset.getAttribute('data-filter-chip') : 'all';

    function run() {
      const q = box ? box.value.trim().toLowerCase() : '';
      let shown = 0;
      items.forEach(function (el) {
        const text = (el.getAttribute('data-filter-item') || '').toLowerCase();
        const cat  = el.getAttribute('data-filter-group') || '';
        const ok = (group === 'all' || cat === group) && (q === '' || text.indexOf(q) !== -1);
        el.style.display = ok ? '' : 'none';
        if (ok) { shown++; }
      });
      if (empty) { empty.style.display = shown ? 'none' : ''; }
      if (counter) { counter.textContent = String(shown); }
    }

    if (box) { box.addEventListener('input', run); }
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach(function (c) { c.classList.remove('is-active'); });
        chip.classList.add('is-active');
        group = chip.getAttribute('data-filter-chip');
        run();
      });
    });
    run();
  })();

  /* ---------------------------------------------------------------- map */

  (function bdMap() {
    const svg = $('#bd-map');
    if (!svg) { return; }

    const viewport = $('#map-viewport', svg);
    const info     = $('#map-info');
    let scale = 1, tx = 0, ty = 0;

    function applyTransform() {
      if (viewport) {
        viewport.setAttribute('transform', 'translate(' + tx + ' ' + ty + ') scale(' + scale + ')');
      }
    }

    function zoom(factor) {
      scale = Math.min(6, Math.max(0.6, scale * factor));
      applyTransform();
    }

    const zin = $('#map-zoom-in'), zout = $('#map-zoom-out'), zreset = $('#map-reset');
    if (zin)  { zin.addEventListener('click', function () { zoom(1.3); }); }
    if (zout) { zout.addEventListener('click', function () { zoom(1 / 1.3); }); }
    if (zreset) {
      zreset.addEventListener('click', function () {
        scale = 1; tx = 0; ty = 0; applyTransform();
        $$('.pin', svg).forEach(function (p) { p.classList.remove('is-active'); });
        showEmpty();
      });
    }

    svg.addEventListener('wheel', function (ev) {
      ev.preventDefault();
      zoom(ev.deltaY < 0 ? 1.12 : 1 / 1.12);
    }, { passive: false });

    // Panning listens on the window rather than capturing the pointer on the
    // SVG: pointer capture would retarget the following click to the <svg>,
    // and pins would stop responding to taps.
    let pressed = false, panned = false, lastX = 0, lastY = 0;
    const DRAG_THRESHOLD = 4;

    svg.addEventListener('pointerdown', function (ev) {
      pressed = true; panned = false;
      lastX = ev.clientX; lastY = ev.clientY;
    });

    window.addEventListener('pointermove', function (ev) {
      if (!pressed) { return; }
      const dx = ev.clientX - lastX, dy = ev.clientY - lastY;
      if (!panned && Math.abs(dx) + Math.abs(dy) < DRAG_THRESHOLD) { return; }
      panned = true;
      const rect = svg.getBoundingClientRect();
      const ratio = rect.width ? 620 / rect.width : 1; // viewBox width over rendered width
      tx += dx * ratio;
      ty += dy * ratio;
      lastX = ev.clientX; lastY = ev.clientY;
      applyTransform();
    });

    ['pointerup', 'pointercancel'].forEach(function (evt) {
      window.addEventListener(evt, function () { pressed = false; });
    });

    // Swallow the click that ends a pan, so dragging across a pin never selects it.
    svg.addEventListener('click', function (ev) {
      if (panned) { ev.stopPropagation(); panned = false; }
    }, true);

    function showEmpty() {
      if (!info) { return; }
      info.innerHTML = '<p class="mapinfo__empty">Tap any pin on the map to see what is there — ' +
        'district facts, peak heights, beaches, airports and ports.</p>';
    }

    function rows(pairs) {
      return '<div class="mapinfo__rows">' + pairs.filter(function (p) { return p[1]; }).map(function (p) {
        return '<div class="mapinfo__row"><span>' + escapeHtml(p[0]) + '</span><span>' + escapeHtml(p[1]) + '</span></div>';
      }).join('') + '</div>';
    }

    $$('.pin', svg).forEach(function (pin) {
      // Pins carry role="button" and tabindex, so honour keyboard activation too.
      pin.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); select(); }
      });
      pin.addEventListener('click', select);

      function select() {
        $$('.pin', svg).forEach(function (p) { p.classList.remove('is-active'); });
        pin.classList.add('is-active');
        if (!info) { return; }

        const d = pin.dataset;
        info.innerHTML =
          '<span class="badge">' + escapeHtml(d.kind || 'Place') + '</span>' +
          '<h3>' + escapeHtml(d.name) + '</h3>' +
          (d.bn ? '<div class="mapinfo__bn">' + escapeHtml(d.bn) + '</div>' : '') +
          rows([
            ['Division', d.division],
            ['District', d.district],
            ['Height', d.height ? d.height + ' m' : ''],
            ['Area', d.area ? d.area + ' km²' : ''],
            ['Population', d.population ? Number(d.population).toLocaleString('en-US') : ''],
            ['Coordinates', d.lat && d.lon ? Number(d.lat).toFixed(3) + '°N, ' + Number(d.lon).toFixed(3) + '°E' : ''],
            ['Best season', d.best]
          ]) +
          (d.note ? '<p>' + escapeHtml(d.note) + '</p>' : '') +
          (d.link ? '<a class="btn btn--ghost" href="' + escapeHtml(d.link) + '">Open full page →</a>' : '');
        info.scrollIntoView({ block: 'nearest', behavior: reduceMotion ? 'auto' : 'smooth' });
      }
    });

    // Layer toggles.
    $$('[data-layer]').forEach(function (chip) {
      chip.addEventListener('click', function () {
        const layer = chip.getAttribute('data-layer');
        const off = chip.classList.toggle('is-off');
        $$('.pin[data-layer="' + layer + '"]', svg).forEach(function (p) {
          p.style.display = off ? 'none' : '';
        });
      });
    });

    showEmpty();
  })();

  /* ------------------------------------------------------ live converters */

  // Currency converter — recalculates instantly from a rate table in the DOM.
  (function currency() {
    const form = $('#currency-tool');
    if (!form) { return; }

    let rates = {};
    try { rates = JSON.parse($('#currency-rates').textContent); } catch (e) { return; }

    const amount = $('#cur-amount', form);
    const from   = $('#cur-from', form);
    const to     = $('#cur-to', form);
    const out    = $('#cur-result');
    const meta   = $('#cur-meta');
    const swap   = $('#cur-swap', form);

    function convert() {
      const a = parseFloat(amount.value);
      const f = rates[from.value], t = rates[to.value];
      if (!isFinite(a) || !f || !t) {
        out.textContent = '—';
        return;
      }
      const value = a / f * t;
      out.textContent = value.toLocaleString('en-US', {
        minimumFractionDigits: value < 1 ? 4 : 2, maximumFractionDigits: value < 1 ? 6 : 2
      }) + ' ' + to.value;
      const unit = (1 / f * t);
      meta.textContent = '1 ' + from.value + ' = ' +
        unit.toLocaleString('en-US', { maximumFractionDigits: unit < 1 ? 6 : 4 }) + ' ' + to.value +
        '  ·  1 ' + to.value + ' = ' + (1 / unit).toLocaleString('en-US', { maximumFractionDigits: (1 / unit) < 1 ? 6 : 4 }) + ' ' + from.value;
    }

    [amount, from, to].forEach(function (el) {
      el.addEventListener('input', convert);
      el.addEventListener('change', convert);
    });

    if (swap) {
      swap.addEventListener('click', function () {
        const a = from.value; from.value = to.value; to.value = a;
        convert();
      });
    }

    $$('[data-quick-amount]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        amount.value = btn.getAttribute('data-quick-amount');
        convert();
      });
    });

    convert();
  })();

  // Gold weight/price converter.
  (function gold() {
    const form = $('#gold-tool');
    if (!form) { return; }

    let units = {}, prices = {};
    try {
      units  = JSON.parse($('#gold-units').textContent);
      prices = JSON.parse($('#gold-prices').textContent);
    } catch (e) { return; }

    const qty   = $('#gold-qty', form);
    const unit  = $('#gold-unit', form);
    const karat = $('#gold-karat', form);
    const metal = $('#gold-metal', form);
    const out   = $('#gold-result');
    const meta  = $('#gold-meta');

    function convert() {
      const q = parseFloat(qty.value);
      const grams = units[unit.value];
      const table = prices[metal.value] || {};
      const perBhori = table[karat.value];
      if (!isFinite(q) || !grams || !perBhori) { out.textContent = '—'; return; }

      const totalGrams = q * grams;
      const perGram = perBhori / 11.6638038;
      const value = totalGrams * perGram;

      out.textContent = '৳ ' + value.toLocaleString('en-US', { maximumFractionDigits: 2 });
      meta.textContent = totalGrams.toLocaleString('en-US', { maximumFractionDigits: 3 }) + ' g  ·  ' +
        (totalGrams / 11.6638038).toLocaleString('en-US', { maximumFractionDigits: 4 }) + ' bhori  ·  ' +
        '৳ ' + perGram.toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' per gram';
    }

    [qty, unit, karat, metal].forEach(function (el) {
      el.addEventListener('input', convert);
      el.addEventListener('change', convert);
    });
    convert();
  })();

  // Time converter between two zones.
  (function timeConverter() {
    const form = $('#time-tool');
    if (!form) { return; }

    const dt   = $('#tc-datetime', form);
    const from = $('#tc-from', form);
    const to   = $('#tc-to', form);
    const out  = $('#tc-result');
    const meta = $('#tc-meta');
    const swap = $('#tc-swap', form);

    // Offset of a zone, in minutes, at a given instant.
    function offsetMinutes(zone, date) {
      const fmt = new Intl.DateTimeFormat('en-US', {
        timeZone: zone, hour12: false,
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
      });
      const p = {};
      fmt.formatToParts(date).forEach(function (part) { p[part.type] = part.value; });
      const asUTC = Date.UTC(+p.year, +p.month - 1, +p.day,
                             +p.hour % 24, +p.minute, +p.second);
      return (asUTC - Math.floor(date.getTime() / 1000) * 1000) / 60000;
    }

    function convert() {
      if (!dt.value) { out.textContent = '—'; return; }
      const parts = dt.value.split(/[-T:]/).map(Number);
      // Interpret the entered wall-clock time as being in the "from" zone.
      let guess = Date.UTC(parts[0], parts[1] - 1, parts[2], parts[3], parts[4] || 0, 0);
      for (let i = 0; i < 3; i++) {
        const off = offsetMinutes(from.value, new Date(guess));
        guess = Date.UTC(parts[0], parts[1] - 1, parts[2], parts[3], parts[4] || 0, 0) - off * 60000;
      }
      const instant = new Date(guess);

      try {
        out.textContent = new Intl.DateTimeFormat('en-GB', {
          timeZone: to.value, weekday: 'short', day: 'numeric', month: 'short',
          year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false
        }).format(instant);

        const diff = (offsetMinutes(to.value, instant) - offsetMinutes(from.value, instant)) / 60;
        const sign = diff >= 0 ? '+' : '−';
        meta.textContent = to.value.split('/').pop().replace(/_/g, ' ') + ' is ' + sign +
          Math.abs(diff).toFixed(Math.abs(diff) % 1 ? 2 : 0).replace('.25', '¼').replace('.5', '½').replace('.75', '¾') +
          ' hours from ' + from.value.split('/').pop().replace(/_/g, ' ');
      } catch (e) {
        out.textContent = '—';
      }
    }

    [dt, from, to].forEach(function (el) { el.addEventListener('change', convert); el.addEventListener('input', convert); });
    if (swap) {
      swap.addEventListener('click', function () {
        const a = from.value; from.value = to.value; to.value = a; convert();
      });
    }

    if (!dt.value) {
      const now = bdParts(new Date());
      dt.value = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()) +
                 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
    }
    convert();
  })();

  /* ------------------------------------------------- next prayer countdown */

  (function nextPrayer() {
    const wrap = $('#prayer-list');
    const label = $('#next-prayer-name');
    const timer = $('#next-prayer-timer');
    if (!wrap) { return; }

    const cards = $$('.prayercard', wrap).filter(function (c) {
      return c.getAttribute('data-prayer') !== 'Sunrise';
    });

    function refresh() {
      const now = bdParts(new Date());
      const mins = now.getHours() * 60 + now.getMinutes() + now.getSeconds() / 60;
      let next = null;

      $$('.prayercard', wrap).forEach(function (c) { c.classList.remove('is-next', 'is-past'); });

      for (let i = 0; i < cards.length; i++) {
        const t = (cards[i].getAttribute('data-time') || '').split(':');
        if (t.length < 2) { continue; }
        const m = (+t[0]) * 60 + (+t[1]);
        if (m > mins && !next) { next = { card: cards[i], mins: m }; }
        else if (m <= mins) { cards[i].classList.add('is-past'); }
      }

      let remaining;
      if (next) {
        next.card.classList.add('is-next');
        remaining = (next.mins - mins) * 60;
        if (label) { label.textContent = next.card.getAttribute('data-prayer'); }
      } else if (cards.length) {
        // Past Isha — the next prayer is tomorrow's Fajr.
        cards[0].classList.add('is-next');
        const t = (cards[0].getAttribute('data-time') || '0:0').split(':');
        remaining = ((+t[0]) * 60 + (+t[1]) + 1440 - mins) * 60;
        if (label) { label.textContent = cards[0].getAttribute('data-prayer') + ' (tomorrow)'; }
      } else {
        return;
      }

      if (timer) {
        const h = Math.floor(remaining / 3600);
        const m = Math.floor((remaining % 3600) / 60);
        const s = Math.floor(remaining % 60);
        timer.textContent = pad(h) + ':' + pad(m) + ':' + pad(s);
      }
    }

    refresh();
    setInterval(refresh, 1000);
  })();

  /* --------------------------------------------------------- copy buttons */

  $$('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const text = btn.getAttribute('data-copy');
      const done = function () {
        const old = btn.textContent;
        btn.textContent = '✓ Copied';
        setTimeout(function () { btn.textContent = old; }, 1400);
      };
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(done).catch(function () {});
      }
    });
  });

})();
