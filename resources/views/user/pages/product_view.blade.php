@extends('user.layouts.page-layout')
@section('my_style')
    <style>
        textarea {
            width: 95%;
            font-size: 20px;
            padding: 20px;
            box-shadow: 0 0 22px 0 rgb(0 0 0 / 16%);
            margin: 20px 0 !important;
            border: none !important;

        }

        textarea:focus {
            outline: none !important;
            border: #262F91 1px solid !important;
            box-shadow: 0 0 22px 0 rgb(38 47 145 / 35%);


        }

        .rating_block {
            margin: 20px 0 !important;
        }


    </style>
@endsection
@section('content')

    <div class="product_view">
        <div class="content">
            <div class="navi">
                <a href="/" class="navi_point">{{__('main')}}</a>
                <a href="" class="navi_point">{{$product->category['name_'.app()->getlocale()]}}</a>
                <a class="navi_point">{{$product['name_'.app()->getlocale()]}}</a>
            </div>
            <div class="b1">
                <div class="b2">
                    <img src="{{Storage::url($product->image)}}" alt="image">
                    @if(auth()->check())
                        @if(auth()->user()->role_id==2)
                            <div style="width: 200px!important;"
                                 onclick="addToCart({{$product->id}},{{$product->min}},{{$product->quantity}})"
                                 class="btn">{{__('product_cart')}}</div>
                        @endif
                    @else
                        <div style="width: 200px!important;"
                             onclick="addToCart({{$product->id}},{{$product->min}},{{$product->quantity}})"
                             class="btn">{{__('product_cart')}}</div>
                    @endif

                </div>
                <div class="b2">
                    <div class="t1">
                        {{$product['name_'.app()->getlocale()]}}
                    </div>
                    <div class="rating r{{$average}}"></div>
                    <div class="clear"></div>
                    <div class="amount">
                        <input type="button" class="left_button" value="-" onClick="change('amount',{{$product->min}},{{$product->quantity}},-1);"/>
                        <input disabled class="count_field" id="amount" type="text" value="1"/>
                        <input type="button" class="right_button" value="+" onClick="change('amount',{{$product->min}},{{$product->quantity}}, 1);"/>

                        <input type="hidden" value="0" id="product_quantity">
                    </div>
                    <div class="price">{{__('price')}}
                        :<span> {{number_format($product->price, null, null, ' ')}} {{__('sum')}}</span></div>
                    @if(auth()->check())
                        @if(auth()->user()->role_id==2)

                          @if($product->quantity<=0)
                            <a style="display: block"
                               href="#"
                                id="buy_error_id"
                               class="buy">{{__('product_buy')}}</a>
                               @else
                                <a style="display: block"
                               href="{{route('user.buy_checkout',['lang'=>app()->getLocale(),'product_id'=>$product->id])}}"
                               class="buy">{{__('product_buy')}}</a>

                           @endif
                        @endif
                    @else

                    @if($product->quantity<=0)
                            <a style="display: block"
                               href="#"
                               id="buy_error_id"
                               class="buy">{{__('product_buy')}}</a>
                               @else
                                <a style="display: block"
                               href="{{route('user.buy_checkout',['lang'=>app()->getLocale(),'product_id'=>$product->id])}}"
                               class="buy">{{__('product_buy')}}</a>

                           @endif
                       <!--  <a style="display: block"
                           href="{{route('user.buy_checkout',['lang'=>app()->getLocale(),'product_id'=>$product->id])}}"
                           class="buy">{{__('product_buy')}}</a> -->
                    @endif

                </div>
                <div class="b2">
                    <div class="t2">{{__('seller')}}</div>
                    <div class="t3">
                        “{{ucfirst($product->merchant->requisite!=null ?$product->merchant->requisite->official_name :  $product->merchant->name)}}
                        ”
                    </div>
                    @if ($product->merchant->address!=null)

                        <div class="seller_info seller_info_geo">
                            {{$product->merchant->address->region['name_'.app()->getlocale()]}} <br>
                            {{$product->merchant->address->a_district['name_'.app()->getlocale()]}} <br>
                            {{$product->merchant->address->street}} <br>
                            {{$product->merchant->address->house}}
                        </div>
                    @endif

                    <div class="seller_info seller_info_web">artshop.uz</div>
                    <div
                            class="seller_info seller_info_phone">{{$product->merchant->extraInfo!=null ?$product->merchant->extraInfo->office_number :  $product->merchant->phone_number}}</div>
                    <div class="seller_info seller_info_mail">{{__('product_write_mess')}}</div>
                </div>
            </div>
            <div class="tabs-block">
                <div class="tabs">
                    <div class="tab">{{__('comment')}}</div>
                    <div class="tab">{{__('delivery_p_r')}}</div>
                    <div class="tab">{{__('payment_way')}}</div>
                    <div class="tab">{{__('product_review')}}</div>
                </div>
                <div class="tabs-content">
                    <div class="tab-item">
                        <div class="description_text">
                            {{ucfirst($product['description_'.app()->getlocale()])}}
                        </div>
                        <div class="tab_desription_content">
                            <div class="description_meta">
                                <div class="description_meta_content">
                                    <div style="margin-bottom: 80px;" class="t4">{{__('seller')}}:</div>
                                    <div style="margin-bottom: 80px;" class="t4">{{__('product_catalog')}}:</div>
                                    {{--                                    <div class="t4">{{__('made_in')}}:</div>--}}
                                </div>
                                <div class="description_meta_content">
                                    <div class="t5">
                                        "{{ucfirst($product->merchant->requisite!=null ?$product->merchant->requisite->official_name :  $product->merchant->name)}}
                                        "
                                    </div>
                                    <div class="t5">{{ucfirst($product->category['name_'.app()->getlocale()])}}</div>
                                    {{--                                    <div class="t5">{{ucfirst($product->made_in)}}</div>--}}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-item">
                        @if ($product->merchant->address!=null)
                            <div class="ico_text cond_delivery">{{__('delivery_type_2')}}</div>
                        @endif
                        <div class="ico_text pickup_delivery">{{__('delivery_type_1')}}</div>
                    </div>
                    <div class="tab-item">
                        @if ($product->merchant->paymentSystem && $product->merchant->paymentSystem->is_cash)
                            <div class="ico_text nal">{{__('payment_way_cash')}}</div>
                        @endif

                        @if ($product->merchant->paymentSystem && $product->merchant->paymentSystem->is_active)
                            <div style="background: url(/assets/img/icons/click.png) 0px 50% no-repeat;!important;"
                                 class="ico_text payme">{{__('payment_way_online')}}</div>
                        @endif

                        @if ($product->merchant->requisite!=null)
                            <div class="ico_text bank">{{__('payment_way_bank')}}</div>
                        @endif

                    </div>
                    <div class="tab-item">
                        <div class="b3" style="display: block">
                            @auth()
                                @if(Auth::check() && Auth::user()->role_id == 2)
                                    <div class="b3" style="display: block">
                                        <div class="product_review_title">{{$product['name_'.app()->getlocale()]}}</div>
                                        <div class="rating_block">
                                            <input name="rating" value="5" id="rating_5" checked type="radio"/>
                                            <label for="rating_5" class="label_rating"></label>

                                            <input name="rating" value="4" id="rating_4" type="radio"/>
                                            <label for="rating_4" class="label_rating"></label>

                                            <input name="rating" value="3" id="rating_3" type="radio"/>
                                            <label for="rating_3" class="label_rating"></label>

                                            <input name="rating" value="2" id="rating_2" type="radio"/>
                                            <label for="rating_2" class="label_rating"></label>

                                            <input name="rating" value="1" id="rating_1" type="radio"/>
                                            <label for="rating_1" class="label_rating"></label>
                                        </div>

                                        <input type="hidden" value="5" id="star_id">
                                        <input type="hidden" value="{{$product->merchant_id}}" id="seller_id">
                                        <textarea name="commit" id="commit_id" rows="6"></textarea>
                                        <div class="send_review" onclick="addReviewToProduct({{$product->id}})"
                                             style="margin: 20px 0;">
                                            {{__('product_leave_review')}}
                                        </div>

                                    </div>
                                @endif
                            @else
                                <div class="description_text">
                                    <a href="{{route('user.login',app()->getLocale())}}">
                                        {{__('product_login')}}
                                    </a>
                                </div>

                            @endauth
                        </div>

                        <div class="review_block" style="width: 99%!important;" id="review_content">
                            @if(count($product->reviews)>0)
                                @foreach($product->reviews as $commit)
                                    <div class="review">
                                        <div class="reviewer_name">{{$commit->user->name}}</div>
                                        <div class="review_text">{{$commit->comment}}</div>
                                        <div
                                                class="date">{{Carbon\Carbon::parse($commit->created_at)->format('d.m.Y')}}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('my_script')
    <script>
        $('.tabs-block').each(function () {
            let ths = $(this);
            ths.find('.tab-item').not(':first').hide();
            ths.find('.tab').click(function () {
                ths.find('.tab').removeClass('active').eq($(this).index()).addClass('active');
                ths.find('.tab-item').hide().eq($(this).index()).fadeIn()
            }).eq(0).addClass('active');
        });

        function change(objName, min, max, step) {
            var obj = document.getElementById(objName);
            var tmp = +obj.value + step;
            if (tmp < min) tmp = min;
            if (tmp > max) tmp = max;
            obj.value = tmp;
            $('#product_quantity').val(tmp)
            clearTimeout(t);
            t = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/add/quantity',
                    data: {
                        val: tmp,
                    },
                    success: function (obj) {


                    }
                });
            }, 500);
        }

        $('[type*="radio"]').change(function () {
            var me = $(this);
            $('#star_id').val(me.attr('value'))
        });


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
                                star: $('#star_id').val(),
                                seller_id: $('#seller_id').val()
                            },
                            success: function (obj) {
                                if (obj.status == 'success') {
                                    Toast.fire({
                                        icon: 'success',
                                        title: '{{__('review_add')}}'
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
                                        title: '{{__('review_exist')}}'
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
                        title: '{{__('review_rule_2')}}'
                    })
                } else if (text_length.trim().length < 12) {
                    Toast.fire({
                        icon: 'warning',
                        title: '{{__('review_rule')}}'
                    })
                }

                $('#commit_id').focus();
            }
        }

        $('#buy_error_id').on('click',function () {
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

            Toast.fire({
                                        icon: 'warning',
                                        title: '{{__('product_not')}}'
                                    })


        })
    </script>
@endsection


