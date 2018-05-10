<div class="page" data-name="order_pay" data-page="order_pay">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back" data-ignore-cache="true">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">Back</span>
                </a>
            </div>
            <div class="title">支付订单</div>
        </div>
    </div>
    <div class="block-title">订单信息</div>
    <div class="list simple-list">
        <ul>
            <li><strong>订单 #{{ $order->id }}</strong></li>
            <li><code>{{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }}): {{ $order->seat_type }} {{ $order->seat_no }}</code></li>
            <li>应付款 ￥{{ $order->price }}</li>
            <li id="status">{!! $order->status !!}</li>
            <li class="row">
                {!! $order->can_pay ? "<button id='confirm_pay' class='col-15 button button-outline button-small button-round color-green'>支付订单</button>" : "" !!}
                {!! $order->can_cancel ? "<button id='cancel_order' class='col-15 button button-outline button-small button-round color-red'>取消订单</button>" : "" !!}
                <a class="col-15 button button-outline button-small button-round back" href="#">返回</a>
                <div class="col-{{ 85 - $order->can_pay * 15 - $order->can_cancel * 15 }}">
                    <!-- 占位 -->
                </div>
            </li>
        </ul>
    </div>

    <div id="can_pay" hidden>{{ $order->can_pay ? "true" : "false" }}</div>
    <div id="can_cancel" hidden>{{ $order->can_cancel ? "true" : "false" }}</div>
    <div id="order_id" hidden>{{ $order->id }}</div>


    @if ($order->can_pay)
        <div id="pay_dialog" style="display: none">
            您将要支付 {{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }}) {{ $order->seat_type }}
            <br />
            您目前余额 ￥{{ \Auth::user()->balance }}，票价￥{{ $order->price }}{{ \Auth::user()->balance < $order->price ? "，余额不足。" : "" }}
        </div>
    @endif


    @if ($order->can_cancel)
        <div id="cancel_dialog" style="display: none">
            您将要取消 {{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }}) {{ $order->seat_type }}
            <br />
            确认取消订单吗？此操作将无法撤销。
        </div>
    @endif

</div>