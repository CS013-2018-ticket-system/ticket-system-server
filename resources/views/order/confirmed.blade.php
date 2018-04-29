@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">订单支付</div>

                    <div class="card-body">

                        <ul class="list-group">
                            @if ($success == false)
                                <li class="list-group-item list-group-item-danger">{{ $msg }}</li>
                            @else
                                <li class="list-group-item list-group-item-success">
                                    您的订单 #{{ $order->id }} 已成功支付！
                                </li>
                                <li class="list-group-item">
                                    消费 ￥{{ $order->price }}，您的账户剩余 ￥{{ \Auth::user()->balance }} 元。
                                </li>

                                <li class="list-group-item">
                                    请牢记您的列车车次 {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }})
                                    <br />{{ $order->departure_date }} {{ $order->departure_time }} 发车
                                    <br />{{ $order->seat_no }}
                                </li>
                            @endif
                        </ul>

                        <hr />

                        <button type="button" class="btn btn-outline-primary" onclick="window.location.href=document.referrer;">返回</button>


                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
