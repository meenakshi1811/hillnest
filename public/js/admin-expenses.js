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

  function formatMoney(amount) {
    return '₹' + Math.round(amount).toLocaleString('en-IN');
  }

  function updateTotalPreview($form) {
    var qty = parseFloat($form.find('[data-expense-qty]').val()) || 0;
    var price = parseFloat($form.find('[data-expense-price]').val()) || 0;
    var total = Math.round(qty * price);

    $form.find('[data-expense-total-value]').text(formatMoney(total));
  }

  $('[data-expense-form]').each(function () {
    var $form = $(this);
    updateTotalPreview($form);

    $form.on('input', '[data-expense-qty], [data-expense-price]', function () {
      updateTotalPreview($form);
    });
  });

  function showErrors($form, errors) {
    Object.keys(errors).forEach(function (field) {
      var message = errors[field][0];
      var $input = $form.find('[name="' + field + '"]');

      if ($input.length) {
        $input.addClass('is-invalid');

        if (field === 'purchased_by') {
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
    var $btn = $form.find('[data-expense-submit]');
    $btn.prop('disabled', isLoading).toggleClass('is-loading', isLoading);
  }

  $(document).on('submit', '[data-expense-form]', function (e) {
    e.preventDefault();

    var $form = $(this);
    clearErrors($form);
    setLoading($form, true);

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
        setLoading($form, false);
        toastr.success(response.message || 'Expense saved.');

        if (window.expensesTable) {
          window.location.reload();
          return;
        }

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
          toastr.error('Please fix the highlighted fields.');
          return;
        }

        toastr.error(getErrorMessage(xhr));
      });
  });

  $(document).on('click', '.js-expense-delete', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var url = $btn.data('url');
    var title = $btn.data('title');

    Swal.fire({
      title: 'Delete expense?',
      html: 'Remove <strong>' + title + '</strong> from expense records?',
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
          toastr.success(response.message || 'Expense deleted.');
          window.location.reload();
        },
        error: function (xhr) {
          toastr.error(getErrorMessage(xhr));
        },
      });
    });
  });
});
