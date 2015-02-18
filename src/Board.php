<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 19:51
 */

require_once(__DIR__.'/I_Board.php');

abstract class Board implements I_Board {

    /**
     * auth = array('api_key', 'secret_key')
     * @var array
     */
    protected $config = [
        'method'    =>  'GET',
        'params'    =>  null,
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
     * Get url or params for request data
     * @param $date_start
     * @param $date_stop
     * @return string
     */
    protected function requestUrl($arg = array(), $atr = 'link') {

        return vsprintf($this->config['link'], $arg);
    }

    /**
     * Get data from url
     * @param $url
     * @return mixed
     */
    protected function getData($url, $params = null, $method = 'GET'){

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, $method == 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

            $out = curl_exec($curl);
            curl_close($curl);
            return $out;
        }
    }

    /**
     * Parsing data from response
     * @param $data
     * @return array
     */
    protected function parseData($data) {

        $rezult = json_decode($data, true);
        return array($rezult);
    }

    /**
     * Check auth user
     * @return bool
     */
    protected function isAuth() {
        return isset($_SESSION[__CLASS__.'auth']) && $_SESSION[__CLASS__.'auth'];
    }

    /**
     * Set auth token
     * @param $token
     */
    protected function setToken($token) {

        $_SESSION[__CLASS__.'token'] = $token;
        $_SESSION[__CLASS__.'auth'] = ($token !== null);
    }

    /**
     * Get auth token
     * @return bool
     */
    protected function getToken() {

        return isset($_SESSION[__CLASS__.'token'])? $_SESSION[__CLASS__.'token']: false;
    }

    protected function authenticate() {
        return true;
    }

    /**
     * Run process building data
     * @param $date_start
     * @param $date_stop
     * @return array|mixed
     */
    public function run($date_start, $date_stop) {

        $start = strtotime($date_start);
        $stop = strtotime($date_stop);

        // authorization if needed
        if($this->config['auth'] !== false) {

            if($this->isAuth()) {
                $url = $this->requestUrl(array($start, $stop, $this->getToken()));
                $params = $this->requestUrl(array($start, $stop, $this->getToken()), 'params');
            }
            else
                $this->authenticate();

        } else {
            $url = $this->requestUrl(array($start, $stop));
            $params = $this->requestUrl(array($start, $stop), 'params');
        }

        $data = $this->getData($url, $params, $this->config['method']);
        $data = $this->parseData($data);

        return $data;
    }

}