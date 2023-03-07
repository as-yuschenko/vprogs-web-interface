<?php
	require ("../db/db_settings.php");
	include('../includes/functions.php');
	
	//printr ($_POST);
	
	$err = 0;
	
	$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
	$db->enableExceptions(true);
	
	$db->exec("BEGIN IMMEDIATE;");
	
	$stmt = $db->prepare('UPDATE ppPart SET desc=:desc where id=:id');
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
