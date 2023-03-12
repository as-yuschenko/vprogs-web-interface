<?php
echo'
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

	$result = $db->query('Select id,num,orDev,orDevRelay,desc,mainScreen from ppRelay where ppDevID ='.$ppDevID);

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
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<input type="submit" value="Обновить">
</form>
';
?>
