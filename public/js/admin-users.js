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

  $(document).on('change', '.js-user-block-toggle', function () {
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
        var isBlocked = !!response.is_blocked;
        $toggle.prop('checked', !isBlocked);
        $label.find('.admin-toggle__label').text(isBlocked ? 'Blocked' : 'Active');
        $label.attr('title', isBlocked ? 'Blocked — click to unblock' : 'Active — click to block');

        var $badge = $('.admin-customer-actions .admin-badge');
        if ($badge.length) {
          $badge
            .removeClass('admin-badge--active admin-badge--cancelled')
            .addClass(isBlocked ? 'admin-badge--cancelled' : 'admin-badge--active')
            .text(isBlocked ? 'Blocked' : 'Active');
        }

        toastr.success(response.message || 'Customer status updated.');
      },
      error: function (xhr) {
        $toggle.prop('checked', previous);
        toastr.error(errorMessage(xhr));
      },
    });
  });
});
