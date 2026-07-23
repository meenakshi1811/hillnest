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

  function errorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    return 'Something went wrong. Please try again.';
  }

  $(document).on('change', '.js-product-toggle', function () {
    var $toggle = $(this);
    var $label = $toggle.closest('.admin-toggle');
    var url = $toggle.data('url');
    var previous = !$toggle.prop('checked');

    $.ajax({
      url: url,
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': csrf,
        Accept: 'application/json',
      },
      success: function (response) {
        var isActive = !!response.is_active;
        $toggle.prop('checked', isActive);
        $label.find('.admin-toggle__label').text(isActive ? 'Active' : 'Hidden');
        $label.attr('title', isActive ? 'Active — click to hide' : 'Hidden — click to activate');
        toastr.success(response.message || 'Product status updated.');
      },
      error: function (xhr) {
        $toggle.prop('checked', previous);
        toastr.error(errorMessage(xhr));
      },
    });
  });

  $(document).on('click', '.js-product-delete', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var url = $btn.data('url');
    var name = $btn.data('name');
    var table = window.productsTable;

    Swal.fire({
      title: 'Delete product?',
      html: 'Are you sure you want to delete <strong>' + name + '</strong>? This cannot be undone.',
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
          toastr.success(response.message || 'Product deleted successfully.');
          if (table) {
            table.ajax.reload(null, false);
          }
        },
        error: function (xhr) {
          toastr.error(errorMessage(xhr));
        },
      });
    });
  });
});
