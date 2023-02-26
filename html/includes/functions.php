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
?>
