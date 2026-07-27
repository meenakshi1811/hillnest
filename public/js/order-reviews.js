$(function () {
  if (typeof toastr !== 'undefined') {
    toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: 'toast-top-right',
      timeOut: 3500,
      showMethod: 'fadeIn',
      hideMethod: 'fadeOut',
    };
  }

  var csrf = $('meta[name="csrf-token"]').attr('content');

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    if (xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join(' ');
    }

    return 'Something went wrong. Please try again.';
  }

  $(document).on('submit', '[data-order-review-form]', function (e) {
    e.preventDefault();

    var $form = $(this);
    var $card = $form.closest('.order-review-card');
    var $submit = $form.find('[data-order-review-submit]');
    var $errors = $form.find('[data-order-review-error]');

    $errors.attr('hidden', true).text('');
    $submit.prop('disabled', true).addClass('is-loading');

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: $form.serialize(),
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
      },
    })
      .done(function (response) {
        if (response.html && response.item_id) {
          $('#review-item-' + response.item_id).replaceWith(response.html);
        }

        if (typeof toastr !== 'undefined') {
          toastr.success(response.message || 'Review submitted.');
        }
      })
      .fail(function (xhr) {
        var message = getErrorMessage(xhr);
        $errors.text(message).removeAttr('hidden');

        if (typeof toastr !== 'undefined') {
          toastr.error(message);
        }
      })
      .always(function () {
        $submit.prop('disabled', false).removeClass('is-loading');
      });
  });
});
