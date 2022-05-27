@extends('admin.layouts.admin-layout')
@section('content')
    <div class="main_info">
        <div class="add product_e addseller">
            <input type="hidden" value="{{$seller->extraInfo!==null?true:false}}" id="check_info">
            <form action="{{route('admin.member.add.seller',['lang'=>app()->getLocale()])}}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" value="3">
                <input type="hidden" name="id" value="{{$seller->id}}">
                <div class="title">{{__('admin_users_seller_edit')}}</div>
                {{--                <div class="table_title">О компании</div>--}}
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
                            <input name="user_name" maxlength="40" type="text" class="inp2"
                                   value="{{$seller->user_name}}">
                        </div>
                        <div class="b7">
                            <div class="inp_label">
                                @error('password')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('user_login_password_label')}}
                                    @enderror
                            </div>
                            <input name="password" type="password" class="inp2" maxlength="32">


                        </div>
                    </div>

                </div>
                {{--                     Extra information Extra information Extra information--}}
                <div class="b5">
                    <div class="inp_titles">{{__('admin_users_seller_main_info')}}</div>
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

                            {{--                            <div class="inp_label">--}}
                            {{--                                @error('extra.ownership')--}}
                            {{--                                <span style="color: #c40001 ">{{$message}}</span>--}}
                            {{--                                @else--}}
                            {{--                                    Форма собственности--}}
                            {{--                                    @enderror--}}
                            {{--                            </div>--}}
                            {{--                            <input name="extra[ownership]" type="text" class="inp2"--}}
                            {{--                                   value="{{ ($seller->extraInfo and$seller->extraInfo->ownership) ? $seller->extraInfo->ownership  :  old('extra.ownership')}}"--}}
                            {{--                                   maxlength="10">--}}
                        </div>
                        <div class="b7">
                            <div class="inp_label">{{__('admin_users_seller_office_phone')}}</div>
                            {{--                            @dd($new_office)--}}
                            <input type="text" class="inp2" maxlength="17" id="office-number"
                                   placeholder="+998-xx-xxx-xx-xx" name="extra[office_format]" value="{{$new_office}}">
                            <input type="hidden" name="extra[office_number]" id="input_office_id"
                                   value="{{ ($seller->extraInfo and$seller->extraInfo->office_number) ? $seller->extraInfo->office_number  :  old('extra.office_number')}}">

                            {{--                            <div class="inp_label">--}}
                            {{--                                @error('extra.stir')--}}
                            {{--                                <span style="color: #c40001 ">{{$message}}</span>--}}
                            {{--                                @else--}}
                            {{--                                    ИНН--}}
                            {{--                                    @enderror--}}
                            {{--                            </div>--}}
                            {{--                            <input id="stir_id" type="text" maxlength="9" minlength="9" class="inp2"--}}
                            {{--                                   value="{{ ($seller->extraInfo and $seller->extraInfo->stir) ? $seller->extraInfo->stir  :  old('extra.stir')}}">--}}
                            {{--                            <input type="hidden" id="input_stir_id" name="extra[stir]"--}}
                            {{--                                   value="{{ ($seller->extraInfo and $seller->extraInfo->stir) ? $seller->extraInfo->stir  :  old('extra.stir')}}">--}}
                        </div>
                    </div>
                    <div class="inp_label">{{__('admin_users_seller_company_description')}}</div>
                    <textarea name="extra[description]" class="inp_area" rows="6">
                        {{ ($seller->extraInfo and $seller->extraInfo->description) ? $seller->extraInfo->description  :  old('extra.description')}}
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
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_passport!=null )
                                    <a id="passport_delete"
                                       href="{{ $seller->extraInfo!=null ?  Storage::url($seller->extraInfo->image_passport) : ''}}"
                                       download
                                       class="trashimg"
                                       style="text-decoration: none;background: url({{asset('/assets/img/admin/download.png')}}) #FFF 90% 50% no-repeat; background-size: 25px 25px;">
                                        {{__('download')}}
                                    </a>
                                @endif
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
                                    <img id="logo_image_id" src="{{ Storage::url($seller->extraInfo->image_logo) }}"
                                         alt="logo"
                                         style="width: 300px;height: 300px">
                                @endif
                            </div>
                            <div class="upload_title2">({{__('admin_users_seller_image_format')}})</div>
                            <div class="upload_btn_block">
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_logo!=null )
                                    <a id="logo_delete"
                                       href="{{$seller->extraInfo!=null ?Storage::url($seller->extraInfo->image_logo): ""}}"
                                       download
                                       class="trashimg"
                                       style="text-decoration: none;background: url({{asset('/assets/img/admin/download.png')}}) #FFF 90% 50% no-repeat; background-size: 25px 25px;">
                                        {{__('download')}}
                                    </a>
                                @endif
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
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_order!=null )
                                    <a id="order_delete"
                                       href="{{$seller->extraInfo!=null ? Storage::url($seller->extraInfo->image_order): ""}}"
                                       download
                                       class="trashimg"
                                       style="text-decoration: none;background: url({{asset('/assets/img/admin/download.png')}}) #FFF 90% 50% no-repeat; background-size: 25px 25px;">
                                        {{__('download')}}
                                    </a>
                                @endif
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
                                @if($seller->extraInfo!=null && $seller->extraInfo->image_license!=null )
                                    <a id="license_delete"
                                       href="{{$seller->extraInfo!=null ?Storage::url($seller->extraInfo->image_license): ""}}"
                                       download class="trashimg"
                                       style="text-decoration: none;background: url({{asset('/assets/img/admin/download.png')}}) #FFF 90% 50% no-repeat; background-size: 25px 25px;">
                                        {{__('download')}}
                                    </a>
                                @endif
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
                                            value="{{$region->id}}" {{$seller->address!=null &&  $region->id==$seller->address->region_id ? "selected" :" "}}>{{$region->name_uz}}</option>
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
                                <option value="null">{{__('admin_regions_district')}}</option>
                                @if($districts && count($districts)>0)
                                    @foreach($districts as $district)
                                        <option
                                            value="{{$district->id}}" {{$seller->address!=null &&  $district->id==$seller->address->district_id ? "selected" :" "}}>{{$district->name_uz}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="inp_label">
                                @error('address.house')
                                <span style="color: #c40001 ">{{$message}}</span>
                                @else
                                    {{__('admin_users_seller_address_house')}}
                                    @enderror
                            </div>
                            <input name="address[house]" type="text" class="inp2" maxlength="32"
                                   value="{{$seller->address!=null ? $seller->address->house : ""}}">

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
            if ($('#check_info').val()) {

                if ($('#' + type + '_tag_id input').length == 0) {
                    let image_url = $('#' + type + '_image_id').attr('src');
                    let html = ' <input type="hidden" value="' + image_url + '" name="delete[' + type + ']">'
                    $('#' + type + '_tag_id').append(html)
                    var image = document.getElementById(type + '_image_id');
                    image.src = URL.createObjectURL(event.target.files[0]);
                    $('#' + type + '_delete').removeAttr('href');
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
    </script>
@endsection
