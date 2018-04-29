@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">车次查询</div>

                    <div class="card-body">
                        <div class="row">

                            <div class="input-group col-md-4">
                                <input type="text" class="form-control" placeholder="出发地" id="from">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">--></span>
                                </div>
                                <input type="text" class="form-control" placeholder="目的地" id="to">
                            </div>


                            <div class="col-md-4">
                                <div class='input-group date' id='datetimepicker1'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">出发日期</span>
                                    </div>
                                    <input type='text' class="form-control" id="date"/>
                                    <span class="input-group-addon">
                                        <i class="fas fa-calendar-alt" style="font-size: 1.3em; margin-top: 0.5em; margin-left: 0.3em"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-info" onclick="query()" id="query">查询</button>
                            </div>
                        </div>

                        <hr />

                        <table id="trains" class="table table-striped table-bordered display nowrap" style="width:100%">
                            <thead>
                            <tr>
                                <th>车次</th>
                                <th>出发站</th>
                                <th>到达站</th>
                                <th>出发时间</th>
                                <th>到达时间</th>
                                <th>历时</th>
                                <th>商务座 / 特等座</th>
                                <th>一等座</th>
                                <th>二等座</th>
                                <th>高级软卧</th>
                                <th>软卧</th>
                                <th>动卧</th>
                                <th>硬卧</th>
                                <th>软座</th>
                                <th>硬座</th>
                                <th>无座</th>
                                <th>其他</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#datetimepicker1').datetimepicker({
                viewMode: 'days',
                format: 'YYYY/MM/DD'
            });
        });

        $(document).ready(function() {
            $('#trains').DataTable({
                "scrollX": true,
                "bPaginate": false,
                "bInfo": false,
            });
        } );

        function query() {
            $("#query").prop("disabled", true);
            $("#query").html("查询中...");


            let from = $("#from").val();
            let to = $("#to").val();
            let date = $("#date").val();
            let table = $('#trains').DataTable();

            let data = {
                from: from,
                to: to,
                date: date,
                _token: "{{ csrf_token() }}"
            };

            $.post("{{ url('/api/train/leftTicket') }}", data, function( data ) {
                if (data.success === false) {
                    toastr.error(data.msg + " (" + data.errcode + ")", '错误')
                } else {
                    table.clear();
                    $.each(data.data, function( index, train ) {
                        table.row.add( [
                            train.train_code,
                            train.user_start_station.station_name + "(" + train.user_start_station.station_code + ")",
                            train.user_end_station.station_name + "(" + train.user_end_station.station_code + ")",
                            train.start_time,
                            train.end_time,
                            train.duration,
                            train.remain_seats[0],
                            train.remain_seats[1],
                            train.remain_seats[2],
                            train.remain_seats[3],
                            train.remain_seats[4],
                            train.remain_seats[5],
                            train.remain_seats[6],
                            train.remain_seats[7],
                            train.remain_seats[8],
                            train.remain_seats[9],
                            train.remain_seats[10],
                            "<a href=\"{{ url('/train/detail') }}?train_no=" + train.train_no + "&from_station_no=" + train.start_station_id + "&to_station_no=" + train.end_station_id + "&seat_types=" + train.seat_type + "&train_date=" + train.date + "&from_station_code=" + train.user_start_station.station_code + "&to_station_code=" + train.user_end_station.station_code + "\" class=\"btn btn-outline-primary\" role=\"button\" target=\"_blank\">查看详情</a>\n"
                        ] ).draw(false);

                    });
                }
                $("#query").prop("disabled", false);
                $("#query").html("查询");

            });
        }
    </script>
@endsection
