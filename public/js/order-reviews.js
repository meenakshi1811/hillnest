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
  var maxImages = 3;

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    if (xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join(' ');
    }

    return 'Something went wrong. Please try again.';
  }

  function renderPreviews($input) {
    var $wrap = $input.closest('.order-review-form__photos').find('[data-review-previews]');
    var files = Array.from($input[0].files || []);

    $wrap.empty();

    if (!files.length) {
      $wrap.attr('hidden', true);
      return;
    }

    $wrap.removeAttr('hidden');

    files.slice(0, maxImages).forEach(function (file) {
      var url = URL.createObjectURL(file);
      var $item = $('<div class="order-review-form__preview"></div>');
      $item.append($('<img alt="">').attr('src', url));
      $wrap.append($item);
    });
  }

  $(document).on('change', '[data-review-images]', function () {
    var input = this;
    var files = Array.from(input.files || []);

    if (files.length > maxImages) {
      if (typeof toastr !== 'undefined') {
        toastr.error('You can upload up to ' + maxImages + ' images.');
      }

      try {
        var transfer = new DataTransfer();
        files.slice(0, maxImages).forEach(function (file) {
          transfer.items.add(file);
        });
        input.files = transfer.files;
      } catch (e) {
        input.value = '';
      }
    }

    renderPreviews($(input));
  });

  $(document).on('submit', '[data-order-review-form]', function (e) {
    e.preventDefault();

    var $form = $(this);
    var $submit = $form.find('[data-order-review-submit]');
    var $errors = $form.find('[data-order-review-error]');
    var formData = new FormData(this);

    $errors.attr('hidden', true).text('');
    $submit.prop('disabled', true).addClass('is-loading');

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
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
