<?php
require ("../db/db_settings.php");
require ("../includes/header.php");
require('../includes/functions.php');
	
//printr($_POST);
	

if (((int)$_POST['num'] < 1) || ((int)$_POST['num'] > 64))
{
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'] , 4, $msg = "Не допустимое знаение номера пользователа");	
	exit();
}

$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec('PRAGMA foreign_keys = ON;');	



//obtain ppPartID
$ppUserID = 0;
$result = $db->query('SELECT MAX(id) id from ppUser where ppDevID = '.(int)$_POST['ppDevID']);
$row = $result->fetchArray(SQLITE3_ASSOC);
$ppUserID = (int)$row['id'];


try
{
	$db->exec('INSERT INTO ppUser(id,ppDevID,num,desc)
	VALUES (
	'.++$ppUserID.',
	'.(int)$_POST['ppDevID'].',
	'.(int)$_POST['num'].',
	"'.$_POST['desc'].'")');
}

catch (Exception $e) {
	if ($db->lastErrorCode() == 19)
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), "Пользователь с указаннм номером уже присутствует в БД.");
	}
	else
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), $db->lastErrorMsg);
	}
	exit();
}


htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID']);

?>
