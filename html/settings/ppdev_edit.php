<?php

$result = $db->query('select portID,addr,mode,translt,ver,desc from ppDev where id = '.$ppDevID);
$row = $result->fetchArray(SQLITE3_ASSOC);
echo '
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
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<input type="submit" value="Обновить">
</form>';
?>
