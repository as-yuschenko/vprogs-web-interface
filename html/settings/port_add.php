<?php
require ("../db/db_settings.php");
require ("../includes/header.php");
require('../includes/functions.php');

//var_dump($_POST);
$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec('PRAGMA foreign_keys = ON;');	
try{
$db->exec("BEGIN IMMEDIATE;");
}
catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage();
    echo 'Error code:'.$db->lastErrorCode().' msg: '.$db->lastErrorMsg().'<br>';
   }
$result = $db->query("SELECT MAX(id) id from port");
$row = $result->fetchArray(SQLITE3_ASSOC);
$id = (int)$row['id'];

try{

$result = $db->exec('
INSERT INTO port
(
	id
	,path
	,bRate
	,delayTX
	,delayResp
	,delayPoll
	,portTypeID
	,`desc`
)
VALUES
(
	'.++$id.',
	"'.$_POST['portAddPath'].'",
	'.(int)$_POST['portSpeed'].',
	'.(int)$_POST['portDelayTx'].',
	'.(int)$_POST['portDelayResp'].',
	'.(int)$_POST['portDelayPoll'].',
	1,
	"'.$_POST['portDesc'].'"
)');
}
catch (Exception $e) {
    //echo 'Exception: ' . $e->getMessage();
    //echo 'Error code:'.$db->lastErrorCode().' msg: '.$db->lastErrorMsg().'<br>';
    if ($db->lastErrorCode() == 19)
    {
		htmlOutRedirect ("settings", PORT_SHOW, $id, $db->lastErrorCode(), "Файл порта уже присутствует в системе.");
		$db->exec("ROLLBACK;");
		exit();
	}
	else
	{
		htmlOutRedirect ("settings", PORT_SHOW, $id, $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
		exit();
	}
}

	$db->exec("COMMIT;");
	htmlOutRedirect ("settings");
	exit();
?>
