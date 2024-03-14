<?php include 'db.php';

//Указываем кодировку, в которой будет получена информация из базы
@mysqli_query ($db, 'set character_set_results = "utf8"');

//Получаем IP-адрес посетителя и сохраняем текущую дату
$visitor_ip = $_SERVER('REMOTE_ADDR');
$date = date("Y-m-d");

//Указываем, были ли посещения за сегодня
$res = mysqli_query($db, "SELECT `visit_id` FROM `visits` WHERE `date`='$date'") or die ("Проблема при подкючении к БД");

//Если сегодня ещё не было посещений
if (mysqli_num_rows($res) == 0)
{
	//Очищаем таблицу ips
	mysqli_query($db, "DELETE FROM `ips`");
	
	//Заносим в базу IP-адрес текущего пользователя
	mysqli_query($db, "INSERT INTO `ips` SET `ip_address`='$visitor_ip'");
	
	//Заносим в базу дату посещения и устанавливаем кол-во просмотров и уник. посещений в значение 1
	$res_count = mysqli_query($db, "INSERT INTO `visits` SET `date`='$date', `hosts`=1, `view`=1");
}

//Если посещения сегодня уже были
else
{
	//Проверяем, есть ли уже в базе IP-адрес, с которого происходит обращение
	$current_ip = mysqli_query($db, "SELECT `ip_id` FROM `ips` WHERE `ip_address`='$visitor_ip'");
	
	//Если такой IP-адрес уже сегодня был (т.е. это не уникальный посетитеть)
	if (mysqli_num_rows($current_ip) == 1)
	{
		//Добавляем для текущей даты +1 просмотр (хит)
		mysqli_query($db, "UPDATE `visits` SET `views`=`views`+1 WHERE `date`='$date'");
	}
	
	//Если сегодня такого IP-адреса ещё не было (т.е. это уникальный пользователь)
	else
	{
		//Заносим в базу IP-адрес этого пользователя
		mysqli_query($db, "INSERT INTO `ips` SET `ip_address`='$current_ip");
		
		//Добавляем в базу +1 уникального посетителя (хост) и +1 просмотр (хит)
		mysqli_query($db, "UPDATE `visits` SET `hosts`=`hosts`+1, `views`=`views`+1 WHERE `date`='$date'");
	}
}

