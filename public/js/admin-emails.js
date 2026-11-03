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
  var ajaxHeaders = {
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
    'X-CSRF-TOKEN': csrf,
  };

  function getErrorMessage(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.errors) {
      return Object.values(xhr.responseJSON.errors).flat().join(' ');
    }

    if (xhr.responseJSON && xhr.responseJSON.message) {
      return xhr.responseJSON.message;
    }

    return 'Something went wrong. Please try again.';
  }

  function escapeHtml(text) {
    return $('<div>').text(text).html();
  }

  function clearErrors($scope) {
    $scope.find('[data-field-error]').attr('hidden', true).text('');
    $scope.find('.is-invalid').removeClass('is-invalid');
  }

  // Shows 422 field errors next to their fields; returns true when any were shown.
  function showErrors($scope, xhr) {
    if (xhr.status !== 422 || !xhr.responseJSON || !xhr.responseJSON.errors) {
      return false;
    }

    var shown = false;

    Object.keys(xhr.responseJSON.errors).forEach(function (field) {
      var key = field.split('.')[0];
      var $error = $scope.find('[data-field-error="' + key + '"]');

      $scope.find('[name="' + key + '"]').addClass('is-invalid');

      if ($error.length) {
        $error.text(xhr.responseJSON.errors[field][0]).removeAttr('hidden');
        shown = true;
      }
    });

    return shown;
  }

  function setLoading($btn, isLoading) {
    $btn.prop('disabled', isLoading).toggleClass('is-loading', isLoading);
  }

  /*
   * What to preview or test: a saved template (list row or compose page),
   * or the unsaved fields of the template form.
   */
  function emailPayload($trigger) {
    if ($trigger.data('template-id')) {
      return { template_id: $trigger.data('template-id') };
    }

    var $templateForm = $trigger.closest('[data-email-template-form]');

    if ($templateForm.length) {
      return $templateForm
        .find('[name]')
        .not('[name="_method"], [name="_token"], [name="test_email"], [name="name"]')
        .serialize();
    }

    return { template_id: $('#email_template_id').val() };
  }

  function withTestEmail(payload, email) {
    if (typeof payload === 'string') {
      return payload + '&test_email=' + encodeURIComponent(email);
    }

    return $.extend({}, payload, { test_email: email });
  }

  // ── Preview ──
  $(document).on('click', '[data-email-preview]', function () {
    var $btn = $(this);
    var $scope = $btn.closest('form').length ? $btn.closest('form') : $(document.body);

    clearErrors($scope);
    $btn.prop('disabled', true);

    $.ajax({ url: window.emailPreviewUrl, method: 'POST', data: emailPayload($btn), headers: ajaxHeaders })
      .done(function (response) {
        Swal.fire({
          width: 720,
          padding: '12px',
          showConfirmButton: false,
          showCloseButton: true,
          html: '<iframe class="admin-email-preview-frame" title="Email preview"></iframe>',
          didOpen: function (popup) {
            popup.querySelector('iframe').srcdoc = response.html;
          },
        });
      })
      .fail(function (xhr) {
        if (!showErrors($scope, xhr)) {
          toastr.error(getErrorMessage(xhr));
        } else {
          toastr.error('Fill in the highlighted fields to preview.');
        }
      })
      .always(function () {
        $btn.prop('disabled', false);
      });
  });

  // ── Test email ──
  $(document).on('click', '[data-email-test]', function () {
    var $btn = $(this);
    var $scope = $btn.closest('form');
    var email = $.trim($('#test_email').val());

    clearErrors($scope);
    setLoading($btn, true);

    $.ajax({ url: window.emailTestUrl, method: 'POST', data: withTestEmail(emailPayload($btn), email), headers: ajaxHeaders })
      .done(function (response) {
        toastr.success(response.message || 'Test email sent.');
      })
      .fail(function (xhr) {
        if (!showErrors($scope, xhr)) {
          toastr.error(getErrorMessage(xhr));
        }
      })
      .always(function () {
        setLoading($btn, false);
      });
  });

  // ── Template form: placeholders and save ──
  var $lastTextField = null;

  $(document).on('focus', '[data-email-template-form] [name="subject"], [data-email-template-form] [name="preheader"], [data-email-template-form] [name="heading"], [data-email-template-form] [name="body"]', function () {
    $lastTextField = $(this);
  });

  $(document).on('click', '[data-copy-placeholder]', function () {
    var placeholder = $(this).data('copy-placeholder');

    if ($lastTextField) {
      var field = $lastTextField.get(0);
      var start = field.selectionStart || 0;
      var end = field.selectionEnd || 0;

      field.value = field.value.slice(0, start) + placeholder + field.value.slice(end);
      field.focus();
      field.setSelectionRange(start + placeholder.length, start + placeholder.length);

      return;
    }

    if (navigator.clipboard) {
      navigator.clipboard.writeText(placeholder).then(function () {
        toastr.info('Copied ' + placeholder + '. Paste it where you need it.');
      });
    }
  });

  $(document).on('submit', '[data-email-template-form]', function (e) {
    e.preventDefault();

    var $form = $(this);
    var $btn = $form.find('[data-email-template-submit]');

    clearErrors($form);
    setLoading($btn, true);

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: $form.find('[name]').not('[name="test_email"]').serialize(),
      headers: ajaxHeaders,
    })
      .done(function (response) {
        toastr.success(response.message || 'Template saved.');
        setTimeout(function () {
          window.location.href = response.redirect;
        }, 600);
      })
      .fail(function (xhr) {
        setLoading($btn, false);

        if (!showErrors($form, xhr)) {
          toastr.error(getErrorMessage(xhr));
        }
      });
  });

  $(document).on('click', '[data-email-template-delete]', function () {
    var $btn = $(this);

    Swal.fire({
      title: 'Delete template?',
      html: 'Delete <strong>' + escapeHtml($btn.data('label')) + '</strong>? Emails already sent are not affected.',
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

      $.ajax({ url: $btn.data('url'), method: 'DELETE', headers: ajaxHeaders })
        .done(function (response) {
          toastr.success(response.message || 'Template deleted.');
          setTimeout(function () {
            window.location.reload();
          }, 600);
        })
        .fail(function (xhr) {
          toastr.error(getErrorMessage(xhr));
        });
    });
  });

  // ── Compose: recipients ──
  var $campaignForm = $('[data-email-campaign-form]');

  function selectedCount() {
    return $campaignForm.find('input[name="user_ids[]"]:checked').length;
  }

  function updatePicker() {
    var isSelected = $campaignForm.find('[data-email-audience]:checked').val() === 'selected';

    $campaignForm.find('[data-email-picker]').prop('hidden', !isSelected);
    $campaignForm.find('[data-email-all-hint]').prop('hidden', isSelected);
    $campaignForm.find('[data-email-picker-count]').text(selectedCount());
  }

  if ($campaignForm.length) {
    updatePicker();

    $campaignForm.on('change', '[data-email-audience], input[name="user_ids[]"]', updatePicker);

    $campaignForm.on('input', '[data-email-picker-search]', function () {
      var term = $.trim($(this).val()).toLowerCase();

      $campaignForm.find('[data-email-picker-item]').each(function () {
        $(this).prop('hidden', term !== '' && String($(this).data('search')).indexOf(term) === -1);
      });
    });

    $campaignForm.on('click', '[data-email-picker-all]', function () {
      $campaignForm.find('[data-email-picker-item]:not([hidden]) input:not(:disabled)').prop('checked', true);
      updatePicker();
    });

    $campaignForm.on('click', '[data-email-picker-none]', function () {
      $campaignForm.find('input[name="user_ids[]"]').prop('checked', false);
      updatePicker();
    });
  }

  // ── Sending in batches with a progress window ──
  function progressHtml(sent, failed, total) {
    var done = sent + failed;
    var percent = total ? Math.round((done / total) * 100) : 100;

    return (
      '<div class="admin-email-progress"><div class="admin-email-progress__bar" style="width:' + percent + '%"></div></div>' +
      '<p class="admin-email-progress__text">Sent ' + sent + ' of ' + total + (failed ? ' · ' + failed + ' failed' : '') + '</p>' +
      '<p class="admin-email-progress__hint">Keep this page open until sending finishes.</p>'
    );
  }

  function sendBatches(sendUrl, showUrl, total) {
    var retries = 0;

    window.onbeforeunload = function () {
      return 'Emails are still sending.';
    };

    Swal.fire({
      title: 'Sending emails…',
      html: progressHtml(0, 0, total),
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
    });

    function next() {
      $.ajax({ url: sendUrl, method: 'POST', headers: ajaxHeaders })
        .done(function (progress) {
          retries = 0;
          Swal.getHtmlContainer().innerHTML = progressHtml(progress.sent, progress.failed, progress.total);

          if (progress.complete) {
            window.onbeforeunload = null;
            Swal.fire({
              icon: progress.failed ? 'warning' : 'success',
              title: 'Done',
              html: 'Sent to <strong>' + progress.sent + '</strong> customer' + (progress.sent === 1 ? '' : 's') +
                (progress.failed ? '. ' + progress.failed + ' could not be sent; see the details.' : '.'),
              confirmButtonText: 'View details',
              confirmButtonColor: '#1E3B2F',
            }).then(function () {
              window.location.href = showUrl;
            });

            return;
          }

          next();
        })
        .fail(function () {
          retries += 1;

          if (retries <= 3) {
            setTimeout(next, 2000 * retries);

            return;
          }

          window.onbeforeunload = null;
          Swal.fire({
            icon: 'error',
            title: 'Sending paused',
            text: 'The connection dropped. You can resume from the details page; nobody gets the email twice.',
            confirmButtonText: 'Open details',
            confirmButtonColor: '#1E3B2F',
          }).then(function () {
            window.location.href = showUrl;
          });
        });
    }

    next();
  }

  $campaignForm.on('submit', function (e) {
    e.preventDefault();

    var $form = $(this);
    var $btn = $form.find('[data-email-campaign-submit]');
    var isSelected = $form.find('[data-email-audience]:checked').val() === 'selected';
    var count = isSelected ? selectedCount() : null;
    var templateName = $form.find('#email_template_id option:selected').text();

    clearErrors($form);

    if (isSelected && count === 0) {
      $form.find('[data-field-error="user_ids"]').text('Select at least one customer.').removeAttr('hidden');

      return;
    }

    Swal.fire({
      title: 'Send this email?',
      html: 'Send <strong>' + escapeHtml(templateName) + '</strong> to ' +
        (isSelected ? '<strong>' + count + '</strong> selected customer' + (count === 1 ? '' : 's') : '<strong>all customers</strong>') +
        '? This cannot be undone.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, send',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#1E3B2F',
      cancelButtonColor: '#8a7560',
      reverseButtons: true,
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }

      setLoading($btn, true);

      $.ajax({ url: $form.attr('action'), method: 'POST', data: $form.serialize(), headers: ajaxHeaders })
        .done(function (response) {
          sendBatches(response.send_url, response.show_url, response.total);
        })
        .fail(function (xhr) {
          setLoading($btn, false);

          if (!showErrors($form, xhr)) {
            toastr.error(getErrorMessage(xhr));
          }
        });
    });
  });

  // ── Campaign details: resume ──
  $(document).on('click', '[data-email-resume]', function () {
    var $btn = $(this);

    $btn.prop('disabled', true);
    sendBatches($btn.data('send-url'), window.location.href, $btn.data('pending'));
  });
});
