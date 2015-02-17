<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 19:51
 */

namespace boards;

abstract class Board implements I_Board {

    protected $config = [];

    /**
     * Board constructor
     * @param array $conf
     */
    function __construct($conf = array()){

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function run($date_start, $date_stop){

    }

}