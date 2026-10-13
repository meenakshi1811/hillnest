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

  $(document).on('click', '.js-order-delete', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var url = $btn.data('url');
    var label = $('<div>').text($btn.data('label')).html();
    var html = 'Delete order <strong>' + label + '</strong> along with its items and payment record? This cannot be undone.';

    if (String($btn.data('paid')) === '1') {
      html += '<br><br><small>This order was paid. Deleting it will not refund the customer on Razorpay.</small>';
    }

    Swal.fire({
      title: 'Delete order?',
      html: html,
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

      $btn.prop('disabled', true);

      $.ajax({
        url: url,
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrf,
          Accept: 'application/json',
        },
        success: function (response) {
          toastr.success(response.message || 'Order deleted.');

          if (window.ordersTable) {
            window.ordersTable.ajax.reload(null, false);
          } else {
            window.location.reload();
          }
        },
        error: function (xhr) {
          $btn.prop('disabled', false);
          toastr.error(getErrorMessage(xhr));
        },
      });
    });
  });
});
