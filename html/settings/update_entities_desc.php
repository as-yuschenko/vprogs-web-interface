<?php
	require ("../db/db_settings.php");
	require ("../includes/header.php");
	include('../includes/functions.php');
	
	function htmlOut ($ppDevID, $err)
	{
		echo'
		<html>
		<head>
		<title>Обрабытываем...</title>
		<meta http-equiv="refresh" content="'.(($err)? "2" : "0" ).'; URL=../?show=settings&do='.PPDEV_SHOW_SETTINGS.'&id='.$ppDevID.'">
		 </head>
		 <body>';
				if ($err)
				{
					echo 'Ошибка!<br>Не удалось внести обновлени.<br>Код ошибки:'.$err.'.<br>Перенаправление.';
				}
			  
			echo'			
		  </body>
		</html>';
		return 0;
	}
	
	//printr ($_POST);
	

	$entName = '';
	
	$ppDevID = (int)$_POST['ppDevID'];
	
	
	
	switch ($_POST['entityType'])
	{
		case PPDEV_UPDATE_ZONES:
			$entName ='ppZone';
		break;
		case PPDEV_UPDATE_PARTS:
			$entName ='ppPart';
		break;
		case PPDEV_UPDATE_RELAYS:
			$entName ='ppRelay';
		break;
		case PPDEV_UPDATE_USERS:
			$entName ='ppUser';
		break;
		default:
			htmlOut ($ppDevID, -1);
			exit();
		break;	
	}
	
	
	$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
	$db->enableExceptions(true);
		
	$db->exec("BEGIN IMMEDIATE;");
		
	//drop mainscreen flags & prepare mainscreen query
	if ($_POST['entityType'] != PPDEV_UPDATE_USERS)
	{
		$stmt_mScr = $db->prepare('UPDATE '.$entName.' SET mainScreen = 1 where id=:id');
		
		try
		{
			$db->exec('UPDATE '.$entName.' SET mainScreen = 0');
		}
		catch (Exception $e) 
		{
			$db->exec("ROLLBACK;");
			htmlOut ($ppDevID, $db->lastErrorCode());
			exit();
		}
	}
		
		
	//prepare desc query
	$stmt_desc = $db->prepare('UPDATE '.$entName.' SET desc=:desc where id=:id');

	unset ($_POST['entityType']);
	unset ($_POST['ppDevID']);
	
	
	
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
				$db->exec("ROLLBACK;");
				htmlOut ($ppDevID, $db->lastErrorCode());
				exit();
			}
			$stmt_desc->reset();
			continue;
		}
		if ($key[0] == 's') // set mainscreen flags 
		{
			$stmt_mScr->bindValue(':id', ((int)substr($key, 1)), SQLITE3_INTEGER);
			try
			{
				$stmt_mScr->execute();
			}
			catch (Exception $e) 
			{
				$db->exec("ROLLBACK;");
				htmlOut ($ppDevID, $db->lastErrorCode());
				exit();	
			}
				$stmt_desc->reset();
		}
	}
		
	$db->exec("COMMIT;");
	htmlOut ($ppDevID, 0);
	

	

	
	
?>
