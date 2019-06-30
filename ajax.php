<?php
require_once 'App.php';

$app = new App();

//Определяем и запрашиваем нужную аякс-функцию
switch ($_POST['action']){
    case 'set':
        $result = $app->setOneOrder($_POST);
        break;
    case 'get':
        $result = $app->getOneOrder($_POST);
        break;
    default:
        break;
}


echo json_encode($result);
