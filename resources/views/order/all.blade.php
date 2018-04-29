@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">所有订单</div>

                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">流水号</th>
                                <th scope="col">车次</th>
                                <th scope="col">下单日期</th>
                                <th scope="col">状态</th>
                                <th scope="col">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{ $order->id }}</th>
                                <td>{{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }})<br />{{ $order->seat_type }} {{ $order->seat_no }}</td>
                                <td>￥{{ $order->price }}</td>
                                <td>{!! $order->status !!}</td>
                                <td>
                                    {!! $order->can_pay ? "<button type='button' class='btn btn-outline-info btn-sm' data-toggle='modal' data-target='#confirmPay'>支付</button>" : "" !!}
                                    {!! $order->can_cancel ? "<button type='button' class='btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#confirmCancel'>取消订单</button>" : "" !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
