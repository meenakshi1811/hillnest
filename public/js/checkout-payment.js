document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('[data-checkout-form]');
  if (!form) return;

  var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  var submitBtn = form.querySelector('[data-checkout-submit]');
  var overlay = document.querySelector('[data-payment-overlay]');
  var overlayMessage = overlay?.querySelector('[data-payment-overlay-message]');
  var paymentError = form.querySelector('[data-payment-error]');
  var allowPageLeave = false;

  function showOverlay(message) {
    if (!overlay) return;
    if (overlayMessage && message) {
      overlayMessage.textContent = message;
    }
    overlay.hidden = false;
    document.body.classList.add('payment-overlay-active');
  }

  function hideOverlay() {
    if (!overlay) return;
    overlay.hidden = true;
    document.body.classList.remove('payment-overlay-active');
  }

  function redirectAfterPayment(url) {
    allowPageLeave = true;
    hideOverlay();
    window.location.assign(url);
  }

  function parseJsonResponse(res) {
    return res.text().then(function (text) {
      if (!text) {
        return {};
      }

      try {
        return JSON.parse(text);
      } catch (error) {
        throw {
          message: 'Unexpected server response while verifying payment. Please check your orders or contact support.',
        };
      }
    }).then(function (data) {
      if (!res.ok) {
        throw data;
      }

      return data;
    });
  }

  function showPaymentError(message) {
    if (!paymentError) return;
    paymentError.textContent = message;
    paymentError.hidden = false;
  }

  function clearPaymentError() {
    if (!paymentError) return;
    paymentError.textContent = '';
    paymentError.hidden = true;
  }

  function setSubmitting(isSubmitting) {
    if (!submitBtn) return;
    submitBtn.disabled = isSubmitting;
    submitBtn.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
  }

  function getFormData() {
    return {
      customer_name: form.querySelector('#customer_name')?.value.trim(),
      customer_email: form.querySelector('#customer_email')?.value.trim(),
      customer_phone: form.querySelector('#customer_phone')?.value.trim(),
      shipping_address: form.querySelector('#shipping_address')?.value.trim(),
      city: form.querySelector('#city')?.value.trim(),
      state: form.querySelector('#state')?.value.trim(),
      pincode: form.querySelector('#pincode')?.value.trim(),
      notes: form.querySelector('#notes')?.value.trim() || null,
      coupon_code: form.querySelector('#coupon_code')?.value.trim() || null,
    };
  }

  function notifyPaymentFailed(orderId, error) {
    if (!window.checkoutPaymentFailedUrl) return;

    fetch(window.checkoutPaymentFailedUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ order_id: orderId, error: error }),
    }).catch(function () {});
  }

  function openRazorpayCheckout(paymentData) {
    if (typeof Razorpay === 'undefined') {
      hideOverlay();
      setSubmitting(false);
      showPaymentError('Payment gateway failed to load. Please refresh and try again.');
      return;
    }

    showOverlay('Please do not close or reload this page while your payment is being processed.');

    var options = {
      key: paymentData.razorpay_key,
      amount: paymentData.amount,
      currency: paymentData.currency,
      name: window.checkoutBrandName || 'Hillnest',
      description: 'Order ' + paymentData.order_number,
      order_id: paymentData.razorpay_order_id,
      prefill: paymentData.customer,
      theme: {
        color: '#1a3a2a',
      },
      handler: function (response) {
        showOverlay('Verifying your payment. Please do not close or reload this page.');

        fetch(window.checkoutPaymentVerifyUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({
            order_id: paymentData.order_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature: response.razorpay_signature,
          }),
        })
          .then(parseJsonResponse)
          .then(function (data) {
            if (data.redirect) {
              redirectAfterPayment(data.redirect);
              return;
            }

            throw {
              message: 'Payment verified, but we could not open your confirmation page. Please check your orders.',
            };
          })
          .catch(function (error) {
            allowPageLeave = false;
            hideOverlay();
            setSubmitting(false);
            showPaymentError(error.message || 'Payment verification failed. Please contact support if amount was deducted.');
          });
      },
      modal: {
        ondismiss: function () {
          hideOverlay();
          setSubmitting(false);
          notifyPaymentFailed(paymentData.order_id, 'Payment cancelled by user.');
          showPaymentError('Payment was cancelled. You can try again when ready.');
        },
      },
    };

    var razorpay = new Razorpay(options);

    razorpay.on('payment.failed', function (response) {
      hideOverlay();
      setSubmitting(false);
      var message = response.error?.description || 'Payment failed. Please try again.';
      notifyPaymentFailed(paymentData.order_id, message);
      showPaymentError(message);
    });

    razorpay.open();
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    clearPaymentError();

    if (!form.reportValidity()) {
      return;
    }

    setSubmitting(true);
    showOverlay('Preparing your secure payment. Please do not close or reload this page.');

    fetch(window.checkoutPaymentCreateUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(getFormData()),
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
        openRazorpayCheckout(data);
      })
      .catch(function (error) {
        hideOverlay();
        setSubmitting(false);

        if (error.errors) {
          var firstKey = Object.keys(error.errors)[0];
          showPaymentError(error.errors[firstKey][0]);
          return;
        }

        showPaymentError(error.message || 'Unable to start payment. Please try again.');
      });
  });

  window.addEventListener('beforeunload', function (event) {
    if (allowPageLeave || !document.body.classList.contains('payment-overlay-active')) {
      return;
    }

    event.preventDefault();
    event.returnValue = '';
  });
});
