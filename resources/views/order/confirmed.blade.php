<div class="page" data-name="order_confirmed" data-page="order_confirmed">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">Back</span>
                </a>
            </div>
            <div class="title">支付订单</div>
        </div>
    </div>
    <div class="block-title">支付信息</div>

    @if ($success == false)
        <div id="pay_success" hidden>false</div>
        <div id="error_msg" hidden>{{ $msg }}</div>
        <div class="card">
            <div class="card-header"><p><i class="material-icons">close</i> 支付失败</p></div>
            <div class="card-content card-content-padding">{{ $msg }}</div>
            <div class="card-footer"><a class="button button-outline back" href="#">返回</a>
            </div>
        </div>

    @else
        <div id="pay_success" hidden>true</div>
        <div class="card">
            <div class="card-header"><p><i class="material-icons">check</i> 订单 #{{ $order->id }} 支付成功</p></div>
            <div class="card-content card-content-padding">
                <code>消费 ￥{{ $order->price }}，您的账户剩余 ￥{{ \Auth::user()->balance }} 元。</code>
                <code>
                    请牢记您的列车车次 {{ $order->train_code }}({{ $order->from_station }}-->{{ $order->to_station }})
                    <br />{{ $order->departure_date }} {{ $order->departure_time }} 发车
                    <br />{{ $order->seat_no }}
                </code>
            </div>
            <div class="card-footer"><a class="button button-outline back" href="#">返回</a></div>
        </div>

    @endif

</div>
