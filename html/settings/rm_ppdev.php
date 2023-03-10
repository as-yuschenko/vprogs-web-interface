<?php
require ("../db/db_settings.php");

function htmlOut ($err)
	{
		echo'
		<html>
		<head>
		<title>Обрабытываем...</title>
		<meta http-equiv="refresh" content="'.(($err)? "2" : "0" ).'; URL=../?show=settings">
		 </head>
		 <body>';
				if ($err)
				{
					echo 'Ошибка!<br>Не удалось удалить устройство.<br>Код ошибки:'.$err.'.<br>Перенаправление.';
				}
			  
			echo'			
		  </body>
		</html>';
		return 0;
	}
	

$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec("BEGIN IMMEDIATE;");
try
{

	$db->exec('DELETE from ppDev where id = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}

try
{

	$db->exec('DELETE from ppZone where ppDevID = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}

try
{

	$db->exec('DELETE from ppPart where ppDevID = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}

try
{

	$db->exec('DELETE from ppRelay where ppDevID = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}

try
{

	$db->exec('DELETE from ppUser where ppDevID = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}

try
{

	$db->exec('DELETE from orPart where ppDevID = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}

try
{

	$db->exec('DELETE from orUser where ppDevID = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	$db->exec("ROLLBACK;");
    htmlOut ($db->lastErrorCode());
    exit();
}
$db->exec("COMMIT;");
htmlOut (0);
?>
