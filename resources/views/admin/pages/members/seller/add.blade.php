@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e addseller">
            <input type="hidden" value="0" id="check_info">
            <form action="{{route('admin.member.add.seller',['lang'=>app()->getLocale()])}}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" value="3">
                <input type="hidden" name="id" value="0">
                <div class="title">{{__('admin_users_seller_add')}}</div>

                {{--                LOGIN INFO--}}

                <div class="b5">
                    <div class="inp_titles">{{__('admin_users_seller_login_password_title')}}</div>
                    <div class="b6">
                        <div class="b7">

                            <div class="inp_label">
                                @error('user_name')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_login_title')}}
                                    @enderror
                            </div>
                            <input name="user_name" type="text" class="inp2" value="{{old('user_name')}}">

                        </div>

                        <div class="b7">

                            <div class="inp_label">
                                @error('password')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('user_login_password_label')}}
                                    @enderror
                            </div>
                            <input name="password" type="password" class="inp2">

                        </div>
                    </div>
                </div>

                {{--                MAIN INFO--}}

                <div class="b5">

                    <div class="inp_titles">
                        @error('main_info_error')
                        <spam style="color: #c40001">{{$message}}</spam>
                        @else
                            {{__('admin_users_seller_main_info')}}
                            @enderror
                    </div>

                    <div class="b6">
                        <div class="b7">

                            <div class="inp_label">
                                @error('name')<span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_company_name')}}
                                    @enderror
                            </div>
                            <input name="name" type="text" class="inp2" value="{{old('name')}}">

                            {{--                            <div class="inp_label">--}}
                            {{--                                @error('extra.ownership')--}}
                            {{--                                <span style="color: #c40001 ">{{$message}}</span>--}}
                            {{--                                @else--}}
                            {{--                                    Форма собственности--}}
                            {{--                                    @enderror--}}
                            {{--                            </div>--}}
                            {{--                            <input name="extra[ownership]" type="text" class="inp2" value="{{old('extra.ownership')}}">--}}

                        </div>

                        <div class="b7">

                            <div class="inp_label">
                                @error('office_number')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_office_phone')}}
                                    @enderror
                            </div>
                            <input type="text" class="inp2" maxlength="17"
                                   id="phone-number" placeholder="+998-xx-xxx-xx-xx">
                            <input type="hidden" name="office_number" id="input_phone_id">

                            {{--                            <div class="inp_label">@error('extra.stir')<span style="color: #c40001 ">{{$message}}</span>--}}
                            {{--                                @else--}}
                            {{--                                    ИНН@enderror--}}
                            {{--                            </div>--}}
                            {{--                            <input id="stir_id" type="text" maxlength="9" minlength="9"--}}
                            {{--                                   class="inp2" value="{{old('extra.stir')}}">--}}
                            {{--                            <input type="hidden" id="input_stir_id" name="extra[stir]" value="{{old('extra.stir')}}">--}}

                        </div>

                    </div>
                    <div class="inp_label">
                        @error('extra.description')
                        <span style="color: #c40001 ">{{$message}}</span>
                        @else
                            {{__('admin_users_seller_company_description')}}
                            @enderror</div>
                    <textarea name="extra[description]" class="inp_area" rows="6">{{old('extra.description')}}
                    </textarea>


                    {{-- IMAGES--}}


                    <div class="b6">
                        <div class="b7">

                            <div class="upload_title1">
                                @error('images.passport')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_image_passport')}}
                                    @enderror
                            </div>
                            <div id="passport_image_id"></div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>

                            <div class="upload_btn_block">
                                <div class="input__wrapper">
                                    <input onchange="putImage(event,'passport')" name="images[passport]" id="passport"
                                           type="file" class="input input__file">
                                    <label for="passport" class="input__file-button">{{__('upload')}}</label>
                                </div>
                            </div>

                            <div class="upload_title1">
                                @error('images.logo')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else {{__('admin_users_seller_image_logo')}} @enderror
                            </div>
                            <div id="logo_image_id"></div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>
                            <div class="upload_btn_block">
                                <div class="input__wrapper">
                                    <input onchange="putImage(event,'logo')" name="images[logo]" id="logo_id"
                                           type="file"
                                           class="input input__file">
                                    <label for="logo_id" class="input__file-button">{{__('upload')}}</label>
                                </div>
                            </div>

                        </div>

                        <div class="b7">

                            <div class="upload_title1">
                                @error('images.order')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_image_order')}}
                                    @enderror
                            </div>
                            <div id="order_image_id"></div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>
                            <div class="upload_btn_block">
                                <div class="input__wrapper">
                                    <input onchange="putImage(event,'order')" name="images[order]" id="order_id"
                                           type="file" class="input input__file">
                                    <label for="order_id" class="input__file-button">{{__('upload')}}</label>
                                </div>
                            </div>

                            <div class="upload_title1">
                                @error('images.license')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                {{__('admin_users_seller_image_license')}}
                                    @enderror
                            </div>
                            <div id="license_image_id"></div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>
                            <div class="upload_btn_block">
                                <div class="input__wrapper">
                                    <input onchange="putImage(event,'license')" name="images[license]" id="license_id"
                                           type="file" class="input input__file">
                                    <label for="license_id" class="input__file-button">{{__('upload')}}</label>
                                </div>
                            </div>

                        </div>
                    </div>




                    <div class="inp_titles">{{__('address')}}</div>
                    <div class="b6">
                        <div class="b7">
                            <div class="inp_label">{{__('admin_region_title')}}</div>
                            <select name="address[region]" class="inp2" id="region_id">
                                <option value="0">{{__('admin_region_creat_title')}}</option>
                                @if($regions && count($regions)>0)
                                    @foreach($regions as $region)
                                        <option value="{{$region->id}}">{{$region['name_'.app()->getLocale()]}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="inp_label">{{__('admin_users_seller_address_street_or_locality')}}</div>
                            <input name="address[street]" type="text" class="inp2">
                        </div>
                        <div class="b7">
                            <div class="inp_label">{{__('admin_regions_district')}}</div>
                            <select name="address[district]" class="inp2" id="district_id" disabled>
                                <option value="0">{{__('admin_regions_create_district_title')}}</option>

                            </select>

                            <div class="inp_label">{{__('admin_users_seller_address_house')}}</div>
                            <input name="address[house]" type="text" class="inp2">

                        </div>
                    </div>

                </div>
                <div class="btns_block" style="margin:0">
                    <a class="btn back_b_list"
                       href="{{route('admin.member.seller.index',['lang'=>app()->getLocale()])}}">{{__('cancel')}}</a>
                    <button type="submit" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('save')}}
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function putImage(event, type) {

            if ($('#check_info').val() != 0) {

                if ($('#' + type + '_tag_id input').length == 0) {
                    let image_url = $('#' + type + '_image_id').attr('src');
                    let html = ' <input type="hidden" value="' + image_url + '" name="delete[' + type + ']">'
                    $('#' + type + '_tag_id').append(html)
                    var image = document.getElementById(type + '_image_id');
                    image.src = URL.createObjectURL(event.target.files[0]);
                } else {
                    var image = document.getElementById(type + '_image_id');
                    image.src = URL.createObjectURL(event.target.files[0]);
                }

            } else {

                // console.log(URL.createObjectURL(event.target.files[0]))
                let html = '<img id="' + type + '_tag_id_1' + '" src="" alt="image" style="width: 300px;height: 300px">'

                $('#' + type + '_image_id').html('').append(html)
                var image = document.getElementById(type + '_tag_id_1');
                console.log(image)
                image.src = URL.createObjectURL(event.target.files[0]);
            }
        }

        $('#region_id').on('change', function (event) {

            console.log( "{{app()->getLocale()}}")
            let id = $(this).find('option:selected').val();
            if (isNaN(id)) {
                $('#district_id').prop('disabled', true);
                $('#district_id').html('')
                $('#district_id').append(new Option('{{__('user_profile_info_address_district_2')}}', 'null'))
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
                            $('#district_id').append(new Option('{{__('user_profile_info_address_district_2')}}', 'null'))
                            $.each(obj.data, function (index, value) {
                                $('#district_id').append(new Option(value['name_{{app()->getLocale()}}'], value.id))
                            })
                        }
                    });
                }, 500);
            }
        })
    </script>
@endsection
