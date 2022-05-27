/* Пункты меню */

$(function () {
    var location = window.location.href;
    var cur_url = '/' + location.split('/').pop();

    $('#menu_nav div').each(function () {
        var link = $(this).find('a').attr('href');

        if (cur_url == link) {
            $(this).addClass('active');
        }
    });
});

$('.phone').click(function () {
    var popup_id = $('#' + $(this).attr("rel"));
    let product_id = $(this).attr("id").split('_')[1]
    clearTimeout(t);
    t = setTimeout(function () {
        getContactInfo(product_id,popup_id)
    }, 300);
})
$('.btn_password_reset').click(function () {
    var popup_id = $('#' + $(this).attr("rel"));
    $(popup_id).show();
    $('#password_reset').show();
})
$('.reg_btn').click(function () {
    var popup_id = $('#' + $(this).attr("rel"));
    $(popup_id).show();
    $('#password').show();
})
$('.close, .fade').click(function () {
    $('.popup').hide();
})

function change(objName, min, max, step) {

    clearTimeout(t);
    t = setTimeout(function () {
        var obj = document.getElementById(objName);
        let arr = objName.split('_');

        var tmp = +obj.value + step;
        var check = +obj.value + step;
        if (tmp < min) tmp = min;
        if (tmp > max) tmp = max;
        obj.value = tmp;
        if (check != 0 && check > 0)
            changeQuantityProduct(arr[1], tmp, true)
    }, 350);

}


$(".regular").slick({
    dots: true,
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 4
});
// $(".vertical1").slick({
//     dots: true,
//     vertical: true,
//     slidesToShow: 1,
//     slidesToScroll: 1
// });
// $('.tabs-block').each(function() {
//     let ths = $(this);
//     ths.find('.tab-item').not(':first').hide();
//     ths.find('.tab').click(function() {
//         ths.find('.tab').removeClass('active').eq($(this).index()).addClass('active');
//         ths.find('.tab-item').hide().eq($(this).index()).fadeIn()
//     }).eq(0).addClass('active');
// });
// function change(objName, min, max, step) {
//     var obj = document.getElementById(objName);
//     var tmp = +obj.value + step;
//     if (tmp < min) tmp = min;
//     if (tmp > max) tmp = max;
//     obj.value = tmp;
// }

!function (i) {
    var o, n;
    i(".title_block").on("click", function () {
        o = i(this).parents(".accordion_item"), n = o.find(".info"),
            o.hasClass("active_block") ? (o.removeClass("active_block"),
                n.slideUp()) : (o.addClass("active_block"), n.stop(!0, !0).slideDown())
    })
}(jQuery);
