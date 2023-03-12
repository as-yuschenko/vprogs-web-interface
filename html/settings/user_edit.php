<?php
echo'
<form action = "settings/user_update.php" method="post">
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Описание</th>

</tr>
';

	$result = $db->query('Select id,num,desc from ppUser where ppDevID ='.$ppDevID);


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
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<input type="submit" value="Обновить">
</form>';
?>
