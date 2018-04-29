@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">车次详情</div>

                    <div class="card-body">
                        <p>
                            <a class="btn btn-outline-primary" data-toggle="collapse" href="#timeTable" role="button" aria-expanded="false" aria-controls="timeTable">
                                列车时刻表
                            </a>
                            <a class="btn btn-outline-primary" data-toggle="collapse" href="#trainBasic" role="button" aria-expanded="false" aria-controls="trainBasic">
                                列车信息
                            </a>
                        </p>
                        <div class="collapse" id="timeTable">
                            <div class="card card-body">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">站名</th>
                                        <th scope="col">到站时间</th>
                                        <th scope="col">出发时间</th>
                                        <th scope="col">停留时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($time_schedule as $station)
                                        <tr class="{{ $station->isEnabled ? "" : "tr-disabled" }}">
                                            <th scope="row">{{ $station->station_no }}</th>
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


                        <div class="collapse" id="trainBasic">
                            <div class="card card-body">
                                <p>
                                {{ $time_schedule[0]->station_train_code }}次 ({{ $time_schedule[0]->start_station_name }}-->
                                {{ $time_schedule[0]->end_station_name }}) <span class="badge badge-primary">{{ $time_schedule[0]->train_class_name }}</span>
                                <span class="badge badge-primary">有空调</span>
                                </p>
                            </div>
                        </div>

                        <hr />

                        <h4>订票</h4><br />

                        <form action="{{ url('/order/add') }}" method="post">
                            {{ csrf_field() }}
                            <input type="text" class="form-control" name="start_time" value="{{ $start_time }}" style="display: none">

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">车次</span>
                                        </div>
                                        <input type="text" class="form-control" name="train_code" value="{{ $time_schedule[0]->station_train_code }}" readonly="readonly">
                                    </div>
                                </div>

                                <div class="form-group col-md-5 offset-md-1">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">始末站</span>
                                        </div>
                                        <input type="text" class="form-control" name="start_end_station" value="{{ $start_station_name }}-->{{ $end_station_name }}" readonly="readonly">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">座位类型</span>
                                        </div>
                                        <select class="form-control" id="seat_type" name="seat_type">
                                            @foreach ($seat_types as $seat_type)
                                                <option value="{{ $seat_type["seat_type"] }}|{{ $seat_type["price"] }}">{{ $seat_type["seat_type"] }}: {{ $seat_type["price"] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-md-5 offset-md-1">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">日期</span>
                                        </div>
                                        <input type="text" class="form-control" name="departure_date" value="{{ $train_date }}" readonly="readonly">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-md-5">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">姓名</span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ \Illuminate\Support\Facades\Auth::user()->name }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group col-md-5 offset-md-1">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">学号</span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ \Illuminate\Support\Facades\Auth::user()->student_id }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">学院</span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ \Illuminate\Support\Facades\Auth::user()->college }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group col-md-5 offset-md-1">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">身份证号</span>
                                        </div>
                                        <input type="text" class="form-control" value="{{ \Illuminate\Support\Facades\Auth::user()->id_number }}" disabled>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="提交订单">
                                    <button class="btn btn-primary" type="button" onclick="window.close()">返回</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
