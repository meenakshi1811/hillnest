$(function () {
  var $form = $('[data-blog-form]');

  if (!$form.length) {
    return;
  }

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

  function slugify(text) {
    return (text || '')
      .toLowerCase()
      .normalize('NFKD').replace(/[̀-ͯ]/g, '')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }

  // ── Slug + Google preview ──────────────────────────────────────────
  var $title = $form.find('#title');
  var $slug = $form.find('[data-slug-input]');
  var $metaTitle = $form.find('#meta_title');
  var $metaDesc = $form.find('#meta_description');
  var $excerpt = $form.find('#excerpt');

  function truncate(text, max) {
    return text.length > max ? text.slice(0, max - 1).trim() + '…' : text;
  }

  function refreshPreview() {
    var slug = $slug.val().trim() || slugify($title.val()) || '…';
    $form.find('[data-slug-preview]').text(slug);
    $form.find('[data-serp-slug]').text(slug);

    var title = $metaTitle.val().trim() || ($title.val().trim() ? $title.val().trim() + ' — HillNest' : 'Article title — HillNest');
    var desc = $metaDesc.val().trim() || $excerpt.val().trim() || 'The summary appears here.';
    $form.find('[data-serp-title]').text(truncate(title, 62));
    $form.find('[data-serp-desc]').text(truncate(desc, 160));
  }

  $title.add($slug).add($metaTitle).add($metaDesc).add($excerpt).on('input', refreshPreview);
  refreshPreview();

  // ── Character counters ─────────────────────────────────────────────
  $form.find('[data-count-for]').each(function () {
    var $counter = $(this);
    var $field = $form.find('#' + $counter.data('count-for'));
    var ideal = String($counter.data('ideal')).split('-').map(Number);

    function update() {
      var length = $field.val().length;
      $counter.text(length + ' / ideal ' + $counter.data('ideal'));
      $counter.toggleClass('is-good', length >= ideal[0] && length <= ideal[1]);
      $counter.toggleClass('is-long', length > ideal[1]);
    }

    $field.on('input', update);
    update();
  });

  // ── FAQ rows ───────────────────────────────────────────────────────
  var $faqList = $form.find('[data-faq-list]');
  var faqTemplate = $form.find('[data-faq-template]').html();
  var faqIndex = $faqList.children('[data-faq-row]').length;

  $form.on('click', '[data-faq-add]', function () {
    $faqList.append(faqTemplate.replace(/__i__/g, String(faqIndex++)));
    $faqList.children('[data-faq-row]').last().find('input').trigger('focus');
  });

  $form.on('click', '[data-faq-remove]', function () {
    $(this).closest('[data-faq-row]').remove();
  });

  // ── Cover photo preview ────────────────────────────────────────────
  var $preview = $('#cover-image-preview');
  var $placeholder = $('#cover-image-placeholder');
  var $fileInput = $('#cover_image');
  var objectUrl = null;

  function showPreview(src) {
    if (!src) {
      $preview.attr('src', '').attr('hidden', true);
      $placeholder.removeAttr('hidden');
      return;
    }

    $preview.attr('src', src).removeAttr('hidden');
    $placeholder.attr('hidden', true);
  }

  $fileInput.on('change', function () {
    var file = this.files && this.files[0];

    if (objectUrl) {
      URL.revokeObjectURL(objectUrl);
      objectUrl = null;
    }

    if (!file) {
      return;
    }

    if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
      $fileInput.val('');
      toastr.error('Please choose a JPG, PNG or WebP photo.');
      return;
    }

    if (file.size > 4 * 1024 * 1024) {
      $fileInput.val('');
      toastr.error('The photo must be 4MB or smaller.');
      return;
    }

    objectUrl = URL.createObjectURL(file);
    showPreview(objectUrl);
  });

  // ── Submit ─────────────────────────────────────────────────────────
  function clearErrors() {
    $form.find('[data-field-error]').attr('hidden', true).text('');
    $form.find('.is-invalid').removeClass('is-invalid');
  }

  function showErrors(errors) {
    var first = null;

    Object.keys(errors).forEach(function (field) {
      // faqs.0.question -> the faqs block
      var key = field.indexOf('faqs.') === 0 ? 'faqs' : field;
      var $error = $form.find('[data-field-error="' + key + '"]');
      $error.text(errors[field][0]).removeAttr('hidden');
      $form.find('[name="' + key + '"]').addClass('is-invalid');
      first = first || $error;
    });

    if (first && first.length) {
      $('html, body').animate({ scrollTop: first.closest('.admin-field').offset().top - 90 }, 250);
    }
  }

  $form.find('input, textarea').on('input', function () {
    $(this).removeClass('is-invalid');
    $form.find('[data-field-error="' + $(this).attr('name') + '"]').attr('hidden', true).text('');
  });

  $form.on('submit', function (e) {
    e.preventDefault();
    clearErrors();

    var $btn = $form.find('[data-blog-submit]');
    $btn.prop('disabled', true).addClass('is-loading');

    var formData = new FormData(this);
    formData.set('is_published', $form.find('[name="is_published"]').is(':checked') ? '1' : '0');
    if ($form.find('[name="remove_cover_image"]').length) {
      formData.set('remove_cover_image', $form.find('[name="remove_cover_image"]').is(':checked') ? '1' : '0');
    }

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
    })
      .done(function (response) {
        toastr.success(response.message || 'Saved.');

        if (response.redirect) {
          setTimeout(function () { window.location.href = response.redirect; }, 700);
          return;
        }

        if (response.slug) {
          $slug.val(response.slug);
          refreshPreview();
        }

        if (response.view_url) {
          $form.find('[data-view-link]').attr('href', response.view_url);
        }

        $fileInput.val('');
        $form.find('[name="remove_cover_image"]').prop('checked', false);
        showPreview(response.cover_image_url || null);
        $('#blog-form-subtitle').text($title.val());
      })
      .fail(function (xhr) {
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          showErrors(xhr.responseJSON.errors);
          toastr.error('Please fix the highlighted fields.');
          return;
        }

        toastr.error((xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong. Please try again.');
      })
      .always(function () {
        $btn.prop('disabled', false).removeClass('is-loading');
      });
  });
});
