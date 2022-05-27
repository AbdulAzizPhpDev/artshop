+
    /*

        GOOGLE CHARTS

     */
    $(document).ready(function() {


        // google.load("visualization", "1", {packages: ["corechart"]});
        // google.setOnLoadCallback(drawChart2);

        // function drawChart2() {
        //     var data = google.visualization.arrayToDataTable([
        //         ['Р“РѕРґ', 'РџСЂРѕРґР°Р¶Рё', 'РџСЂРѕСЃРјРѕС‚СЂС‹'],
        //         ['2013', 1000, 400],
        //         ['2014', 1170, 460],
        //         ['2015', 660, 1120],
        //         ['2016', 1030, 540]
        //     ]);
        //
        //     var options = {
        //         title: '',
        //         hAxis: {title: '', titleTextStyle: {color: '#333'}},
        //         vAxis: {minValue: 0}
        //     };
        //
        //     var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        //     chart.draw(data, options);
        // }

        // $(window).resize(function () {
        //     drawChart2();
        // });

        /*
            ! function(i) {
                var o, n;
                i(".title_block").on("click", function() {
                    o = i(this).parents(".accordion_item"), n = o.find(".info"),
                        o.hasClass("active_block") ? (o.removeClass("active_block"),
                            n.slideUp()) : (o.addClass("active_block"), n.stop(!0, !0).slideDown())
                })
            }(jQuery);

            */

        $('.view').click(function(event) {
            $('.category').removeClass('active')
            var num = $(this).attr('data-num');
            $('#subcategory'+num).addClass('active')
        });
        $('#back').click(function(event) {
            $('.subcategory').removeClass('active');
            $('.category').addClass('active');
        });
        $('.view_sub').click(function(event) {
            $('.subcategory').removeClass('active')
            var num = $(this).attr('data-num');
            $('#subcat_view'+num).addClass('active')
        });
        $('#back_sub').click(function(event) {
            $('.subcat_view').removeClass('active');
            $('.subcategory').addClass('active');
        });

        $('.buyer_view').click(function(event) {
            $('.buyers_list').removeClass('active')
            var num = $(this).attr('data-num');
            $('#buyer_info'+num).addClass('active')
        });
        $('.back_b_list').click(function(event) {
            $('.buyer_info').removeClass('active');
            $('.buyers_list').addClass('active');
        });

        $('.open_menu').click(function() {
            $('.open_menu').hide();
            $('.sidebar').removeClass('active');
            $('.unpin').addClass('active');
        });
        $('.unpin').click(function() {
            $('.sidebar').addClass('active');
            $('.unpin').removeClass('active');
            $('.open_menu').show();
        });


        $('.tabs-block').each(function() {
            let ths = $(this);
            ths.find('.tab-item').not(':first').hide();
            ths.find('.tab').click(function() {
                ths.find('.tab').removeClass('active').eq($(this).index()).addClass('active');
                ths.find('.tab-item').hide().eq($(this).index()).fadeIn()
            }).eq(0).addClass('active');
        });


        $(function () {
            var location = window.location.href;
            var cur_url = location.split('/').pop();

            $('.menu div').each(function () {
                var link = $(this).find('a').attr('href');

                if (cur_url == link) {
                    $(this).addClass('active');
                }
            });
        });
        $('#deliv_btn1').click(function () {
            var popup_id = $('#' + $(this).attr("rel"));
            $(popup_id).show();
            $('#deliv_terms1').show();
        })
        $('.close, .fade').click(function() {
            $('.popup').hide();
        })

        $(".radio_option").change(function() {

            if ($('#bank').prop("checked")) {
                $('.popup_pay_b').addClass('active');
            } else {
                $('.popup_pay_b').removeClass('active');
            }

        });

    });
