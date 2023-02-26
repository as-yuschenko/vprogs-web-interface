<H3>События<H3>
	<hr>
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Время системы</td>
	<th class="">Время устройства</td>
	<th class="">Порт</td>
	<th class="">Адрес</td>
	<th class="">Номер</td>
	<th class="">Код</td>
	<th class="">Событие</td>
	<th class="">Раздел</td>
	<th class="">Зона</td>
	<th class="">Реле</td>
	<th class="">Программа</td>
	<th class="">XO</td>
	<th class="">Прибор</td>
	<th class="">Loop</td>
	<th class="">Тран</td>
</tr>
<?php
	$result = $db->query('SELECT vEventsSimp.id,  sTime, dTime, port, adr, num, code, vEventsSimp.`desc`, part, `zone`, relay, rState, `user`, oDev, oUnit, trlt, eTypeID FROM vEventsSimp, eDesc where vEventsSimp.code = eDesc.id  order by vEventsSimp.id desc limit 30;');
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{

		
		switch ($row['eTypeID'])
		{
			case 1:
				echo ("<tr class=\"bg_red\">\n");
			break;
			case 2:
				echo ("<tr class=\"bg_green\">\n");
			break;
			case 4:
			echo ("<tr class=\"bg_yellow\">\n");
			break;
			default:
				echo ("<tr>\n");
			break;
		}
		

		echo("<td>$row[id]</td>\n");
		echo("<td>$row[sTime]</td>\n");
		echo("<td>$row[dTime]</td>\n");
		echo("<td>$row[port]</td>\n");
		echo("<td>$row[adr]</td>\n");
		echo("<td>$row[num]</td>\n");
		echo("<td>$row[code]</td>\n");
		echo("<td>$row[desc]</td>\n");
		echo("<td>$row[part]</td>\n");
		echo("<td>$row[zone]</td>\n");
		echo("<td>$row[relay]</td>\n");
		echo("<td>$row[rState]</td>\n");
		echo("<td>$row[user]</td>\n");
		echo("<td>$row[oDev]</td>\n");
		echo("<td>$row[oUnit]</td>\n");
		echo("<td>$row[trlt]</td>\n");
		echo ("</tr>\n");
		
	}

?>
</table>
