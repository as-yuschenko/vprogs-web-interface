<?php
require ("./includes/header.php");
require ("./includes/functions.php");
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Муравей</title>
	<link rel="stylesheet" href="./css/main.css">
</head>
<body>
	

<table width = "100%">
<tr>
<td align = "left"><a href="./">Домой</a></td>
<td></td>
<td align = "right"><a href="./index.php?show=settings">Настройки</a></a></td>
</tr>
</table>


<?php
	$db = new SQLite3('/valrond/cpp/_bases/develop.sqlite', SQLITE3_OPEN_READWRITE);
	
	/*
	while ($row = $result->fetchArray()) 
	{
		var_dump($row);
	}
	*/
?>




<div>
	<?php
		//var_dump ($_GET);
		if (empty($_GET)) 
		{
			include ("includes/events.php");
		}
		else if (!empty($_GET['show']))
		{
			if ($_GET['show'] == "zones") include ("includes/zones.php");
			if ($_GET['show'] == "parts") include ("includes/parts.php");
			if ($_GET['show'] == "relays") include ("includes/relays.php");
			if ($_GET['show'] == "settings") include ("includes/settings.php");
			//if ($_GET['show'] == "telegram") include ("includes/telegram.php");
		}
	?>
</div>
<!--
<form>
<fieldset>
    <legend>Выбрать порт</legend>
		<select>
			<option>Все</option>
			<?php
			/*
	$result = $db->query('SELECT id, `desc` from port where portTypeID =1 -- and state = 1');
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//var_dump($row);
		echo ("<option>".$row[id]." ".$row[desc]."</option>");	
	}
	*/
?>
		</select>	
  </fieldset>	
<fieldset>
    <legend>Выбрать С2000-ПП</legend>
		<select>
			<option>Все</option>
			<?php
			/*
	$result = $db->query('SELECT id, portID, addr, `desc` FROM ppDev where id>0 -- and state = 1');
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//var_dump($row);
		echo ("<option>".$row[portID]."-".$row[addr]." ".$row[desc]."</option>");	
	}
	*/
?>
		</select>	
  </fieldset>	
  <fieldset>
    <legend>Выбрать зону</legend>
		<select>
			<option>Все</option>
			<?php
			/*
	$result = $db->query('SELECT id, portID, addr, `desc` FROM ppDev where id>0 -- and state = 1');
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//var_dump($row);
		echo ("<option>".$row[portID]."-".$row[addr]." ".$row[desc]."</option>");	
	}
	*/
?>
		</select>	
  </fieldset>
<p><input type="submit" value="Опросить"></p>
</form>

-->
<?php 
//phpinfo();
?>
</body>
</html>
