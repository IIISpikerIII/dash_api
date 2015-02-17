<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 19:51
 */

require_once(__DIR__.'/I_Board.php');

abstract class Board implements I_Board {

    protected $config = [
        'method'    =>  'GET',
        'auth'      =>  false,
    ];

    /**
     * Board constructor
     * @param array $conf
     */
    function __construct($conf = array()){

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get url for request data
     * @param $date_start
     * @param $date_stop
     * @return string
     */
    protected function requestUrl($date_start, $date_stop) {

        return sprintf($this->config['link'], $date_start, $date_stop);
    }

    /**
     * Get data from url
     * @param $url
     * @return mixed
     */
    protected function getData($url){

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = curl_exec($curl);
            curl_close($curl);
            return $out;
        }
    }

    protected function parseData($data) {

        return json_decode($data);
    }

    public function run($date_start, $date_stop) {

        $start = strtotime($date_start);
        $stop = strtotime($date_stop);
        $url = $this->requestUrl($start, $stop);

        $data = $this->getData($url);
        $data = $this->parseData($data);

        return $data;
    }

}