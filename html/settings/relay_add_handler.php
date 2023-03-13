<?php
require ("../db/db_settings.php");
require ("../includes/header.php");
require('../includes/functions.php');
	
//printr($_POST);
	
if (((int)$_POST['num'] < 1) || ((int)$_POST['num'] > 512))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 1, $msg = "Не допустимое знаение номера реле");	
	exit();
}

if (((int)$_POST['orDevAddr'] < 0) || ((int)$_POST['orDevAddr'] > 127))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 2, $msg = "Не допустимое знаение адреса прибора");	
	exit();
}

if (((int)$_POST['orDevRelay'] < 0) || ((int)$_POST['orDevRelay'] > 255))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 3, $msg = "Не допустимое знаение номера викода прибора");	
	exit();
}


$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec('PRAGMA foreign_keys = ON;');	




//obtain ppZoneID
$ppRelayID = 0;
$result = $db->query('SELECT MAX(id) id from ppRelay where ppDevID = '.(int)$_POST['ppDevID']);
$row = $result->fetchArray(SQLITE3_ASSOC);
$ppRelayID = (int)$row['id'];


try
{
	$db->exec('INSERT INTO ppRelay(id,ppDevID,num,orDev,orDevRelay,mainScreen,desc)
	VALUES (
	'.++$ppRelayID.',
	'.(int)$_POST['ppDevID'].',
	'.(int)$_POST['num'].',
	'.(int)$_POST['orDevAddr'].',
	'.(int)$_POST['orDevRelay'].',
	'.(($_POST['mainScreen']) ? "1" : "0").',
	"'.$_POST['desc'].'")');
}

catch (Exception $e) {
	if ($db->lastErrorCode() == 19)
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), "Реле с указаннм номером уже присутствует в БД.");
	}
	else
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), $db->lastErrorMsg);
	}
	exit();
}


htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID']);

?>
