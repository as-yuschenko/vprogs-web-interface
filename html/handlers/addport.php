<?php
//var_dump($_POST);
$db = new SQLite3('/valrond/cpp/_bases/develop.sqlite', SQLITE3_OPEN_READWRITE);

$errCode = 0;

$result = $db->query("SELECT MAX(id) id from port");
$row = $result->fetchArray(SQLITE3_ASSOC);
$id = (int)$row['id'] + 1;

try{
$db->enableExceptions(true);
$result = $db->exec("
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
	$id,
	\"$_POST[portAddPath]\",
	".(int)$_POST['portSpeed'].",
	".(int)$_POST['portDelayTx'].",
	".(int)$_POST['portDelayResp'].",
	".(int)$_POST['portDelayPoll'].",
	1,
	\"$_POST[portDesc]\"
)");
}
catch (Exception $e) {
    //echo 'Exception: ' . $e->getMessage();
    //echo 'Error code:'.$db->lastErrorCode().' msg: '.$db->lastErrorMsg().'<br>';
    $errCode = $db->lastErrorCode();
}

//<meta http-equiv="refresh" content="2; URL=../?show=settings">
?>

<html>
  <head>
    <title>Обрабытываем...</title>
	<meta http-equiv="refresh" content="2; URL=../?show=settings">
  </head>
  <body>
	  <?php
		if (!$errCode)
		{
			echo 'Порт успешно добавен.';
		}
		else if ($errCode == 19)
		{
			echo 'Ошибка!<br>Файл порта уже присутствует в системе.';
		}
		else 
		{
			echo 'Ошибка!';
		}
	  ?>
    
    <br>
    Перенаправление.
  </body>
</html>
