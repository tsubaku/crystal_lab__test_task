<!DOCTYPE html>
<html lang="en">

<head>
    <title>Заглушка. Эмулирует запросы от партнёра и запрос на добавление товара в базу</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8"/>
    <meta http-equiv="Cache-control" content="NO-CACHE">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/main.css">
</head>

<body>

<div class="logicalBlock">
    <h2>Имитация POST-запроса от партнёров</h2>
    <form action="" method="post">
        <label for="fio">ФИО</label>
        <input type="text" id="fio" name="fio" value="" title="fio"/>
        <hr>
        <label for="phone">Телефон</label>
        <input type="text" id="phone" name="phone" value="" title="phone"/>
        <hr>
        <label for="article">Артикул</label>
        <input type="text" id="article" name="article" value="" title="article"/>
        <hr>
        <label for="sum">Сумма</label>
        <input type="text" id="sum" name="sum" value="" title="sum"/>
        <hr>
        <input type="submit" value="Послать" id="SubmitButton" name="SubmitButton"/>
    </form>
</div>


<div class="logicalBlock">
    <h2>Добавление товара в базу</h2>
    <form action="" method="post">
        <label for="name">Название товара</label>
        <input type="text" id="name" name="name" value="" title="name"/>
        <hr>
        <label for="price">Цена</label>
        <input type="text" id="price" name="price" value="" title="price"/>
        <hr>
        <label for="price">article</label>
        <input type="text" id="article" name="article" value="" title="article"/>
        <hr>
        <input type="submit" value="Послать" id="SubmitButton2" name="SubmitButton2"/>
    </form>
</div>


<?php
#Подключаем логику
require_once('App.php');

#Если пришёл заказ от партнёра
if (isset($_POST['SubmitButton'])) {
    #Валидируем пришедшие от партнёра (или из заглушки) данные
    $app = new App();
    $valid = $app->validation();
    if ($valid == true) {
        echo "Данные валидны <hr>";
    } else {
        echo "Ошибка <b>-1</b>. Данные НЕ валидны  <hr>";
        die();
    }

    #Пишем заказ в БД
    $id = $app->setOrder();
    echo "Номер заказа в БД: $id <hr>";
}


#Если мы хоти добавить товар в базу
if (isset($_POST['SubmitButton2'])) { //check if form was submitted
    $app = new App();
    #Пишем в БД
    $id = $app->setProduct();
    echo "Ок: $id <hr>";
}

?>


</body>
</html>

