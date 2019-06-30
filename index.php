<!DOCTYPE html>
<html lang="en">

<head>
    <title>Интерфейс менеджера</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8"/>
    <meta http-equiv="Cache-control" content="NO-CACHE">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/bootstrap.css"/>
    <link rel="stylesheet" href="./css/main.css">

    <script type="text/javascript" src="./js/jquery-3.3.1.js"></script>     <!--  Скрипты jquery   -->
    <script type="text/javascript" src="./js/main.js"></script>             <!--  Скрипты аякса   -->
</head>


<body>

<!-- Блок таблицы заказов -->
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1>Таблица заказов</h1>
            <table class="table table-bordered table-striped table-light">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">Корзина</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>

                <?php
                #Подключаем логику
                require_once('App.php');
                $app = new App();

                #Достаём из БД таблицу заказов
                $orders = $app->getOrders();
                if (is_array($orders)) {
                    foreach ($orders as $order) {
                        echo "<tr>";
                        foreach ($order as $orderKey => $orderVal) {
                            switch ($orderKey) {
                                case "id":
                                    echo "<th>$orderVal</th>";
                                    $id = $orderVal; //Запоминаем номер заказа
                                    break;
                                case "status":
                                    echo "<td id='$orderKey-$id' class='statusOrder'>$orderVal</td>";
                                    break;
                                default:
                                    echo "<td id='$orderKey-$id'>$orderVal</td>";
                                    break;
                            }
                        }
                        echo "<td><button type='button' class='btn btn-primary' id='edit$id' onclick='getOneOrder(this.id);'>Edit</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td>Ошибка: $orders</td></tr>";
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="status"></div>


<!--  Блок модального окна -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Редактировать заказ</h2>
        </div>
        <div class="modal-body">
            <input type="hidden" id="id" name="id"/>
            <label for="fio">ФИО</label>
            <input type="text" id="fio" name="fio" title="fio"/>
            <hr>
            <label for="phone">Телефон</label>
            <input type="text" id="phone" name="phone" title="phone"/>
            <hr>
            <label for="orderStatus">Статус</label>
            <select id="orderStatus" name="orderStatus">
                <option id="status1" value="1">новый заказ</option>
                <option id="status2" value="2">клиенту позвонил менеджер</option>
                <option id="status3" value="3">заказ на доставке курьера</option>
                <option id="status4" value="4">заказ доставлен клиенту</option>
                <option id="status5" value="5">клиент отказался от заказа</option>
            </select>
            <hr>
            <input type="button" value="OK" id="buttonOk" name="buttonOk" onclick="setOrder();"/>
            <input type="button" value="Cancel" id="buttonCancel" name="buttonCancel"/>
        </div>
        <div class="modal-footer">
            <h3></h3>
        </div>
    </div>
</div>

<div id="status">
</div>

</body>
</html>

