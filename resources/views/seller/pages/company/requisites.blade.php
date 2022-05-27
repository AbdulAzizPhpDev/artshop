@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info">
        <form action="{{route('seller.company.store.requisites',app()->getLocale())}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$requisites!=null ? $requisites->id : 0}}">
            <div class="requisites">
                <div class="title">{{__('company')}}</div>
                <div class="table_title">{{__('requisites_set')}}</div>
                @if($errors->any())
                    {{ implode('', $errors->all(':message')) }}
                @endif
                <div class="b1">
                    <div class="b2">
                        <div class="t1">{{__('com_name')}}</div>
                        <input name="official_name" maxlength="64" type="text" class="inp1" required
                               value="{{$requisites!=null ? $requisites->official_name : " "}}">

                        <div class="t1">{{__('ownership')}}</div>
                        <select style="width: 423px;
    height: 40px;" class="inp1" name="ownership" id="ownership_id" required>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ДП" ? "selected" : " " ) : " "}} value="ДП">
                                ДП
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ООО" ? "selected" : " " ) : " "}} value="ООО">
                                ООО
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ЧП" ? "selected" : " " ) : " "}} value="ЧП">
                                ЧП
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ОАО" ? "selected" : " " ) : " "}} value="ОАО">
                                ОАО
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership==" ЗАО" ? "selected" : " " ) : " "}} value=" ЗАО">
                                ЗАО
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ИП ОАО" ? "selected" : " " ) : " "}} value="ИП ОАО">
                                ИП ОАО
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership==" СП ООO" ? "selected" : " " ) : " "}} value=" СП ООO">
                                СП ООO
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ЯТТ" ? "selected" : " " ) : " "}} value="ЯТТ">
                                ЯТТ
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership==" ПК" ? "selected" : " " ) : " "}} value=" ПК">
                                ПК
                            </option>
                            <option
                                    {{$requisites!=null ? ($requisites->ownership=="ГО" ? "selected" : " " ) : " "}} value="ГО">
                                ГО
                            </option>
                        </select>

                    </div>
                    <div class="b2">
                        <div class="t1">{{__('stir')}}</div>
                        <input id="stir_id" type="text" maxlength="9" minlength="9" class="inp1" required
                               value="{{$requisites!=null ? $requisites->stir : " "}}">
                        <input type="hidden" id="input_stir_id" name="stir" required
                               value="{{$requisites!=null ? $requisites->stir : " "}}">

                        <div class="t1">ОКЭД</div>
                        <input name="activity" type="text" class="inp1" required
                               value="{{$requisites!=null ? $requisites->activity : " "}}">
                    </div>
                    <div class="b3">
                        <div class="t1">{{__('director')}}</div>
                        <input type="text" class="inp1" placeholder="Имя" name="first_name" required
                               value="{{$requisites!=null ? $requisites->first_name : " "}}">
                        <div class="t1">{{__('bank_account_1')}}</div>
                        <input type="text" class="inp1" name="bank_account" required
                               value="{{$requisites!=null ? $requisites->bank_account : " "}}">
                        <div class="t1">{{__('bank_account_2')}}</div>
                        <input type="text" class="inp1" name="bank_account2"
                               value="{{$requisites!=null ? $requisites->bank_account2 : " "}}">
                    </div>
                    <div class="b3">
                        <div class="t1">&nbsp;</div>
                        <input type="text" class="inp1" placeholder="Фамилия" name="last_name" required
                               value="{{$requisites!=null ? $requisites->last_name : " "}}">
                        <div class="t1">{{__('bank_info')}}</div>
                        <input type="text" class="inp1" name="bank_info" required
                               value="{{$requisites!=null ? $requisites->bank_info : " "}}">
                        <div class="t1">{{__('bank_info')}}</div>
                        <input type="text" class="inp1" name="bank_info2"
                               value="{{$requisites!=null ? $requisites->bank_info2 : " "}}">
                    </div>
                    <div class="b3">
                        <div class="t1">&nbsp;</div>
                        <input type="text" class="inp1" placeholder="Отчество" name="meddle_name" required
                               value="{{$requisites!=null ? $requisites->meddle_name : ''}}">
                        <div class="t1">{{__('bank_name')}}</div>
                        <input type="text" class="inp1" name="bank_name" required
                               value="{{$requisites!=null ? $requisites->bank_name : ' '}}">
                        <div class="t1">{{__('bank_name')}}</div>
                        <input type="text" class="inp1" name="bank_name2"
                               value="{{$requisites!=null ? $requisites->bank_name2 : ''}}">
                    </div>
                </div>
                <button style="display: block" type="submit" class="btn">{{__('save')}}</button>
            </div>
        </form>
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
    </script>
@endsection