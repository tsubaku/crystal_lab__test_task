<?php

/**
 * Class App. Основная логика
 */
class App
{
    /**
     * Валидация данных - просто проверяем, что все пришедшие поля заполнены. Для лучшей проверки нужно определить точно количество полей, типы данных в них и граничные значения.
     * @return bool
     */
    public function validation()
    {
        #Проверка на определённость
        foreach ($_POST as $val) {
            if (empty($val)) {
                return false;
            }
        }

        #Проверка на допустимые значения поля article
        $allowableArticle = ['canon', 'hp', 'xerox'];
        if (!in_array($_POST['article'], $allowableArticle)) {
            return false;
        }

        return true;
    }


    /**
     * Запись заказа в базу. Возвращаем либо ОК, либо текст ошибки
     * @return string
     */
    public function setOrder()
    {
        try {
            # Cоздать соединение
            $pdo = $this->connectToBase();

            # Добавить заказ в базу
            $stmt = $pdo->prepare('INSERT INTO orders SET 
                fio = :fio, 
                phone = :phone, 
                article = :article, 
                sum = :sum,
                status = :status
                ');
            $stmt->execute(array(
                'fio' => $_POST['fio'],
                'phone' => $_POST['phone'],
                'article' => $_POST['article'],
                'sum' => $_POST['sum'],
                'status' => 1
            ));
            $id = $pdo->lastInsertId();
        } catch (Exception $ex) {
            $id = "Ошибка <b>-1</b>" . $ex->getMessage();
        }

        return $id;
    }

    /** Добавить товар в базу
     * @return string
     */
    public function setProduct()
    {
        try {
            # Cоздать соединение
            $pdo = $this->connectToBase();

            # Добавить заказ в базу
            $stmt = $pdo->prepare('INSERT INTO products SET 
                name = :name, 
                price = :price,
                article = :article
            
                ');
            $stmt->execute(array(
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'article' => $_POST['article']
            ));
            $id = $pdo->lastInsertId();
        } catch (Exception $ex) {
            $id = "Ошибка <b>-1</b>" . $ex->getMessage();
        }

        return $id;
    }

    /**
     * Получить все заказы
     * @return array|string
     */
    public function getOrders()
    {
        try {
            # Cоздать соединение
            $pdo = $this->connectToBase();

            $stmt = $pdo->query('SELECT * FROM `orders`');
            $arrayOrders = $stmt->fetchAll(); //Обработать запрос, переведя ВСЕ данные в массив $arrayOrders

        } catch (Exception $ex) {
            $arrayOrders = "Ошибка <b>-2</b>" . $ex->getMessage();
        }

        return $arrayOrders;
    }


    /**
     * Получить данные одного заказа
     * @param $post
     * @return array|string
     */
    public function getOneOrder($post)
    {
        $id = $post['id'];

        try {
            # Cоздать соединение
            $pdo = $this->connectToBase();

            //Подготовить переменные и выполнить запрос к базе
            $stmt = $pdo->prepare('SELECT * FROM `orders` WHERE `id` = :id');
            $stmt->execute(array('id' => $id));
            $arrayOneOrder = $stmt->fetchAll(); //Обработать запрос, переведя ВСЕ данные в массив

        } catch (Exception $ex) {
            $arrayOneOrder = "Ошибка <b>-3</b>" . $ex->getMessage();
        }

        return $arrayOneOrder;
    }

    /**
     * Отредактировать заказ в базе
     * @param $post
     * @return array|string
     */
    public function setOneOrder($post)
    {
        $id = $post['id'];
        $fio = $post['fio'];
        $phone = $post['phone'];
        $orderStatus = $post['orderStatus'];

        $setOneOrder = true;
        try {
            # Cоздать соединение
            $pdo = $this->connectToBase();

            //Подготовить переменные и выполнить запрос к базе
            $stmt = $pdo->prepare('UPDATE `orders` SET fio = :fio, phone = :phone, status = :orderStatus WHERE `id` = :id');
            $stmt->execute(array(
                'id' => $id,
                'fio' => $fio,
                'phone' => $phone,
                'orderStatus' => $orderStatus,
            ));
            //$setOneOrder = $stmt->fetchAll(); //Обработать запрос, переведя ВСЕ данные в массив

        } catch (Exception $ex) {
            $setOneOrder = "Ошибка <b>-3</b>" . $ex->getMessage();
        }

        return $setOneOrder;
    }


    /**
     * Соединение с БД
     * @return PDO
     */
    function connectToBase()
    {
        # Читаем конфиг
        $connect_conf = $this->readConfig('config.ini');    // Получаем все параметры
        $hostname = $connect_conf['dpo']['hostname'];
        $username = $connect_conf['dpo']['username'];
        $password = $connect_conf['dpo']['password'];
        $dbName = $connect_conf['dpo']['dbName'];
        $charset = $connect_conf['dpo']['charset'];

        # Cоздать соединение
        $dsn = "mysql:host=$hostname;dbname=$dbName;charset=$charset";
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
        $pdo = new PDO($dsn, $username, $password, $opt);

        return $pdo;
    }


    /**
     * Функция чтения конфига из файла
     * @param $file -Путь к файлу настроек
     * @return array|bool
     */
    function readConfig($file)
    {
        return parse_ini_file($file, true);
    }


}