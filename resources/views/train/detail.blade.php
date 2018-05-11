<div class="page" data-name="train_detail" data-page="train_detail">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">Back</span>
                </a>
            </div>
            <div class="title">车次详情</div>
        </div>
    </div>
    <div class="page-content">

        <div class="block-title">列车信息</div>
        <div class="list accordion-list">
            <ul>
                <li class="accordion-item">
                    <a href="#" class="item-content item-link">
                        <div class="item-inner">
                            <div class="item-title">列车时刻表</div>
                        </div>
                    </a>

                    <div class="accordion-item-content">
                        <div class="block">

                            <div class="data-table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="label-cell sortable-cell">#</th>
                                        <th class="label-cell sortable-cell">站名</th>
                                        <th class="label-cell sortable-cell">到站时间</th>
                                        <th class="label-cell sortable-cell">出发时间</th>
                                        <th class="label-cell sortable-cell">停留时间</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($time_schedule as $station)
                                        <tr class="{{ $station->isEnabled ? "" : "tr-disabled" }}">
                                            <td>{{ $station->station_no }}</td>
                                            <td>{{ $station->station_name }}</td>
                                            <td>{{ $station->arrive_time }}</td>
                                            <td>{{ $station->start_time }}</td>
                                            <td>{{ $station->stopover_time }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </li>
                <li class="accordion-item"><a href="#" class="item-content item-link">
                        <div class="item-inner">
                            <div class="item-title">列车信息</div>
                        </div></a>
                    <div class="accordion-item-content">
                        <div class="block">
                            <p>{{ $time_schedule[0]->station_train_code }}次 ({{ $time_schedule[0]->start_station_name }}-->{{ $time_schedule[0]->end_station_name }}) <span class="badge badge-primary">{{ $time_schedule[0]->train_class_name }}</span>
                                <span class="badge badge-primary">有空调</span></p>
                        </div>
                    </div>
                </li>

                <li class="accordion-item">
                    <a href="#" class="item-content item-link">
                        <div class="item-inner">
                            <div class="item-title">订票</div>
                        </div>
                    </a>
                    <div class="accordion-item-content">
                        <div class="block">
                            <form action="{{ url('/order/add') }}" method="post" class="form-ajax-submit" id="orderAddForm">

                                <input id="_token" name="_token" type="text" value="{{ csrf_token() }}" style="display: none" hidden />
                                <input type="text" class="form-control" name="start_time" value="{{ $start_time }}" style="display: none" hidden />

                                <div class="list no-hairlines-md">
                                <ul>
                                    <li>
                                        <div class="row">
                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">车次</div>
                                                    <div class="item-input-wrap">
                                                        <input id="departure" type="text" name="train_code" value="{{ $time_schedule[0]->station_train_code }}" readonly="readonly" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">始末站</div>
                                                    <div class="item-input-wrap">
                                                        <input id="arrival" type="text" name="start_end_station" value="{{ $start_station_name }}-->{{ $end_station_name }}" readonly="readonly" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content item-input col-50">
                                                <a class="item-link smart-select smart-select-init" style="width: 100%;" data-open-in="popover">
                                                    <select name="seat_type">
                                                        @foreach ($seat_types as $seat_type)
                                                            <option value="{{ $seat_type["seat_type"] }}|{{ $seat_type["price"] }}">{{ $seat_type["seat_type"] }}: {{ $seat_type["price"] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="item-content">
                                                        <div class="item-inner">
                                                            <div class="item-title">座位类型</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">日期</div>
                                                    <div class="item-input-wrap">
                                                        <input type="text" name="departure_date" value="{{ $train_date }}" readonly="readonly" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">姓名</div>
                                                    <div class="item-input-wrap">
                                                        <input type="text" value="{{ \Illuminate\Support\Facades\Auth::user()->name }}" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">学号</div>
                                                    <div class="item-input-wrap">
                                                        <input type="text" value="{{ \Illuminate\Support\Facades\Auth::user()->student_id }}" disabled />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">学院</div>
                                                    <div class="item-input-wrap">
                                                        <input type="text" value="{{ \Illuminate\Support\Facades\Auth::user()->college }}" disabled />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="item-content item-input col-50">
                                                <div class="item-inner">
                                                    <div class="item-title item-label">身份证号</div>
                                                    <div class="item-input-wrap">
                                                        <input type="text" value="{{ \Illuminate\Support\Facades\Auth::user()->id_number }}" disabled />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="item-content item-input col-20">
                                                <button class="button button-fill" type="submit">提交订单</button>

                                            </div>

                                        </div>
                                    </li>

                                </ul>

                            </div>
                            </form>


                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>