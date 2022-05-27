@extends('admin.layouts.user-layout')
@section('style')
    <style>
        .inp2 {
            height: 37px !important;
        }
    </style>
@endsection
@section('content')
    <div class="main_info">
        <div class="fields">
            <div class="title">{{__('user_profile_title')}}</div>
            <form action="{{route('user.profile.information.store',['lang'=>app()->getLocale()])}}" method="post">
                @csrf
                <div class="b1">
                    <div class="b5">
                        <div class="t1">{{__('user_profile_info_title')}}</div>
                        <div class="b6">
                            <div class="b7">
                                <div class="t3">
                                    @error('name')
                                    <span style="color: red">{{$message}}</span>
                                    @else
                                        {{__('user_register_user_name_label')}}
                                        @enderror
                                </div>
                                <input type="text" class="inp inp2" value="{{$user->name}}" name="name">


                            </div>
                            <div class="b7">
                                <div class="t3">{{__('user_profile_info_birth_day')}}</div>

                                <input type="date" class="inp inp2" name="birth_day"
                                       value="{{$user->birth_day!=null ? $user->birth_day  : ""}}">
                                {{--                                       value="{{$user->birth_day!=null ? \Carbon\Carbon::parse($user->birth_day)->format('m/d/Y')  : ""}}">--}}


                            </div>
                        </div>
                        <div class="t1">{{__('user_profile_info_sex')}}</div>
                        <div class="b6">

                            <div class="b7">
                                <label for="male" class="t3">{{__('user_profile_info_male')}}</label>
                                <input {{$user->gender ? "checked" : ""}}  type="radio" id="male" value="male"
                                       name="gender"
                                       style="width: 20px;height: 20px" class=" check">
                            </div>
                            <div class="b7">
                                <label for="female" class="t3">{{__('user_profile_info_female')}}</label>
                                <input {{!$user->gender ? "checked" : ""}} type="radio" id="female" value="female"
                                       name="gender"
                                       style="width: 20px;height: 20px" class=" check">
                            </div>

                        </div>
                    </div>

                    <div class="b5">
                        <div class="t1">{{__('user_profile_info_address_title')}}</div>
                        <div class="b6">
                            <div class="b7">

                                <div class="t3">{{__('user_profile_info_address_region')}}</div>
                                <select name="address[region]" class="inp inp2 sel" id="region_id">
                                    <option value="null">{{__('user_profile_info_address_region_2')}}</option>
                                    @if($regions && count($regions)>0)
                                        @foreach($regions as $region)
                                            <option
                                                value="{{$region->id}}" {{$user->addressInfo!=null &&  $region->id==$user->addressInfo->region_id ? "selected" :" "}}>{{$region->name_uz}}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="t3">{{__('user_profile_info_address_district')}}</div>
                                <select name="address[district]" class="inp inp2 sel"
                                        id="district_id" {{$districts!=null ? " " : "disabled"}}>
                                    <option value="null">{{__('user_profile_info_address_district_2')}}</option>
                                    @if($districts && count($districts)>0)
                                        @foreach($districts as $district)
                                            <option
                                                value="{{$district->id}}" {{$user->addressInfo!=null &&  $district->id==$user->addressInfo->district_id ? "selected" :" "}}>{{$district->name_uz}}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="t3">{{__('user_profile_info_address_street')}}</div>
                                <input type="text" class="inp inp2" name="address[street]"
                                       value="{{$user->addressInfo!=null ?$user->addressInfo->street : " "}}">
                                <div class="t3">{{__('user_profile_info_address_postcode')}}</div>
                                <input type="text" class="inp inp2" name="address[postcode]"
                                       value="{{$user->addressInfo!=null ? $user->addressInfo->postcode  :""}}">
                            </div>

                            <div class="b7">

                                <div class="t3">{{__('user_profile_info_address_house_number')}}</div>
                                <input type="text" class="inp inp2" name="address[house]"
                                       value="{{$user->addressInfo!=null ? $user->addressInfo->house :""}}">
                                <div class="t3">{{__('user_profile_info_address_entrance')}}</div>
                                <input type="text" class="inp inp2" name="address[entrance]"
                                       value="{{$user->addressInfo!=null ?$user->addressInfo->entrance :"" }}">
                                <div class="t3">{{__('user_profile_info_address_floor')}}</div>
                                <input type="text" class="inp inp2" name="address[floor]"
                                       value="{{$user->addressInfo!=null ? $user->addressInfo->floor :""}}">
                                <div class="t3">{{__('user_profile_info_address_apartment')}}</div>
                                <input type="text" class="inp inp2" name="address[apartment]"
                                       value="{{$user->addressInfo!=null ? $user->addressInfo->apartment : "" }}">

                            </div>
                        </div>
                    </div>

                    <button type="submit" style="display: block;background-color: #FFFFFF;
                    color: #000000; border: #5eaff0 solid 1px" class="btn">
                        {{__('save')}}
                    </button>

                </div>
            </form>
        </div>
    </div>
    @error('success')
    <input type="hidden" id="check_success_1" value="1">
    @enderror
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

        $('#role_id').on('change', function () {
            $('#form_id').submit()
        })
    </script>


@endsection


