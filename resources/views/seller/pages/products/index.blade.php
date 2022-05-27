@extends('seller.layout.seller-layout')
@section('content')
    <div class="main_info seller_panel">
        <div class="title">{{__('products')}}</div>
        <div class="products">
            <div class="table_title">
                {{__('seller_p_list')}}
            </div>
            @error('update_error')
            <div class="table_title" style="color: red">{{$message}}</div>
            @enderror
            
            <form action="{{route('seller.product.search',app()->getLocale())}}" method="post" autocomplete="off">
                @csrf
                <div class="filter_block">

                    <input style="    width: 480px!important;" type="text" class="search" placeholder="{{__('search')}}"
                           name="search" value="{{isset($search)?($search=='null' ? "" :$search ): ""}}">

                   <!--  <select class="select" name="sell_type">

                        <option value="all">{{__('selling_type_all')}}</option>
                        <option value="retail" {{isset($sell_type)? ($sell_type=="retail" ?"selected":""): ""}}>
                            {{__('selling_type_1')}}
                        </option>
                        <option value="wholesale" {{isset($sell_type)? ($sell_type=="wholesale" ?"selected":""): ""}}>
                            {{__('selling_type_2')}}
                        </option>
                        <option value="both" {{isset($sell_type)? ($sell_type=="both" ?"selected":""): ""}}>
                            {{__('selling_type_3')}}
                        </option>

                    </select>
 -->

                </div>
                <a class="btn addbtn left_btn" href="{{route('seller.product.add',app()->getLocale())}}">
                   {{__('add_product')}}
                </a>
                <div class="fltr_btns">
                        <button type="submit" class="fltr_accept">{{__('filter')}}</button>
                    <a href="{{route('seller.product.index',app()->getLocale())}}"
                       style="text-decoration: none!important;"
                       class="fltr_cancel">{{__('reset')}}</a>
                </div>
            </form>
            <div class="clear"></div>
            <div class="table_block">
                <table>
                    <tbody>
                    <tr style="font-weight: bold">
                        <th class="row2">{{__('image')}}</th>
                        <th class="row6">{{__('info')}}</th>
                        <th class="row4">{{__('In_stock')}}</th>
                        <th class="row4">{{__('price')}}</th>
                        <th class="row7">{{__('state')}}</th>
                        <th class="row8">{{__('actions')}}</th>
                    </tr>
                    @if($products!=null)
                        @foreach($products as $product)
                            <tr>
                                <th>
                                    <img src="{{Storage::url($product->image)}}">
                                </th>
                                <th>
                                    <div class="pr">{{$product['name_'.app()->getLocale()]}}</div>
                                    <div class="pr">{{__('state')}}:
                                        <span>{{$product->is_active ? __('active') : __('block')}}</span></div>
                                    <div class="pr">{{__('catalog')}}: <span>{{$product->category['name_'.app()->getLocale()]}}</span></div>
                                    <div class="pr">{{__('look_at')}}: <a
                                                href="{{route('user.product_view',['id'=>$product->id,'lang'=>app()->getLocale()])}}">{{__('view_in_site')}}</a>
                                    </div>
                                </th>
                                <th>{{$product->quantity}}</th>
                                <th>{{number_format($product->price, null, null, ' ')}} сум</th>
                                <th class="check"><label class="iosCheck blue">
                                        <input type="checkbox" id="update_status_{{$product->id}}"
                                               {{$product->is_active ? "checked" : ""}}  onchange="updateProductStatus({{$product->id}},'/ajax/product',false)">
                                        <i></i>
                                    </label>
                                </th>
                                <th>
                                    <div class="b1">

                                        <a href="{{route('seller.product.edit',['id'=>$product->id,'lang'=>app()->getLocale()])}}"
                                           style="text-decoration: none!important;" class="view"
                                           data-content="edit"></a>
                                        <div class="trash" onclick="showModal({{$product->id}})"></div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                

            @component('admin.components.pagination',['pagination'=>$products])
                @endcomponent
            </div>

        </div>
    </div>
    <input type="hidden" value="{{route('seller.product.index',app()->getLocale())}}" id="redirect_url">
    <div class="popup" id="modal_id" style="display:none;">
        <input type="hidden" value="null" id="delete_product_id">
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
            <div class="t1" style="color: rgba(239,78,78,0.74);margin-bottom: 10px;">{{__('admin_product_delete')}}</div>
            <button id="close" class="btn save" style="background-color: #FFFFFF;
                    border: 2px solid #5eaff0; color: #000;height: 52px">{{__('cancel')}}
            </button>
            <button type="submit" onclick="deleteProduct()" style="background-color:rgba(239,78,78,0.74);
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

        $('#role_id').on('change', function () {
            $('#form_id').submit()
        })


        function showModal(id) {
            $('#modal_id').show();
            $('#delete_product_id').val(id);


        }

        $('#close').click(function () {
            $('#modal_id').hide();
        })

        function deleteProduct() {
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

    </script>


@endsection
