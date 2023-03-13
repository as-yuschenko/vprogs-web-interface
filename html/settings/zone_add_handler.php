<?php
require ("../db/db_settings.php");
require ("../includes/header.php");
require('../includes/functions.php');
	
//printr($_POST);
	
if (((int)$_POST['num'] < 1) || ((int)$_POST['num'] > 512))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 1, $msg = "Не допустимое знаение номера зон");	
	exit();
}

if (((int)$_POST['orDevAddr'] < 0) || ((int)$_POST['orDevAddr'] > 127))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 2, $msg = "Не допустимое знаение адреса прибора");	
	exit();
}

if (((int)$_POST['orDevLoop'] < 0) || ((int)$_POST['orDevLoop'] > 255))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 3, $msg = "Не допустимое знаение номера шлейфа прибора");	
	exit();
}

if (((int)$_POST['ppPartNum'] < 1) || ((int)$_POST['ppPartNum'] > 64))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 4, $msg = "Не допустимое знаение номера раздела");	
	exit();
}

$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec('PRAGMA foreign_keys = ON;');	



//obtain ppPartID
$ppPartID = 0;
$result = $db->query('SELECT id from ppPart where ppDevID = '.(int)$_POST['ppDevID'].' and num = '.(int)$_POST['ppPartNum']);
$row = $result->fetchArray(SQLITE3_ASSOC);
if ($row === false)
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 5, $msg = "Раздел с номером ".(int)$_POST['ppPartNum']." отсутствует в БД.<br>Пожалуйста снаала добавьте раздел.");	
	exit();
}
else
{
	$ppPartID = $row['id'];
}

//obtain ppZoneID
$ppZoneID = 0;
$result = $db->query('SELECT MAX(id) id from ppZone where ppDevID = '.(int)$_POST['ppDevID']);
$row = $result->fetchArray(SQLITE3_ASSOC);
$ppZoneID = (int)$row['id'];


try
{
	$db->exec('INSERT INTO ppZone(id,ppDevID,num,orDev,orDevLoop,ppZoneTypeID,ppPartID,mainScreen,desc)
	VALUES (
	'.++$ppZoneID.',
	'.(int)$_POST['ppDevID'].',
	'.(int)$_POST['num'].',
	'.(int)$_POST['orDevAddr'].',
	'.(int)$_POST['orDevLoop'].',
	'.(int)$_POST['type'].',
	'.$ppPartID.',
	'.(($_POST['mainScreen']) ? "1" : "0").',
	"'.$_POST['desc'].'")');
}

catch (Exception $e) {
	if ($db->lastErrorCode() == 19)
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), "Зона с указаннм номером уже присутствует в БД.");
	}
	else
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), $db->lastErrorMsg);
	}
	exit();
}


htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID']);

?>
