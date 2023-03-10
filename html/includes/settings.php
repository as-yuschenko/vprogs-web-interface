
<?php
	$portsID = array();
?>

<H3>Порты<H3>
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
		echo('<td><a href="./index.php?show=settings&do='.PORT_SHOW_SETTINGS.'&id='.$row['id'].'"> Настройки </a></td>' );
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
			echo '<p><form action = "settings/add_port.php" method="post"><table>';
			echo '<tr><td>Путь к файлу порта </td><td><input type="text" name="portAddPath" value="/dev/ttyS0"></td></tr>';
			echo '<tr><td>Скорость обмена </td><td><select name="portSpeed">';
			echo '<option label = "1200" value = "1200"></option>';
			echo '<option label = "2400" value = "2400"></option>';
			echo '<option label = "9600" value = "9600"></option>';
			echo '<option label = "19200" value = "19200"></option>';
			echo '<option label = "38400" value = "38400"></option>';
			echo '<option label = "57600" value = "57600"></option>';
			echo '<option label = "115200" value = "115200" selected></option>';
			echo '</select></td></tr>';
			echo '<tr><td>Описание </td><td><input type="text" name="portDesc"></td></tr>';		
			echo '<tr><td>Без неободимости не изменйте нижеследуюие настройки</td></tr>';
			echo '<tr><td>Задержка при обмене, мс </td><td><input type="text" name="portDelayTx" value="50"></td></tr>';	
			echo '<tr><td>Ожидание ответа устройства, мс </td><td><input type="text" name="portDelayResp" value="200"></td></tr>';	
			echo '<tr><td>Пауза между запросами, мс </td><td><input type="text" name="portDelayPoll" value="50"></td></tr>';	
			echo '<tr><td><br><input type="submit" value="Добавить!"></td></tr>';
			echo '</table></form></p>';
		}
	}
?>
<br>
<H3>С2000-ПП<H3>
	<a href="./index.php?show=settings&do=<?php echo PPDEV_ADD;?>">Добавить С2000-ПП</a>
<?php
	if (!empty ($_GET['do']))
	{
		if ($_GET['do'] == PPDEV_ADD)
		{
			echo '<div>
					<form enctype="multipart/form-data" action = "settings/add_ppdev.php" method="post">
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
			echo '		</fieldset>
					</form>
				  </div>';
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
		echo('<a href="./index.php?show=settings&do='.PPDEV_SHOW_SETTINGS.'&id='.$row['id'].'"> Настройки </a>' );
		echo("</td>");
		echo ("</tr>");
		
	}
?>
</table>

<?php
	if (!empty ($_GET['do']))
		if (($_GET['do'] == PPDEV_SHOW_SETTINGS) && ($_GET['id'] > 0))
			include ('settings/show_ppdev_settings.php');
		
?>

