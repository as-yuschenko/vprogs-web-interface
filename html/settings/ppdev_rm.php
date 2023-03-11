<?php
require ("../db/db_settings.php");
require('../includes/functions.php');


$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec('PRAGMA foreign_keys = ON;');	
$db->exec("BEGIN IMMEDIATE;");
try
{

	$db->exec('DELETE from ppDev where id = '.$_POST['ppDevID']);
}
catch (Exception $e) {
	htmlOutRedirect ("settings", PPDEV_SHOW, $_POST['ppDevID'], $db->lastErrorCode(), $db->lastErrorMsg());
	$db->exec("ROLLBACK;"); 
    exit();
}

$db->exec("COMMIT;");
htmlOutRedirect ("settings");
?>
