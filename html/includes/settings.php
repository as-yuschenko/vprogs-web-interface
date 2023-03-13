
<?php
	$portsID = array();
?>

<H3>Порты</H3>
<a href="./index.php?show=settings&do=<?php echo PORT_ADD;?>">Dобавить порт</a>
<?php
	if (!empty ($_GET['do']))
	{
		if ($_GET['do'] == PORT_ADD)
		{
			echo '<div class = "form_add_entity"><form action = "settings/port_add.php" method="post"><table>';
			echo '<tr><td class = "bottom_line">Путь к файлу порта </td><td><input type="text" name="portAddPath" value="/dev/ttyS0"></td></tr>';
			/*
			echo '<tr><td>Скорость обмена </td><td><select name="portSpeed">';
			echo '<option label = "1200" value = "1200"></option>';
			echo '<option label = "2400" value = "2400"></option>';
			echo '<option label = "9600" value = "9600"></option>';
			echo '<option label = "19200" value = "19200"></option>';
			echo '<option label = "38400" value = "38400"></option>';
			echo '<option label = "57600" value = "57600"></option>';
			echo '<option label = "115200" value = "115200" selected></option>';
			echo '</select></td></tr>';
			*/
			echo '<tr><td class = "bottom_line">Скорость обмена </td><td>';
			echo'<input name="portSpeed" type="radio" value="1200">1200<br>';
			echo'<input name="portSpeed" type="radio" value="2400">2400<br>';
			echo'<input name="portSpeed" type="radio" value="9600">9600<br>';
			echo'<input name="portSpeed" type="radio" value="19200">19200<br>';
			echo'<input name="portSpeed" type="radio" value="38400">38400<br>';
			echo'<input name="portSpeed" type="radio" value="57600">57600<br>';
			echo'<input name="portSpeed" type="radio" value="115200" checked>115200<br>';
			echo '</td></tr>';
			echo '<tr><td class = "bottom_line">Описание </td><td><input type="text" name="portDesc"></td></tr>';		
			echo '<tr><td><br><h4>Без неободимости не изменйте нижеследуюие настройки</h4></td></tr>';
			echo '<tr><td class = "bottom_line">Задержка при обмене, мс </td><td><input type="text" name="portDelayTx" value="50"></td></tr>';	
			echo '<tr><td class = "bottom_line">Ожидание ответа устройства, мс </td><td><input type="text" name="portDelayResp" value="200"></td></tr>';	
			echo '<tr><td class = "bottom_line">Пауза между запросами, мс </td><td><input type="text" name="portDelayPoll" value="50"></td></tr>';	
			echo '<tr><td></td><td><br><input type="submit" value="Добавить!"></td></tr>';
			echo '</table></form></div>';
		}
	}
?>
<hr>

<table border=1>
<tr>
	<th class="">Порт</th>
	<th class="">Путь</th>
	<th class="">Скорость</th>
	<th class="">Описание</th>
	<th class="">Действия</th>
</tr>

<?php
	$result = $db->query('SELECT id,path,bRate,desc from port where portTypeID =1');
	$n = 0;
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		$portsID[$n] = $row['id'];
		$n++;
		
		//var_dump($row);
		echo ("<tr>\n");
		echo("<td>".$row['id']."</td>");
		echo("<td>".$row['path']."</td>");
		echo("<td>".$row['bRate']."</td>");
		echo("<td>".$row['desc']."</td>");
		echo('<td><a href="./index.php?show=settings&do='.PORT_SHOW.'&id='.$row['id'].'"> Настройки </a></td>' );
		echo ("</tr>");
		
	}
?>
</table>


<br>
<H3>С2000-ПП</H3>
<a href="./index.php?show=settings&do=<?php echo PPDEV_ADD;?>">Добавить С2000-ПП</a>
<?php
	if (!empty ($_GET['do']))
	{
		if ($_GET['do'] == PPDEV_ADD)
		{
			include ('settings/ppdev_add_form.php');
		}
	}
?>
<hr>
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Порт</th>
	<th class="">Адрес</th>
	<th class="">Версия</th>
	<th class="">Режим</th>
	<th class="">Прямая трансляция</th>
	<th class="">Описание</th>
	<th class="">Действия</th>
</tr>
<?php
	$result = $db->query('SELECT id, portID, addr, mode, translt, ver, `desc` FROM ppDev where id>0');
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//var_dump($row);
		echo ("<tr>\n");
		echo("<td>$row[id]</td>");
		echo("<td>$row[portID]</td>");
		echo("<td>$row[addr]</td>");
		echo("<td>$row[ver]</td>");
		echo("<td>");
		echo(($row['mode']) ? "Ведущий" : "Ведомый");
		echo("</td>");
		echo("<td>");
		echo(($row['translt']) ? "Включена" : "Выключена");
		echo("</td>");
		echo("<td>$row[desc]</td>");
		echo("<td>");
		echo('<a href="./index.php?show=settings&do='.PPDEV_SHOW.'&id='.$row['id'].'"> Настройки </a>' );
		echo("</td>");
		echo ("</tr>");
		
	}
?>
</table>

<?php
	if (!empty ($_GET['do']) && !empty ($_GET['id']))
		if ($_GET['do'] == PPDEV_SHOW)
			include ('settings/ppdev_show.php');
		
?>

