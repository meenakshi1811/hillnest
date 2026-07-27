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

  $(document).on('click', '.js-review-delete', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var url = $btn.data('url');
    var label = $btn.data('label');

    Swal.fire({
      title: 'Delete review?',
      html: 'Remove the review for <strong>' + label + '</strong>? This cannot be undone.',
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
          toastr.success(response.message || 'Review deleted.');

          if (window.reviewsTable) {
            window.reviewsTable.ajax.reload(null, false);
          } else {
            window.location.reload();
          }
        },
        error: function (xhr) {
          toastr.error(getErrorMessage(xhr));
        },
      });
    });
  });
});
