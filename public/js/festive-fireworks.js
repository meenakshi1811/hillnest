(function () {
  // The header and the Diwali page can both include this file; set it up once.
  if (window.HillnestFireworks) {
    return;
  }

  var reduceMotion = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);

  // [main colour, lighter tint] pairs, saturated enough to read on the cream background.
  var palette = [
    ['#E8A500', '#FFD966'],
    ['#E53975', '#FF9CC2'],
    ['#8E44EC', '#C9A3FF'],
    ['#F57C00', '#FFBE73'],
    ['#E53935', '#FF8A80'],
    ['#12A08A', '#86E3CF'],
  ];
  var gold = palette[0];
  var burstTypes = ['peony', 'peony', 'ring', 'twoTone', 'willow', 'glitter'];
  var sprites = {};

  function random(min, max) {
    return min + Math.random() * (max - min);
  }

  function pick(list) {
    return list[Math.floor(Math.random() * list.length)];
  }

  function rgba(hex, alpha) {
    var value = parseInt(hex.slice(1), 16);

    return 'rgba(' + (value >> 16) + ',' + ((value >> 8) & 255) + ',' + (value & 255) + ',' + alpha + ')';
  }

  // Pre-rendered soft glow per colour: much cheaper than canvas shadowBlur on every spark.
  function glowSprite(colors) {
    var key = colors[0];

    if (!sprites[key]) {
      var sprite = document.createElement('canvas');
      var g = sprite.getContext('2d');
      var gradient = g.createRadialGradient(32, 32, 0, 32, 32, 32);

      sprite.width = 64;
      sprite.height = 64;
      gradient.addColorStop(0, colors[1]);
      gradient.addColorStop(0.2, colors[0]);
      gradient.addColorStop(0.5, rgba(colors[0], 0.3));
      gradient.addColorStop(1, rgba(colors[0], 0));
      g.fillStyle = gradient;
      g.fillRect(0, 0, 64, 64);
      sprites[key] = sprite;
    }

    return sprites[key];
  }

  /**
   * Options:
   *   fullscreen  Size to the window rather than the canvas element.
   *   additive    Blend sparks so they glow; for dark backgrounds only.
   *   pace        'site': an opening volley, then calmer so it doesn't get in the way.
   *               'show': a continuous display, for the Diwali page.
   *   burstZone   [min, max] share of the height where rockets burst.
   */
  function create(canvas, options) {
    options = options || {};

    var ctx = canvas.getContext('2d');
    var pace = options.pace || 'site';
    var burstZone = options.burstZone || [0.24, 0.55];
    var INTENSE_FOR_MS = 15000;
    var rockets = [];
    var sparks = [];
    var width = 0;
    var height = 0;
    var isSmallScreen = false;
    var maxSparks = 600;
    var running = false;
    var startedAt = 0;
    var nextLaunchAt = 0;
    var openingVolley = pace === 'show' ? 4 : 3;

    function resize() {
      var dpr = Math.min(window.devicePixelRatio || 1, 1.5);

      width = options.fullscreen ? window.innerWidth : canvas.clientWidth;
      height = options.fullscreen ? window.innerHeight : canvas.clientHeight;
      isSmallScreen = width < 640;
      maxSparks = isSmallScreen ? 260 : 650;
      canvas.width = Math.round(width * dpr);
      canvas.height = Math.round(height * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function launch(x) {
      var targetY = height * random(burstZone[0], burstZone[1]);
      var gravity = 0.16;

      rockets.push({
        x: typeof x === 'number' ? x : width * random(0.12, 0.88),
        y: height + 10,
        vx: random(-0.6, 0.6),
        vy: -Math.sqrt(2 * gravity * (height + 10 - targetY)),
        gravity: gravity,
        colors: pick(palette),
        trail: [],
      });
    }

    function addSpark(x, y, angle, speed, colors, settings) {
      sparks.push({
        x: x,
        y: y,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        colors: colors,
        life: 1,
        decay: settings.decay,
        friction: settings.friction,
        gravity: settings.gravity,
        size: settings.size,
        trailLength: settings.trailLength,
        glitter: !!settings.glitter,
        trail: [],
      });
    }

    function explode(x, y, main) {
      var type = pick(burstTypes);
      var second = pick(palette);
      var power = isSmallScreen ? 3.6 : 5.2;
      var count = isSmallScreen ? 46 : 90;
      var base = { decay: random(0.011, 0.015), friction: 0.97, gravity: 0.045, size: isSmallScreen ? 10 : 14, trailLength: 6 };
      var i;
      var angle;

      // Brief flash at the burst centre.
      sparks.push({ x: x, y: y, vx: 0, vy: 0, colors: main, life: 1, decay: 0.14, friction: 1, gravity: 0, size: isSmallScreen ? 70 : 110, trailLength: 0, trail: [] });

      if (type === 'ring') {
        for (i = 0; i < count * 0.7; i++) {
          angle = (Math.PI * 2 * i) / (count * 0.7);
          addSpark(x, y, angle, power * 0.95, main, base);
        }

        return;
      }

      if (type === 'willow') {
        for (i = 0; i < count * 0.8; i++) {
          addSpark(x, y, random(0, Math.PI * 2), power * random(0.35, 0.75), gold, {
            decay: random(0.006, 0.0085), friction: 0.975, gravity: 0.03, size: base.size * 0.8, trailLength: 16,
          });
        }

        return;
      }

      for (i = 0; i < count; i++) {
        angle = random(0, Math.PI * 2);
        addSpark(x, y, angle, power * Math.sqrt(random(0.3, 1)), type === 'twoTone' && i % 2 ? second : main, {
          decay: base.decay,
          friction: base.friction,
          gravity: base.gravity,
          size: base.size,
          trailLength: base.trailLength,
          glitter: type === 'glitter',
        });
      }
    }

    function drawTrail(points, x, y, color, alpha, lineWidth) {
      if (!points.length) {
        return;
      }

      ctx.globalAlpha = alpha;
      ctx.strokeStyle = color;
      ctx.lineWidth = lineWidth;
      ctx.beginPath();
      ctx.moveTo(points[0][0], points[0][1]);

      for (var i = 1; i < points.length; i++) {
        ctx.lineTo(points[i][0], points[i][1]);
      }

      ctx.lineTo(x, y);
      ctx.stroke();
    }

    function drawGlow(colors, x, y, size, alpha) {
      ctx.globalAlpha = alpha;
      ctx.drawImage(glowSprite(colors), x - size / 2, y - size / 2, size, size);
    }

    function updateRockets() {
      for (var i = rockets.length - 1; i >= 0; i--) {
        var rocket = rockets[i];

        rocket.trail.push([rocket.x, rocket.y]);

        if (rocket.trail.length > 10) {
          rocket.trail.shift();
        }

        rocket.x += rocket.vx;
        rocket.y += rocket.vy;
        rocket.vy += rocket.gravity;

        if (rocket.vy >= -0.6) {
          rockets.splice(i, 1);
          explode(rocket.x, rocket.y, rocket.colors);
          continue;
        }

        drawTrail(rocket.trail, rocket.x, rocket.y, gold[0], 0.55, 2);
        drawGlow(gold, rocket.x, rocket.y, 12, 1);
      }
    }

    function updateSparks() {
      for (var i = sparks.length - 1; i >= 0; i--) {
        var spark = sparks[i];

        if (spark.trailLength) {
          spark.trail.push([spark.x, spark.y]);

          if (spark.trail.length > spark.trailLength) {
            spark.trail.shift();
          }
        }

        spark.vx *= spark.friction;
        spark.vy = spark.vy * spark.friction + spark.gravity;
        spark.x += spark.vx;
        spark.y += spark.vy;
        spark.life -= spark.decay;

        if (spark.life <= 0) {
          sparks.splice(i, 1);
          continue;
        }

        var alpha = spark.life;

        // Sparks twinkle as they burn out; glitter bursts twinkle the whole time.
        if (spark.glitter || spark.life < 0.35) {
          alpha *= Math.random() < 0.5 ? 1 : 0.25;
        }

        drawTrail(spark.trail, spark.x, spark.y, spark.colors[0], spark.life * 0.45, spark.size * 0.18);
        drawGlow(spark.colors, spark.x, spark.y, spark.size, alpha);
      }
    }

    function scheduleNext(now) {
      if (openingVolley > 0) {
        openingVolley--;
        nextLaunchAt = now + random(350, 650);
      } else if (pace === 'show') {
        nextLaunchAt = now + random(500, 1100);
      } else if (now - startedAt < INTENSE_FOR_MS) {
        nextLaunchAt = now + random(1100, 2400);
      } else {
        nextLaunchAt = now + random(3000, 6000);
      }
    }

    function frame(now) {
      if (!running) {
        return;
      }

      if (now >= nextLaunchAt && sparks.length < maxSparks) {
        launch();

        if (!isSmallScreen && Math.random() < (pace === 'show' ? 0.4 : 0.25)) {
          launch();
        }

        scheduleNext(now);
      }

      ctx.clearRect(0, 0, width, height);
      ctx.globalCompositeOperation = options.additive ? 'lighter' : 'source-over';
      ctx.lineCap = 'round';
      ctx.lineJoin = 'round';
      updateRockets();
      updateSparks();
      ctx.globalAlpha = 1;

      window.requestAnimationFrame(frame);
    }

    function start() {
      if (running) {
        return;
      }

      running = true;
      startedAt = startedAt || performance.now();
      nextLaunchAt = performance.now() + 300;
      window.requestAnimationFrame(frame);
    }

    function stop() {
      running = false;
      rockets = [];
      sparks = [];
      ctx.clearRect(0, 0, width, height);
    }

    resize();

    if (options.fullscreen || typeof ResizeObserver !== 'function') {
      window.addEventListener('resize', resize);
    } else {
      new ResizeObserver(resize).observe(canvas);
    }

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        stop();
      } else {
        start();
      }
    });

    return {
      start: start,
      stop: stop,
      launch: launch,
      // Bursts immediately at a point given as shares of the width and height (0 to 1).
      burstAt: function (xShare, yShare) {
        explode(width * xShare, height * yShare, pick(palette));
      },
    };
  }

  window.HillnestFireworks = { create: create, reduceMotion: reduceMotion };

  // Site-wide show during the sale. Pages with their own show set data-fireworks="page" on <body>.
  if (reduceMotion || document.body.dataset.fireworks === 'page') {
    return;
  }

  var canvas = document.createElement('canvas');

  if (!canvas.getContext || !canvas.getContext('2d')) {
    return;
  }

  canvas.className = 'festive-fireworks';
  canvas.setAttribute('aria-hidden', 'true');
  document.body.appendChild(canvas);
  create(canvas, { fullscreen: true }).start();
})();
