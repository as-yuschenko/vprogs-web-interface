<?php
/*DEV*/
$result = $db->query('select portID,addr,mode,translt,ver,desc from ppDev where id = '.$_GET['id']);
$row = $result->fetchArray(SQLITE3_ASSOC);

echo '<h3>С2000-ПП</h3>
<form action = "settings/ppdev_update.php" method="post">
<table border=1>

	<tr><td>Адрес</td><td><input type="text" name="addr" value="'.$row['addr'].'"></td></tr>
	<tr><td>Номер порта</td><td><input type="text" name="portID" value="'.$row['portID'].'"></td></tr>
	<tr><td>Режим работ</td><td>
							<input name="mode" type="radio" value="1" '.(((int)$row['mode'] == 1) ? "checked" : "").'>Master<br>
							<input name="mode" type="radio" value="0" '.(((int)$row['mode'] == 0) ? "checked" : "").'>Slave<br>
							</td></tr>
	<tr><td>Прма трансли</td><td>
							<input name="translt" type="radio" value="1" '.(((int)$row['translt'] == 1) ? "checked" : "").'>ON<br>
							<input name="translt" type="radio" value="0" '.(((int)$row['translt'] == 0) ? "checked" : "").'>OFF<br>
							</td></tr>
	<tr><td>Верси</td><td><input type="text" name="ver" value="'.$row['ver'].'"></td></tr>
	<tr><td>Описание</td><td><input type="text" name="desc" value="'.$row['desc'].'"></td></tr>
</table>
<br>
<input type="hidden" name="ppDevID" value="'.$_GET['id'].'">
<input type="submit" value="Обновить">
</form>';

/*ZONES*/
echo'
<br>
<p><b>ZONES</b></p>

<a href = "./?show=settings&do='.PPZONE_ADD.'&id='.$_GET['id'].'">Добавить зону</a>
<form action = "settings/zone_update.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Адрес прибора</th>
	<th class="">Номер шлейфа</th>
	<th class="">Описание</th>
	<th class="">На главной</th>
	<th class="">Тип шлейфа</th>
	<th class="">Номер раздела ПП</th>
	<th class="">Описание раздела ПП</th>
	<th class="">Номер раздела С2000М</th>
	<th class="">Описание раздела С2000М</th>
</tr>
';

	$result = $db->query('
	Select 
	ppZone.id
	,ppZone.num
	,ppZone.orDev
	,ppZone.orDevLoop
	,ppZone.desc
	,ppZoneType.desc `type`
	,ppZone.mainScreen
	,ppPart.num `partNum`
	,ppPart.desc `partDesc`
	,orPart.num `opartNum`
	,orPart.desc `opartDesc`
	from ppZone
	left join ppZoneType on ppZone.ppZoneTypeID = ppZoneType.id
	left join ppPart on ppZone.ppPartID = ppPart.id
	left join orPart on ppZone.orPartID = orPart.id
	where ppZone.ppDevID = '.$_GET['id'].'
	');

	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");
		echo("<td>".$row['orDev']."</td>");
		echo("<td>".$row['orDevLoop']."</td>");
		/*	Form parsing
		 * Prefix [d] is zone descritpion  
		 * Prefix [s] is view zone on start page  
		 * */
		echo('<td><input type="text" name="d'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo('<td><input type="checkbox" name = "s'.$row['id'].'"'.(($row['mainScreen'])? " checked" :"").'></td>');

		echo("<td>".$row['type']."</td>");
		echo("<td>".$row['partNum']."</td>");
		echo("<td>".$row['partDesc']."</td>");
		echo("<td>".$row['opartNum']."</td>");
		echo("<td>".$row['opartDesc']."</td>");
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="ppDevID" value="'.$_GET['id'].'">
<input type="submit" value="Обновить">
</form>
';

/*PARTS*/
echo'
<br>
<p><b>PARTS</b></p>

<a href = "./?show=settings&do='.PPPART_ADD.'&id='.$_GET['id'].'">Добавить раздел</a>
<form action = "settings/part_update.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Описание</th>
	<th class="">На главной</th>

</tr>
';

	$result = $db->query('Select id,num,desc,mainScreen from ppPart where ppDevID ='.$_GET['id']);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		echo('<td><input type="text" name="d'.$row['id'].'" value="'.$row['desc'].'"</td>');
		echo('<td><input type="checkbox" name = "s'.$row['id'].'"'.(($row['mainScreen'])? " checked" :"").'></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="ppDevID" value="'.$_GET['id'].'">
<input type="submit" value="Обновить">
</form>';


/*RELAYS*/
echo'
<br>
<p><b>RELAYS</b></p>

<a href = "./?show=settings&do='.PPREALY_ADD.'&id='.$_GET['id'].'">Добавить реле</a>
<form action = "settings/relay_update.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Адрес прибора</th>
	<th class="">Номер выХода</th>
	<th class="">Описание</th>
	<th class="">На главной</th>
</tr>
';

	$result = $db->query('Select id,num,orDev,orDevRelay,desc,mainScreen from ppRelay where ppDevID ='.$_GET['id']);

	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");
		echo("<td>".$row['orDev']."</td>");
		echo("<td>".$row['orDevRelay']."</td>");
		echo('<td><input type="text" name="d'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo('<td><input type="checkbox" name = "s'.$row['id'].'"'.(($row['mainScreen'])? " checked" :"").'></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="ppDevID" value="'.$_GET['id'].'">
<input type="submit" value="Обновить">
</form>
';


/*USERS*/
echo'
<br>
<p><b>USERS</b></p>

<a href = "./?show=settings&do='.PPUSER_ADD.'&id='.$_GET['id'].'">Добавить юзера</a>
<form action = "settings/user_update.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Описание</th>

</tr>
';

	$result = $db->query('Select id,num,desc from ppUser where ppDevID ='.$_GET['id']);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		echo('<td><input type="text" name="d'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="ppDevID" value="'.$_GET['id'].'">
<input type="submit" value="Обновить">
</form>';


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

	$result = $db->query('Select id,num,desc from orPart where ppDevID ='.$_GET['id']);


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

	$result = $db->query('Select id,num,pwd,desc from orUser where ppDevID ='.$_GET['id']);


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
<input type="hidden" name="ppDevID" value="'.$_GET['id'].'">
<input type="submit" value="Удалить устройство">
</form>
</p>
'
;
?>
