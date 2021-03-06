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
        'secret_key'    =>  'ХХХХХХХХХХХХХХХХХХХХХХХ',
        'app_id'        =>  'ХХХХХХХ',
    );

    function __construct(){
        parent::__construct();

        $this->config['id'] = 'vk';
        $this->config['link']           = 'https://api.vk.com/method/photos.search';
        $this->config['params']         = 'q=cats&v=5.28&start_time=%d&end_time=%d&access_token=%s';
        $this->config['auth_url']       = 'https://oauth.vk.com/authorize?client_id=%s&scope=%s&redirect_uri=%s&response_type=code&v=%s&state="SESSION_STATE" ';
        $this->config['access_url']     = 'https://oauth.vk.com/access_token?client_id=%s&client_secret=%s&code=%s&redirect_uri=%s';
        $this->config['scope']          = 'friends,video,offline';
        $this->config['auth']           =  $this->auth;
    }

    /**
     * Parse data in format
     * @param $data
     * @return array
     */
    protected function parseData($data) {

        $data = json_decode($data, true);
        $rezult = array();

        if(!isset($data['response']['items']) || sizeof($data['response']['items']) == 0)
            return array($this->config['id'] = null);

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

    /**
     * Get Request Token
     * @throws BoardException
     */
    protected function getRequestToken(){

        $url = $this->getRequestTokenUrl();
        $this->backUrl = $_SERVER['REQUEST_URI'];

        header('Location:'.$url);
        exit;
    }

    /**
     * Get Access token from param code
     * @throws BoardException
     */
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