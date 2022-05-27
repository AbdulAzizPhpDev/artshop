let time
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

updateStatus = (id, url) => {
    let status = $('#update_status_' + id).prop('checked')
    clearTimeout(time);
    time = setTimeout(function () {

        $.ajax({
            type: 'POST',
            url: '/ajax/' + url + '/update/status',
            data: {
                id: id,
                status: status
            },
            beforeSend: function () {
                $('#update_status_' + id).prop('disabled', true);
            },
            success: function (data) {
                $('#update_status_' + id).prop('disabled', false);
            }
        });
    }, 350);
}
updateProductStatus = (id, url, status) => {
    clearTimeout(time);
    time = setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: url + '/update/status',
            data: {
                id: id,
                status: status
            },
            beforeSend: function () {
                $('#update_status_' + id).prop('disabled', true);
            },
            success: function (data) {
                window.location.reload();
            }
        });
    }, 350);
}

deleteProduct = () => {

    var pathname = window.location.pathname;
    let url = '/' + pathname.split('/')[2];

    let id = $('#delete_product_id').val();
    clearTimeout(time);
    time = setTimeout(function () {
        $.ajax({
            type: 'post',
            url: '/ajax/product/delete',
            data: {
                id: id
            },
            beforeSend: function () {
                $('#modal_id').hide();
                $('#delete_product_id').val(null);
            },
            success: function (data) {
                window.location.href = $('#redirect_url').val();
            }
        });
    }, 350);
}


updateStatusUserAndSeller = (id, url) => {
    // let status = $('#update_status_' + id).prop('checked')
    clearTimeout(time);
    time = setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: '/ajax/member/user-seller/' + url,
            data: {
                id: id,
            },
            beforeSend: function () {
                $('#update_status_' + id).prop('disabled', true);
            },
            success: function (data) {
                if (!data.status) {
                    $('#update_status_' + id).prop('disabled', false);
                } else {
                    window.location.reload();
                }

            }
        });
    }, 350);
}

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
            $phone.val('+' + String.fromCharCode(e.keyCode) + '');
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
            $("#input_phone_id").val('')
        }
    });

$('#office-number')

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
            $phone.val('+' + String.fromCharCode(e.keyCode) + '');
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

            $("#input_office_id").val($(this).val().replace(new RegExp('-', 'g'), ""))


        } else {
            $("#input_office_id").val($(this).val().replace(new RegExp('-', 'g'), ""))

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


$('#stir_id')
    .keydown(function (e) {
        var key = e.which || e.charCode || e.keyCode || 0;
        $phone = $(this);
        return (key == 8 ||
            key == 9 ||
            key == 46 ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
    })
    .keyup(function () {
        if ($(this).val().length === 17) {
            $("#input_stir_id").val($(this).val())
        } else {
            $("#input_stir_id").val($(this).val())

        }
    });

$('#region_id').on('change', function (event) {
    let id = $(this).find('option:selected').val();
    if (isNaN(id)) {
        $('#district_id').prop('disabled', true);
        $('#district_id').html('')
        $('#district_id').append(new Option('Район', 'null'))
    } else {
        clearTimeout(time)
        time = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: '/ajax/districts',
                data: {
                    id: id,
                },

                success: function (obj) {
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

$('#catalog_id').on('change', function (event) {
    let id = $(this).find('option:selected').val();
    if (isNaN(id)) {
        $('#category_id').prop('disabled', true);
        $('#category_id').html('')
        $('#category_id').append(new Option('Субкатегория', 'null'))
    } else {
        clearTimeout(time)
        time = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: '/ajax/categories',
                data: {
                    id: id,
                },

                success: function (obj) {
                    $('#category_id').prop('disabled', false);
                    $('#category_id').html('')
                    $('#category_id').append(new Option('Субкатегория', 'null'))
                    $.each(obj.data, function (index, value) {
                        $('#category_id').append(new Option(value.name_uz, value.id))
                    })
                }
            });
        }, 500);
    }
})

removeProductToWishList = (id) => {

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


}












