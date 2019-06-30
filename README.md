# Тестовое задание
[Само тестовое задание (pdf)](ТЗ_кодер.pdf)

# Описание:
В БД две таблицы: 
- **orders (Заказы)**. Статус нового заказа присваивается автоматически (единица), остальные данные берутся из POST-запроса. Перед этим происходит валидация запроса: все поля должны быть заполнены, а поле article содержать только разрешённые значения. Если валидация не прошла, то скрипт возвращает ошибку -1.
- **products (Товары)**. Здесь все товары. Принтеры и картриджи в одной таблице, просто у принтеров то же название, что и у артикула, а у картриджей уникальные.

# Файлы
- **send.php** - Страница для формирования запросов добавления заказа или товара в базу.
- **index.php** - Страница менеджера. На неё выводится таблица заказов. У каждого заказа есть кнопка Edit, по нажатию на которую вызывается модальное окно с данными товара.
- **js/jquery-3.3.1** -Используется только в для выбора нужного элемента селекта статуса заказа в модальном окне.
- **css/Bootstrap v4.1.1** -Используется для вёрстки таблицы заказов.
- **ajax.php** - Вспомогательный, для AJAX вызовов
- **App.php** - Файл класса, в котором хранится основная логика сайта
- **config.ini** - Конфиг для подключения к БД
- **crystal_lab.sql** - Дамп БД

#Комментарии
Полностью сделать задание за шесть часов не успел. Сделал только добавление товаров и заказов в базу, их просмотр и редактирование менеджером в модальном окне. Для добавления заказов сделал валидацию (хотя и не полную, можно было сделать её более строгой и запретить отправку, пока все поля не будут заполнены верно, при этом неправильно заполненные поля бы подсвечивались и рядом появлялись подсказки).

**Что не успел:**
- Угадывание элемента заказа по его сумме.
- Живой поиск и статистика по заказам (дополнительные задания).

#Скриншоты

![Добавление заказа или товара](https://github.com/tsubaku/crystal_lab_-test_task-/raw/master/img/screen0.png)
![Интерфейс менеджера](https://github.com/tsubaku/crystal_lab_-test_task-/raw/master/img/screen1.png)