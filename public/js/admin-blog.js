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

  function reloadTable() {
    if (window.blogTable) {
      window.blogTable.ajax.reload(null, false);
    }
  }

  $(document).on('change', '.js-blog-toggle', function () {
    var $toggle = $(this);
    var previous = !$toggle.prop('checked');

    $.ajax({
      url: $toggle.data('url'),
      method: 'PATCH',
      headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
      success: function (response) {
        toastr.success(response.message || 'Article updated.');
        reloadTable();
      },
      error: function (xhr) {
        $toggle.prop('checked', previous);
        toastr.error(errorMessage(xhr));
      },
    });
  });

  $(document).on('click', '.js-blog-delete', function (e) {
    e.preventDefault();

    var $btn = $(this);

    Swal.fire({
      title: 'Delete article?',
      html: 'Delete <strong>' + $('<div>').text($btn.data('name')).html() + '</strong>? Its page will stop working. This cannot be undone.',
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
        url: $btn.data('url'),
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
        success: function (response) {
          toastr.success(response.message || 'Article deleted.');
          reloadTable();
        },
        error: function (xhr) {
          toastr.error(errorMessage(xhr));
        },
      });
    });
  });
});
