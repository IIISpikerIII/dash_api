<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 20:58
 */

require_once(__DIR__.'/Board.php');

class WeatherBoard extends Board {

    function __construct(){
        parent::__construct();

        $this->config['id'] = 'weather';
        $this->config['link'] = 'http://api.openweathermap.org/data/2.5/history/city?q=moskow&start=%d&end=%d';
    }

    protected function parseData($data) {

        $data = json_decode($data, true);
        $rezult = array();

        foreach($data['list'] as $val)
            $rezult[$val['dt']] = $val['main']['temp'];

        return array($this->config['id'] => $rezult);
    }
}