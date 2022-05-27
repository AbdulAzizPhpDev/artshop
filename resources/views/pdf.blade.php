<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/assets/pdf/pdf.css">
    <title>Document</title>

    <style>
        table.table-bordered > thead  > tr > th {

            border: 2px solid black;
        }
        table.table-bordered > tbody  > tr > td{

            border: 2px solid black;
        }


        table > tbody  > tr > td {
            border-bottom: 2px solid white;
            border-top: 2px solid white;
        }

        table > thead  > tr > th {
            border-bottom: 2px solid white;
            border-top: 2px solid white;
        }
        table > tbody  > tr > th {
            border-bottom: 2px solid white;
            border-top: 2px solid white;
        }
    </style>

</head>
<body>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">


                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h3 class="float-left">
                                <img width="75" height="75" src="/artshop.png" alt=""> ArtShop
                                <img width="75" height="75" src="/artshop_qr.png" alt="">
                                <p class="mt-4"><b>{{__('print_order')}}:</b> {{$order->id}}<br></p>
                            </h3>
                            <p class="float-right" style="font-size: 24px">{{ucfirst(__('time'))}}
                                : {{Carbon\Carbon::parse($order->created_at)->format('d.m.Y')}}</p>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            {{__('seller')}}
                            <address>
                                <strong>{{$order->mertchant->name}}</strong><br>
                                @if($order->mertchant->address)
                                    {{$order->mertchant->address->region['name_'.app()->getLocale()]}}
                                    , {{$order->mertchant->address->a_district['name_'.app()->getLocale()]}}<br>
                                    {{$order->mertchant->address->street}}, {{$order->mertchant->address->house}}<br>
                                @endif
                                {{__('phone')}}: {{$order->mertchant->phone_number}}<br>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                        </div>

                        <div class="col-sm-4 invoice-col">
                            {{__('bayer')}}
                            <address>
                                <strong>{{$order->orderer->name}}</strong><br>
                                @if($order->address)
                                    {{$order->address->region['name_'.app()->getLocale()]}}
                                    @if($order->address->a_district)
                                    , {{$order->address->a_district['name_'.app()->getLocale()]}}<br>
                                    @endif
                                        @if($order->address->street)
                                    {{$order->address->street}},
                                    @endif
                                    @if($order->address->house)
                                    {{$order->address->house}}<br>
                                    @endif
                                @endif
                                <br>{{__('phone')}}: {{$order->orderer->phone_number}}<br>

                            </address>
                        </div>
                        <!-- /.col -->

                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->

                    @if(count($order->orderList)>0)

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>{{__('name')}}</th>
                                        <th>{{__('price')}}</th>
                                        <th>{{__('quantity')}}</th>
                                        <th>{{__('total')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->orderList as $item)
                                        <tr>
                                            <td>{{$item['product']['name_'.app()->getLocale()]}}</td>
                                            <td>{{number_format(($item['product']->price ), null, null, ' ')}} {{__('sum')}}</td>
                                            <td>{{$item->product_quantity}}</td>
                                            <td>{{number_format(($item['product']->price*$item->product_quantity), null, null, ' ')}} {{__('sum')}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>

                @endif
                <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">
                            <p class="lead">{{__('invoice_payment_type')}}:</p>
                            <p>{{__($order->payment_method)}}</p>

                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table ">
                                    <tbody>
                                    <tr>
                                        <th style="width:50%">{{__('product_price_total')}}:</th>
                                        <td>{{number_format($order->total_price, null, null, ' ')}} {{__('sum')}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('delivery_p_2')}}:</th>
                                        @if($order->address_id==0)
                                            <td>{{number_format(0, null, null, ' ')}} {{__('sum')}}</td>
                                        @else
                                            <td>{{number_format($order->deliveryTable->where('region_id',$order->address->region_id)->first()->price, null, null, ' ')}}  {{__('sum')}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>{{__('total')}}:</th>
                                        @if($order->address_id==0)
                                            <td>{{number_format($order->total_price, null, null, ' ')}}</td>
                                        @else
                                            <td>{{number_format($order->total_price+$order->deliveryTable->where('region_id',$order->address->region_id)->first()->price, null, null, ' ')}} {{__('sum')}}</td>
                                        @endif


                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                </div>
                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<script>
    window.addEventListener("load", window.print());
</script>
</body>
</html>
