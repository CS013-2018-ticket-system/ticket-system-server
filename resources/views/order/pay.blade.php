@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">订单支付</div>

                    <div class="card-body">
                        @if ($request->success == 1)
                            <div class="alert alert-success" role="alert">
                                您的订单 #{{ $order->id }} 创建成功！
                            </div>
                        @endif
                            <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">流水号</th>
                                    <th scope="col">订单详情</th>
                                    <th scope="col">订单总价</th>
                                    <th scope="col">状态</th>
                                    <th scope="col">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    <td>{{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }})<br />{{ $order->seat_type }} {{ $order->seat_no }}</td>
                                    <td>￥{{ $order->price }}</td>
                                    <td id="status">{!! $order->status !!}</td>
                                    <td id="operation">
                                        {!! $order->can_pay ? "<button type='button' class='btn btn-outline-info btn-sm' data-toggle='modal' data-target='#confirmPay'>支付</button>" : "" !!}
                                        {!! $order->can_cancel ? "<button type='button' class='btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#confirmCancel'>取消订单</button>" : "" !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <hr />

                        <a role="button" class="btn btn-outline-primary" href="{{ url('/order') }}">返回所有订单</a>

                    </div>

                </div>
            </div>
        </div>
    </div>

    @if ($order->can_pay)
    <!-- Modal -->
    <div class="modal fade" id="confirmPay" tabindex="-1" role="dialog" aria-labelledby="confirmPayLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">确认支付订单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    您将要支付 {{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }}) {{ $order->seat_type }}
                    <br />
                    您目前余额 ￥{{ \Auth::user()->balance }}，票价￥{{ $order->price }}{{ \Auth::user()->balance < $order->price ? "，余额不足。" : "" }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    @if (\Auth::user()->balance < $order->price)
                        <button type="button" class="btn btn-primary" disabled>余额不足</button>
                    @else
                        <a class="btn btn-primary" href="{{ url("/order/pay/confirm/" . $order->id) }}" role="button">确认支付</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif


    @if ($order->can_cancel)
    <!-- Modal -->
    <div class="modal fade" id="confirmCancel" tabindex="-1" role="dialog" aria-labelledby="confirmCancelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">取消订单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    您将要取消 {{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }}) {{ $order->seat_type }}
                    <br />
                    确认取消订单吗？此操作将无法撤销。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">返回</button>
                    <button type="button" class="btn btn-primary" onclick="confirmCancel()" data-dismiss="modal">确认取消</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        function confirmCancel() {
            $.get( "{{ url("/order/cancel/" . $order->id) }}", function(data) {
                $("#operation").html("");
                $("#status").html(data.status);
                if (data.type === "success") {
                    toastr.success(data.msg, data.title);
                } else {
                    toastr.info(data.msg, data.title);
                }
            });
        }
    </script>

@endsection
