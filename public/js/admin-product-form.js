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
  var $form = $('[data-product-form]');

  if (!$form.length) {
    return;
  }

  var $preview = $('#product-image-preview');
  var $placeholder = $('#product-image-placeholder');
  var $fileInput = $('#image');
  var previewObjectUrl = null;

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    if (xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join(' ');
    }

    return 'Something went wrong. Please try again.';
  }

  function clearErrors() {
    $form.find('[data-field-error]').attr('hidden', true).text('');
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.admin-image-upload__controls').removeClass('is-invalid');
  }

  function showErrors(errors) {
    Object.keys(errors).forEach(function (field) {
      var message = errors[field][0];
      var $input = $form.find('[name="' + field + '"]');

      if ($input.length) {
        $input.addClass('is-invalid');

        if (field === 'image') {
          $input.closest('.admin-image-upload__controls').addClass('is-invalid');
        }
      }

      $form.find('[data-field-error="' + field + '"]').text(message).removeAttr('hidden');
    });
  }

  function setLoading(isLoading) {
    var $btn = $form.find('[data-product-submit]');
    $btn.prop('disabled', isLoading).toggleClass('is-loading', isLoading);
  }

  function showPreview(src) {
    if (!src) {
      $preview.attr('src', '').attr('hidden', true);
      $placeholder.removeAttr('hidden');
      return;
    }

    $preview.attr('src', src).removeAttr('hidden');
    $placeholder.attr('hidden', true);
  }

  function syncPreviewOnLoad() {
    var src = ($preview.attr('src') || '').trim();
    showPreview(src || null);
  }

  syncPreviewOnLoad();

  function revokePreviewUrl() {
    if (previewObjectUrl) {
      URL.revokeObjectURL(previewObjectUrl);
      previewObjectUrl = null;
    }
  }

  $fileInput.on('change', function () {
    var file = this.files && this.files[0];

    revokePreviewUrl();
    $form.find('[data-field-error="image"]').attr('hidden', true).text('');
    $fileInput.removeClass('is-invalid');

    if (!file) {
      return;
    }

    if (!file.type.match(/^image\/(jpeg|png|webp|jpg)$/)) {
      $fileInput.val('');
      toastr.error('Please choose a JPG, PNG, or WebP image.');
      return;
    }

    if (file.size > 4 * 1024 * 1024) {
      $fileInput.val('');
      toastr.error('Image must be 4MB or smaller.');
      return;
    }

    previewObjectUrl = URL.createObjectURL(file);
    showPreview(previewObjectUrl);
  });

  $form.find('input:not([type="file"]), textarea').on('input', function () {
    var name = $(this).attr('name');

    $(this).removeClass('is-invalid');
    $form.find('[data-field-error="' + name + '"]').attr('hidden', true).text('');
  });

  $form.on('submit', function (e) {
    e.preventDefault();

    clearErrors();
    setLoading(true);

    var formData = new FormData(this);
    formData.set('is_active', $form.find('[name="is_active"]').is(':checked') ? '1' : '0');
    formData.set('is_featured', $form.find('[name="is_featured"]').is(':checked') ? '1' : '0');
    formData.set('is_bestseller', $form.find('[name="is_bestseller"]').is(':checked') ? '1' : '0');
    formData.set('is_trending', $form.find('[name="is_trending"]').is(':checked') ? '1' : '0');

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
        setLoading(false);

        if (response.image_url) {
          revokePreviewUrl();
          showPreview(response.image_url);
          $fileInput.val('');
        }

        if ($('#product-form-subtitle').length && $form.find('#name').val()) {
          $('#product-form-subtitle').text($form.find('#name').val());
        }

        toastr.success(response.message || 'Product saved successfully.');

        if (response.redirect) {
          setTimeout(function () {
            window.location.href = response.redirect;
          }, 700);
        }
      })
      .fail(function (xhr) {
        setLoading(false);

        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          showErrors(xhr.responseJSON.errors);
          toastr.error('Please fix the highlighted fields.');
          return;
        }

        toastr.error(getErrorMessage(xhr));
      });
  });
});
