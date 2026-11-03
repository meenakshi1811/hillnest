document.addEventListener('DOMContentLoaded', function () {
  var page = document.querySelector('[data-diwali]');
  var gift = page && page.querySelector('[data-diwali-gift]');

  if (!gift) {
    return;
  }

  var stage = page.querySelector('[data-diwali-stage]');
  var coupon = page.querySelector('[data-diwali-coupon]');
  var hint = page.querySelector('[data-diwali-hint]');
  var canvas = page.querySelector('[data-diwali-fireworks]');
  var engine = window.HillnestFireworks;
  var animate = !!(engine && !engine.reduceMotion && canvas && canvas.getContext && canvas.getContext('2d'));
  var show = animate ? engine.create(canvas, { additive: true, pace: 'show', burstZone: [0.1, 0.42] }) : null;
  var revealed = false;

  function clamp(value) {
    return Math.min(0.92, Math.max(0.08, value));
  }

  // A cluster of bursts around the gift, then a few rockets for good measure.
  function celebrate() {
    var giftRect = gift.getBoundingClientRect();
    var canvasRect = canvas.getBoundingClientRect();
    var x = (giftRect.left + giftRect.width / 2 - canvasRect.left) / canvasRect.width;
    var y = (giftRect.top + giftRect.height / 2 - canvasRect.top) / canvasRect.height;
    var offsets = [[0, -0.05], [-0.16, -0.12], [0.16, -0.12], [-0.3, 0.02], [0.3, 0.02], [0, -0.22]];

    offsets.forEach(function (offset, index) {
      setTimeout(function () {
        show.burstAt(clamp(x + offset[0]), clamp(y + offset[1]));
      }, index * 130);
    });

    for (var i = 0; i < 4; i++) {
      setTimeout(show.launch, 350 + i * 220);
    }
  }

  function reveal() {
    if (revealed) {
      return;
    }

    revealed = true;
    stage.classList.add('is-open');

    if (hint) {
      hint.hidden = true;
    }

    if (show) {
      celebrate();
    }

    setTimeout(function () {
      gift.hidden = true;
      coupon.hidden = false;

      // Next frame, so the card animates in rather than appearing instantly.
      window.requestAnimationFrame(function () {
        coupon.classList.add('is-visible');
      });
    }, animate ? 550 : 0);
  }

  gift.addEventListener('click', reveal);

  if (show) {
    show.start();
    setTimeout(reveal, 3500);
  } else {
    reveal();
  }

  page.querySelectorAll('[data-diwali-copy]').forEach(function (button) {
    button.addEventListener('click', function () {
      var code = button.getAttribute('data-diwali-copy');
      var done = function () {
        button.textContent = 'Copied!';
        button.classList.add('is-copied');
        setTimeout(function () {
          button.textContent = 'Copy';
          button.classList.remove('is-copied');
        }, 2000);
      };

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(done);
      } else {
        // Older browsers: copy through a temporary text field.
        var field = document.createElement('textarea');
        field.value = code;
        field.setAttribute('readonly', '');
        field.style.position = 'absolute';
        field.style.left = '-9999px';
        document.body.appendChild(field);
        field.select();
        document.execCommand('copy');
        field.remove();
        done();
      }
    });
  });
});
