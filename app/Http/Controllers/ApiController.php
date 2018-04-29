<?php

namespace App\Http\Controllers;

use App\AccessToken;
use App\Station;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

class ApiController extends Controller
{
    const ERR_FROM_STATION_NOT_FOUND = 10001;
    const ERR_TO_STATION_NOT_FOUND = 10002;
    const ERR_INVALID_DATE = 10003;
    const ERR_REQUEST_TIMEOUT = 10004;

    private $user;

    public function __construct()
    {
    }

    public function apiUserInfo()
    {
        return Response::json(array(
            "success" => true,
            "data" => Auth::user(),
        ));
    }

    public function apiLeftTicket(Request $request) {
        $success = true;
        $msg = "";
        $errcode = 10000;

        $from = $request->from;
        $to = $request->to;
        $date = $request->date;

        $from_station = Station::where('station_name', $from);
        if ($from_station->count()) {
            $from_station = $from_station->first();
        } else {
            $success = false;
            $msg = "未找到该出发地";
            $errcode = ApiController::ERR_FROM_STATION_NOT_FOUND;
            return Response::json(array(
                "success" => $success,
                "errcode" => $errcode,
                "msg" => $msg,
            ));
        }

        $to_station = Station::where('station_name', $to);
        if ($to_station->count()) {
            $to_station = $to_station->first();
        } else {
            $success = false;
            $msg = "未找到该目的地";
            $errcode = ApiController::ERR_TO_STATION_NOT_FOUND;
            return Response::json(array(
                "success" => $success,
                "errcode" => $errcode,
                "msg" => $msg,
            ));
        }

        $date = Carbon::parse($date);
        if ($date->diffInDays(Carbon::now()) < 0) {
            $success = false;
            $msg = "出发时间不可早于今天";
            $errcode = ApiController::ERR_INVALID_DATE;
            return Response::json(array(
                "success" => $success,
                "errcode" => $errcode,
                "msg" => $msg,
            ));
        } elseif ($date->diffInDays(Carbon::now()) > 20) {
            $success = false;
            $msg = "仅可购买20天以内的车票";
            $errcode = ApiController::ERR_INVALID_DATE;
            return Response::json(array(
                "success" => $success,
                "errcode" => $errcode,
                "msg" => $msg,
            ));
        } else {
            $date = $date->toDateString();

            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->get("https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date={$date}&leftTicketDTO.from_station={$from_station->station_code}&leftTicketDTO.to_station={$to_station->station_code}&purpose_codes=ADULT",
                    ['timeout' => 10]);
                $return_json = json_decode($res->getBody())->data;
                $data = array();

                // |预订|54000G711711|G7117|NJH|AOH|NJH|SZH|14:42|16:07|01:25|N||20180428|3|H1|01|06|1|0|||||||无||||无|无|无||O090M0O0|O9MO|0
                foreach ($return_json->result as $train) {
                    $train = explode("|", $train);
                    for ($i = 20; $i <= 33; $i++) {
                        if ($train[$i] == "") {
                            $train[$i] = "--";
                        }
                    }

                    $data[] = array(
                        "note" => $train[0],
                        "train_no" => $train[2],
                        "train_code" => $train[3],
                        "start_station" => Station::where('station_code', $train[4])->first(),
                        "end_station" => Station::where('station_code', $train[5])->first(),
                        "user_start_station" => Station::where('station_code', $train[6])->first(),
                        "user_end_station" => Station::where('station_code', $train[7])->first(),
                        "start_time" => $train[8],
                        "end_time" => $train[9],
                        "duration" => $train[10],
                        "can_buy" => ($train[11] == "Y"),
                        "date" => Carbon::parse($train[13])->toDateString(),
                        "remain_seats" => [
                            $train[32], $train[31], $train[30], $train[20], $train[23], $train[33], $train[28],
                            $train[24], $train[29], $train[26], $train[22]
                        ],
                        "seat_type" => $train[35],
                        "start_station_id" => $train[16],
                        "end_station_id" => $train[17],
                    );
                }

                return Response::json(array(
                    "success" => true,
                    "errcode" => 10000,
                    "data" => $data,
                ));
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                return Response::json(array(
                    "success" => false,
                    "errcode" => ApiController::ERR_REQUEST_TIMEOUT,
                    "msg" => "请求超时，请稍后再试",
                ));
            }

        }


    }
}
