<?php
// Хост {обычно localhost}
$db_host = "localhost";
// Имя базы данных
$db_name = "stats";
// Логин для подключения к базе данных
$db_user = "root";
// Пароль для подключения к базе данных
$db_pass = "";

$db = mysqli_connect ($db_host, $db_user, "", $db_name) or die ("Невозможно подключится к БД");
?>
