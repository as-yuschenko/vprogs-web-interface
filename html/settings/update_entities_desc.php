<?php
	require ("../db/db_settings.php");
	require ("../includes/header.php");
	include('../includes/functions.php');
	
	printr ($_POST);
	

	
	$err = 0;
	$entName = '';
	
	
	
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
			$err = -1;
		break;	
	}
	
	if (!$err)
	{
		unset ($_POST['entityType']);
		
		$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
		$db->enableExceptions(true);
		
		$db->exec("BEGIN IMMEDIATE;");
		$stmt = $db->prepare('UPDATE '.$entName.' SET desc=:desc where id=:id');
		
		foreach ($_POST as $id => $desc )
		{
			if ($err) break;
			
			$stmt->bindValue(':id', (int)$id, SQLITE3_INTEGER);
			$stmt->bindValue(':desc', $desc, SQLITE3_TEXT);
			try
			{
				$stmt->execute();
			}
			catch (Exception $e) 
			{
				$db->exec("ROLLBACK;");
				$err = $db->lastErrorCode();
			}
			$stmt->reset();
		}
		
		if (!$err)$db->exec("COMMIT;");
	}
	
	echo'
	<html>
	<head>
    <title>Обрабытываем...</title>
	<meta http-equiv="refresh" content="2; URL=../?show=settings">
	 </head>
	 <body>';
			if (!$err)
			{
				echo 'Обновлени внесен.';
			}
			else 
			{
				echo 'Ошибка!<br>Не удалось внести обновлени.<br>Код ошибки:'.$err.'.';
			}
		  
		echo'	
		<br>
		Перенаправление.
	  </body>
	</html>';
	
	
?>
