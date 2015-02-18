<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 17.02.15
 * Time: 21:08
 */

require_once(__DIR__.'/src/autoloader.php');

$model = new WeatherBoard();
$rezult = $model->run('13.02.2015','17.02.2015');
print_r($rezult);

$boards = new Boards();
$boards->addBoard(new WeatherBoard());
$boards->addBoard(new VkBoard());
$rezult = $boards->run('13.02.2015','17.02.2015');
print_r($rezult);
