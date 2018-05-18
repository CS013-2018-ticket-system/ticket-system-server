<div class="page" data-name="all_orders" data-page="all_orders">
    <div class="fab fab-right-bottom">
        <a href="#" id="refresh_orders">
            <i class="icon material-icons">refresh</i>
        </a>
    </div>

    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">返回</span>
                </a>
            </div>
            <div class="title">所有订单</div>
        </div>
    </div>
    <div class="page-content">

        <div class="block-title">订单信息</div>
        <div class="data-table">
            <table>
                <thead>
                <tr>
                    <th class="label-cell sortable-cell">流水号</th>
                    <th class="label-cell sortable-cell">车次</th>
                    <th class="label-cell sortable-cell">上/下车站</th>
                    <th class="label-cell sortable-cell">下单日期</th>
                    <th class="label-cell sortable-cell">状态</th>
                    <th class="label-cell sortable-cell">操作</th>

                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td style='font-size: 1.5em; font-weight: 600'>{{ $order->train_code }}</td>
                        <td>{{ $order->from_station }}
                            <i class="material-icons">arrow_forward</i>
                            {{ $order->to_station }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{!! $order->status !!}</td>
                        <td>
                            <a class="button button-fill detail-button" data-ignore-cache="true" href="{{ "/pay/" . $order->id }}">查看详情</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>