<div class="page" data-name="order_pay" data-page="order_pay">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back" data-ignore-cache="true">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">返回</span>
                </a>
            </div>
            <div class="title">我的账户</div>
        </div>
    </div>
    <div class="page-content">

        <div class="block-title">账户余额</div>
        <div class="block block-strong">
            <p><span style="font-size: 1.2em">￥</span><span style="font-size: 1.8em">{{ $yuan }}.</span>
                <span style="font-size: 1.4em">{{ sprintf("%02d", $cent) }}</span></p>
        </div>

        <div class="block-title">交易记录</div>
        <div class="data-table">
            <table>
                <thead>
                <tr>
                    <th class="label-cell sortable-cell">交易流水</th>
                    <th class="label-cell sortable-cell">订单流水</th>
                    <th class="label-cell sortable-cell">交易金额</th>
                    <th class="label-cell sortable-cell">交易日期</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($trades as $trade)
                    <tr>
                        <td>#{{ $trade->id }}</td>
                        <td><a class="link popover-open" href="#" data-popover=".popover-order-{{ $trade->order_id }}-{{ md5($trade->created_at) }}">#{{ $trade->order_id }}</a></td>
                        <td><code>{{ $trade->amount }}</code></td>
                        <td>{{ $trade->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @foreach ($trades as $trade)

        <div class="popover popover-order-{{ $trade->order_id }}-{{ md5($trade->created_at) }}">
            <div class="popover-inner">
                <div class="block">
                    <p>{{ $trade->note }}</p>
                    <p><a href="/pay/{{ $trade->order_id }}" class="popover-close">订单详情</a></p>
                </div>
            </div>
        </div>

        @endforeach

    </div>
</div>