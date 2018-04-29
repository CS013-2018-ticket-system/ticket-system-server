<?php

use Illuminate\Database\Seeder;
use App\Station;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new GuzzleHttp\Client();
        $res = $client->get('https://kyfw.12306.cn/otn/resources/js/framework/station_name.js');
        $js = $res->getBody();
        $js = str_replace("var station_names ='@", "", $js);
        $js = str_replace("';", "", $js);

        foreach (explode("@", $js) as $station) {
            $station = explode("|", $station);
            $station_obj = new Station(array(
                "station_name" => $station[1],
                "station_abbr" => $station[0],
                "station_py" => $station[3],
                "station_code" => $station[2],
                "station_id" => $station[5]
            ));
            $station_obj->save();
        }
    }
}
