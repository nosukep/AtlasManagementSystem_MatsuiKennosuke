$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault(); // clickイベントに関して「preventDefault」する
    $(this).addClass('un_like_btn');
    $(this).removeClass('like_btn');
    var post_id = $(this).attr('post_id'); //post_id="{{ $post->id }}"を取得
    var count = $('.like_counts' + post_id).text(); // <span class="like_counts{{ $post->id }}">の要素を取得
    var countInt = Number(count); //数値化
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // CSRFトークンを追加(419エラー防止)
      method: "post", //送信方式
      url: "/like/post/" + post_id, //データ送信先
      data: {
        post_id: $(this).attr('post_id'), //送信するデータ
      },
    }).done(function (res) {
      console.log(res);
      $('.like_counts' + post_id).text(countInt + 1);
    }).fail(function (res) {
      console.log('fail');
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    $(this).removeClass('un_like_btn');
    $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(countInt - 1);
    }).fail(function () {

    });
  });

  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
