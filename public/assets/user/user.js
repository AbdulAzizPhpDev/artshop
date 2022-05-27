var t;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#phone-number')

    .keydown(function (e) {

        var key = e.which || e.charCode || e.keyCode || 0;
        $phone = $(this);


        // Don't let them remove the starting '('
        if ($phone.val().length === 1 && (key === 8 || key === 46)) {
            $phone.val('+');
            return false;
        }
        // Reset if they highlight and type over first char.
        else if ($phone.val().charAt(0) !== '+') {
            $phone.val('+');
        }


        // Auto-format- do not expose the mask as the user begins to type
        if (key !== 8 && key !== 9) {
            if ($phone.val().length === 4) {
                $phone.val($phone.val() + '-');
            }
            if ($phone.val().length === 7) {
                $phone.val($phone.val() + '-');
            }
            if ($phone.val().length === 11) {
                $phone.val($phone.val() + '-');
            }
            if ($phone.val().length === 14) {
                $phone.val($phone.val() + '-');
            }
        }


        // Allow numeric (and tab, backspace, delete) keys only
        return (key == 8 ||
            key == 9 ||
            key == 46 ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
    })

    .keyup(function () {

        if ($(this).val().length === 17) {

            $("#input_phone_id").val($(this).val().replace(new RegExp('-', 'g'), ""))


        } else {
            $("#input_phone_id").val($(this).val().replace(new RegExp('-', 'g'), ""))

        }
    })

    .bind('focus click', function () {
        $phone = $(this);

        if ($phone.val().length === 0) {
            $phone.val('+998');
        } else {
            var val = $phone.val();
            $phone.val('').val(val); // Ensure cursor remains at the end
        }
    })

    .blur(function () {
        $phone = $(this);

        if ($phone.val() === '+998') {
            $phone.val('');
        }
        if ($phone.val().length <= 16) {
            $phone.val('');
            $("#input_office_id").val('')
        }
    });

$('#dropdown_id').hover(function () {
    $('#sub_dropdown_id').css("display", "block");
})
    .mouseleave(function () {
        $('#sub_dropdown_id').css("display", "none");
    })


addProductToWishList = (id) => {
    clearTimeout(t);
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    $('#wish_list_svg_id').toggleClass("unclickable");
    if ($('#auth_check_id').val()) {
        t = setTimeout(function () {
            $.ajax({
                type: 'post',
                url: '/ajax/wish-list-add',
                data: {
                    wish_list_id: id
                },
                success: function (obj) {

                    if (obj.status != false) {
                        $('#wish_list_' + id).css("background-image", "url('/assets/img/icons/heart.png')");
                        Toast.fire({
                            icon: 'success',
                            title: ' продукт успешно добавлен в список желаний'
                        })

                    } else {
                        $('#wish_list_' + id).removeAttr("style");
                        Toast.fire({
                            icon: 'success',
                            title: 'продукт успешно удален из списка желаний'
                        })

                    }

                }
            });
        }, 350);
    } else {
        window.location = $('#login_id').val()
    }

}

removeProductToWishList = (id) => {
    if ($('#auth_check_id').val()) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        t = setTimeout(function () {
            $.ajax({
                type: 'post',
                url: '/ajax/wish-list-remove',
                data: {
                    wish_list_id: id
                },
                success: function (obj) {
                    Toast.fire({
                        icon: 'success',
                        title: 'продукт успешно удален из списка желаний'
                    })
                    $('#wish_list_' + id).remove()
                }
            });
        }, 350);
    } else {
        window.location = "/login"
    }

}

$('#region_id').on('change', function (event) {
    let id = $(this).find('option:selected').val();
    if (isNaN(id)) {
        $('#district_id').prop('disabled', true);
        $('#district_id').html('')
        $('#district_id').append(new Option('Район', 'null'))
    }
    else {
        clearTimeout(t)
        t = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: '/ajax/districts',
                data: {
                    id: id,
                    seller_id: $('#seller_id').val()
                },

                success: function (obj) {

                    if ($('#payment_amount').length > 0) {
                        if (obj.price != null) {
                            if (obj.price.price == 0) {
                                $('#payment_status').html('Бесплатно')
                            } else {
                                $('#payment_status').html('Платная')
                            }
                            $('#payment_amount').html(obj.price.price)
                            $('#total_price').html(parseInt(obj.price.price) + parseInt($('#cart_price').val()))
                        }


                    }
                    $('#district_id').prop('disabled', false);
                    $('#district_id').html('')
                    $('#district_id').append(new Option('Район', 'null'))
                    $.each(obj.data, function (index, value) {
                        $('#district_id').append(new Option(value.name_uz, value.id))
                    })
                }
            });
        }, 500);
    }
})

function dateConvert(time) {
    var date = new Date(time)
    var day = date.getDate();
    var month = date.getMonth();
    var year = date.getFullYear();
    return day + '.' + month + '.' + year;
}

addReviewToProduct = (id) => {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    let text_length = $('#commit_id').val();
    if (text_length.trim().length > 12) {
        if ($('#auth_check_id')) {
            clearTimeout(t);
            t = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/add-review',
                    data: {
                        id: id,
                        review: $('#commit_id').val(),
                        star: $('#star_id').val()
                    },
                    success: function (obj) {
                        if (obj.status == 'success') {
                            Toast.fire({
                                icon: 'success',
                                title: 'отзыв добавлены успешно'
                            })
                            let html = '<div class="review">\n' +
                                '                                        <div class="reviewer_name">' + obj.user_name + '</div>\n' +
                                '                                        <div class="review_text">' + obj.rate.comment + '</div>\n' +
                                '                                        <div\n' +
                                '                                            class="date">' + dateConvert(obj.rate.created_at) + '</div>\n' +
                                '                                    </div>'
                            $('#review_content').append(html)
                            $('#commit_id').val(' ')
                            $('#star_id').val(5)
                            $('#rating_5').prop('checked', true)
                        } else if (obj.status == 'exist') {
                            Toast.fire({
                                icon: 'warning',
                                title: 'ты уже оставил отзыв'
                            })

                            $('#commit_id').val(' ')
                            $('#star_id').val(5)
                            $('#rating_5').prop('checked', true)


                        }
                    }
                });
            }, 500);
        }
    } else {

        if (text_length.trim().length == 0) {
            Toast.fire({
                icon: 'warning',
                title: 'поле должно быть заполнено'
            })
        } else if (text_length.trim().length < 12) {
            Toast.fire({
                icon: 'warning',
                title: 'добавить более 12 символов'
            })
        }

        $('#commit_id').focus();
    }
}

getContactInfo = (product_id, popup_id) => {
    $(popup_id).show();
    $('#contacts').show();
    $.ajax({
        type: 'POST',
        url: '/ajax/get-contact-info',
        data: {
            product_id: product_id
        },
        success: function (obj) {
            $('#contact_title').html(obj.name);
            $('#contact_phone').html(obj.phone);
            $('#contact_mail').html(obj.email);
            $(popup_id).show();
            $('#contacts').show();
        }
    });
}

number_format = (number, decimals, dec_point, thousands_sep) => {

    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

