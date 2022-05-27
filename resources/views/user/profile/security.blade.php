@extends('admin.layouts.user-layout')
@section('style')
    <style>
        .inp {
            height: 37px !important;
        }

        .edit {
            background: url({{asset('/assets/img/admin/pen.png')}}) 11px 50% no-repeat #FFF;
            z-index: 100;
            border-radius: 4px;
            outline: none;
            display: block;
            position: relative;
            width: 47px;
            height: 37px;
            bottom: 38px;
            left: 371px;
            padding: 0;
            margin: 0;
            cursor: pointer;
        }

        .inp_model {
            border: 1px solid #ebedf2;
            border-radius: 5px;
            outline: none;
            width: 430px;
            margin: 12px 0px;
            height: 35px;
            padding: 2px 10px;
            font-size: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="main_info">
        <div class="fields">
            <div class="title">{{__('user_profile_title')}}</div>
            <div class="t1">{{__('user_security_title')}}</div>
            <div class="b1">
                <form action="{{route('user.profile.post.login.security',['lang'=>app()->getLocale()])}}" method="post"
                      autocomplete="off">
                    @csrf
                    <div class="b5">
                        <div class="t1">{{__('user_security_login_title')}}</div>
                        <div class="t2">
                            {{__('user_security_login_text')}}
                        </div>

                        @error('user_name')
                        <div class="t2" style="color: red">
                            {{$message}}
                        </div>
                        @enderror

                        @error('phone_number')
                        <div class="t2" style="color: red">
                            {{$message}}
                        </div>
                        @enderror

                        <div class="b6">
                            <div class="b7">
                                <input required class="inp"
                                       type="text" disabled id="format_phone_id"
                                       autocomplete="off" placeholder="+998-90-000-00-00"
                                       value="{{session()->has('user_phone') ?session()->get('user_phone')['format']  : $new_phone}}">
                                <span class="edit" onclick="showModal()"></span>
                                <input type="hidden" name="phone_number" id="input_phone_id" autocomplete="off"
                                       value="{{session()->has('user_phone') ?session()->get('user_phone')['phone']  : $user->phone_number}}">

                                <input type="hidden" value="{{$user->phone_number}}" id="check_user_phone_id">

                                <div style="margin-top: 10px" id="verified_id">
                                    @error('confirm_code')
                                    <p style="margin: 4px 0;color: red">{{$message}}</p>
                                    @enderror
                                    @if (session()->has('user_phone'))
                                        <input type="text" maxlength="6" class="inp inp_msg" required
                                               name="confirm_code" id="confirm_code_id" value="{{old('confirm_code')}}"
                                               style="background: url({{asset('/assets/img/admin/verified.png')}}) 18px 50%
                                                       no-repeat #FFF;">
                                    @endif

                                </div>
                            </div>

                            <div class="b7">
                                <input type="text" maxlength="24" class="inp inp_msg"
                                       placeholder="examplenamextv@gmail.com" name="user_name"
                                       value="{{$user->user_name!=null ? $user->user_name : old('user_name')}}">
                            </div>
                        </div>
                    </div>

                    <button id="post_login_security_id" type="submit" class="btn">{{__('save')}}</button>
                </form>
                <form action="{{route('user.profile.post.security',['lang'=>app()->getLocale()])}}" method="post"
                      autocomplete="off">
                    @csrf
                    <div class="b5">
                        <div class="t1">{{__('user_security_password_title')}}</div>
                        <div class="t2">
                            {{__('user_security_password_text')}}
                            @error('success')
                            <div class="t2" style="color: #00e031">
                                Ваш пароль успешно изменен
                            </div>
                            @enderror
                        </div>
                        <div class="b6">

                            <div class="b7">
                                <div class="t3">
                                    @error('password')
                                    <span style="color: red"> {{$message}}</span>
                                    @else
                                        {{__('user_security_password_current')}}
                                        @enderror
                                </div>
                                <input required type="password" class="inp inp2" name="password" maxlength="24" min="8"
                                >
                                <div class="t3">
                                    @error('confirm_password')
                                    <span style="color: red"> {{$message}}</span>
                                    @else
                                        {{__('user_security_password_reenter')}}
                                        @enderror

                                </div>
                                <input required type="text" class="inp inp2" name="confirm_password" maxlength="24"
                                       min="8"
                                       value="{{old('confirm_password')}}">

                            </div>

                            <div class="b7">
                                <div class="t3">
                                    @error('new_password')
                                    <span style="color: red"> {{$message}}</span>
                                    @else
                                        {{__('user_security_password_new')}}
                                        @enderror
                                </div>
                                <input required type="text" name="new_password" class="inp inp2" maxlength="24" min="8"
                                       value="{{old('new_password')}}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn">{{__('save')}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="popup" id="modal_id" style="display:none;">
        <div class="fade"></div>
        <div style="width: 450px;
    background-color: #FFF;
    border-radius: 20px;
    margin: 0 auto;
    margin-top: -200px;
    position: relative;
    top: 50%;
    z-index: 3;
    padding: 55px;
    text-align: center;">
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">Редактировать номер телефона</div>
            <div>
                <input type="text" class="inp_model" id="model_number_id"
                       placeholder="+998-90-000-00-00" maxlength="17">

            </div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="checkPhoneNumber()" style="background-color:rgba(239,78,78,0.74);
                    border: 2px solid #5eaff0; color: #000;height: 52px" class="btn save">{{__('accept')}}
            </button>
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

        let phone_number = null;


        function showModal() {
            $('#model_number_id').val('');
            $('#modal_id').show();
        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        $('.inp_model')

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
                    // $("#input_phone_id").val($(this).val().replace(new RegExp('-', 'g'), ""))
                    phone_number = $(this).val().replace(new RegExp('-', 'g'), "")
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

        function checkPhoneNumber() {
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
            let pattern = new RegExp(/^\+998\d{9}$/gm);
            if (pattern.test(phone_number) && phone_number != $("#check_user_phone_id").val()) {
                t = setTimeout(function () {
                    $.ajax({
                        type: 'post',
                        url: '/ajax/change-phone',
                        data: {
                            phone: phone_number,
                            format_phone: $('#model_number_id').val()
                        },
                        beforeSend: function () {
                            $('#post_login_security_id').prop('disabled', true);
                            $('#confirm_code_id').prop('disabled', true);
                        },
                        success: function (obj) {
                            if (obj.state == 'success') {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'мы отправляем вам код подтверждения на ваш телефон'
                                });

                            }
                            $('#post_login_security_id').prop('disabled', false);
                            $('#confirm_code_id').prop('disabled', false);
                            $('#input_phone_id').val(obj.phone)
                            $('#format_phone_id').val($('#model_number_id').val())

                            $('#modal_id').hide();

                            $('#verified_id').html(' <input type="text" name="confirm_code" maxlength="6" class="inp inp_msg" required' +
                                'name="confirm_code" id="confirm_code_id" ' +
                                'style="background: url({{asset('/assets/img/admin/verified.png')}}) 18px 50%' +
                                'no-repeat #FFF;">')
                            $('#confirm_code_id').focus();


                        }
                    });
                }, 350);
            } else if (phone_number == $("#check_user_phone_id").val()) {
                Toast.fire({
                    icon: 'warning',
                    title: 'введенный номер телефона такой же, как и старый'
                });
            } else if (!pattern.test(phone_number)) {
                Toast.fire({
                    icon: 'warning',
                    title: 'введен номер телефона неправильного формата'
                })
            }
        }

    </script>






@endsection
