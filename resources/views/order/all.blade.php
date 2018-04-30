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
                            @foreach ($orders as $order)
                            <tr>
                                <th scope="row">{{ $order->id }}</th>
                                <td>{{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }})</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{!! $order->status !!}</td>
                                <td>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ url("/order/pay/" . $order->id) }}" role="button">查看详情</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
