$(function () {
  $('.cancel-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var delete_date = $(this).attr('delete-date');
    var delete_part_id = $(this).attr('delete-part-id');
    var delete_part = $(this).attr('delete-part');
    $('.cancel-date-hidden').val(delete_date);
    $('.cancel-part-hidden').val(delete_part_id);
    $('.cancel-modal-date').text(delete_date);
    $('.cancel-modal-part').text(delete_part);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });
});
