<?php
var_dump($_GET);
echo ('<h1>Выxоды</h1>');

$ppDevAddr = 0;
$portID = 0;
$result = $db->query("Select addr, portID from ppDev where ppDev.id=$_GET[dev_id]");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
{
	$ppDevAddr = $row['addr'];
	$portID = $row['portID']; 
}
echo ("<h2>Порт $portID Адрес $ppDevAddr</h2>");


if (empty($_GET['do']))
{
	sendCommand ($portID, ETYPE_RELAY."-0-".$ppDevAddr."-0-".ACT_READ_STATE);
}
else
{
	sendCommand ($portID, $_GET['do']);	
}
$stateDesc = "";
$result = $db->query("SELECT id,num,hpState,desc from ppRelay");

	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		if ($row['hpState'] == 1) 
		{
			$stateDesc = "Включено";
			echo "<div class=\"view_zone bg_green\">";
		}
		
		else 
		{
			$stateDesc = "Выключено";
			echo "<div class=\"view_zone bg_gray\">";
		}
		
		echo "<p class = \"ent_name\">$row[num] - $row[desc]</p>
			<p class = \"hps_name\">$stateDesc</p>
			<br>
			<a href=\"./index.php?show=relays&dev_id=$_GET[dev_id]&do=".ETYPE_RELAY."-".$row['id']."-".$ppDevAddr."-".$row['num']."-".ACT_ON."\">Вкл</a> 
			<a href=\"./index.php?show=relays&dev_id=$_GET[dev_id]&do=".ETYPE_RELAY."-".$row['id']."-".$ppDevAddr."-".$row['num']."-".ACT_OFF."\">Выкл</a>
			<a href=\"./index.php?show=relays&dev_id=$_GET[dev_id]&do=".ETYPE_RELAY."-".$row['id']."-".$ppDevAddr."-".$row['num']."-".ACT_READ_STATE."\">Перезапрос</a>
			</div>\n";
			
	}

?>
