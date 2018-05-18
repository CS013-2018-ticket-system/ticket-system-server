<div class="page" data-name="order_pay" data-page="order_pay">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back" data-ignore-cache="true">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">返回</span>
                </a>
            </div>
            <div class="title">支付订单</div>
        </div>
    </div>
    <div class="page-content">

        <div class="block-title">订单信息</div>
        <div class="list simple-list">
            <ul>
                <li><strong>订单 #{{ $order->id }}</strong></li>
                <li><code>{{ $order->departure_date }} {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }}): {{ $order->seat_type }} {{ $order->seat_no }}</code></li>
                <li>应付款 ￥{{ $order->price }}</li>
                <li id="status">{!! $order->status !!}</li>
                <li class="row">
                    {!! $order->can_pay ? "<button id='confirm_pay' class='col-30 tablet-15 desktop-15 button button-small button-outline button-round color-green'>支付订单</button>" : "" !!}
                    {!! $order->can_cancel ? "<button id='cancel_order' class='col-30 tablet-15 desktop-15 button button-small button-outline button-round color-red'>取消订单</button>" : "" !!}
                    <a class="col-30 tablet-15 desktop-15 button button-small button-outline button-round back" href="#">返回</a>
                    <div class="col-{{ 100 - (1 + $order->can_pay + $order->can_cancel) * ($agent->isMobile() ? 30 : 15) }}">
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
                您目前余额 ￥{{ \Auth::user()->balance }}，票价￥{{ $order->price }}{{ \Auth::user()->balance < $order->price ? "，余额不足。" : "" }}
                <br />确认支付？
            </div>
        @endif


        @if ($order->can_cancel)
            <div id="cancel_dialog" style="display: none">
                确认取消订单吗？此操作将无法撤销。
            </div>
        @endif
    </div>
</div>