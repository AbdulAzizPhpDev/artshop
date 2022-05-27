$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function videoCount(id) {
    var video = $('#video_' + id);
    var videoElement = video.get(0);
    if (!videoElement.paused) {
        t = setTimeout(function () {
            $.ajax({
                type: 'post',
                url: '/ajax/count',
                data: {
                    video_id: id
                },
                success: function (obj) {
                    if (obj.status == true) {
                        $('#infovideo_view_' + obj.id).html(obj.count)
                    }
                }
            });
        }, 500);
    }
}

function videoLike(id, status) {

    if ($('#auth_check_id').val() == 1) {
        t = setTimeout(function () {
            $.ajax({
                type: 'post',
                url: '/ajax/like',
                data: {
                    video_id: id,
                    status: status
                },
                success: function (obj) {
                    if (obj.status == 'login') {
                        window.location = "/login"
                    } else if (obj.status == 'new') {
                        $('#like_' + obj.id).html(obj.like)
                        $('#dislike_' + obj.id).html(obj.dislike)

                    } else if (obj.status == 'update') {
                        $('#like_' + obj.id).html(obj.like)
                        $('#dislike_' + obj.id).html(obj.dislike)

                    }

                }
            });
        }, 500);
    }else{

    window.location = "/login"
    }
}
