
<?php
	$portsID = array();
?>

<H3>Порты<H3>
<hr>

<table border=1>
<tr>
	<th class="">Порт</td>
	<th class="">Путь</td>
	<th class="">Скорость</td>
	<th class="">Описание</td>
	<th class="">Статус</td>
</tr>

<?php
	$result = $db->query('SELECT id, path, bRate, `desc`, `state` from port where portTypeID =1');
	$n = 0;
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		$portsID[$n] = $row['id'];
		$n++;
		
		//var_dump($row);
		if ($row['state']) echo ("<tr class=\"bg_green\">\n");
		else echo ("<tr class=\"bg_red\">\n");
		
		echo("<td>".$row['id']."</td>");
		echo("<td>".$row['path']."</td>");
		echo("<td>".$row['bRate']."</td>");
		echo("<td>".$row['desc']."</td>");
		echo("<td>");
		echo(($row['state']) ? "В работе" : "Не доступен");
		echo("</td>");
		echo ("</tr>");
		
	}
?>
</table>


<a href="./index.php?show=settings&do=<?php echo PORT_ADD;?>">Dобавить порт</a>
<?php
	if (!empty ($_GET['do']))
	{
		if ($_GET['do'] == PORT_ADD)
		{
			echo '<p>
					<form action = "handlers/addport.php" method="post">
						<fieldset>';
			
			echo '<p><label for="portAddPath">Путь к файлу порта <em>*</em>  </label><input type="text" name="portAddPath" value="/dev/ttyS0"></p>';
			echo '<p><label for="portSpeed">Скорость обмена </label><select name="portSpeed">';
			echo '<option label = "1200" value = "1200"></option>';
			echo '<option label = "2400" value = "2400"></option>';
			echo '<option label = "9600" value = "9600"></option>';
			echo '<option label = "19200" value = "19200"></option>';
			echo '<option label = "38400" value = "38400"></option>';
			echo '<option label = "57600" value = "57600"></option>';
			echo '<option label = "115200" value = "115200" selected></option>';
			echo '</select>';
			echo '<p><label for="portDesc">Описание </label><input type="text" name="portDesc"></p>';		
			echo '<br>';
			echo '<p><label for="portDelayTx">Задержка при обмене, мс </label><input type="text" name="portDelayTx" value="50"></p>';	
			echo '<p><label for="portDelayResp">Ожидание ответа устройства, мс </label><input type="text" name="portDelayResp" value="200"></p>';	
			echo '<p><label for="portDelayPoll">Пауза между запросами, мс </label><input type="text" name="portDelayPoll" value="50"></p>';	
			echo '<br>';
			echo '<input type="submit" value="Добавить!">';
			echo "		</fieldset>
					</form>
				  </p>";
		}
	}
?>
<br>
<H3>С2000-ПП<H3>
	<hr>
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Порт</td>
	<th class="">Адрес</td>
	<th class="">Версия</td>
	<th class="">Режим</td>
	<th class="">Прямая трансляция</td>
	<th class="">Описание</td>
	<th class="">Статус</td>
	<th class="">Сущности</td>
</tr>
<?php
	$result = $db->query('SELECT id, portID, addr, mode, translt, ver, state, `desc` FROM ppDev where id>0');
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//var_dump($row);
		if ($row['state']) echo ("<tr class=\"bg_green\">\n");
		else echo ("<tr class=\"bg_red\">\n");
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
		echo(($row['state']) ? "В работе" : "Не доступен");
		echo("</td>");
		echo("<td>");
		echo("<a href=\"./index.php?show=zones&dev_id=$row[id]\"> Зоны |</a>" );
		echo("<a href=\"./index.php?show=parts&dev_id=$row[id]\"> Разделы |</a>" );
		echo("<a href=\"./index.php?show=relays&dev_id=$row[id]\"> Реле </a>" );
		echo("</td>");
		echo ("</tr>");
		
	}
?>
</table>

<a href="./index.php?show=settings&do=<?php echo PPDEV_ADD;?>">Добавить С2000-ПП</a>
<?php
	if (!empty ($_GET['do']))
	{
		if ($_GET['do'] == PPDEV_ADD)
		{
			echo '<p>
					<form enctype="multipart/form-data" action = "handlers/addppdev.php" method="post">
						<fieldset>';
			
			echo '<p><label for="ppDevCNU">Путь к файлу конфигураии С2000-ПП <em>*</em>  </label><input type="file" name="ppDevCNU"></p>';
			echo '<p><label for="ppDevTXT">Путь к файлу конфигураии С2000М </label><input type="file" name="ppDevTXT"></p>';
			
			echo '<p><label for="ppDevPort">Добавить к порту </label><select name="ppDevPort">';
			for ($i = 0; $i < count($portsID); $i++)
			{
				echo '<option label = "'.$portsID[$i].'" value = "'.$portsID[$i].'"></option>';
			}
			
			
			echo '</select>';
			
			echo '<p><label for="ppDevWM">Режим работ </label><select name="ppDevWM">';
			echo '<option label = "Master" value = "1"></option>';
			echo '<option label = "Slave" value = "0" selected></option>';
			echo '</select>';
			echo '<p><label for="ppDevDesc">Описание </label><input type="text" name="ppDevDesc"></p>';		
			echo '<br>';
			echo '<br>';
			echo '<input type="submit" value="Добавить!">';
			echo "		</fieldset>
					</form>
				  </p>";
		}
	}
?>
