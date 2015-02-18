<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 19:51
 */

require_once(__DIR__.'/I_Board.php');

class Boards implements I_Board {

    private $boards = array();

    /**
     * Board constructor
     * @param array $conf
     */
    function __construct($conf = array()){

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function addBoard(Board $board) {

        $this->boards[] = $board;
    }

    public function run($date_start, $date_stop) {

        $rezult = array();

        foreach($this->boards as $board) {
            $rez = $board->run($date_start, $date_stop);
            $rezult = array_merge($rezult, $rez);
        }

        return $rezult;
    }

}