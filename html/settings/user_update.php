<?php
	require ("../db/db_settings.php");
	require ("../includes/header.php");
	require('../includes/functions.php');

	
	//printr ($_POST);
	
	
	$ppDevID = (int)$_POST['ppDevID'];
	unset ($_POST['ppDevID']);

	
	$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
	$db->enableExceptions(true);
		
	$db->exec("BEGIN IMMEDIATE;");
	/*	
	//drop mainscreen flags 
	try
	{
		$db->exec('UPDATE ppUser SET mainScreen = 0');
	}
	catch (Exception $e) 
	{
		htmlOutRedirect ("settings", PPDEV_SHOW, $ppDevID, $db->lastErrorCode());
		$db->exec("ROLLBACK;");
		exit();
	}
	
	//prepare mainscreen query
	$stmt_mScr = $db->prepare('UPDATE ppUser SET mainScreen = 1 where id=:id');
	*/
	
	
	//prepare desc query
	$stmt_desc = $db->prepare('UPDATE ppUser SET desc=:desc where id=:id');
	
	foreach ($_POST as $key => $val )
	{			
		if ($key[0] == 'd') // set desc
		{
			$stmt_desc->bindValue(':id', ((int)substr($key, 1)), SQLITE3_INTEGER);
			$stmt_desc->bindValue(':desc', $val , SQLITE3_TEXT);
			try
			{
				$stmt_desc->execute();
			}
			catch (Exception $e) 
			{
				htmlOutRedirect ("settings", PPDEV_SHOW, $ppDevID, $db->lastErrorCode());
				$db->exec("ROLLBACK;");
				exit();
			}
			$stmt_desc->reset();
		}
		/*
		else if ($key[0] == 's') // set mainscreen flags 
		{
			$stmt_mScr->bindValue(':id', ((int)substr($key, 1)), SQLITE3_INTEGER);
			try
			{
				$stmt_mScr->execute();
			}
			catch (Exception $e) 
			{
				htmlOutRedirect ("settings", PPDEV_SHOW, $ppDevID, $db->lastErrorCode());
				$db->exec("ROLLBACK;");
				exit();
			}
				$stmt_desc->reset();
		}
		*/
	}
		
	$db->exec("COMMIT;");
	htmlOutRedirect ("settings", PPDEV_SHOW, $ppDevID);
?>
