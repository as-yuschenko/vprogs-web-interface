<?php

/*ZONES*/
echo'
<br>
<h3>ZONES</h3>
<form action = "settings/update_entities_desc.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Адрес прибора</td>
	<th class="">Номер шлейфа</td>
	<th class="">Описание</td>
	<th class="">Тип шлейфа</td>
	<th class="">Номер раздела ПП</td>
	<th class="">Описание раздела ПП</td>
	<th class="">Номер раздела С2000М</td>
	<th class="">Описание раздела С2000М</td>
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
		
		echo('<td><input type="text" name="'.$row['id'].'" value="'.$row['desc'].'"></td>');
		
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
<input type="hidden" name="entityType" value="'.PPDEV_UPDATE_ZONES.'">
<input type="submit" value="Обновить">
</form>
';

/*PARTS*/
echo'
<br>
<h3>PARTS</h3>
<form action = "settings/update_entities_desc.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Описание</td>

</tr>
';

	$result = $db->query('Select id,num,desc from ppPart where ppDevID ='.$_GET['id']);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		echo('<td><input type="text" name="'.$row['id'].'" value="'.$row['desc'].'"</td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="entityType" value="'.PPDEV_UPDATE_PARTS.'">
<input type="submit" value="Обновить">
</form>';


/*RELAYS*/
echo'
<br>
<h3>RELAYS</h3>
<form action = "settings/update_entities_desc.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Адрес прибора</td>
	<th class="">Номер выХода</td>
	<th class="">Описание</td>
</tr>
';

	$result = $db->query('Select id,num,orDev,orDevRelay,desc from ppRelay where ppDevID ='.$_GET['id']);

	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");
		echo("<td>".$row['orDev']."</td>");
		echo("<td>".$row['orDevRelay']."</td>");
		echo('<td><input type="text" name="'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="entityType" value="'.PPDEV_UPDATE_RELAYS.'">
<input type="submit" value="Обновить">
</form>
';


/*USERS*/
echo'
<br>
<h3>USERS</h3>
<form action = "settings/update_entities_desc.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Описание</td>

</tr>
';

	$result = $db->query('Select id,num,desc from ppUser where ppDevID ='.$_GET['id']);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		echo('<td><input type="text" name="'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="entityType" value="'.PPDEV_UPDATE_USERS.'">
<input type="submit" value="Обновить">
</form>';


/*ORION PARTS*/
echo'
<br>
<h3>ORION PARTS</h3>
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Описание</td>

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
?>
