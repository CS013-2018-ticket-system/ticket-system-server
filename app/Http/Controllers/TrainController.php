<?php

namespace App\Http\Controllers;

use App\Station;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    const SEAT_TYPE = array(
        "WZ" => "无座",
        "M" => "一等座",
        "A9" => "商务座 / 特等座",
        "A4" => "软卧",
        "A3" => "硬卧",
        "O" => "二等座",
        "F" => "动卧",
        "A6" => "高级软卧",
        "A1" => "硬座",
        "A2" => "软座",
    );

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index() {
        return view("train/schedule");
    }

    public function detail(Request $request) {
        $train_no = $request->train_no;
        $from_station_no = $request->from_station_no;
        $to_station_no = $request->to_station_no;
        $from_station_code = $request->from_station_code;
        $to_station_code = $request->to_station_code;

        $seat_types = $request->seat_types;
        $train_date = $request->train_date;

        $client = new \GuzzleHttp\Client();
        $res = $client->get("https://kyfw.12306.cn/otn/leftTicket/queryTicketPrice?train_no={$train_no}&from_station_no={$from_station_no}&to_station_no={$to_station_no}&seat_types={$seat_types}&train_date={$train_date}",
            ['timeout' => 10]);
        $data = json_decode($res->getBody(), true)["data"];

        $return_seat_types = array();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, TrainController::SEAT_TYPE)) {
                $return_seat_types[] = array(
                    "seat_type" => TrainController::SEAT_TYPE[$key],
                    "price" => $value,
                );
            }
        }

        $client = new \GuzzleHttp\Client();
        $res = $client->get("https://kyfw.12306.cn/otn/czxx/queryByTrainNo?train_no={$train_no}&from_station_telecode={$from_station_code}&to_station_telecode={$to_station_code}&depart_date={$train_date}",
            ['timeout' => 10]);
        $data = json_decode($res->getBody())->data->data;

        return view("train/detail")->with(array(
            "seat_types" => $return_seat_types,
            "time_schedule" => $data,
            "start_station_name" => Station::where('station_code', $from_station_code)->first()->station_name,
            "end_station_name" => Station::where('station_code', $to_station_code)->first()->station_name,
            "train_date" => $train_date,
        ));
    }
}
