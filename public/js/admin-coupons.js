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

  function showErrors($form, errors) {
    Object.keys(errors).forEach(function (field) {
      var message = errors[field][0];
      var $input = $form.find('[name="' + field + '"]');

      if ($input.length) {
        $input.addClass('is-invalid');

        if (field === 'type') {
          $input.first().closest('.admin-segmented').addClass('is-invalid');
        }
      }

      $form.find('[data-field-error="' + field + '"]').text(message).removeAttr('hidden');
    });
  }

  function clearErrors($form) {
    $form.find('[data-field-error]').attr('hidden', true).text('');
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.admin-segmented.is-invalid').removeClass('is-invalid');
  }

  function setLoading($form, isLoading) {
    var $btn = $form.find('[data-coupon-submit]');
    $btn.prop('disabled', isLoading).toggleClass('is-loading', isLoading);
  }

  function updateValueField($form) {
    var type = $form.find('[data-coupon-type]:checked').val();
    var $label = $form.find('[data-coupon-value-label]');
    var $value = $form.find('[data-coupon-value]');

    if (type === 'percent') {
      $label.text('Discount percentage');
      $value.attr('max', 100).attr('placeholder', '10');
    } else {
      $label.text('Discount amount (₹)');
      $value.removeAttr('max').attr('placeholder', '200');
    }
  }

  function updateAssignmentField($form) {
    var assignment = $form.find('[data-coupon-assignment]:checked').val();
    var isAll = assignment === 'all';
    var $customerField = $form.find('[data-coupon-customer-field]');
    var $customerSelect = $form.find('#user_id');

    $customerField.toggle(!isAll);
    $form.find('[data-coupon-all-hint]').toggle(isAll);

    if (isAll) {
      $customerSelect.prop('required', false).val('');
    } else {
      $customerSelect.prop('required', true);
    }
  }

  $('[data-coupon-form]').each(function () {
    var $form = $(this);
    updateValueField($form);
    updateAssignmentField($form);
  });

  $(document).on('change', '[data-coupon-type]', function () {
    updateValueField($(this).closest('[data-coupon-form]'));
  });

  $(document).on('change', '[data-coupon-assignment]', function () {
    updateAssignmentField($(this).closest('[data-coupon-form]'));
  });

  $(document).on('click', '[data-generate-code]', function () {
    var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    var code = 'HN-';

    for (var i = 0; i < 6; i += 1) {
      code += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    $(this).closest('.admin-input-group').find('#code').val(code);
  });

  $(document).on('submit', '[data-coupon-form]', function (e) {
    e.preventDefault();

    var $form = $(this);
    clearErrors($form);
    setLoading($form, true);

    $.ajax({
      url: $form.attr('action'),
      method: $form.find('[name="_method"]').val() || 'POST',
      data: $form.serialize(),
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
      },
    })
      .done(function (response) {
        setLoading($form, false);
        toastr.success(response.message || 'Coupon saved.');

        if (response.redirect) {
          setTimeout(function () {
            window.location.href = response.redirect;
          }, 600);
        }
      })
      .fail(function (xhr) {
        setLoading($form, false);

        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          showErrors($form, xhr.responseJSON.errors);
          toastr.error(getErrorMessage(xhr));
          return;
        }

        toastr.error(getErrorMessage(xhr));
      });
  });

  $(document).on('click', '.js-coupon-delete', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var url = $btn.data('url');
    var code = $btn.data('code');

    Swal.fire({
      title: 'Delete coupon?',
      html: 'Remove coupon <strong>' + code + '</strong>?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#8a3838',
      cancelButtonColor: '#1E3B2F',
      reverseButtons: true,
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }

      $.ajax({
        url: url,
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrf,
          Accept: 'application/json',
        },
        success: function (response) {
          toastr.success(response.message || 'Coupon deleted.');
          window.location.reload();
        },
        error: function (xhr) {
          toastr.error(getErrorMessage(xhr));
        },
      });
    });
  });
});
