<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 18:25
 */
namespace boards;

interface I_Board {

    /**
     * Get result data
     * @param $date_start
     * @param $date_stop
     */
    function run($date_start, $date_stop);

}