<?php
echo'
<form action = "settings/zone_update.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Прибор</th>
	<th class="">ШС</th>
	<th class="">Описание</th>
	<th class="">На главной</th>
	<th class="">Тип шлейфа</th>
	<th class="">Раздел ПП</th>
	<th class="">Раздел С2000М</th>
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
	,orPart.num `orPartNum`
	,orPart.desc `orPartDesc`
	from ppZone
	left join ppZoneType on ppZone.ppZoneTypeID = ppZoneType.id
	left join ppPart on ppZone.ppPartID = ppPart.id
	left join orPart on ppZone.orPartID = orPart.id
	where ppZone.ppDevID = '.$ppDevID.'
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
		echo('<td>'.$row['partNum'].'&nbsp;-&nbsp;'.$row['partDesc'].'</td>');
		echo('<td>'.(($row['orPartNum'] > 0) ? $row['orPartNum'].'&nbsp;-&nbsp;'.$row['orPartDesc'].'' : '').'</td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<input type="submit" value="Обновить">
</form>
';
?>
