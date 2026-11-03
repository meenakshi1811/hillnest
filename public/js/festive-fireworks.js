(function () {
  var canvas = document.querySelector('.festive-fireworks');

  if (!canvas || !canvas.getContext) {
    return;
  }

  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    canvas.remove();
    return;
  }

  var ctx = canvas.getContext('2d');
  var host = canvas.parentElement;
  var colors = ['#C9973A', '#E8A33D', '#F28C28', '#E0679B', '#C26DD9', '#9B5DE5', '#D4AF37'];
  var particles = [];
  var width = 0;
  var height = 0;
  var headerHeight = 0;
  var running = false;
  var nextBurstAt = 0;

  function pick(list) {
    return list[Math.floor(Math.random() * list.length)];
  }

  function resize() {
    var dpr = Math.min(window.devicePixelRatio || 1, 2);

    // The canvas extends below the header so bursts can spill over the top of the page.
    width = canvas.clientWidth;
    height = canvas.clientHeight;
    headerHeight = host.clientHeight;
    canvas.width = Math.round(width * dpr);
    canvas.height = Math.round(height * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function burst() {
    var x = width * (0.06 + Math.random() * 0.88);
    var y = headerHeight * (0.3 + Math.random() * 0.45);
    var mainColor = pick(colors);
    var accentColor = pick(colors);
    var isSmallScreen = width < 640;
    var count = isSmallScreen ? 28 : 44;
    var power = isSmallScreen ? 0.75 : 1.15;

    for (var i = 0; i < count; i++) {
      var angle = (Math.PI * 2 * i) / count + Math.random() * 0.2;
      var speed = (3.2 + Math.random() * 2.4) * power;

      particles.push({
        originX: x,
        originY: y,
        x: x,
        y: y,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        life: 1,
        decay: 0.011 + Math.random() * 0.008,
        color: Math.random() < 0.3 ? accentColor : mainColor,
        lineWidth: 1.4 + Math.random() * 1.2,
      });
    }
  }

  function frame(now) {
    if (!running) {
      return;
    }

    if (now >= nextBurstAt) {
      burst();
      nextBurstAt = now + 900 + Math.random() * 1600;
    }

    ctx.clearRect(0, 0, width, height);
    ctx.lineCap = 'round';
    ctx.shadowBlur = 8;

    for (var i = particles.length - 1; i >= 0; i--) {
      var p = particles[i];

      p.vx *= 0.95;
      p.vy = p.vy * 0.95 + 0.035;
      p.x += p.vx;
      p.y += p.vy;
      p.life -= p.decay;

      if (p.life <= 0) {
        particles.splice(i, 1);
        continue;
      }

      // Each spark is a ray pointing back toward the burst centre, so the burst reads as long light streaks.
      ctx.globalAlpha = p.life;
      ctx.strokeStyle = p.color;
      ctx.shadowColor = p.color;
      ctx.lineWidth = p.lineWidth;
      ctx.beginPath();
      ctx.moveTo(p.x - (p.x - p.originX) * 0.55, p.y - (p.y - p.originY) * 0.55);
      ctx.lineTo(p.x, p.y);
      ctx.stroke();
    }

    ctx.globalAlpha = 1;
    window.requestAnimationFrame(frame);
  }

  function start() {
    if (running) {
      return;
    }

    running = true;
    nextBurstAt = performance.now() + 400;
    window.requestAnimationFrame(frame);
  }

  function stop() {
    running = false;
    particles = [];
    ctx.clearRect(0, 0, width, height);
  }

  resize();

  if (typeof ResizeObserver === 'function') {
    new ResizeObserver(resize).observe(host);
  } else {
    window.addEventListener('resize', resize);
  }

  document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
      stop();
    } else {
      start();
    }
  });

  start();
})();
