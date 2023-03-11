<?php
function sendCommand ($portID, $cmd)
{
	if (!extension_loaded('sockets')) die('The sockets extension is not loaded.');

	$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
	if (!$socket) die('Unable to create AF_UNIX socket');

	$path = dirname( __FILE__, 2)."/thread".$portID.".sock";
	//echo ("<p>$path</p>");


	//echo ("<p>$msg</p>");
	$len = strlen($cmd);



	if (socket_connect($socket, $path))
	{
		$res = socket_send($socket, $cmd, $len, NULL);
		if ($res == $len)
		{
			socket_recv($socket, $msg, 5, NULL);
			//if ($msg == 0) echo ("<H1>SUCCES</H1>");
			//else echo ("<H1>FAILURE</H1>");
		}
		else return -1;
		
	} 
	return -1;

}
function vardump($var) 
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

function printr($var) 
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function htmlOutRedirect ($param, $do = 0, $id = 0, $err = 0, $msg = "")
{
	$url = '../?show='.$param.''.(($do) ? "&do=$do" : "").''.(($id) ? "&id=$id" : "");
	
	echo'<html><head><title>Обрабытываем...</title>';
	if (!$err) echo'<meta http-equiv="refresh" content="0; URL='.$url.'">';

	if ($err)
	{
		echo '<h2>Ошибка!</h2><h3>Код ошибки:'.$err.'.</h3><h4>'.$msg.'</h4><h4><a href = "'.$url.'">Назад</a></h4>';
	}
			  
	echo'</body></html>';
	return 0;
}
?>
