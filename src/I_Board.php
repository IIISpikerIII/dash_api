<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 18:25
 */

require_once (__DIR__.'/BoardException.php');
interface I_Board {

    /**
     * Get result data
     * @param $date_start
     * @param $date_stop
     */
    function run($date_start, $date_stop);

}