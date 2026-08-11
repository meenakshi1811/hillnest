$(function () {
  const csrf = $('meta[name="csrf-token"]').attr('content');

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

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    if (xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join(' ');
    }

    return 'Something went wrong. Please try again.';
  }

  function initAjaxForm(options) {
    const $form = $(options.formSelector);

    if (!$form.length) {
      return;
    }

    function clearErrors() {
      $form.find('[data-field-error]').attr('hidden', true).text('');
      $form.find('input.is-invalid').removeClass('is-invalid');
      $form.find(options.inputWrapSelector + '.is-invalid').removeClass('is-invalid');
    }

    function showErrors(errors) {
      Object.keys(errors).forEach(function (field) {
        const message = errors[field][0];
        const $input = $form.find('[name="' + field + '"]');

        if (!$input.length) {
          return;
        }

        $input.addClass('is-invalid');
        $input.closest(options.inputWrapSelector).addClass('is-invalid');
        $form.find('[data-field-error="' + field + '"]').text(message).removeAttr('hidden');
      });
    }

    function setLoading(isLoading) {
      const $btn = $form.find(options.submitSelector);

      $btn.prop('disabled', isLoading).toggleClass('is-loading', isLoading);
      $btn.find('.auth-submit__text').toggle(!isLoading);
      $btn.find('.auth-submit__icon').toggle(!isLoading && $btn.find('.auth-submit__icon').length);
      $btn.find('.auth-submit__loader').toggle(isLoading);
    }

    $form.find('input[name]').on('input', function () {
      const name = $(this).attr('name');

      $(this).removeClass('is-invalid');
      $(this).closest(options.inputWrapSelector).removeClass('is-invalid');
      $form.find('[data-field-error="' + name + '"]').attr('hidden', true).text('');
    });

    $form.on('submit', function (e) {
      e.preventDefault();

      clearErrors();
      setLoading(true);

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
          setLoading(false);

          if (typeof options.onSuccess === 'function') {
            options.onSuccess(response, $form);
          }

          if (typeof toastr !== 'undefined') {
            toastr.success(response.message || options.successMessage);
          }
        })
        .fail(function (xhr) {
          setLoading(false);

          if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            showErrors(xhr.responseJSON.errors);

            if (typeof toastr !== 'undefined') {
              toastr.error('Please fix the highlighted fields.');
            }

            const $firstInvalid = $form.find('.is-invalid').first();

            if ($firstInvalid.length) {
              $firstInvalid.trigger('focus');
            }

            return;
          }

          if (typeof toastr !== 'undefined') {
            toastr.error(getErrorMessage(xhr));
          }
        });
    });
  }

  initAjaxForm({
    formSelector: '[data-register-form]',
    submitSelector: '[data-register-submit]',
    inputWrapSelector: '.auth-input-wrap',
    successMessage: 'Account created successfully.',
    onSuccess: function (response) {
      window.location.href = response.redirect || '/account/orders';
    },
  });

  initAjaxForm({
    formSelector: '[data-profile-form]',
    submitSelector: '[data-profile-submit]',
    inputWrapSelector: '.account-field__wrap',
    successMessage: 'Profile updated successfully.',
    onSuccess: function (response) {
      if (response.name) {
        $('.account-user-card h2').text(response.name);
        $('.account-user-card__avatar').text(response.name.charAt(0).toUpperCase());
      }

      if (response.email || response.phone) {
        $('.account-user-card__email').text(response.email || response.phone);
      }
    },
  });
});
