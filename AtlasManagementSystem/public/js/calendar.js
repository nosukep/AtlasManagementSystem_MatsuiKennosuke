$(function () {
  $('.cancel-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var delete_date = $(this).attr('delete-date');
    var delete_part = $(this).attr('delete-part');
    $('.cancel-modal-hidden').val(delete_date);
    $('.cancel-modal-date').text(delete_date);
    $('.cancel-modal-part').text(delete_part);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });
});
