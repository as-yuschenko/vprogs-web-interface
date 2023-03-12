<?php

$ppDevID = $_GET['id'];

/*DEV*/
echo'<h3>С2000-ПП</h3>';
include ('ppdev_edit.php');

/*ZONES*/
echo'
<br>
<p><b>ZONES</b></p>
<a href = "./?show=settings&do='.PPZONE_ADD.'&id='.$ppDevID.'">Добавить зону</a>';
echo'
<div class = "form_add_entity">
<form action = "settings/zone_add.php" method="post">
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<table>
<tr><td class = "bottom_line">Номер зон</td><td><input type="text" name="num" value=""></td></tr>
<tr><td class = "bottom_line">Адрес прибора</td><td><input type="text" name="orDevAddr" value=""></td></tr>
<tr><td class = "bottom_line">Номер шлейфа</td><td><input type="text" name="orDevLoop" value=""></td></tr>
<tr><td class = "bottom_line">Тип зон</td>
	<td>
		<input type="radio" name="type" value = "1" checked>Состояние ШС<br>
		<input type="radio" name="type" value = "2">Состояние КЦ<br>
		<input type="radio" name="type" value = "3">Состояние прибора<br>
		<input type="radio" name="type" value = "4">Вкл/Выкл автоматики<br>
		<input type="radio" name="type" value = "5">Дистанционный пуск<br>
		<input type="radio" name="type" value = "6">Температура/Влажность<br>
		<input type="radio" name="type" value = "7">Счетчик импульсов<br>
		<input type="radio" name="type" value = "8">РИП напряжение/ток<br>
	</td>
</tr>
<tr><td class = "bottom_line">Номер раздела</td><td><input type="text" name="ppPartNum" value=""></td></tr>
<tr><td class = "bottom_line">Описание</td><td><input type="text" name="desc" value=""></td></tr>
<tr><td class = "bottom_line">Отобразить на главной</td><td><input type="checkbox" name="mainScreen"></td></tr>
<tr><td></td><td><br><input type="submit" value="Добавить"></td></tr>
</table>
</form>
</div>
';

include ('zone_edit.php');



/*PARTS*/
echo'
<br>
<p><b>PARTS</b></p>

<a href = "./?show=settings&do='.PPPART_ADD.'&id='.$ppDevID.'">Добавить раздел</a>';
include ('part_edit.php');


/*RELAYS*/
echo'
<br>
<p><b>RELAYS</b></p>

<a href = "./?show=settings&do='.PPREALY_ADD.'&id='.$ppDevID.'">Добавить реле</a>';
include ('relay_edit.php');



/*USERS*/
echo'
<br>
<p><b>USERS</b></p>

<a href = "./?show=settings&do='.PPUSER_ADD.'&id='.$ppDevID.'">Добавить юзера</a>';
include ('user_edit.php');



/*ORION PARTS*/
echo'
<br>
<h3>ORION PARTS</h3>
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Описание</th>

</tr>
';

	$result = $db->query('Select id,num,desc from orPart where ppDevID ='.$ppDevID);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		//echo('<td><input type="text" name="p'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo('<td>'.$row['desc'].'</td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
';


/*ORION USERS*/
echo'
<br>
<h3>ORION USERS</h3>
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Пароль</td>
	<th class="">Описание</td>

</tr>
';

	$result = $db->query('Select id,num,pwd,desc from orUser where ppDevID ='.$ppDevID);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		echo("<td>".$row['pwd']."</td>");	
		echo('<td>'.$row['desc'].'</td>');
		//echo('<td><input type="text" name="u'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
';

/*REMOVE ppDev*/
echo'
<hr>
<p align = "center">
<form action = "settings/ppdev_rm.php" method="post">
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<input type="submit" value="Удалить устройство">
</form>
</p>
'
;
?>
