document.addEventListener('DOMContentLoaded', function () {
  var couponBlock = document.querySelector('[data-checkout-coupon]');
  if (!couponBlock) return;

  var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  var input = couponBlock.querySelector('[data-coupon-input]');
  var errorEl = couponBlock.querySelector('[data-coupon-error]');
  var applyBtn = couponBlock.querySelector('[data-coupon-apply]');
  var removeBtn = couponBlock.querySelector('[data-coupon-remove]');
  var totals = document.querySelector('[data-checkout-totals]');

  function formatMoney(amount) {
    return '₹' + Math.round(amount).toLocaleString('en-IN');
  }

  function showError(message) {
    if (!errorEl) return;
    errorEl.textContent = message;
    errorEl.hidden = false;
  }

  function clearError() {
    if (!errorEl) return;
    errorEl.textContent = '';
    errorEl.hidden = true;
  }

  function updateTotals(summary) {
    if (!totals) return;

    totals.querySelector('[data-total-subtotal]').textContent = formatMoney(summary.subtotal);

    var discountRow = totals.querySelector('[data-total-discount-row]');
    var discountValue = totals.querySelector('[data-total-discount]');

    if (summary.discount > 0) {
      discountRow.hidden = false;
      discountValue.textContent = '-' + formatMoney(summary.discount);
    } else {
      discountRow.hidden = true;
    }

    totals.querySelector('[data-total-shipping]').textContent =
      summary.shipping > 0 ? formatMoney(summary.shipping) : 'FREE';
    totals.querySelector('[data-total-grand]').textContent = formatMoney(summary.total);
  }

  function setAppliedState(coupon) {
    input.readOnly = true;
    input.value = coupon.code;

    if (applyBtn) {
      applyBtn.remove();
    }

    if (!removeBtn) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'checkout-coupon__btn checkout-coupon__btn--remove';
      btn.setAttribute('data-coupon-remove', '');
      btn.textContent = 'Remove';
      btn.addEventListener('click', removeCoupon);
      couponBlock.querySelector('.checkout-coupon__row').appendChild(btn);
      removeBtn = btn;
    }

    var appliedNote = couponBlock.querySelector('.checkout-coupon__applied');
    if (!appliedNote) {
      appliedNote = document.createElement('p');
      appliedNote.className = 'checkout-coupon__applied';
      couponBlock.appendChild(appliedNote);
    }

    appliedNote.innerHTML = '<span>' + coupon.code + '</span> applied — ' + coupon.label + ' off';
  }

  function setRemovedState() {
    input.readOnly = false;
    input.value = '';

    if (removeBtn) {
      removeBtn.remove();
      removeBtn = null;
    }

    if (!applyBtn) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'checkout-coupon__btn';
      btn.setAttribute('data-coupon-apply', '');
      btn.textContent = 'Apply';
      btn.addEventListener('click', applyCoupon);
      couponBlock.querySelector('.checkout-coupon__row').appendChild(btn);
      applyBtn = btn;
    }

    var appliedNote = couponBlock.querySelector('.checkout-coupon__applied');
    if (appliedNote) {
      appliedNote.remove();
    }
  }

  function applyCoupon() {
    clearError();

    fetch(window.checkoutCouponApplyUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ coupon_code: input.value.trim() }),
    })
      .then(function (response) {
        return response.json().then(function (data) {
          if (!response.ok) {
            throw data;
          }
          return data;
        });
      })
      .then(function (data) {
        setAppliedState(data.coupon);
        updateTotals(data.summary);
      })
      .catch(function (error) {
        var message = 'Unable to apply coupon.';

        if (error.errors && error.errors.coupon_code) {
          message = error.errors.coupon_code[0];
        } else if (error.message) {
          message = error.message;
        }

        showError(message);
      });
  }

  function removeCoupon() {
    clearError();

    fetch(window.checkoutCouponRemoveUrl, {
      method: 'DELETE',
      headers: {
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
    })
      .then(function (response) {
        return response.json().then(function (data) {
          if (!response.ok) {
            throw data;
          }
          return data;
        });
      })
      .then(function (data) {
        setRemovedState();
        updateTotals(data.summary);
      })
      .catch(function (error) {
        showError(error.message || 'Unable to remove coupon.');
      });
  }

  if (applyBtn) {
    applyBtn.addEventListener('click', applyCoupon);
  }

  if (removeBtn) {
    removeBtn.addEventListener('click', removeCoupon);
  }
});
