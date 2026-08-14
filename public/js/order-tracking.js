(function () {
  var modal = document.querySelector('[data-tracking-modal]');
  if (!modal) return;

  var orderEl = modal.querySelector('[data-tracking-modal-order]');
  var hintEl = modal.querySelector('[data-tracking-modal-hint]');
  var numberWrap = modal.querySelector('[data-tracking-modal-number-wrap]');
  var numberEl = modal.querySelector('[data-tracking-modal-number]');
  var linkEl = modal.querySelector('[data-tracking-modal-link]');
  var copyBtn = modal.querySelector('[data-tracking-copy]');
  var openButtons = document.querySelectorAll('[data-tracking-open]');
  var closeTriggers = modal.querySelectorAll('[data-tracking-close]');
  var lastFocused = null;
  var currentNumber = '';

  function openModal(button) {
    var orderNumber = button.getAttribute('data-order-number') || '';
    var trackingNumber = (button.getAttribute('data-tracking-number') || '').trim();
    var trackingUrl = (button.getAttribute('data-tracking-url') || '').trim();

    currentNumber = trackingNumber;
    orderEl.textContent = orderNumber ? 'Order ' + orderNumber : '';

    if (trackingNumber) {
      numberEl.textContent = trackingNumber;
      numberWrap.hidden = false;
      hintEl.textContent = 'Copy the tracking number below and use it on the courier website to follow your shipment.';
    } else {
      numberWrap.hidden = true;
      hintEl.textContent = trackingUrl
        ? 'Open the courier tracking link below to follow your shipment.'
        : 'Tracking details are not available yet.';
    }

    if (trackingUrl) {
      linkEl.href = trackingUrl;
      linkEl.hidden = false;
    } else {
      linkEl.hidden = true;
    }

    lastFocused = document.activeElement;
    modal.hidden = false;
    document.body.classList.add('tracking-modal-active');

    var focusTarget = trackingNumber ? copyBtn : (trackingUrl ? linkEl : modal.querySelector('.tracking-modal__close'));
    if (focusTarget) focusTarget.focus();
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('tracking-modal-active');
    if (lastFocused && typeof lastFocused.focus === 'function') {
      lastFocused.focus();
    }
  }

  function copyTrackingNumber() {
    if (!currentNumber) return;

    function showCopied() {
      if (window.toastr) {
        toastr.success('Tracking number copied to clipboard.');
      } else {
        copyBtn.textContent = 'Copied!';
        setTimeout(function () {
          copyBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Copy';
        }, 1600);
      }
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(currentNumber).then(showCopied).catch(function () {
        fallbackCopy(showCopied);
      });
    } else {
      fallbackCopy(showCopied);
    }
  }

  function fallbackCopy(onSuccess) {
    var textarea = document.createElement('textarea');
    textarea.value = currentNumber;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'absolute';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    try {
      document.execCommand('copy');
      onSuccess();
    } catch (e) {}
    document.body.removeChild(textarea);
  }

  openButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      openModal(button);
    });
  });

  closeTriggers.forEach(function (trigger) {
    trigger.addEventListener('click', closeModal);
  });

  if (copyBtn) {
    copyBtn.addEventListener('click', copyTrackingNumber);
  }

  document.addEventListener('keydown', function (event) {
    if (modal.hidden) return;
    if (event.key === 'Escape') closeModal();
  });
})();
