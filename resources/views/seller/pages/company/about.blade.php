@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e addseller">
            <input type="hidden" value="{{$seller->extraInfo!==null?true:false}}" id="check_info">
            <form action="{{route('seller.company.store.info',['lang'=>app()->getLocale()])}}" method="post"
                  autocomplete="off"
                  enctype="multipart/form-data">
                @csrf
                <div class="title">{{__('company')}}</div>
                <div class="table_title">{{__('about_company')}}</div>
                @error('alard')
                <div class="table_title" style="color: #ff8100cc;">{{ucfirst($message)}}</div>
                @enderror
                <div class="b5">
                    <div class="inp_titles">{{__('admin_users_seller_login_password_title')}}</div>

                    <div class="b6">
                        <div class="b7">
                            <div class="inp_label">
                                @error('phone_number')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('phone')}}
                                    @enderror
                            </div>

                            <input type="text" class="inp2" maxlength="17" id="phone-number"
                                   placeholder="+998-xx-xxx-xx-xx" value="{{$new_phone}}" name="phone_format">
                            <input type="hidden" name="phone_number" id="input_phone_id"
                                   value="{{$seller->phone_number}}">

                            <div class="inp_label">
                                @error('password')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('user_login_password_label')}}
                                    @enderror
                            </div>
                            <input name="password" type="password" class="inp2" maxlength="32">
                        </div>
                        <div class="b7">
                            <div class="inp_label">
                                @error('user_name')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_login_title')}}
                                    @enderror
                            </div>
                            <input name="user_name" maxlength="40" type="text" class="inp2"
                                   value="{{$seller->user_name}}">


                        </div>
                    </div>

                </div>
                {{--                     Extra information Extra information Extra information--}}
                <div class="b5">
                    <div class="inp_titles"> {{__('admin_users_seller_main_info')}}</div>
                    <div class="b6">
                        <div class="b7">
                            <div class="inp_label">
                                @error('name')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_company_name')}}
                                    @enderror

                            </div>
                            <input name="name" type="text" class="inp2" value="{{$seller->name}}">

                        </div>
                        <div class="b7">
                            <div class="inp_label">{{__('admin_users_seller_office_phone')}}</div>
                            {{--                            @dd($new_office)--}}
                            <input type="text" class="inp2" maxlength="17" id="office-number"
                                   placeholder="+998-xx-xxx-xx-xx" name="extra[office_format]" value="{{$new_office}}">
                            <input type="hidden" name="extra[office_number]" id="input_office_id"
                                   value="{{ ($seller->extraInfo and$seller->extraInfo->office_number) ? $seller->extraInfo->office_number  :  old('extra.office_number')}}">

                        </div>
                    </div>
                    <div class="inp_label">

                        @error('extra.description')
                        <span style="color: #c40001 ">{{$message}}</span>
                        @else
                            {{__('admin_users_seller_company_description')}}
                            @enderror

                    </div>
                    <textarea style="width: 97%;padding:15px!important;" name="extra[description]" class="inp_area"
                              rows="6">{{ ($seller->extraInfo and $seller->extraInfo->description) ? $seller->extraInfo->description  :  old('extra.description')}}
                    </textarea>
                    <div class="b6">
                        <div class="b7">
                            {{--                            passportpassportpassportpassportpassportpassportpassportpassportpassport--}}
                            <div class="upload_title1">
                                @error('images.passport')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_image_passport')}}
                                    @enderror

                            </div>
                            <div id="passport_tag_id">
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_passport!=null )
                                    <img id="passport_image_id"
                                         src="{{Storage::url($seller->extraInfo->image_passport)}}"
                                         alt="passport"
                                         style="width: 300px;height: 300px">
                                @endif
                            </div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>

                            <div class="upload_btn_block">

                                <div class="input__wrapper">
                                    <input onchange="putImage(event,'passport')" name="images[passport]" id="passport"
                                           type="file" class="input input__file">
                                    <label for="passport" class="input__file-button">{{__('upload')}}</label>
                                </div>
                            </div>
                            {{--                            logologologologologologologologologologologo--}}
                            <div class="upload_title1">
                                @error('images.logo')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_image_logo')}}
                                    @enderror
                            </div>
                            <div id="logo_tag_id">
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_logo!=null )
                                    <img id="logo_image_id" src="{{Storage::url($seller->extraInfo->image_logo)}}"
                                         alt="logo"
                                         style="width: 300px;height: 300px">
                                @endif
                            </div>
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
                            {{--                            orderorderorderorderorderorderorderorderorder--}}
                            <div class="upload_title1">
                                @error('images.order')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_image_order')}}
                                    @enderror
                            </div>
                            <div id="order_tag_id">
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_order!=null )
                                    <img id="order_image_id" src="{{Storage::url($seller->extraInfo->image_order)}}"
                                         alt="order"
                                         style="width: 300px;height: 300px">
                                @endif
                            </div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>
                            <div class="upload_btn_block">

                                <div class="input__wrapper">
                                    <input onchange="putImage(event,'order')" name="images[order]" id="order_id"
                                           type="file"
                                           class="input input__file">
                                    <label for="order_id" class="input__file-button">{{__('upload')}}</label>
                                </div>
                            </div>
                            {{--                            licenselicenselicenselicenselicenselicenselicenselicense--}}
                            <div class="upload_title1">
                                @error('images.license')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_image_license')}}
                                    @enderror

                            </div>
                            <div id="license_tag_id">
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_license!=null )
                                    <img id="license_image_id" src="{{Storage::url($seller->extraInfo->image_license)}}"
                                         alt="license"
                                         style="width: 300px;height: 300px">
                                @endif
                            </div>
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

                    {{--                    Address Address Address AddressAddress Address AddressAddress--}}

                    <div class="inp_titles">{{__('address')}}</div>
                    <div class="b6">
                        <div class="b7">
                            <div class="inp_label">
                                @error('address.region')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_region_title')}}
                                    @enderror
                            </div>
                            <select name="address[region]" class="inp2" id="region_id">
                                <option value="null">{{__('admin_region_creat_title')}}</option>
                                @if($regions && count($regions)>0)
                                    @foreach($regions as $region)
                                        <option
                                                value="{{$region->id}}" {{$seller->address!=null &&  $region->id==$seller->address->region_id ? "selected" :" "}}>{{$region['name_'.app()->getLocale()]}}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="inp_label">
                                @error('address.street')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_address_street_or_locality')}}
                                    @enderror

                            </div>
                            <input name="address[street]" type="text" class="inp2" maxlength="64"
                                   value="{{$seller->address!=null ? $seller->address->street :""}}">


                            <div class="inp_label">
                                @error('address.x_coordinate')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('x_coordinate')}}
                                    @enderror
                            </div>
                            <input name="address[x_coordinate]" type="text" class="inp2" maxlength="32"
                                   value="{{$seller->address!=null ? $seller->address->x_coordinate : ""}}">
                        </div>
                        <div class="b7">
                            <div class="inp_label">
                                @error('address.district')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_regions_district')}}
                                    @enderror
                            </div>
                            <select name="address[district]" class="inp2"
                                    id="district_id" {{$districts!=null ? " " : "disabled"}}>
                                <option value="null">{{__('admin_regions_create_district_title')}}</option>
                                @if($districts && count($districts)>0)
                                    @foreach($districts as $district)
                                        <option
                                                value="{{$district->id}}" {{$seller->address!=null &&  $district->id==$seller->address->district_id ? "selected" :" "}}>{{$district['name_'.app()->getLocale()]}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="inp_label">
                                @error('address.house')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_address_house_1')}}
                                    @enderror
                            </div>
                            <input name="address[house]" type="text" class="inp2" maxlength="32"
                                   value="{{$seller->address!=null ? $seller->address->house : ""}}">

                            <div class="inp_label">
                                @error('address.y_coordinate')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('y_coordinate')}}
                                    @enderror
                            </div>
                            <input name="address[y_coordinate]" type="text" class="inp2" maxlength="32"
                                   value="{{$seller->address!=null ? $seller->address->y_coordinate : ""}}">


                        </div>
                    </div>
                </div>

                <button type="submit" class="btn save" style="display: block!important;">{{__('save')}}</button>
            </form>
            @error('success')

            <input type="hidden" id="check_success_1" value="1">
            @enderror

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            popUp()
        });

        function popUp() {
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
            if ($('#check_success_1').val() == 1) {
                Toast.fire({
                    icon: 'success',
                    title: '{{__('data_success')}}'
                })
            }
        }

        function putImage(event, type) {
            if ($('#check_info').val()) {
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
                let html = '<img id="' + type + '_tag_id_1' + '" src="/" alt="image" style="width: 300px;height: 300px">'
                $('#' + type + '_tag_id').html('').append(html)
                var image = document.getElementById(type + '_tag_id_1');
                image.src = URL.createObjectURL(event.target.files[0]);
            }
        }

        $('#region_id').on('change', function (event) {
            let id = $(this).find('option:selected').val();
            if (isNaN(id)) {
                $('#district_id').prop('disabled', true);
                $('#district_id').html('')
                $('#district_id').append(new Option('{{__('admin_regions_create_districts_title')}}', 'null'))
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
                            $('#district_id').append(new Option('{{__('admin_regions_create_districts_title')}}', 'null'))
                            $.each(obj.data, function (index, value) {
                                $('#district_id').append(new Option(value.name_{{app()->getLocale()}}, value.id))
                            })
                        }
                    });
                }, 500);
            }
        })
    </script>
@endsection
