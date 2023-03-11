<?php
	require ("../db/db_settings.php");
	require ("../includes/header.php");
	require('../includes/functions.php');

	
	//printr ($_POST);
	
	
	if (((int)$_POST['addr'] < 1) || ((int)$_POST['addr'] > 255))
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, (int)$_POST['ppDevID'], -1, "Не верно указан адрес на шине Modbus.");
		exit();
	}

	
	$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
	$db->enableExceptions(true);
	$db->exec('PRAGMA foreign_keys = ON;');	

	try
	{
		$db->exec('UPDATE ppDev SET portID = '.(int)$_POST['portID'].',addr = '.(int)$_POST['addr'].',mode = '.(int)$_POST['mode'].',translt = '.(int)$_POST['translt'].',ver = '.(int)$_POST['ver'].',desc = "'.$_POST['desc'].'" where id = '.(int)$_POST['ppDevID']);
	}
	catch (Exception $e) 
	{
		//echo 'Exception: ' . $e->getMessage();
		//echo 'Error code:'.$db->lastErrorCode().' msg: '.$db->lastErrorMsg().'<br>';
		if ($db->lastErrorCode() == 19)
		{
			htmlOutRedirect ("settings", PPDEV_SHOW, (int)$_POST['ppDevID'], $db->lastErrorCode(), "Не верно указан номер порта.");
		}
		else
		{
			htmlOutRedirect ("settings", PPDEV_SHOW, (int)$_POST['ppDevID'], $db->lastErrorCode(), $db->lastErrorMsg());
		}
		exit();
	}
	htmlOutRedirect ("settings", PPDEV_SHOW, (int)$_POST['ppDevID']);
	exit();
	
?>
