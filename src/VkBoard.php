<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 20:58
 */

require_once(__DIR__.'/Board.php');

class VkBoard extends Board {

    const API_VERSION = '5.28';
    private $backUrl;

    private $auth = array(
        'secret_key'    =>  'U0RpXo9X5xMPnkhEkRJe',
        'app_id'        =>  '3688869',
    );

    function __construct(){
        parent::__construct();

        $this->config['id'] = 'vk';
        $this->config['link']           = 'https://api.vk.com/method/photos.search';
        $this->config['params']         = 'q=moskow&v=5.28&start_time=%d&end_time=%d&access_token=%s';
        $this->config['auth_url']       = 'https://oauth.vk.com/authorize?client_id=%s&scope=%s&redirect_uri=%s&response_type=code&v=%s&state="SESSION_STATE" ';
        $this->config['access_url']     = 'https://oauth.vk.com/access_token?client_id=%s&client_secret=%s&code=%s&redirect_uri=%s';
        $this->config['scope']          =  'friends,video,offline';
        $this->config['auth']           =   $this->auth;
    }

    protected function parseData($data) {

        $data = json_decode($data, true);
        $rezult = array();

        foreach($data['response']['items'] as $val)
            $rezult[$val['date']] = isset($val['photo_75'])? $val['photo_75']: null;

        return array($this->config['id'] => $rezult);
    }

    protected function authenticate() {

        if (isset($_GET['code'])) {
            $this->getAccessToken();
        } else {
            $this->getRequestToken();
        }
    }

    protected function getRequestToken(){

        $url = $this->getRequestTokenUrl();
        $this->backUrl = $_SERVER['REQUEST_URI'];

        header('Location:'.$url);
        exit;
    }

    protected function getAccessToken(){

        if(!isset($_GET['code'])) throw new BoardException(__CLASS__.' Not find "code"');

        $url = $this->getAccessUrl($_GET['code']);

        $resp = $this->getData($url);
        $resp = json_decode($resp, true);

        if(isset($resp['error'])) throw new BoardException(__CLASS__.' '.$resp['error_description']);

        $this->setToken($resp['access_token']);
        header('Location:'.$this->backUrl);
        exit;
    }

    /**
     * Get url for auth token
     * @return string
     */
    protected function getRequestTokenUrl() {

        return sprintf($this->config['auth_url'], $this->auth['app_id'], $this->config['scope'], $this->config['redirect_url'], self::API_VERSION);
    }

    /**
     * Get url for access token
     * @return string
     */
    protected function getAccessUrl($code) {

        return sprintf($this->config['access_url'], $this->auth['app_id'], $this->auth['secret_key'], $code, $this->config['redirect_url']);
    }
}