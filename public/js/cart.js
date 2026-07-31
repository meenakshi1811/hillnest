$(function () {
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 3500,
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
  };

  const csrf = $('meta[name="csrf-token"]').attr('content');

  function formatMoney(amount) {
    return '₹' + Math.round(amount).toLocaleString('en-IN');
  }

  function updateCartCount(count) {
    $('#cart-count').text(count);
  }

  function cartButtonHtml(variant, inCart) {
    const isPage = variant === 'page';
    const addClass = isPage
      ? 'product-detail__cart-btn product-detail__cart-btn--add'
      : 'product-card-rosier__btn';
    const removeClass = isPage
      ? 'product-detail__cart-btn product-detail__cart-btn--remove'
      : 'product-card-rosier__btn product-card-rosier__btn--remove';

    if (inCart) {
      return (
        '<span class="cart-action__status">✓ In your cart</span>' +
        '<button type="button" class="' +
        removeClass +
        '" data-cart-remove><span>Remove from Cart</span></button>'
      );
    }

    return (
      '<button type="button" class="' +
      addClass +
      '" data-cart-add><span>Add to Cart</span></button>'
    );
  }

  function setCartActionState($action, inCart, quantity) {
    const variant = $action.hasClass('cart-action--page') ? 'page' : 'card';

    $action.toggleClass('cart-action--in-cart', inCart);

    if (quantity && $action.find('[name="quantity"]').length) {
      $action.find('[name="quantity"]').val(quantity);
    }

    $action.find('.cart-action__buttons').html(cartButtonHtml(variant, inCart));
  }

  function updateAllCartActions(productId, inCart, quantity) {
    $('[data-cart-action][data-product-id="' + productId + '"]').each(function () {
      setCartActionState($(this), inCart, quantity);
    });
  }

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    if (xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join(' ');
    }

    return 'Something went wrong. Please try again.';
  }

  function updateCartPageSummary(response) {
    $('[data-cart-subtotal]').text(formatMoney(response.subtotal));
    $('[data-cart-shipping]').text(
      response.shipping > 0 ? formatMoney(response.shipping) : 'FREE'
    );
    $('[data-cart-total]').text(formatMoney(response.total));

    if ($('[data-shipping-block]').length) {
      const $block = $('[data-shipping-block]');

      if (response.shipping_enabled === false) {
        $block.attr('hidden', true).empty();
      } else if (response.show_shipping_progress) {
        $block.removeAttr('hidden').html(
          '<div class="cart-summary__shipping-progress">' +
            '<div class="cart-summary__shipping-progress-bar" data-shipping-progress style="width:' +
            response.free_shipping_progress +
            '%"></div>' +
            '</div>' +
            '<p class="cart-summary__shipping-msg" data-shipping-msg">Add <strong>' +
            formatMoney(response.amount_to_free) +
            '</strong> more for free shipping</p>'
        );
      } else if (response.has_free_threshold && response.amount_to_free === 0) {
        $block.removeAttr('hidden').html(
          '<p class="cart-summary__shipping-msg cart-summary__shipping-msg--success" data-shipping-msg">✓ You qualify for free shipping</p>'
        );
      } else {
        $block.attr('hidden', true).empty();
      }
    }

    if (typeof response.cart_count !== 'undefined') {
      const label = response.cart_count === 1 ? 'item' : 'items';
      $('[data-cart-page-count]').text(response.cart_count + ' ' + label);
    }
  }

  function updateCartLineItem($line, response) {
    $line.find('[data-qty-input]').val(response.quantity);
    $line.find('[data-line-total]').text(formatMoney(response.line_total));

    const $summaryItem = $('[data-summary-item][data-product-id="' + response.product_id + '"]');

    if ($summaryItem.length) {
      $summaryItem.find('[data-summary-qty]').text(
        'Qty ' + response.quantity + ' · ' + formatMoney(response.unit_price)
      );
      $summaryItem.find('[data-summary-line-total]').text(formatMoney(response.line_total));
    }
  }

  function removeCartLineItem($line, productId, response) {
    const $summaryItem = $('[data-summary-item][data-product-id="' + productId + '"]');

    $line.slideUp(260, function () {
      $(this).remove();
    });

    $summaryItem.slideUp(260, function () {
      $(this).remove();

      if ($('[data-cart-line]').length === 0) {
        window.location.reload();
      }
    });

    updateCartPageSummary(response);
  }

  function submitCartQuantity($row, quantity) {
    const $line = $row.closest('[data-cart-line]');
    const $stepper = $row.find('.cart-qty-stepper');
    const url = $row.data('update-url');
    const productId = $line.data('product-id');

    $stepper.addClass('is-loading');
    $row.find('.cart-qty-stepper__btn').prop('disabled', true);

    return $.ajax({
      url: url,
      method: 'PATCH',
      data: {
        quantity: quantity,
        _token: csrf,
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
    })
      .done(function (response) {
        updateCartCount(response.cart_count);
        updateAllCartActions(productId, response.in_cart, response.quantity || 1);

        if (!response.in_cart) {
          removeCartLineItem($line, productId, response);
          toastr.success(response.message || 'Removed from cart.');
          return;
        }

        updateCartLineItem($line, response);
        updateCartPageSummary(response);
        toastr.success(response.message || 'Quantity updated.');
      })
      .fail(function (xhr) {
        toastr.error(getErrorMessage(xhr));
      })
      .always(function () {
        $stepper.removeClass('is-loading');
        $row.find('.cart-qty-stepper__btn').prop('disabled', false);
      });
  }

  $(document).on('click', '[data-cart-add]', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const $action = $btn.closest('[data-cart-action]');
    const productId = $action.data('product-id');
    const quantity = $action.find('[name="quantity"]').val() || 1;

    $btn.prop('disabled', true);

    $.ajax({
      url: $action.data('add-url'),
      method: 'POST',
      data: {
        quantity: quantity,
        _token: csrf,
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
    })
      .done(function (response) {
        toastr.success(response.message || 'Added to cart successfully.');
        updateCartCount(response.cart_count);
        updateAllCartActions(productId, true, response.quantity);
      })
      .fail(function (xhr) {
        toastr.error(getErrorMessage(xhr));
      })
      .always(function () {
        $btn.prop('disabled', false);
      });
  });

  $(document).on('click', '[data-cart-remove]', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const $action = $btn.closest('[data-cart-action]');
    const productId = $action.data('product-id');

    $btn.prop('disabled', true);

    $.ajax({
      url: $action.data('remove-url'),
      method: 'DELETE',
      data: {
        _token: csrf,
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
    })
      .done(function (response) {
        toastr.success(response.message || 'Removed from cart.');
        updateCartCount(response.cart_count);
        updateAllCartActions(productId, false, 1);
      })
      .fail(function (xhr) {
        toastr.error(getErrorMessage(xhr));
      })
      .always(function () {
        $btn.prop('disabled', false);
      });
  });

  $(document).on('click', '[data-qty-minus]', function () {
    const $row = $(this).closest('[data-cart-update-form]');
    const $input = $row.find('[data-qty-input]');
    const val = parseInt($input.val(), 10) || 1;

    if ($row.find('.cart-qty-stepper').hasClass('is-loading') || val <= 0) {
      return;
    }

    submitCartQuantity($row, val - 1);
  });

  $(document).on('click', '[data-qty-plus]', function () {
    const $row = $(this).closest('[data-cart-update-form]');
    const $input = $row.find('[data-qty-input]');
    const val = parseInt($input.val(), 10) || 1;
    const max = parseInt($input.attr('max'), 10) || 20;

    if (val >= max || $row.find('.cart-qty-stepper').hasClass('is-loading')) {
      return;
    }

    submitCartQuantity($row, val + 1);
  });

  $(document).on('click', '[data-cart-page-remove]', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const $line = $btn.closest('[data-cart-line]');
    const productId = $line.data('product-id');
    const url = $btn.data('remove-url');

    if ($btn.prop('disabled')) {
      return;
    }

    $btn.prop('disabled', true);

    $.ajax({
      url: url,
      method: 'DELETE',
      data: { _token: csrf },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
    })
      .done(function (response) {
        toastr.success(response.message || 'Removed from cart.');
        updateCartCount(response.cart_count);
        updateAllCartActions(productId, false, 1);
        removeCartLineItem($line, productId, response);
      })
      .fail(function (xhr) {
        toastr.error(getErrorMessage(xhr));
        $btn.prop('disabled', false);
      });
  });
});
