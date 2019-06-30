//<!-- AJAX блок -->
function XmlHttp() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}


function ajax(param) {
    if (window.XMLHttpRequest) req = new XmlHttp();
    method = (!param.method ? "POST" : param.method.toUpperCase());

    if (method == "GET") {
        send = null;
        param.url = param.url + "&ajax=true";
    }
    else {
        send = "";
        for (var i in param.data) send += i + "=" + param.data[i] + "&";
        // send=send+"ajax=true"; // если хотите передать сообщение об успехе
    }

    req.open(method, param.url, true);
    if (param.statbox) document.getElementById(param.statbox).innerHTML = '<img src="./img/wait.gif">';
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(send);
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) //если ответ положительный
        {
            if (param.success) param.success(req.responseText);
        }
    }
}

//<!-- конец AJAX блока -->

//При загрузке страницы подменяем цифровые статусы заказов словесными описаниями
$(document).ready(function () {
    var statusArray = document.getElementsByClassName('statusOrder');
    for (i = 0; i < statusArray.length; i++) {
        statusArray[i].innerHTML = getAlias(statusArray[i].innerHTML);
    }
});


/**
 * Получить данные заказа из БД и отрисовать их в модальном окне
 * @param buttonName
 */
function getOneOrder(buttonName) {
    var id = parseInt(buttonName.replace(/\D+/g, "")); //убираем из id кнопки текст, оставляем только цифру, равну id
    console.log(id);
    ajax({
        url: "./ajax.php",
        type: "POST",
        async: true,
        statbox: "status",
        data:
            {
                action: 'get',
                id: id
            },
        success: function (data) {
            document.getElementById("status").innerHTML = ''; //удалить значок ожидания
            console.log("success" + data);
            var ArrayOneOrder = JSON.parse(data);

            //Обновляем инпуты модального окна
            document.getElementById("id").value = ArrayOneOrder[0]['id'];
            document.getElementById("fio").value = ArrayOneOrder[0]['fio'];
            document.getElementById("phone").value = ArrayOneOrder[0]['phone'];
            //Обновляем селект
            $('#orderStatus').val(ArrayOneOrder[0]['status']).change();

            //Открыть модальное окно
            var modal = document.getElementById('myModal');
            var buttonCancel = document.getElementById('buttonCancel');

            modal.style.display = "block";

            //Закрытие модального окна
            window.onclick = function (event) {
                if ((event.target == modal) || (event.target == buttonCancel)) {
                    modal.style.display = "none";
                }

            };

        },
        error: function (error) {
            console.log("eror_change_cell");
        }
    })
}

/**
 * Записывает данные из модального окна в БД
 * @returns {boolean}
 */
function setOrder() {
    //Получить данные из полей ввода
    var id = document.getElementById('id').value;
    var fio = document.getElementById('fio').value;
    var phone = document.getElementById('phone').value;
    var orderStatus = GetSelectOption('orderStatus');
    console.log('fio=' + fio + ' phone=' + phone + ' orderStatus=' + orderStatus);

    //Записать данные в базу
    ajax({
        url: "./ajax.php",
        type: "POST",
        async: true,
        statbox: "status",
        data:
            {
                action: 'set',
                id: id,
                fio: fio,
                phone: phone,
                orderStatus: orderStatus
            },
        success: function (data) {
            document.getElementById("status").innerHTML = ''; //удалить значок ожидания
            console.log("success" + data);

            //Если обновление в БД прошло удачно, то обновляем данные на странице
            if (data == "true") {
                document.getElementById('fio-' + id).innerHTML = fio;
                document.getElementById('phone-' + id).innerHTML = phone;
                document.getElementById('status-' + id).innerHTML = getAlias(orderStatus);
            } else {
                document.getElementById("status").innerHTML = 'Что-то пошло не так и информация не обновилась в базе. <br> Ошибка:' + data;
            }

            //Закрываем модальное окно
            var modal = document.getElementById('myModal');
            modal.style.display = "none";

        },
        error: function (error1) {
            console.log("eror_change_cell");
        }
    });
    return true;
}


/**
 * Получаем текстовое название статуса (алиас) по его номеру
 * @param statusOrder
 * @returns {string|string|string|string|string|string}
 */
function getAlias(statusOrder) {
    switch (statusOrder) {
        case '1':
            aliasStatusOrder = 'Новый заказ';
            break;
        case '2':
            aliasStatusOrder = 'Клиенту позвонил менеджер';
            break;
        case '3':
            aliasStatusOrder = 'Заказ на доставке курьера';
            break;
        case '4':
            aliasStatusOrder = 'Заказ доставлен клиенту';
            break;
        case '5':
            aliasStatusOrder = 'Клиент отказался от заказа';
            break;
        default:
            aliasStatusOrder = 'Не определён';
            break;
    }
    return aliasStatusOrder;
}

/**
 * Получаем значение из списка
 * @param name_selector
 * @returns {string}
 * @constructor
 */
function GetSelectOption(name_selector) {
    var selind = document.getElementById(name_selector).options.selectedIndex;  // получаем индекс выбранного элемента
    //var txt = document.getElementById(name_selector).options[selind].text;      //Выбранный пункт списка
    var val = document.getElementById(name_selector).options[selind].value;     //Его номер по порядку
    return val;
}

